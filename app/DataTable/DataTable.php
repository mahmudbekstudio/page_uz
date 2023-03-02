<?php

namespace App\DataTable;

use App\Http\Requests\DataTableRequest;
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class DataTable
{
    protected int $page;
    protected int $itemsPerPage;
    protected string $sortBy;
    protected bool $sortDesc;
    protected array $filter = [];

    protected array $columns = [];
    protected array $responseData = ['current_page', 'data', 'from', 'last_page', 'per_page', 'to', 'total'];

    protected string $repositoryClass;
    protected RepositoryInterface $repository;

    const DEFAULT_ITEMS_PER_PAGE = 10;

    public function __construct(protected DataTableRequest $request)
    {
        $data = $request->all();
        $this->page = $currentPage = Arr::get($data, 'page', 1);
        $this->itemsPerPage = Arr::get($data, 'itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);
        $this->sortBy = Arr::get(Arr::get($data, 'sortBy', []), 0, '');
        $this->sortDesc = Arr::get(Arr::get($data, 'sortDesc', []), 0, 'false') === 'true';
        $this->filter = Arr::get($data, 'filter', []);
        $this->repository = app($this->repositoryClass);

        if ($this->itemsPerPage <= 0) {
            $this->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
        }

        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
    }

    abstract protected function handle();

    public function toArray(): array
    {
        $repository = $this->handle();

        if (!empty($this->filter)) {
            $repository = $repository->scopeQuery(function($query){
                foreach ($this->filter as $key => $val) {
                    if (isset($val['condition']) && isset($val['value']) && $val['value'] != '') {
                        $where = isset($val['logic']) && strtolower(trim($val['logic'])) == 'or' ? 'orWhere' : 'where';
                        $query = $query->{$where}($this->getFieldNameByKey($key), $val['condition'], $val['value']);
                    } elseif(!isset($val['condition']) && !isset($val['value'])) {
                        $query = $query->where(function($query) use ($val){
                            foreach ($val as $subKey => $subVal) {
                                $where = isset($subVal['logic']) && strtolower(trim($subVal['logic'])) == 'or' ? 'orWhere' : 'where';
                                $query = $query->{$where}($this->getFieldNameByKey($subKey), $subVal['condition'], $subVal['value']);
                            }
                        });
                    }
                }
                return $query;
            });
        }

        if ($this->sortBy) {
            $field = $this->getFieldNameByKey($this->sortBy);

            if ($field) {
                $repository = $repository->orderBy($field, $this->sortDesc ? 'desc' : 'asc');
            }
        }

        return Arr::only(
            $this->transform($repository->paginate($this->itemsPerPage, $this->selectColumns())->toArray()),
            $this->responseData
        );
    }

    protected function transform(array $list): array
    {
        return $list;
    }

    private function selectColumns(): array
    {
        if (empty($this->columns)) {
            return ['*'];
        }

        $result = [];

        foreach ($this->columns as $fieldName => $column) {
            $result[] = $fieldName . ' AS ' . $column;
        }

        return $result;
    }

    private function getFieldNameByKey($key): string
    {
        foreach ($this->columns as $fieldName => $column) {
            if ($key == $column) {
                return $fieldName;
            }
        }

        return '';
    }
}

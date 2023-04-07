<?php

namespace App\Rules;

use App\Repositories\PostRepository;
use Illuminate\Contracts\Validation\Rule;

class CheckPostParentDeepLimit implements Rule
{
    private $id;
    private $typeId;
    private PostRepository $postRepository;

    public function __construct($typeId, $id = 0)
    {
        $this->typeId = $typeId;
        $this->id = $id;
        $this->postRepository = app(PostRepository::class);
    }

    public function passes($attribute, $value): bool
    {
        $list = [];
        $count = 0;
        $parentId = (int)$value;
        $parentPageDeepLimit = config('app.parentPageDeepLimit') - 1;

        if($this->id) {
            if($this->id == $parentId) {
                return false;
            }

            $list[] = $this->id;
            $parentPageDeepLimit++;
        }

        while ($parentId > 0) {
            $list[] = (int)$parentId;
            $count++;
            $category = $this->postRepository->getById($parentId);
            $parentId = $category->parent_id;

            if($count > $parentPageDeepLimit || in_array($parentId, $list)) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return trans('validation.parent_deep_limit_exceed');
    }
}

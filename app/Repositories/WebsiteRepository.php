<?php
namespace App\Repositories;

use App\Helpers\DataFormat;
use App\Models\Website;
use App\Models\WebsiteMeta;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\StaticInstance;
use App\Repositories\Traits\Vars;
use Illuminate\Support\Arr;

class WebsiteRepository extends BaseRepository {

    use StaticInstance, Vars, GetById;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Website::class;
    }

    public static function changeStatusOfCurrentWebsite(int $status)
    {
        /**
         * @var Website $website
         */
        $website = self::getInstance()->getCurrent();

        if (in_array($status, $website->getAllStatuses()) && $website->status !== $status) {
            $website = Website::find($website->id);
            $website->status = $status;
            $website->save();
        }
    }

    public function setCurrent(int $id) {
        $this->setVar('current-website', $this->getRoot($id));
    }

    private function getRoot(int $id)
    {
        $website = $this->getById($id);

        if ($website->domain_id) {
            $website = $this->getById($website->domain_id);
        }

        return $website;
    }

    public function getCurrent() {
        return $this->getVar('current-website');
    }

    public function getMain() {
        return $this->getByDomain(config('app.main_website'));
    }

    /**
     * Get website model by domain name
     *
     * @param string $domain
     * @return Website|null
     */
    public function getByDomain(string $domain) {
        if(isset($_ENV['current-website'])) {
            $currentWebsite = json_decode($_ENV['current-website'], true);
            $website = $this->getVar(Arr::get($currentWebsite, 'id', 0));

            if (!$website || $website->domain !== Arr::get($currentWebsite, 'domain', '')) {
                $website = new Website($currentWebsite);
                $this->setVar($website->id, $website);
            }
        }

        $website = $this->getVarByField('domain', $domain);

        if($website) {
            return $website;
        }

        $website = $this->model->where('domain', $domain)->first();

        if($website) {
            if($website->domain_id) {
                $website = $this->getById($website->domain_id);
            } else {
                $this->setVar($website->id, $website);
            }
        }

        return $website;
    }

    public function getMetas($id = 0)
    {
        if (!$id) {
            $id = $this->getCurrent()->id;
        }

        $result = $this->getVar('metas_' . $id);

        if(!$result) {
            $result = $this->getUpdatedMetas($id);
        }

        return $result;
    }

    public function getUpdatedMetas(int $id = null) {
        if (!$id) {
            $id = $this->getCurrent()->id;
        }

        $current = $this->getById($id);
        $metas = $current->metas()->get();
        $result = [];

        foreach($metas as $meta) {
            $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
        }

        $this->setVar('metas_' . $id, $result);

        return $result;
    }

    public function storeMetas($metas)
    {
        foreach ($metas as $key => $meta) {
            $value = Arr::get($meta, 'value');
            $format = Arr::get($meta, 'format');

            WebsiteMeta::updateOrCreate([
                'meta_key' => $key,
            ], [
                'meta_value' => DataFormat::toString($value, $format),
                'meta_format' => $format,
            ]);
        }

        return $this->getUpdatedMetas();
    }

    public function getMetaValue(string $keyValue, string $lang = '') {
        $metas = $this->getMetas();

        if (gettype($metas[$keyValue]) === 'array' && isset($metas[$keyValue][$lang])) {
            return $metas[$keyValue][$lang];
        }

        return $metas[$keyValue] ?: null;
    }

    public function getMetaLangValue(string $keyValue) {
        return $this->getMetaValue($keyValue, isLang());
    }
}

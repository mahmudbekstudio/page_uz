<?php
namespace App\Repositories;

use App\Helpers\DataFormat;
use App\Models\Website;
use App\Repositories\Traits\StaticInstance;
use App\Repositories\Traits\Vars;

class WebsiteRepository extends BaseRepository {

    use StaticInstance, Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return Website::class;
    }

    /**
     * Get website mode by id
     *
     * @param int $id
     * @return Website|null
     */
    public function getById(int $id) {
        $result = $this->getVar($id);

        if(!$result) {
            $result = $this->find($id);
            $this->setVar($id, $result);
        }

        return $result;
    }

    public function setCurrent(int $id) {
        $this->setVar('current-website', $this->getById($id));
    }

    public function getCurrent() {
        return $this->getVar('current-website');
    }

    /**
     * Get website model by domain name
     *
     * @param string $domain
     * @return Website|null
     */
    public function getByDomain(string $domain) {
        $website = $this->getVarByField('domain', $domain);

        if($website) {
            return $website;
        }

        $website = $this->model->where('domain', $domain)->first();

        if($website) {
            $this->setVar($website->id, $website);
        }

        return $website;
    }

    public function getMetaValue(string $keyValue, string $lang = '') {
        $meta = $this->getCurrent()->metas()->where('meta_key', $keyValue)->where('lang', $lang)->first();

        if (!$meta) {
            return null;
        }

        return DataFormat::toFormat($meta->meta_value, $meta->meta_format);
    }

    public function getMetaLangValue(string $keyValue) {
        return $this->getMetaValue($keyValue, isLang());
    }
}

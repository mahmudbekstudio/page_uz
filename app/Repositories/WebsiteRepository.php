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
     * @param string $id
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
        $website = $this->getById($id);

        if ($website->domain_id) {
            $website = $this->getById($website->domain_id);
        }

        $this->setVar('current-website', $website);
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
        if(isset($_ENV['current-website'])) {
            $website = new Website($_ENV['current-website']);
            $this->setVar($website->id, $website);
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
        $result = $this->getVar('metas_' . $id);

        if(!$result) {
            $current = $id ? $this->getById($id) : $this->getCurrent();
            $metas = $current->metas;
            $result = [];

            foreach($metas as $meta) {
                $key = $meta->lang ? $meta->meta_key . '_' . $meta->lang : $meta->meta_key;
                $result[$key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
            }

            $this->setVar('metas_' . $id, $result);
        }

        return $result;
    }

    public function getMetaValue(string $keyValue, string $lang = '') {
        $metas = $this->getMetas();
        $key = $lang ? $keyValue . '_' . $lang : $keyValue;
        return $metas[$key] ?: null;
    }

    public function getMetaLangValue(string $keyValue) {
        return $this->getMetaValue($keyValue, isLang());
    }
}

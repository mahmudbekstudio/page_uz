<?php
namespace App\Repositories;

use App\Helpers\DataFormat;
use App\Models\User;
use App\Models\Website;
use App\Repositories\Traits\StaticInstance;
use App\Repositories\Traits\Vars;

class UserRepository extends BaseRepository {

    use StaticInstance, Vars;

    /**
     * model
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function getByEmail($email)
    {
        $user = $this->getVarByField('email', $email);

        if(!$user) {
            $user = $this->findByField('email', $email)->first();

            if(!$user) {
                return null;
            }
        }

        $this->setVar($user->id, $user);

        return $user;
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

    public function getAuthed()
    {
        return auth()->user();
    }

    public function getMetas($id = 0)
    {
        $result = $this->getVar('metas_' . $id);

        if(!$result) {
            $current = $id ? $this->getById($id) : $this->getAuthed();
            $metas = $current->metas;
            $result = [];

            foreach($metas as $meta) {
                $result[$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
            }

            $this->setVar('metas_' . $id, $result);
        }

        return $result;
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

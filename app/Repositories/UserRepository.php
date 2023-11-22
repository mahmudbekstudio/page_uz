<?php
namespace App\Repositories;

use App\Helpers\DataFormat;
use App\Models\User;
use App\Repositories\Traits\GetById;
use App\Repositories\Traits\StaticInstance;
use App\Repositories\Traits\Vars;

class UserRepository extends BaseRepository {

    use StaticInstance, Vars, GetById;

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

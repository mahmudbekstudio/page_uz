<?php

use App\Helpers\DataFormat;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use App\Repositories\WebsiteRepository;

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        dd(22222);
        if(!isset($parameters['domain'])) {
            $parameters['domain'] = $_SERVER['HTTP_HOST'];
        }

        return app('url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('update_dotenv')) {
    /**
     * Update DotEnv file
     *
     * @param string $filePath
     * @param array $values
     */
    function update_dotenv(string $filePath, array $values)
    {
        $updated = [];
        $lines = file($filePath);
        for($i = 0; $i < count($lines); $i++) {
            $pos = strpos($lines[$i], '=');
            $varName = substr($lines[$i], 0, $pos);

            if (isset($values[$varName])) {
                $lines[$i] = $varName . '=' . $values[$varName];
                $updated[] = $varName;
            }
        }

        foreach($values as $key => $val) {
            if(!in_array($key, $updated)) {
                $lines[] = $key . '=' . $val;
            }
        }

        file_put_contents($filePath, implode("\n", $lines));
    }
}

if (! function_exists('generateRandomKey')) {
    /**
     * Random key generate
     *
     * @return string
     */
    function generateRandomKey()
    {
        return 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
    }
}

if (! function_exists('randomString64')) {
    /**
     * Random string 64
     *
     * @return string
     */
    function randomString64()
    {
        return Str::random(64);
    }
}

if (! function_exists('responseJson')) {
    /**
     * Response json
     *
     * @param bool $result
     * @param array $message
     * @param array $data
     * @param array $setting
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJson(bool $result, array $message = [], array $data = [], array $setting = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'result' => $result,
            'message' => $message,
            'data' => $data,
            'setting' => []
        ]);
    }
}

if (! function_exists('responseJsonMessage')) {
    /**
     * Response json message
     *
     * @param bool $result
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJsonMessage(bool $result, $message, array $setting = []): \Illuminate\Http\JsonResponse
    {
        return responseJson($result, (array)$message, [], $setting);
    }
}

if (! function_exists('responseJsonData')) {
    /**
     * Reponse json data
     *
     * @param bool $result
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function responseJsonData(bool $result, array $data, array $setting = []): \Illuminate\Http\JsonResponse
    {
        return responseJson($result, [], $data, $setting);
    }
}

if (! function_exists('responseSetting')) {
    /**
     * Response setting
     *
     * @param array $setting
     * @return array
     */
    /*function responseSetting(array $setting): array
    {
        return !empty($setting) ? $setting : ['user' => auth()->user()];
    }*/
}

if (! function_exists('getUserByToken')) {
    /**
     * Get user by token
     *
     * @param string $token
     * @return \App\Models\User|null
     */
    function getUserByToken(string $token)
    {
        if(!$token) {
            return null;
        }

        try {
            dd(JWTAuth::setToken($token));
            //dd(JWTAuth::refresh());
            return JWTAuth::toUser();
        } catch (Exception $e) {
            return null;
        }
    }
}

if (! function_exists('getUserData')) {
    /**
     * Get user data
     *
     * @param \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|int|null $user
     * @return array
     */
    function getUserData($user = null): array
    {
        $userData = [];

        if (is_int($user) && $user > 0) {
            try {
                $user = app(UserRepository::class)->getById($user);
            } catch (Exception $e) {
                $user = null;
            }
        } elseif(is_null($user)) {
            $user = auth()->user();
        }

        if($user) {
            $userData = [
                'id' => $user->id,
                'status' => $user->status,
                'email' => $user->email,
                'role' => $user->role,
                'meta' => [],
                'created_at' => $user->created_at,
            ];

            foreach($user->metas as $meta) {
                $userData['meta'][$meta->meta_key] = DataFormat::toFormat($meta->meta_value, $meta->meta_format);
            }
        }

        return $userData;
    }
}

if (! function_exists('websiteData')) {
    function websiteData($isJson = false)
    {
        $instance = WebsiteRepository::getInstance();
        $website = $instance->getCurrent();
        $data = [
            'id' => $website->id,
            'status' => $website->status,
            'domain' => app()->domain(),
            'created' => $website->created_at,
            'metas' => $instance->getMetas()
        ];
        return $isJson ? json_encode($data) : $data;
    }
}

if (! function_exists('settingData')) {
    function settingData()
    {
        $user = auth()->user();

        if(!$user) {
            return [];
        }

        $data = [
            'user' => getUserData($user),
        ];
        return $data;
    }
}

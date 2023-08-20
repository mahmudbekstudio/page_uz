<?php

namespace App\Exceptions;

use App\Repositories\WebsiteRepository;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            $path = explode('/', $request->path());
            $websiteMetas = WebsiteRepository::getInstance()->getMetas();

            if (
                !count($path) ||
                $path[0] === '' ||
                in_array($path[0], Arr::get($websiteMetas, 'languages_list'))
            ) {
                return goHome();
            }

            return go404();
        }

        return parent::render($request, $e);
    }
}

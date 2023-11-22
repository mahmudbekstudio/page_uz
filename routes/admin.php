<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Ajax routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your Ajax!
|
*/

use App\Http\Controllers\Admin\FolderFileController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\FeatureController;

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('settings', [MainController::class, 'settings'])->name('settings');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('profile', [UserController::class, 'getProfile'])->name('getProfile');
        Route::post('profile', [UserController::class, 'updateProfile'])->name('updateProfile');
    });

    Route::group(['middleware' => [
        'role:' . implode('|', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_PUBLISHER])
    ]], function() {
        // for super admin, admin, manager and publisher

        Route::group(['middleware' => [
            'role:' . implode('|', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN, User::ROLE_MANAGER])
        ]], function() {
            // for super admin, admin and manager
            Route::group(['prefix' => 'file-manager', 'as' => 'file-manager.'], function () {
                Route::get('folder-content/{id}', [FolderFileController::class, 'folderContent'])->name('folder-content');
                Route::get('folder-static-content/{id}', [FolderFileController::class, 'folderStaticContent'])->name('folder-static-content');
                Route::post('create-folder', [FolderFileController::class, 'createFolder'])->name('create-folder');
                Route::put('rename-folder', [FolderFileController::class, 'renameFolder'])->name('rename-folder');
                Route::delete('delete-folder/{id}', [FolderFileController::class, 'deleteFolder'])->name('delete-folder');
                Route::post('upload-file/{id}', [FolderFileController::class, 'uploadFile'])->name('upload-file');
                Route::post('rename-file', [FolderFileController::class, 'renameFile'])->name('rename-file');
                Route::delete('delete-file/{id}', [FolderFileController::class, 'deleteFile'])->name('delete-file');
            });

            Route::group(['prefix' => 'post/{type}', 'as' => 'post.'], function () {
                Route::get('list', [PostController::class, 'list'])->name('list');
                Route::post('create', [PostController::class, 'create'])->name('create');
                Route::put('edit/{post}', [PostController::class, 'edit'])->name('edit');
                Route::get('get/{post}', [PostController::class, 'get'])->name('get');
                Route::delete('delete/{post}', [PostController::class, 'delete'])->name('delete');

                Route::get('active-list/{selectedId}', [PostController::class, 'activeList'])->name('active-list');
            });

            Route::group(['prefix' => 'category/{type}', 'as' => 'category.'], function () {
                Route::get('list', [CategoryController::class, 'list'])->name('list');
                Route::post('create', [CategoryController::class, 'create'])->name('create');
                Route::put('edit/{category}', [CategoryController::class, 'edit'])->name('edit');
                Route::get('get/{category}', [CategoryController::class, 'get'])->name('get');
                Route::delete('delete/{category}', [CategoryController::class, 'delete'])->name('delete');

                Route::get('active-list/{selectedId}', [CategoryController::class, 'activeList'])->name('active-list');
            });

            Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
                Route::get('list', [UserController::class, 'list'])->name('list');
                Route::get('{id}', [UserController::class, 'byId'])->name('byId');
                Route::post('create', [UserController::class, 'create'])->name('create');
                Route::put('update/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('delete/{user}', [UserController::class, 'delete'])->name('delete');
            });

            Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
                Route::get('list', [MenuController::class, 'list'])->name('list');
                Route::get('get/{menu}', [MenuController::class, 'get'])->name('get');
                Route::post('create', [MenuController::class, 'create'])->name('create');
                Route::put('edit/{menu}', [MenuController::class, 'edit'])->name('edit');
                Route::delete('delete/{menu}', [MenuController::class, 'delete'])->name('delete');

                Route::get('links', [MenuController::class, 'links'])->name('links');
            });

            Route::group(['prefix' => 'template', 'as' => 'template.'], function () {
                Route::get('list', [TemplateController::class, 'list'])->name('list');
                Route::get('get/{template}', [TemplateController::class, 'get'])->name('get');
                Route::get('get-by-type/{type}', [TemplateController::class, 'getByType'])->name('get-by-type');
                Route::post('create', [TemplateController::class, 'create'])->name('create');
                Route::put('edit/{template}', [TemplateController::class, 'edit'])->name('edit');
                Route::delete('delete/{template}', [TemplateController::class, 'delete'])->name('delete');

                Route::get('blocks', [TemplateController::class, 'blocks'])->name('blocks');
            });

            Route::group(['prefix' => 'feature', 'as' => 'feature.'], function () {
                Route::get('list', [FeatureController::class, 'list'])->name('list');
                Route::get('get/{feature}', [FeatureController::class, 'get'])->name('get');
                Route::get('get-by-type/{type}', [FeatureController::class, 'getByType'])->name('get-by-type');
                Route::post('create', [FeatureController::class, 'create'])->name('create');
                Route::put('edit/{feature}', [FeatureController::class, 'edit'])->name('edit');
                Route::delete('delete/{feature}', [FeatureController::class, 'delete'])->name('delete');
            });

            Route::group(['middleware' => [
                'role:' . implode('|', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])
            ]], function() {
                // for super admin and admin
                Route::group(['prefix' => 'type', 'as' => 'type.'], function () {
                    Route::get('list', [TypeController::class, 'list'])->name('list');
                    Route::post('create', [TypeController::class, 'create'])->name('create');
                    Route::put('edit/{id}', [TypeController::class, 'edit'])->name('edit');
                    Route::get('get/{type}', [TypeController::class, 'get'])->name('get');
                    Route::get('get-by-type/{type}', [TypeController::class, 'getByType'])->name('get-by-type');
                    Route::delete('delete/{type}', [TypeController::class, 'delete'])->name('delete');
                    Route::get('categories', [TypeController::class, 'getCategories'])->name('categories');
                    Route::get('not-used-categories', [TypeController::class, 'getNotUsedCategories'])->name('not-used-categories');
                });

                Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
                    Route::get('get', [SettingController::class, 'get'])->name('get');
                    Route::put('update', [SettingController::class, 'update'])->name('update');
                });

                Route::group(['prefix' => 'website', 'as' => 'website.'], function () {
                    Route::get('list', [WebsiteController::class, 'list'])->name('list');
                    Route::get('{id}', [WebsiteController::class, 'byId'])->name('byId');
                    Route::post('create', [WebsiteController::class, 'create'])->name('create');
                    Route::put('update/{website}', [WebsiteController::class, 'update'])->name('update');
                    Route::delete('delete/{website}', [WebsiteController::class, 'delete'])->name('delete');
                });

                Route::group(['middleware' => ['role:' . User::ROLE_SUPER_ADMIN]], function() {
                    // for super admin
                });
            });
        });
    });
});

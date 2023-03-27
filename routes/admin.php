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
                Route::post('create-folder', [FolderFileController::class, 'createFolder'])->name('create-folder');
                Route::put('rename-folder', [FolderFileController::class, 'renameFolder'])->name('rename-folder');
                Route::delete('delete-folder/{id}', [FolderFileController::class, 'deleteFolder'])->name('delete-folder');
                Route::post('upload-file/{id}', [FolderFileController::class, 'uploadFile'])->name('upload-file');
                Route::post('rename-file', [FolderFileController::class, 'renameFile'])->name('rename-file');
                Route::delete('delete-file/{id}', [FolderFileController::class, 'deleteFile'])->name('delete-file');
            });

            Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
                Route::get('list', [UserController::class, 'list'])->name('list');
                Route::get('{id}', [UserController::class, 'byId'])->name('byId');
                Route::post('create', [UserController::class, 'create'])->name('create');
                Route::put('update/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('delete/{user}', [UserController::class, 'delete'])->name('delete');
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
                    Route::delete('delete/{type}', [TypeController::class, 'delete'])->name('delete');
                    Route::get('categories', [TypeController::class, 'getCategories'])->name('categories');
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

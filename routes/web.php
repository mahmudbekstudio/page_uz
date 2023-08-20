<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
    Route::post('login', [\App\Http\Controllers\Web\AuthController::class, 'login'])->name('login');
    Route::get('login-page', [\App\Http\Controllers\Web\AuthController::class, 'loginPage'])->name('login-page');
});

Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function() {
        Route::get('logout', [\App\Http\Controllers\Web\AuthController::class, 'logout'])->name('logout');
        Route::get('cabinet', [\App\Http\Controllers\Web\AuthController::class, 'cabinet'])->name('cabinet');
    });
});


Route::get('admin/auth/reset-password/{token}', [\App\Http\Controllers\Web\MainController::class, 'admin'])->name('admin.auth.forgot-password');
Route::get('admin/{any?}', [\App\Http\Controllers\Web\MainController::class, 'admin'])->where('any', '.*')->name('admin');

/*Route::get('/{routeName}/{any?}', function () {
    return '22222';
})->whereIn('routeName', config('app.special_routes'));*/

Route::get('{lang}/{typeName}/{routeName}', [\App\Http\Controllers\Web\MainController::class, 'index'])
    ->whereIn('lang', config('app.locale_list'));
/*Route::get('{any?}', [\App\Http\Controllers\Web\MainController::class, 'redirectToHome'])
    ->where('any', '.*');*/

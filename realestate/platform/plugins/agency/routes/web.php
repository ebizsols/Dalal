<?php

use Botble\Agency\Models\Agency;
use Botble\Agency\Http\Controllers\AgencyController;
use Botble\Agency\Http\Controllers\AgentController;
//echo "--tesdtgs"; exit;
Route::group(['namespace' => 'Botble\Agency\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'agencies', 'as' => 'agency.'], function () {

            Route::resource('', 'AgencyController')->parameters(['' => 'agency']);;

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AgencyController@deletes',
                'permission' => 'agency.destroy',
            ]);
        });
        Route::get('agencies/assignAgent/{id}', [
                'as'        => 'agency.assignAgent',
                'uses'      => 'AgentController@assignAgent',

        ]);
        //Route::resource('', 'AgentController')->parameters(['' => 'agency']);;
        Route::post('agencies/saveAgent', [
            'as'        => 'agency.saveAgent',
            'uses'      => 'AgentController@saveAgent',

        ]);
        // Route::POST('agencies/assignAgent/{id}/edit', [
        // 'as'        => 'agency.edit',
        // 'uses'      => 'AgentController@edit',

        // ]);

    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get(SlugHelper::getPrefix(Agency::class, 'agencies'), 'PublicController@agencies')
                ->name('public.agencies');

            Route::get(SlugHelper::getPrefix(Agency::class, 'agencies') . '/{slug}', 'PublicController@agency');
        });
    }
});
//Route::post('/admin/addageency', [AgencyController::class,'addagency']);

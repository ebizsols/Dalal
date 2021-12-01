<?php

use Botble\Auction\Models\Auction;
//echo "--tesdtgs"; exit;
Route::group(['namespace' => 'Botble\Auction\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'auctions', 'as' => 'auction.'], function () {

            Route::resource('', 'AuctionController')->parameters(['' => 'auction']);;

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AuctionController@deletes',
                'permission' => 'auction.destroy',
            ]);
        });
        Route::get('auctions/assignAgent/{id}', [
                'as'        => 'auction.assignAgent',
                'uses'      => 'AgentController@assignAgent',

        ]);
        //Route::resource('', 'AgentController')->parameters(['' => 'auction']);;
        Route::post('auctions/saveAgent', [
            'as'        => 'auction.saveAgent',
            'uses'      => 'AgentController@saveAgent',

        ]);
        // Route::POST('auctions/assignAgent/{id}/edit', [
        // 'as'        => 'auction.edit',
        // 'uses'      => 'AgentController@edit',

        // ]);

    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get(SlugHelper::getPrefix(Auction::class, 'auctions'), 'PublicController@auctions')
                ->name('public.auctions');

            Route::get(SlugHelper::getPrefix(Auction::class, 'auctions') . '/{slug}', 'PublicController@auction');
        });
    }
});
//Route::post('/admin/addageency', [AuctionController::class,'addauction']);

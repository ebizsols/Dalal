<?php

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
            
//
            Route::get('displayBids/{id}', [
                'as'         => 'displayBids',
                'uses'       => 'AuctionController@displayBids',
                'permission' => 'auction.displayBids',
            ]);
            Route::post('displayBids/{id}', [
                'as'         => 'displayBids',
                'uses'       => 'AuctionController@displayBids',
                'permission' => 'auction.displayBids',
            ]);
//            Route::get('displayBids', 'AuctionController@displayBids');
        });

    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get(SlugHelper::getPrefix(Auction::class, 'auctions'), 'PublicController@auctions')
                ->name('public.auctions');

            Route::get(SlugHelper::getPrefix(Auction::class, 'auctions') . '/{slug}', 'PublicController@agency');
        });
    }
});

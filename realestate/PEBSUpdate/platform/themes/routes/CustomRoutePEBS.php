<?php
Route::get('agencies', 'FlexHomeController@getAgencies')->name('public.agencies');
Route::get('agency/{id}', 'FlexHomeController@getAgency')->name('public.agency');
Route::get('ajax/agencies/featured', 'FlexHomeController@ajaxGetFeaturedAgencies')->name('public.ajax.featured-agencies');

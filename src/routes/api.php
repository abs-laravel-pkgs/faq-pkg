<?php
Route::group(['namespace' => 'Abs\FaqPkg\Api', 'middleware' => ['api']], function () {
	Route::group(['prefix' => 'faq-pkg/api'], function () {
		Route::group(['middleware' => ['auth:api']], function () {
			// Route::get('taxes/get', 'TaxController@getTaxes');
		});
	});
});
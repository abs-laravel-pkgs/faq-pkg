<?php

Route::group(['namespace' => 'Abs\FaqPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'faq-pkg'], function () {

	//CUSTOMERS
	Route::get('/faqs/get-list', 'FaqController@getFaqList')->name('getFaqList');
	Route::get('/faq/get-form-data', 'FaqController@getFaqFormData')->name('getFaqFormData');
	Route::post('/faq/save', 'FaqController@saveFaq')->name('saveFaq');
	Route::get('/faq/delete/{id}', 'FaqController@deleteFaq')->name('deleteFaq');

});
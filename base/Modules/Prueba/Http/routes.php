<?php

Route::group(['middleware' => 'web', 'prefix' => 'prueba', 'namespace' => 'Modules\Prueba\Http\Controllers'], function()
{
    Route::get('/', 'PruebaController@index');
});

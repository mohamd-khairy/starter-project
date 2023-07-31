<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1/report', 'middleware' => 'auth:sanctum'], function () {

    Route::get('builder', 'ReportController@index'); //
    Route::get('show', 'ReportController@show'); //

    Route::post('config/chart-details', 'ConfigController@updateDetails')->name('dashboard.config.chart_details');

    Route::get('draft-actions', 'DraftController@actions');
    Route::get('draft/{id}/draw', 'DraftController@drawDraft');
    Route::post('draft-this', 'DraftController@storeDraft'); //
    Route::resource('draft', 'DraftController')->only('index', 'destroy', 'update', 'show');

    Route::get('pinned-actions', 'PinnedController@actions');
    Route::post('pinned/add-draft', 'PinnedController@addDraft');
    Route::get('pinned/get-related-draft', 'PinnedController@getRelatedDraft');
    Route::get('pinned/{id}/draw', 'PinnedController@drawPinned');
    Route::post('pinned/{id}/status', 'PinnedController@status');
    Route::get('pinned/{id}/reload', 'PinnedController@reload');
    Route::get('pinned-active', 'PinnedController@active');
    Route::resource('pinned', 'PinnedController')->only('index', 'destroy', 'update', 'show');

    /**************************************************************************************************** */
    Route::get('config', 'ConfigController@index')->name('dashboard.config.index');
    Route::get('{modalType}/files', 'ArchiveFilesController@index')->name('dashboard.files.index');
    Route::get('files/{file}/download', 'ArchiveFilesController@download')->name('dashboard.files.download');
    Route::delete('files/destroy/{file}', 'ArchiveFilesController@destroy')->name('dashboard.files.destroy');
    Route::get('files/download/{file}', 'ArchiveFilesController@downloadFile');
    Route::get('archive', 'ReportController@archive');
});

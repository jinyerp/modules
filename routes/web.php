<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

//if(isset($prefixAdmin) && !$prefixAdmin)
$prefixAdmin = "admin";

Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.')
->prefix($prefixAdmin)->group(function () {

    // 모듈관리
    Route::resource('modules',\Jiny\Modules\Http\Controllers\Modules::class);


});

<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// 모듈에서 설정되 접속 prefix값을 읽어 옵니다.
$prefix = "_admin";//admin_prefix();

Route::middleware(['web','auth:sanctum', 'verified'])
->name('admin.')
->prefix($prefix)->group(function () {

    // 모듈관리
    Route::get('modules',[\Jiny\Modules\Http\Controllers\Modules::class,"index"]);
    Route::get('module/store',[\Jiny\Modules\Http\Controllers\ModuleStore::class,"index"]);

    Route::resource('module/setting', \Jiny\Modules\Http\Controllers\SettingController::class);

});

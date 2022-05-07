<?php

use Illuminate\Support\Facades\DB;

if(!function_exists("isModule")) {
    function isModule($code) {
        /*
        $row = DB::table('jiny_modules')
            ->where('enable',true)
            ->where('code',$code)->first();
        if($row) {
            return $row->installed;
        }
        return false;
        */
        if(function_exists($code)) return true;
        return false;
    }
}

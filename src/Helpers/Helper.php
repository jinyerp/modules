<?php

use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

if(!function_exists("isModule")) {
    function isModule($code) {
        if(Module::has($code)) {
            return true;
        }
        return false;
    }
}

if(!function_exists("moduleName")) {
    function moduleName($code)
    {
        $temp = explode('-',$code);
        $moduleName = "";

        foreach($temp as $name) {
            $moduleName .= ucfirst($name);
        }
        return $moduleName;
    }
}


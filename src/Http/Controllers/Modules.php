<?php

namespace Jiny\Modules\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

use Nwidart\Modules\Facades\Module;
use CzProject\GitPhp\Git;

use Jiny\Table\Http\Controllers\ResourceController;
class Modules extends ResourceController
{
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Controllers\SetMenu;

    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        $this->setVisit($this); // Livewire와 양방향 의존성 주입

        $this->actions['table'] = "jiny_modules"; // 테이블 정보
        $this->actions['paging'] = 100; // 페이지 기본값

        $this->actions['view_main'] = "modules::modules.main";
        $this->actions['view_main_layout'] = "modules::modules.main_layout";

        $this->actions['view_list'] = "modules::modules.list";
        $this->actions['view_form'] = "modules::modules.form";


        // 테마설정
        //setTheme("admin/sidebar");
    }

    public function hookIndexed($wire, $rows)
    {
        $path = base_path('modules').DIRECTORY_SEPARATOR;

        foreach($rows as $i => $row) {
            if($row->url) {
                $rows[$i]->ext = substr($row->url,strrpos($row->url,'.')+1);
            } else {
                $rows[$i]->ext = "";
            }



            if(is_dir($path.$row->code)) {
                $git = new Git;
                $repo = $git->open($path.$row->code);
                $tags = $repo->getTags();
                if(is_array($tags)) {
                    $version = array_reverse($tags);
                    $rows[$i]->version = $version[0]; //최종버젼
                } else {
                    $rows[$i]->version = null;
                }
            } else {
                $rows[$i]->version = null;
            }


        }

        /*
        dd(Module::all());


        $path = base_path('Modules').DIRECTORY_SEPARATOR."modules_statuses.json";
        if(file_exists($path)) {
            $modules = json_decode(file_get_contents($path),true);
        } else {
            $modules = [];
        }

        for($i=0;$i<count($rows);$i++) {
            $name = ucfirst($rows[$i]->code);

            if(isset($modules[$name])) {
                $rows[$i]->enable = $modules[$name];
                $rows[$i]->installed = true;
            } else {
                $rows[$i]->enable = false;
                $rows[$i]->installed = false;
            }
        }
        */


        return $rows;
    }



    public function hookStored($wire, $form)
    {
        // 모듈 패키지의 setup/install 실행
        /*
        $name = explode('/', $form['code']);
        $namespace = "\\".ucfirst($name[0])."\\".ucfirst($name[1])."\\"."Setup";
        $mod = new $namespace ();
        $mod->install();
        */
    }


}

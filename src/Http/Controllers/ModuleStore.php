<?php
namespace Jiny\Modules\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Models\User;
//use App\Models\Role;
//use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
//use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

use Illuminate\Support\Facades\DB;

use Nwidart\Modules\Facades\Module;
use CzProject\GitPhp\Git;
use GuzzleHttp\Client;

use Jiny\Table\Http\Controllers\ResourceController;
class ModuleStore extends ResourceController
{
    use \Jiny\Table\Http\Livewire\Permit;
    use \Jiny\Table\Http\Controllers\SetMenu;

    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        $this->setVisit($this); // Livewire와 양방향 의존성 주입
    }

    /*
    private function getStoreUrl()
    {
        $url = "https://jinyerp-src.github.io/module-server/modules.json";
        $client = new Client();
        $body = $client->get($url)->getBody();
        return json_decode($body);
    }

    private function getStoreFile()
    {
        $path = base_path('modules').DIRECTORY_SEPARATOR;
        $filename = $path.'modules.json';
        $json = file_get_contents($filename);
        return json_decode($json);
    }

    private function parsing($rows)
    {
        // 설치된 모듈 정보
        $module_info = DB::table("jiny_modules")->get();

        foreach($rows as $i => $row) {
            if($row->url) {
                $rows[$i]->ext = substr($row->url,strrpos($row->url,'.')+1);
            } else {
                $rows[$i]->ext = "";
            }

            $rows[$i]->installed = null; //초기화
            foreach($module_info as $module) {
                if($row->code == $module->code) {
                    // 설치가 되어 있는 경우
                    $rows[$i]->installed = $module->installed;
                    break;
                }
            }





        }

        return $rows;
    }
    */


    public function index(Request $request)
    {
        // 메뉴 설정
        $this->menu_init();

        return view("modules::store.main",[
            'actions' => $this->actions
        ]);

    }



}

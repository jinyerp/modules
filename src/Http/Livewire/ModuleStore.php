<?php

namespace Jiny\Modules\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use ZipArchive;

use Nwidart\Modules\Facades\Module;
use CzProject\GitPhp\Git;

class ModuleStore extends Component
{
    public $modules=[];

    public function mount()
    {
        $rows = $this->getStoreUrl();
        $this->modules = $this->parsing($rows);
        //dd($this->modules);
    }

    public function render()
    {
        return view("modules::store.livewire.list");
    }

    private function getStoreUrl()
    {
        $url = "https://jinyerp-src.github.io/module-server/modules.json";
        $client = new Client();
        $body = $client->get($url)->getBody();
        return json_decode($body);
    }

    private function parsing($rows)
    {
        // 설치된 모듈 정보
        $module_info = DB::table("jiny_modules")->get();

        $data = [];

        foreach($rows as $i => $row) {
            $item = [];
            foreach($row as $key => $value) {
                $item[$key] = $value;
            }

            if($row->url) {
                $item['ext'] = substr($row->url,strrpos($row->url,'.')+1);
            } else {
                $item['ext'] = null;
            }

            $item['installed'] = null; //초기화
            foreach($module_info as $module) {
                if($row->code == $module->code) {
                    // 설치가 되어 있는 경우
                    $item['installed'] = $module->installed;
                    break;
                }
            }

            $code = $row->code;
            $data[$code] = $item;
            //$rows[$i] = $item;


            /*
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
            */


        }

        return $data;
    }

    private function getItem($code)
    {
        //dd($this->modules);
        foreach( $this->modules as $module) {
            if($module['code'] == $code) {
                return $module;
            }
        }
    }

    public function install($code)
    {

        $module = $this->getItem($code);
        if($module) {
            //dd($module);
            if($module['ext'] == "zip") {
                dd($code.": download zip file");
            } else
            if($module['ext'] == "git") {
                //dd($code.": clone");
                $this->repoClone($module);
            }
        }
    }

    protected $listeners = [
        'popupInstallOpen','popupInstallClose',
        'install',
        'uninstall',
        'enable',
        'disable'
    ];

    public $popup = false;

    public function popupInstallOpen()
    {
        $this->popup = true;
    }

    public function popupInstallClose()
    {
        $this->popup = false;
    }

    private function repoClone($item)
    {
        $moduleName = $this->moduleName($this->item['code']);

        // 경로 생성
        //$vendor = explode("/",$item['code']);
        $path = base_path('modules').DIRECTORY_SEPARATOR.$moduleName;
        if(!is_dir($path)) {
            mkdir($path, 777, true);
        }


        // 깃 저장소 복제
        $git = new Git;
        $repo = $git->cloneRepository($item['url'], $path);

        // 4. 모듈정보 DB 삽입
        DB::table("jiny_modules")->insert([
            'code' => $item['code'],
            'enable'=>1,
            'installed'=> date("Y-m-d H:i:s")
        ]);

        // json 정보 갱신
        $code = $item['code'];
        $this->modules[$code]['enable'] = 1;
        $this->modules[$code]['installed'] = date("Y-m-d H:i:s");

        // 모듈 활성화
        $module = Module::find($moduleName);
        if($module) {
            $module->enable();
        }


        //$this->item=[]; // 초기화
        //$this->mode = null;
        //$this->popupInstallClose();

        // 모듈 정보파일 새로 생성
        $this->createJsonModule();


        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    // CamelCase 형태로 모듈 이름 반환
    private function moduleName($code)
    {
        $tmep = explode('-',$code);
        $moduleName = "";
        foreach($temp as $name) {
            $moduleName .= ucfirst($name);
        }
        return $moduleName;
    }

    private function createJsonModule()
    {
        $path = base_path('modules').DIRECTORY_SEPARATOR;
        $filename = $path.'modules.json';
        $module_info = DB::table("jiny_modules")->get();
        $json = json_encode($module_info, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        file_put_contents($filename, $json);
    }


}

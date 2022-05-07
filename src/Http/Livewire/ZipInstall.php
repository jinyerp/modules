<?php

namespace Jiny\Modules\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use ZipArchive;

class ZipInstall extends Component
{
    public $actions;

    public function mount()
    {

    }

    public function render()
    {
        return view("jinyadmin::livewire.popup.install");
    }


    /** ----- ----- ----- ----- -----
     *  팝업창 관리
     */
    protected $listeners = [
        'popupInstallOpen','popupInstallClose',
        'install',
        'uninstall',
        'enable',
        'disable'
    ];
    public $popupInstall = false;

    public function popupInstallOpen()
    {
        $this->popupInstall = true;
    }

    public function popupInstallClose()
    {
        $this->popupInstall = false;
    }


    /** ----- ----- ----- ----- -----
     *  설치 프로세스
     */
    public $code;
    public $mode;
    public $item;
    public function install($code)
    {
        $this->code = $code;
        $this->mode = "install";

        // 정보 데이터 읽기
        $row = $this->fetch($code);
        if ($row) {
            foreach($row as $key => $value) {
                $this->item[$key] = $value;
            }
        }

        $this->popupInstallOpen();
    }


    /**
     *  파일 다운로드
     */
    public function download()
    {
        $this->item['url'] = "https://github.com/jinyerp/work-module/archive/refs/heads/master.zip";

        // 다운로드 url 체크
        if($this->item['url']) {

            // 1. 다운로드
            $path = base_path('Modules').DIRECTORY_SEPARATOR;
            $filename = $path.str_replace("/","-",$this->item['code']).".zip";
            $source = $this->item['url'];
            $response = (new Client)->get($source);
            file_put_contents($filename, $response->getBody());

            $vendor = explode("/",$this->item['code']);

            // 2. 압축풀기
            if (file_exists($filename)) {
                $archive = new ZipArchive;
                $archive->open($filename);
                $archive->extractTo($path.$vendor[0]); // 압축풀기
                $archive->close();

                // 다운로드 파일 삭제
                unlink($filename);
            }

            // laravel-module add
            $modulesPath = base_path("Modules").DIRECTORY_SEPARATOR."modules_statuses.json";
            $modules = json_decode(file_get_contents($modulesPath), true);
            $code = ucfirst($this->item['code']);
            $modules[$code] = false;
            file_put_contents($modulesPath, json_encode($modules,JSON_PRETTY_PRINT));


            // 4. DB 정보 갱신
            $row = DB::table("jiny_modules")->where('code',$this->item['code'])->first();
            if($row) {
                // 기존 설치되어 있는 경우, 설치일자만 재설정
                DB::table("jiny_modules")->where('code',$this->item['code'])->update([
                    'installed'=> date("Y-m-d H:i:s")
                ]);
            }

        } else {
            // 다운로드 url이 없습니다.
        }

        $this->item=[]; // 초기화
        $this->mode = null;
        $this->popupInstallClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }


    /** ----- ----- ----- ----- -----
     *  제거 프로세스
     */
    public function uninstall($code)
    {
        $this->code = $code;
        $this->mode = "uninstall";

        // 정보 데이터 읽기
        $row = $this->fetch($code);
        if ($row) {
            foreach($row as $key => $value) {
                $this->item[$key] = $value;
            }
        }

        $this->popupInstallOpen();
    }

    public function remove()
    {
        if ($this->item['code']) {

            // 모든 파일을 삭제
            $path = base_path('Modules').DIRECTORY_SEPARATOR;
            $filename = $path.$this->item['code'];
            if(file_exists($filename) && is_dir($filename)) {
                $this->unlinkAll($filename);
            }

            // laravel-module add
            $modulesPath = base_path("Modules").DIRECTORY_SEPARATOR."modules_statuses.json";
            $modules = json_decode(file_get_contents($modulesPath), true);
            $code = ucfirst($this->item['code']);
            unset($modules[$code]);
            file_put_contents($modulesPath, json_encode($modules,JSON_PRETTY_PRINT));

            // 테이블 갱신
            DB::table("jiny_modules")->where('code',$this->item['code'])->update([
                'enable'=>false,
                'installed'=> false
            ]);
        }

        $this->item=[]; // 초기화
        $this->mode = null;
        $this->popupInstallClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');
    }

    // delete all files and sub-folders from a folder
    public function unlinkAll($dir) {
        foreach( scandir($dir) as $file) {
            if($file == "." || $file == "..") continue;
            if(is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
                $this->unlinkAll($dir.DIRECTORY_SEPARATOR.$file);
            } else {
                //dump($dir.DIRECTORY_SEPARATOR.$file);
                unlink($dir.DIRECTORY_SEPARATOR.$file);
            }
        }
        rmdir($dir);
    }


    protected function fetch($code)
    {
        $row = DB::table("jiny_modules")->where('code',$code)->first();
        return $row;
    }


    public function enable($code)
    {
        // laravel-module
        $modulesPath = base_path("Modules").DIRECTORY_SEPARATOR."modules_statuses.json";
        $modules = json_decode(file_get_contents($modulesPath), true);
        $code = ucfirst($code);
        $modules[$code] = true;
        file_put_contents($modulesPath, json_encode($modules,JSON_PRETTY_PRINT));

        //$this->popupInstallClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');

    }

    public function disable($code)
    {
        //$this->popupInstallOpen();

        // laravel-module
        $modulesPath = base_path("Modules").DIRECTORY_SEPARATOR."modules_statuses.json";
        $modules = json_decode(file_get_contents($modulesPath), true);
        $code = ucfirst($code);
        $modules[$code] = false;
        file_put_contents($modulesPath, json_encode($modules,JSON_PRETTY_PRINT));

        //$this->popupInstallClose();

        // Livewire Table을 갱신을 호출합니다.
        $this->emit('refeshTable');

    }


    /** ----- ----- ----- ----- -----
     *  json 데이터 읽기
     */
    /*
    public $dataType = "table";
    protected function dataFetch($actions)
    {
        // table 필드를 source로 활용
        if(isset($actions['table']) && $actions['table']) {
            $source = $actions['table'];
        }

        if(isset($actions['source']) && $actions['source']) {
            $source = $actions['source'];
        }

        //$source = "https://jinytheme.github.io/store/themelist.json";
        if ($source) {
            if($pos = strpos($source,"://")) {
                $this->dataType = "uri";

                // url 리소스
                $response = HTTP::get($source);
                $body = $response->body();
                $json = json_decode($body);
                return $json->data;
            } else {
                $this->dataType = "file";

                // 파일 리소스
                $path = resource_path().$source;
                if (file_exists($path)) {
                    $json = file_get_contents($path);
                    $rows = json_decode($json)->data;
                    return $rows;
                }
            }
        }

        return [];
    }
    */
}

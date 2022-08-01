# Jiny Module Management for laravel
`지니모듈`은 `nWidart/laravel-modules`의 확장 패키지로서 라라벨 프레임워크에서 패키지 형태의 
모듈을 생성하고 관리하는 도구 모음 입니다.

## 설치
컴포저 명령을 통하여 다음과 같이 콘솔 창에서 실행합니다.
```
composer require jiny/modules
```

설정파일 배포
```
php artisan vendor:publish --provider="Jiny\Modules\JinyModulesServiceProvider"
```

composer.json 파일을 열어 Modules에 대한 네임스페이스를 추가합니다.
```
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/"
    }
  }
}
```

수정후에는 다시 컴포저를 갱신합니다.

```
composer dump-autoload
```

## 확장 atrisan 명령

### 설치된 모듈리스트 출력
```
php artisan module:list
```

### url경로를 통하여 다운로드 설치
```
php artisan module:geturl 주소명
```
* 깃주소: 외부 접근이 가능한 공개된 저장소만 가능
* zip파일: 직접 다운로드가 가능한 경로만 가능

### 모듈 삭제
```
php artisan module:remove 모듈명
```



## 메뉴얼
* [nWidart/laravel-modules](https://nwidart.com/laravel-modules/v6/introduction)

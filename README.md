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
php artisan vendor:publish --provider="Jiny\Modules\JinyModuleServiceProvider"
```

## 메뉴얼
* [nWidart/laravel-modules](https://nwidart.com/laravel-modules/v6/introduction)
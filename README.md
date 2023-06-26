# NAVER SMART Editor for Laravel




## Installation
```
composer require wangta69/laravel-editor
```
```
php artisan vendor:publish --provider="Pondol\Editor\EditorServiceProvider"
```

### 2. 이미지등을 저장할 storage를 만든다.
> 아래와 같이 걸어두면 /project/public/storage 의 내용들이  /project/storage/app/public 으로 심볼릭 링크로 된다.
```
php artisan storage:link
```
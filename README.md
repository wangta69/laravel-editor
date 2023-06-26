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


####
> https;//yourdomain/editor/smart-editor

naver smart editor 가 정상적으로 노출되는 것을 확인했다면 이제 실제 프로제트에서 editor를 호출하여 작업해 보자
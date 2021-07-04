構築  

---------------  
事前設定  
1. env.example、laravel/.env.example、frontApp/.env.exampleをそれぞれコピーして.envファイルを作成し、環境変数を設定する
2. ./etc/nginx/conf.d/default.conf のserver_nameを、ローカルマシンの/etc/hosts  にも設定しておく
---------------  
$ cd frontApp  
$ yarn install  
$ yarn build  
$ docker-compose up -d  
$ docker-compose exec app ash  
$ composer update --dev  
$ php artisan key:generate  
$ php artisan config:cache  
$ php artisan migrate  
$ exit  
$ docker-compose exec front_app ash  
$ yarn install  
#!/bin/bash
angular_node_file="./node_modules/zone.js/package.json";
laravel_vendor_file="./vendor/doctrine/dbal/composer.json";

cd angularcrud;

if [ -f "$angular_node_file" ]
then
	echo "Angular node modules already installed, skip npm install ...";
else
	npm install;
fi

PROCESS_NUM=$(lsof -n -i:4520|wc -l)
if [ $PROCESS_NUM -eq 0 ]; # not exist
then
	nohup ng serve --host 127.0.0.1  --port=4520 --proxy-config=proxy.config.json > log_ng_serve.log &
else
	echo "Seems Angular frontend ng serve is running ...";
fi

cd ../laravelapi;
pwd;
if [ -f "$laravel_vendor_file" ]
then
	echo "Laravel vendor already installed, skip composer update ... ";
else
	composer update;
fi

PROCESS_NUM=$(ps -ef | grep "port=4624" | grep -v "grep" | wc -l)
if [ $PROCESS_NUM -eq 0 ]; # not exist
then
	nohup php artisan serve --host=127.0.0.1 --port=4624 > storage/logs/artisan_serve.log &
else
	echo "Seems Laravel backend is running ...";
fi


echo "";
echo "##############################"
echo "";
echo "Starting generate Angurlar CRUD component code:"
echo "";
echo "##############################"
cd ../;
php ./laravelapi/artisan angular:crud;



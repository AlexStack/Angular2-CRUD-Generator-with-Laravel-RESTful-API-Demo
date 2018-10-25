@echo off
cls
title Setup dependencies environment for AngularCRUD frontend and Laravel backend API server
echo ##############################
echo:
echo Updating dependencies environment for AngularCRUD frontend and Laravel backend API server
echo:
echo ##############################
echo: 
echo You need install angular cli and composer first and I assumed you already have them
echo: 
echo This script will open 2 cmd windows, One is Angular frontend http://localhost:4520
echo Another cmd window is for Laravel backend API server provide demo API data
echo:

:: start Angular Front Live Development Server
cd angularcrud

netstat -o -n -a | findstr /RC:":4520 .*LISTENING" > NUL
if "%ERRORLEVEL%" equ "0" (
  echo Angular Frontend Port 4520 is listening ...
) else (
	if exist ./node_modules/zone.js (
	    echo Angular node modules  exists, skip npm install ... 
	    start "Angular frontend, ng serve" /min cmd /c "ng serve --host 127.0.0.1  --port=4520 --proxy-config=proxy.config.json"
	) else (
	    echo Angular node modules  not exists, open a new cmd window for npm install and ng serve ...
	    start "Angular frontend, ng serve" /min cmd /c "npm install && ng serve --host 127.0.0.1  --port=4520 --poll=1500 --proxy-config=proxy.config.json"
	)
)





:: start Laravel backend API server
cd ../laravelapi

if exist ./vendor/doctrine (
    echo Laravel vendor exists, skip Laravel composer update ...
) else (
    echo Laravel vendor not exists, Laravel backend needs composer update ...
    call composer update
)

netstat -o -n -a | findstr /RC:":4624 .*LISTENING" > NUL
if "%ERRORLEVEL%" equ "0" (
  echo Laravel Backend Port 4624 is listening ...
) else (
	echo Starting Laravel backend API server
	start "Laravel API backend, artisan serve" /min cmd /c "php artisan serve --host=127.0.0.1 --port=4624"
)


:: usage
cd ../
echo:
echo:
echo ##############################
echo:
echo Starting generate Angurlar CRUD component code:
echo:
echo ##############################
echo:
php ./laravelapi/artisan angular:crud

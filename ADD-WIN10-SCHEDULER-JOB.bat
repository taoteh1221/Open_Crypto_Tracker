@echo off

:: Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com

echo:
echo Enter the time interval in minutes to run this scheduled task 
set /p cron_interval="(5, 10, 15, 20, or 30...leave blank / hit enter for default of 20): "

if "%cron_interval%"=="" (
set "cron_interval=20"
echo:
echo Using default time interval in minutes: 
) else (
echo:
echo Using custom time interval in minutes: 
)

echo %cron_interval%
echo:

echo Enter the full path to the PHP CLI binary 
set /p php_cli_binary="(leave blank / hit enter for default of C:/xampp/php/php-win.exe): "

if "%php_cli_binary%"=="" (
set "php_cli_binary=C:/xampp/php/php-win.exe"
echo:
echo Using default PHP CLI binary path: 
) else (
echo:
echo Using custom PHP CLI binary path: 
)

echo %php_cli_binary%
echo:

echo Enter the full path to cron.php 
set /p cron_php="(leave blank / hit enter for default of C:/xampp/htdocs/cron.php): "

if "%cron_php%"=="" (
set "cron_php=C:/xampp/htdocs/cron.php"
echo:
echo Using default cron.php path: 
) else (
echo:
echo Using custom cron.php path: 
)

echo %cron_php%
echo:

echo Windows will now add this Task Scheduler entry. If you wish
echo to remove this entry in the future, open Task Scheduler and
echo choose 'CRYPTO_TRACKER_CRON', then click 'Delete' on the right.
echo:

pause
echo:

schtasks /Create /TN CRYPTO_TRACKER_CRON /TR "%php_cli_binary% %cron_php%" /SC MINUTE /MO %cron_interval%

echo:

echo Task 'CRYPTO_TRACKER_CRON' has been created, with PHP CLI binary path set as '%php_cli_binary%', cron path set as '%cron_php%', and set to run every %cron_interval% minutes.

echo:

pause
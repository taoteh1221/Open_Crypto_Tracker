@echo off


set /p cron_interval=Enter the time interval in minutes to run this scheduled task (5, 10, 15, 20, or 30...leave blank / hit enter for default of 20):

IF "%cron_interval%" == "" (
set cron_interval=20
echo Using default time interval (in minutes): %cron_interval%
) ELSE (
echo Using custom time interval (in minutes): %cron_interval%
)

echo:


set /p php_cli_binary=Enter the full path to the PHP CLI binary (leave blank / hit enter for default of C:/xampp/php/php-win.exe):

IF "%php_cli_binary%" == "" (
set php_cli_binary=C:/xampp/php/php-win.exe
echo Using default PHP CLI binary path: %php_cli_binary%
) ELSE (
echo Using custom PHP CLI binary path: %php_cli_binary%
)

echo:


set /p cron_php=Enter the full path to cron.php (leave blank / hit enter for default of C:/xampp/htdocs/cron.php):

IF "%cron_php%" == "" (
set cron_php=C:/xampp/htdocs/cron.php
echo Using default cron.php path: %cron_php%
) ELSE (
echo Using custom cron.php path: %cron_php%
)

echo:

schtasks /Create /TN CRYPTO_TRACKER_CRON /TR "%php_cli_binary% %cron_php%" /SC MINUTE /MO %cron_interval%
pause
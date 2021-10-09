@echo off
schtasks /Create /TN XAMPP_CRYPTO_TRACKER /TR "C:/xampp/php/php-win.exe C:/xampp/htdocs/cron.php" /SC MINUTE /MO 20
pause
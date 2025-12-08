@echo off
echo Refreshing database...
call php artisan migrate:fresh
if %errorlevel% neq 0 (
    echo Migration failed.
    pause
    exit /b %errorlevel%
)

echo Importing SQL file...
call php artisan import:sql "backupData\insert-data-berkala.sql"
if %errorlevel% neq 0 (
    echo SQL Import failed.
    pause
    exit /b %errorlevel%
)

echo Database refresh and import completed successfully.
pause

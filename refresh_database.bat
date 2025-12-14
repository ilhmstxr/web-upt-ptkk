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


echo Importing Inject Data...
call php artisan import:sql "backupData\inject-data.sql"
if %errorlevel% neq 0 (
    echo Inject Data Import failed.
    pause
    exit /b %errorlevel%
)


echo Processing Inject Data migration...
call php artisan process:inject-data
if %errorlevel% neq 0 (
    echo Processing Inject Data failed.
    pause
    exit /b %errorlevel%
)

echo Database refresh and import completed successfully.
pause

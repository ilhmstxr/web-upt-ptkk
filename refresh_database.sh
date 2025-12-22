@echo off
echo Refreshing database...
call php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo Migration failed.
    pause
    exit /b %errorlevel%
)

echo Importing SQL file...
@REM call php artisan import:sql "backupData\insert-data-berkala.sql"
call php artisan import:sql "backupData\web-upt-ptkk-server.sql"
if %errorlevel% neq 0 (
    echo SQL Import failed.
    pause
    exit /b %errorlevel%
)

@REM echo Fixing Inject Data Kompetensi IDs...
@REM call php artisan fix:inject-data-kompetensi
@REM if %errorlevel% neq 0 (
@REM     echo Fix Inject Data Kompetensi failed.
@REM     pause
@REM     exit /b %errorlevel%
@REM )


@REM echo Importing Inject Data...
@REM call php artisan import:sql "backupData\inject-data.sql"
@REM if %errorlevel% neq 0 (
@REM     echo Inject Data Import failed.
@REM     pause
@REM     exit /b %errorlevel%
@REM )


@REM echo Processing Inject Data migration...
@REM call php artisan process:inject-data
@REM if %errorlevel% neq 0 (
@REM     echo Processing Inject Data failed.
@REM     pause
@REM     exit /b %errorlevel%
@REM )

@REM echo Database refresh and import completed successfully.
pause

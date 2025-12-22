@echo off
echo ===================================================
echo     ROMBAK DATABASE - UPT PTKK (Auto Fix Applied)
echo ===================================================

echo [1/4] Resetting Database (Fresh Migrate + Seed)...
call php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo Migration failed.
    pause
    exit /b %errorlevel%
)

echo [2/4] Importing Raw Monev Data (Temp Tables)...
echo Importing mentah-jawaban-user-monev.sql...
cmd /c "mysql -u root upt-ptkk-dev-server < backupData/mentah-jawaban-user-monev.sql"
echo Importing data-mentah-monev.sql...
cmd /c "mysql -u root upt-ptkk-dev-server < backupData/data-mentah-monev.sql"
if %errorlevel% neq 0 (
    echo Raw Import failed.
    pause
    exit /b %errorlevel%
)

echo [3/4] Generating Clean SQL from Raw Data...
call php artisan import:monev-legacy
if %errorlevel% neq 0 (
    echo Artisan command failed.
    pause
    exit /b %errorlevel%
)

echo [4/4] Importing Clean Monev Data...
cmd /c "mysql -u root upt-ptkk-dev-server < dokumen/insert-data-monev-fix.sql"
if %errorlevel% neq 0 (
    echo Clean Import failed.
    pause
    exit /b %errorlevel%
)

echo.
echo ===================================================
echo     SUCCESS! Database has been overhauled.
echo ===================================================
pause

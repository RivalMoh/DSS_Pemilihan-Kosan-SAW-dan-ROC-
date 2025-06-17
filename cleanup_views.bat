@echo off
echo Starting view cleanup...

REM Create backup directory with timestamp
set "timestamp=%date:/=-%_%time::=-%"
set "timestamp=%timestamp: =0%"
mkdir "views_backup_%timestamp%"

REM Backup and remove old admin structure
xcopy /E /I /Y "resources\views\admin\index" "views_backup_%timestamp%\admin\index"
rmdir /S /Q "resources\views\admin\index"

REM Backup and remove old partials
xcopy /E /I /Y "resources\views\admin\partials" "views_backup_%timestamp%\admin\partials"
rmdir /S /Q "resources\views\admin\partials"

REM Backup and remove old layout files
if exist "resources\views\admin\layouts\app.blade.php" (
    xcopy /Y "resources\views\admin\layouts\app.blade.php" "views_backup_%timestamp%\admin\layouts\"
    del "resources\views\admin\layouts\app.blade.php"
)

REM Backup and remove unused admin sections
xcopy /E /I /Y "resources\views\admin\perhitungan" "views_backup_%timestamp%\admin\perhitungan"
rmdir /S /Q "resources\views\admin\perhitungan"

xcopy /E /I /Y "resources\views\admin\rekomendasi-kos" "views_backup_%timestamp%\admin\rekomendasi-kos"
rmdir /S /Q "resources\views\admin\rekomendasi-kos"

REM Check if user views are used before removing
if not exist "app\Http\Controllers\UserController.php" (
    xcopy /E /I /Y "resources\views\admin\user" "views_backup_%timestamp%\admin\user"
    rmdir /S /Q "resources\views\admin\user"
)

echo.
echo Cleanup complete. Backups saved to: views_backup_%timestamp%
echo Please verify the application works correctly.
pause

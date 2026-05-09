@echo off
echo ===================================
echo  Import Products with Real Images
echo ===================================
echo.
echo This will add 30 products with working images to your database.
echo.
echo Please enter your MySQL details:
echo.

set /p MYSQL_USER="MySQL Username (default: root): "
if "%MYSQL_USER%"=="" set MYSQL_USER=root

set /p MYSQL_PASS="MySQL Password: "

set /p DB_NAME="Database Name (default: recommendation_engine): "
if "%DB_NAME%"=="" set DB_NAME=recommendation_engine

echo.
echo Importing products...
echo.

mysql -u %MYSQL_USER% -p%MYSQL_PASS% %DB_NAME% < products_complete_80.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ===================================
    echo  SUCCESS! Products imported.
    echo ===================================
    echo.
    echo 80+ products with real images have been added!
    echo Now refresh your browser to see the images.
    echo.
) else (
    echo.
    echo ===================================
    echo  ERROR! Import failed.
    echo ===================================
    echo.
    echo Please check:
    echo - MySQL username and password are correct
    echo - Database "recommendation_engine" exists
    echo - MySQL is running
    echo.
)

pause

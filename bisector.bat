@echo off
dir i18n\qqq.json>NUL 2>NUL
if errorlevel 1 exit /b 0
exit /b 1
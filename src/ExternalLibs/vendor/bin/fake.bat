@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../fakerino/fakerino/app/fake
php "%BIN_TARGET%" %*

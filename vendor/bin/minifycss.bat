@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../externals/minify/bin/minifycss
php "%BIN_TARGET%" %*

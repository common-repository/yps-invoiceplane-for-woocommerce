@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../externals/minify/bin/minifyjs
php "%BIN_TARGET%" %*

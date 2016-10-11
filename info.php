<?php
session_start();
var_dump($_SESSION);
echo "session.upload_progress.enabled: ".ini_get ( "session.upload_progress.enabled")."<hr>";
phpinfo();

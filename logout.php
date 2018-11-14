<?php
header("Content-Type:text/html; charset=utf-8");
//開啟Session
session_start();
//清除Session
session_destroy();
//導到login.php
setcookie("session_id", "", time() + 60 * 60 * 24 * 30);

header("Location:login.php");

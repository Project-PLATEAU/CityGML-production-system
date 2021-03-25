<?php
session_start();
$key = ini_get("session.upload_progress.prefix").'example';
echo isset($_SESSION[$key]) ? json_encode($_SESSION[$key]) : json_encode(null);
<?php
define('MUDDER_ROOT', substr(dirname(__FILE__), 0, -3));
require MUDDER_ROOT . "/core/lib/qrcode.php";
$value = $_GET['content'];
header("Content-type: image/png");
$errorCorrectionLevel = 'L';
$matrixPointSize = 5;
QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
exit;
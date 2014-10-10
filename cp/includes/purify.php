<?php
require_once "htmlpurifier/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purifier = new HTMLPurifier($config);
?>
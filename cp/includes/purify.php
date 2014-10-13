<?php

 // $config->set('Core', 'Encoding', 'ISO-8859-1'); // not using UTF-8
 
require_once "htmlpurifier/HTMLPurifier.auto.php";
$config = HTMLPurifier_Config::createDefault();
$config->set('Core.Encoding', 'UTF-8');
$config->set('HTML.DefinitionRev', 1);
$purifier = new HTMLPurifier($config);
?>
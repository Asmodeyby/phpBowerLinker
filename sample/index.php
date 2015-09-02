<?php

require_once '../dist/Bower.php';
$bowerClass = new Helper_Bower("bower.json", "bower_components/");
$bowerClass->getAssets();
$bowerClass->prepareAssets();
$assets = $bowerClass->processAssets();
$assets = join("\r", $assets);

include "template.php";

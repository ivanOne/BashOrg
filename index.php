<?php
require_once("lib/Models.php");
error_reporting(-1);
$var = new Models();
$var->select('one')->execute()->fetch();
var_dump($var);
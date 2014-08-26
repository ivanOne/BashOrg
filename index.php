<?php
require_once("lib/Models.php");

$var = db::db()->select('one')->execute()->fetch();
var_dump($var);
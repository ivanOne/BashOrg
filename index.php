<?php
require_once("lib/Models.php");
$var = new Models();
$var->select('one')->execute()->fetch();
<?php
include_once $config->paths["core/class"]."pay_core.class.php";
include_once $config->paths["core/class"]."objeto.class.php";

$core = new pay_core(); 
//$core->defLanguaje($config->defaults["languaje"]);
//$core->defBrowser();
$core->defDB();

$core->defUser();

$core->setEnviroment();

$core->fireEvent("system/ready");
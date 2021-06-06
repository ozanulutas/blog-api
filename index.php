<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

require_once "src/config.php";

DatabaseConnection::connect(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD);


$section = $_GET["section"] ?? $_POST["section"] ?? "blog";
$action = $_GET["action"] ?? $_POST["action"] ?? "default";

$module = ucfirst($section) . "Controller";

if(file_exists("controller/$module.php")) {

    include "controller/$module.php";
    $controller = new $module();
    $controller->runAction($action);

}


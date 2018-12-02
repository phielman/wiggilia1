<?php
try
{
    require_once './lib/index.php';
    // error_reporting(0);
    _Controller::$config = include './lib/config.php';
    _View::$dir = './lib/view/';
    _Model::$dsn = _Controller::$config['_db']['dsn'];
    _Model::$user = _Controller::$config['_db']['user'];
    _Model::$password = _Controller::$config['_db']['password'];

    if (empty($_GET['controller'])) {
        $_GET['controller'] = 'Index';
    }

    if (empty($_GET['action'])) {
        $_GET['action'] = 'index';
    }

    $controller = 'controller_' . $_GET['controller'];
    $controller = new $controller;
    $action = $controller->{$_GET['action']}();
    echo $action;
    /*if(function_exists($action))
echo $action;
else {
'404';
$index = new controller_Index;
echo $index->error404();
}*/
} catch (Exception $e) {
    echo 'ERROR: [' . $e->getCode() . '] ' . $e->getMessage();
}

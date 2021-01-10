<?php

namespace system;

class App
{
    public static $ROOT_DIR;
    public $db;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        $this->db = new Database();
    }

    public function run()
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $pathParts = explode('/', $path);
        $controllerName = $pathParts[1];
        $actionName = $pathParts[2] ?? '';
        $controller = 'controllers' . '\\' . ucfirst($controllerName) . 'Controller';
        $action = $actionName === '' ? 'actionIndex' : 'action' . ucfirst($actionName);

        if (!(class_exists($controller) && method_exists($objController = new $controller, $action))) {
            throw new \DomainException('Page is not found');
        }

        $objController->$action();
    }
}
<?php

use router\Router;
use controller\UserController;
require_once 'router/Router.php';
require_once 'controller/AdminController.php';


$router = new Router();
$router->get('/', [new UserController(), 'login']);
$router->run();

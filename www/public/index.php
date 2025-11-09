<?php

require '../coretools.php';

require path_to('core/Router.php');

$router = new Router();
require path_to('routes/web.php');

require path_to('routes/api.php');


$resource_path = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->route($resource_path, $method);
} catch (Exception $e) {
    dd($e->getMessage());
}

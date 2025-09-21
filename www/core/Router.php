<?php
// Stripped down (like, REALLY REALLY) version from Laracasts.

class Router
{
    private $routes = [];

    public function add($method, $resource_path, $controller)
    {
        $new_route = [
            'method' => $method,
            'resource_path' => $resource_path,
            'controller' => $controller
        ];

        array_push($this->routes, $new_route);
    }

    public function route($target_resource_path, $target_method)
    {

        foreach ($this->routes as $current_route) {

            if ($this->route_matches($target_resource_path, $target_method, $current_route)) {

                return require path_to("controllers/{$current_route['controller']}");
            }
        }


        http_response_code(404);
        require path_to('views/404.php');
        die();
    }


    private function route_matches($target_resource_path, $target_method, $route)
    {
        return $route['resource_path'] === $target_resource_path && strcasecmp($route['method'], $target_method) === 0;
    }
}

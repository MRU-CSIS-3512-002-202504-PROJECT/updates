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
            'controller' => $controller,
            'restricted' => false // need now for restricted routes
        ];

        array_push($this->routes, $new_route);

        return $this;  // for chaining
    }

    public function route($target_resource_path, $target_method)
    {

        foreach ($this->routes as $current_route) {

            if ($this->route_matches($target_resource_path, $target_method, $current_route)) {
                if ($current_route['restricted']) {
                    $this->authorize();
                }

                return require path_to("controllers/{$current_route['controller']}");
            }
        }


        http_response_code(404);
        require path_to('views/404.php');
        die();
    }

    private function authorize()
    {
        if (!isset($_SESSION['authorized'])) {
            redirect('/');
        }
    }


    public function restrict()
    {
        // array_key_last is a built-in PHP thing. Look it up!
        $last_routes_index = array_key_last($this->routes);
        $this->routes[$last_routes_index]['restricted'] = true;
    }


    private function route_matches($target_resource_path, $target_method, $route)
    {
        return $route['resource_path'] === $target_resource_path && strcasecmp($route['method'], $target_method) === 0;
    }
}
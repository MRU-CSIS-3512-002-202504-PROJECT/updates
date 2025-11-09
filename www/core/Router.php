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

        // When we add not, our updated Router has to handle cases where
        // there is a path parameter (like /foo/bar/:id).
        //
        // If we detect a path parameter, we'll add two additional
        // key/value pairs to the $new_route. These will be used when
        // we determine whether an incoming resource path actually matches
        // a known route or not. (See route_matches method.)
        $split_path = $this->split_resource_path($resource_path);

        if (str_starts_with($split_path['last'], ":")) {
            $new_route['path_without_param'] = $split_path['start'];
            $new_route['path_param'] = $split_path['last'];
        }

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

        // If the methods don't match, the route doesn't match.
        if (strcasecmp($route['method'], $target_method) !== 0) {
            return false;
        }

        // If the resource paths match exactly, the route matches 
        // (since we've already checked the method).
        if ($route['resource_path'] === $target_resource_path) {
            return true;
        }

        // If we've gotten this far, we are dealing with either an invalid route,
        // or a route involving a path parameter.

        // If there's no path parameter and we didn't match exactly earlier,
        // we can't have a match.
        if (!isset($route['path_param'])) {
            return false;
        }


        if (!str_starts_with($target_resource_path, $route['path_without_param'])) {
            return false;
        }

        // If the target path is not longer than the segment path, there can't be a parameter.
        // e.g. /api/watchlist/ should not match /api/watchlist/:tmdb_id
        if (strlen($target_resource_path) <= strlen($route['path_without_param']) + 1) {
            return false;
        }


        // We have a match! Put the path parameter into the $_GET so the controller
        // can access it.
        $key = substr($route['path_param'], 1); // leave off the colon (ex :tmdb => tmdb)
        $_GET[$key] = $this->split_resource_path($target_resource_path)['last'];

        return true;
    }

    // Splits a given resource path into two parts: the last segment, and
    // everything before it (we'll call that 'start').
    //
    // For example:
    //    /some/resource/path => "/some/resource" and "path"
    //    /some/api/path/with/:param => "/some/api/path/with" and ":param"
    //    /foo => "/foo" and ""
    private function split_resource_path($path)
    {
        // Use built-in PHP functions.
        // See https://www.php.net/manual/en/function.basename.php
        // See https://www.php.net/manual/en/function.dirname.php
        $start = dirname($path);
        $last_segment = basename($path);


        // dirname on a path like "/foo" returns "/". We want to return "/foo".
        // Also, if the path is just "/", dirname returns "/" and basename returns an empty string,
        // which is not what the logic expects for the root.
        if ($start === '/' && $last_segment !== '') {
            $start .= $last_segment;
            $last_segment = '';
        }

        return ["start" => $start, "last" => $last_segment];
    }
}

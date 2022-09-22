<?php namespace Kyaaaa\Routing;

class  Router
{
    private ?string $_url;
    private array $routes;
    private array $_nameRoute;
    private string $baseRoute;

    function __construct()
    {
        $this->baseRoute = '';
        $this->routes = [];
        $this->_nameRoute = [];
        $this->_url = $_SERVER['REQUEST_URI'];
        $this->namespace = "";
    }

    /**
     * GET method
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    function get(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, 'GET', $name);
    }

    /**
     * POST method
     * @param string $path
     * @param  $callable
     * @param string|null $name
     * @return Route
     */
    function post(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, 'POST', $name);
    }

    /**
     * DELETE method
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    function delete(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, 'DELETE', $name);
    }

    /**
     * PUT method
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return Route
     */
    function put(string $path, $callable, ?string $name = null): Route
    {
        return $this->add($path, $callable, 'PUT', $name);
    }

    /**
     * @param string $base_route
     * @param callable $callable
     */
    function group(string $base_route, callable $callable): void
    {
        $cur_base_route = $this->baseRoute;
        $this->baseRoute .= $base_route;
        call_user_func($callable);
        $this->baseRoute = $cur_base_route;
    }

    /**
     * @param string $path
     * @param $callable $callable
     * @param string|null $name
     * @param string $method
     * @return Route
     */
    private function add(string $path, $callable, string $method, ?string $name = null): Route
    {
        $path = $this->baseRoute . '/' . trim($path, '/');
        $path = $this->baseRoute ? rtrim($path, '/') : $path;

        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if (is_string($callable) && $name == null) {
            $name = $callable;
        }

        if ($name) {
            $this->_nameRoute[$name] = $route;
        }
        return $route;
    }

    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    function url(string $name, array $params = []): string
    {
        try {
            if (!isset($this->_nameRoute[$name])) {
                throw new \Exception('No route match');
            }
            return $this->_nameRoute[$name]->geturl($params);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @return mixed
     */
    function run()
    {
        try {
            if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
                throw new \Exception('Request method is not defined ');
            }
            $routesRequestMethod = $this->routes[$_SERVER['REQUEST_METHOD']];
            $i = 0;
            foreach ($routesRequestMethod as $route) {
                if ($route->match($this->_url)) {
                    return $route->call();
                } else {
                    $i++;
                }
            }
            if (count($routesRequestMethod) === $i) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                print("Not Found : ".http_response_code());
                exit;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
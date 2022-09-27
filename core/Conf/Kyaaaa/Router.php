<?php namespace Core\Conf\Kyaaaa;

class Router
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
        error_reporting(0);
        require_once __DIR__ . "/Common.php";

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
                echo '<html><head><title>Kyaaaa~ 404 !</title><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body style="background: #202124;font-size: 15px;"><div style="padding:10px;background:#b12776;margin:0 auto;color:#fff;max-width:768px;font-weight:normal;font-family:Courier New;border-radius: 8px;font-size: 17px;letter-spacing: 1.8px;text-align: center;margin-top: 50px">'.http_response_code().': Not Found</div><div style="margin-left: 25px;"></div></body></html>';
                exit;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
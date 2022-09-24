<?php namespace Core\Conf\Kyaaaa;

class Route{
    private string $_path;
    private $callable;
    private array $_matches;
    private array $_params;
    private array $_get_params, $middleware_tab;
    private bool $middleware_exist;
    /**
     * @var mixed|null
     */
    function __construct(string $path, $callable)
    {
        $this->_path = trim($path, '/');
        $this->callable = $callable;
        $this->_matches = [];
        $this->_params = [];
        $this->_get_params = [];
        $this->middleware_tab = [];
        $this->middleware_exist = false;
    }

    /**
     * @param string|null $url
     * @return bool
     */
    function match(?string $url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->_path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        array_shift($_GET);
        $this->_matches = $matches;
        foreach ($matches as $key => $val) {
            $_GET[$this->_get_params[$key]] = $val;
        }
        return true;
    }

    /**
     *
     */
    function call()
    {
        try {
            if (count($this->middleware_tab) > 0) {
                $this->middleware_exist = false;
                foreach ($this->middleware_tab as $middleware) {
                    $this->controllerMiddleware($middleware, true);
                }
                $this->middleware_tab = [];
            }
            $this->controllerMiddleware($this->callable);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * @param array $match
     * @return string
     */
    private function paramMatch(array $match): string
    {
        if (isset($this->_params[$match[1]])) {
            return '(' . $this->_params[$match[1]] . ')';
        }
        array_push($this->_get_params, $match[1]);
        return '([^/]+)';
    }

    /**
     * @param $param
     * @param $regex
     * @return $this
     */
    function with($param, $regex): Route
    {
        $this->_params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * @return array
     */
    function getmatch(): array
    {
        return $this->_matches;
    }

    /**
     * @param $params
     * @return array|string|string[]
     */
    function getUrl($params)
    {
        $path = $this->_path;
        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }

    /**
     * @param  $middleware
     * @return $this
     */
    function middleware($middleware): Route
    {
        $this->middleware_tab[] = $middleware;
        return $this;
    }

    /**
     * @param $callable
     * @param bool $is_middleware
     */
    private function controllerMiddleware($callable, bool $is_middleware = false): void
    {
        try {
            if (is_string($callable) || is_array($callable)) {
                $params = is_string($callable) ? explode('#', $callable) : $callable;
                if (count($params) != 2) {
                    throw new \Exception("Error : on class/method is not well defined");
                }
                $class_name = $params[0];
                $class_method = $params[1];
                $class_instance = new $class_name;
                if (!method_exists($class_instance, $class_method)) {
                    throw new \Exception("method : $class_method does not belong the class : $class_name.");
                }
                call_user_func_array([$class_instance, $class_method], $this->_matches);
            } else {
                if (isset($callable) && is_callable($callable, true)) {
                    call_user_func_array($callable, $this->_matches);
                }
            }
            return;
        } catch (\Exception $ex) {
            print_r($ex->getMessage());
            exit();
        }
    }
}
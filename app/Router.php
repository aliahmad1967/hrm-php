<?php
/**
 * Router Class
 * Handles URL routing and controller dispatching
 */

class Router {
    private $routes = [];
    private $params = [];
    private $basePath = '';
    
    public function __construct() {
        $this->basePath = dirname($_SERVER['SCRIPT_NAME']);
    }
    
    /**
     * Add a route
     */
    public function add($route, $params = []) {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-zA-Z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }
    
    /**
     * Match route to URL
     */
    public function match($url) {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Dispatch the route
     */
    public function dispatch($url) {
        $url = $this->removeQueryString($url);
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $controller . 'Controller';
            $controller = "App\\Controllers\\$controller";
            
            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Action '$action' not found in controller '$controller'");
                }
            } else {
                throw new \Exception("Controller '$controller' not found");
            }
        } else {
            throw new \Exception('Page not found', 404);
        }
    }
    
    /**
     * Remove query string from URL
     */
    protected function removeQueryString($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
    
    /**
     * Convert string to StudlyCaps
     */
    protected function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    /**
     * Convert string to camelCase
     */
    protected function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    /**
     * Get parameters
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Get all routes
     */
    public function getRoutes() {
        return $this->routes;
    }
}
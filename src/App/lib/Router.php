<?php

namespace App\lib;

class Router
{
    protected $routes = [];

    /**
     * @param string $method
     * @param string $url
     * @param $target
     * @return void
     */
    public function addRoute(string $method, string $url, $target, $routeName = null)
    {
        if (is_array($target)) {
            $path = $this->normalizePath($url);
            $this->routes[$method][$url] = ['path' => $path, 'method' => strtoupper($method), 'controller' => $target, 'middlewares' => [], $routeName];
        } else {
            $this->routes[$method][$url] = $target;
        }
    }

    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {
                if (is_array($target)) {
                    $path = $this->normalizePath($url);
                    $method = strtoupper($method);
                    $params = null;
                    $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                    if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                        // Pass the captured parameter values as named arguments to the target function
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named subpattern matches
                    } else if (!preg_match("#^{$target['path']}$#", $path) || $target['method'] !== $method) {
                        continue;
                    }
                    [$class, $function] = $target['controller'];
                    $controllerInstance = new $class;
                    call_user_func_array([$controllerInstance, $function], $params);
//                    $controllerInstance->{$function}();
                    return;
                } else {
                    // Use named subpatterns in the regular expression pattern to capture each parameter value separately
                    $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                    if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                        // Pass the captured parameter values as named arguments to the target function
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named subpattern matches
                        call_user_func_array($target, $params);
                        return;
                    }
                }
            }
        }
        throw new Exception('Route not found');
    }

    private function normalizePath(string $path):string {
        $path = trim($path, '/');
        $path = "/{$path}/";
        return preg_replace('#[/]{2,}#', '/', $path);
    }
}
<?php

namespace Core;


class Router
{
    private $routes = [];

    private $middlewareStack = [];

    public function group($prefix, $middlewares, callable $callback)
    {
        $this->middlewareStack[] = $middlewares;

        if ($prefix != '') {
            $prevRouteCount = count($this->routes);

            $callback();

            // Thêm prefix vào tất cả các route mới được thêm trong callback
            for ($i = $prevRouteCount; $i < count($this->routes); $i++) {
                if ($this->routes[$i]['path'] === '') {
                    $this->routes[$i]['path'] = $prefix;
                } else {
                    $this->routes[$i]['path'] = rtrim($prefix, '/') . '/' . ltrim($this->routes[$i]['path'], '/');
                }
            }

        } else {
            $callback();
        }

        array_pop($this->middlewareStack);
    }

    private function controllerResolver($controllerDef, $params = [])
    {

        try {
            // 1) Nếu là callable (closure, function, [obj,method], ...)
            if (is_callable($controllerDef)) {
                return fn() => call_user_func_array($controllerDef, $params);
            }

            // 2) Nếu là object instance
            if (is_object($controllerDef)) {
                if (method_exists($controllerDef, '__invoke')) {
                    return fn() => call_user_func_array([$controllerDef, '__invoke'], $params);
                }
                if (method_exists($controllerDef, 'index')) {
                    return fn() => call_user_func_array([$controllerDef, 'index'], $params);
                }
                throw new \Exception('Controller object has no callable method.');
            }

            // 3) Nếu là string: có thể là "Class@method" hoặc "Class"
            if (is_string($controllerDef)) {
                $parts = explode('@', $controllerDef);
                $classPart = $parts[0];
                $methodName = $parts[1] ?? 'index';

                $controllerClass = '\\Controller\\' . str_replace('/', '\\', $classPart);

                if (!class_exists($controllerClass)) {
                    throw new \Exception("Controller class {$controllerClass} not found.");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $methodName)) {
                    throw new \Exception("Method {$methodName} not found on controller {$controllerClass}.");
                }

                return fn() => call_user_func_array([$controller, $methodName], $params);
            }

            throw new \Exception('Unsupported controller definition.');
        } catch (\Throwable $e) {
            error_log('Router dispatch error: ' . $e->getMessage());
            http_response_code(500);
            echo "500 Internal Server Error";
            return;
        }
    }

    private function createMiddlewareFromString(array $middlewareStr)
    {
        foreach ($middlewareStr as $mw) {
            if (is_string($mw)) {
                $className = '\\Core\\Middleware\\' . str_replace('/', '\\', $mw);
                if (class_exists($className)) {
                    yield new $className();
                } else {
                    error_log("Middleware class {$className} not found.");
                }
            } elseif ($mw instanceof Middleware\MiddlewareInterface) {
                yield $mw;
            } else {
                error_log("Invalid middleware definition: " . print_r($mw, true));
            }
        }
    }


    public function addRoute($method, $path, $controller, array $middlewares = [])
    {
        $groupMiddleware = array_merge(...$this->middlewareStack ?: [[]]); // Handle nested groups if needed

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller, // Controller path or callable
            'middlewares' => array_merge($groupMiddleware, $middlewares),
        ];

        return $this;
    }

    public function dispatch($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method))
                continue;

            // Xử lý route động: chuyển {param} thành regex
            $routePattern = preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $routePattern = '#^' . $routePattern . '$#';

            if (!preg_match($routePattern, $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            $controllerCallback = $this->controllerResolver($route['controller'], $params);

            if (!$controllerCallback) {
                http_response_code(500);
                echo "500 Internal Server Error";
                return;
            }

            $pipeline = new Pipeline();
            $middlewareInstances = iterator_to_array($this->createMiddlewareFromString($route['middlewares']));
            $pipeline->process($middlewareInstances, $controllerCallback);

            return;
        }
        http_response_code(404);
        echo "404 Not Found";
    }

    // Helper methods
    public function get($path, $controller, array $middlewares = [])
    {
        return $this->addRoute('GET', $path, $controller, $middlewares);
    }

    public function post($path, $controller, array $middlewares = [])
    {
        return $this->addRoute('POST', $path, $controller, $middlewares);
    }

    public function put($path, $controller, array $middlewares = [])
    {
        return $this->addRoute('PUT', $path, $controller, $middlewares);
    }

    public function delete($path, $controller, array $middlewares = [])
    {
        return $this->addRoute('DELETE', $path, $controller, $middlewares);
    }

    public function test()
    {
        echo "
        
            <h1>Router is working!</h1>

        
        ";
    }
}
<?php

namespace Core\Middleware;
use Core\Session;
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next, $request = [], $params = []): array
    {
        // TODO: Implement authentication logic here
        echo "TEST AUTHMIDDLEWARE PASSED \n.";
        return $next();
    }
}
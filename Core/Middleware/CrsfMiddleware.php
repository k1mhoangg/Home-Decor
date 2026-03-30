<?php

namespace Core\Middleware;

class CrsfMiddleware implements MiddlewareInterface
{
    public function handle(callable $next, $request = [], $params = []): array
    {
        // TODO: Implement CSRF protection logic
        echo "CSRF Middleware: Checking CSRF token...\n";
        return $next();
    }
}
<?php

namespace Core\Middleware;

class SanitizerMiddleware implements MiddlewareInterface
{
    public function handle(callable $next, $request = [], $params = []): array
    {
        // TODO: Implement sanitization logic
        echo "SanitizerMiddleware: Sanitizing request data...\n";
        return $next();
    }
}
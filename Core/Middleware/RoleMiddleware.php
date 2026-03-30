<?php

namespace Core\Middleware;
class RoleMiddleware implements MiddlewareInterface
{
    public function handle(callable $next, $request = [], $params = []): array
    {
        // TODO: Implement role-based access control logic here
        echo "TEST ROLEMIDDLEWARE PASSED \n.";
        return $next();
    }
}
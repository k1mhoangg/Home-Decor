<?php
namespace Core\Middleware;

interface MiddlewareInterface
{
    /**
     * Xử lý middleware.
     *
     * @param array $request Dữ liệu yêu cầu (có thể là thông tin về route, params, v.v.)
     * @param callable $next Hàm tiếp theo trong chuỗi middleware
     * @param array $params Tham số bổ sung cho middleware
     * @return array Có thể trả về dữ liệu đã được xử lý hoặc một mảng chứa thông tin để tiếp tục chuỗi middleware.
     */
    public function handle(callable $next, $request = [], $params = []): array;
}
?>
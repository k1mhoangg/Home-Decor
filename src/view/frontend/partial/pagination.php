<?php
// Phân trang cho trang pricing (frontend)
// $pagination object (Core\Pagination) được truyền từ controller
if (!isset($pagination) || !$pagination->hasPages())
    return;

$current = $pagination->getCurrentPage();
$total = $pagination->getTotalPages();
$baseUrl = strtok($_SERVER['REQUEST_URI'], '?'); // remove existing query string

// Giữ lại các tham số tìm kiếm nếu có
$query = $_GET;
unset($query['page']);
$queryStr = http_build_query($query);

function buildPageUrl($baseUrl, $queryStr, $page) {
    $params = $queryStr ? ($queryStr . '&') : '';
    return $baseUrl . '?' . $params . 'page=' . $page;
}
?>

<div class="flex flex-col items-center justify-center my-8">
    <p class="text-gray-500 mb-2 text-sm">Hiển thị <span><?php echo (($current - 1) * 10 + 1); ?></span> đến
        <span><?php echo min($current * 10, $pagination->getTotal()); ?></span> trong tổng số
        <span><?php echo $pagination->getTotal(); ?></span> kết quả
    </p>
    <ul class="inline-flex items-center space-x-1">
        <?php if ($current > 1): ?>
            <li><a class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-gray-700" href="<?php echo buildPageUrl($baseUrl, $queryStr, $current - 1); ?>">Trước</a></li>
        <?php endif; ?>

        <?php for ($i = max(1, $current - 2); $i <= min($total, $current + 2); $i++): ?>
            <li>
                <a class="px-3 py-1 rounded <?php echo ($i === $current) ? 'bg-blue-500 text-white font-bold' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'; ?>" href="<?php echo buildPageUrl($baseUrl, $queryStr, $i); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current < $total): ?>
            <li><a class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-gray-700" href="<?php echo buildPageUrl($baseUrl, $queryStr, $current + 1); ?>">Sau</a></li>
        <?php endif; ?>
    </ul>
</div>

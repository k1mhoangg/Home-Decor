<?php
// $pagination object (Core\Pagination) được truyền từ controller
if (!isset($pagination) || !$pagination->hasPages())
    return;

$current = $pagination->getCurrentPage();
$total = $pagination->getTotalPages();
$baseUrl = strtok($_SERVER['REQUEST_URI'], '?'); // remove existing query string
?>

<div class="card-footer d-flex align-items-center m-2">
    <p class="m-0 text-muted">Hiển thị <span><?php echo (($current - 1) * 10 + 1); ?></span> đến
        <span><?php echo min($current * 10, $pagination->getTotal()); ?></span> trong tổng số
        <span><?php echo $pagination->getTotal(); ?></span> kết quả
    </p>
    <ul class="pagination m-0 ms-auto">
        <?php if ($current > 1): ?>
            <li class="page-item"><a class="page-link"
                    href="<?php echo $baseUrl; ?>?page=<?php echo ($current - 1); ?>">Trước</a></li>
        <?php endif; ?>

        <?php for ($i = max(1, $current - 2); $i <= min($total, $current + 2); $i++): ?>
            <li class="page-item <?php echo ($i === $current) ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo $baseUrl; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current < $total): ?>
            <li class="page-item"><a class="page-link"
                    href="<?php echo $baseUrl; ?>?page=<?php echo ($current + 1); ?>">Sau</a></li>
        <?php endif; ?>
    </ul>
</div>
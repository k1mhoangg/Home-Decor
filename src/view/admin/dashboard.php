<?php include __DIR__ . '/partial/header.php'; ?>

<?php
// $countUsers, $countContacts, $recentUsers, $recentContacts
// are provided by Controller\Admin\AdminController::index()
if (session_status() === PHP_SESSION_NONE)
    session_start();
$currentUser = $_SESSION['user']['username'] ?? 'Admin';
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<!-- Main dashboard content -->
<main class="page-main">
    <div class="container-xl">
        <div class="row g-2 align-items-center page-header">
            <div class="col">
                <h2 class="page-title">Bảng điều khiển</h2>
                <div class="text-muted">Tổng quan hệ thống</div>
            </div>
        </div>

        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="stamp stamp-md bg-blue-lt me-3"><i class="ti ti-users"></i></span>
                            <div>
                                <h3 class="m-0"><?php echo htmlspecialchars($countUsers ?? 0); ?></h3>
                                <div class="text-muted">Tổng người dùng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="stamp stamp-md bg-green-lt me-3"><i class="ti ti-mail"></i></span>
                            <div>
                                <h3 class="m-0"><?php echo htmlspecialchars($countContacts ?? 0); ?></h3>
                                <div class="text-muted">Yêu cầu liên hệ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="stamp stamp-md bg-red-lt me-3"><i class="ti ti-activity"></i></span>
                            <div>
                                <h3 class="m-0">—</h3>
                                <div class="text-muted">Hoạt động gần đây</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Người dùng mới</h3>
                        <div class="card-actions">
                            <a href="/admin/users" class="btn btn-sm">Xem tất cả</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-small mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentUsers)):
                                        foreach ($recentUsers as $u): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($u['id']); ?></td>
                                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                                <td><?php echo htmlspecialchars($u['role']); ?></td>
                                                <td><?php echo htmlspecialchars($u['created_at'] ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liên hệ mới</h3>
                        <div class="card-actions">
                            <a href="/admin/contacts" class="btn btn-sm">Xem tất cả</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-small mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recentContacts)):
                                        foreach ($recentContacts as $c): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($c['id']); ?></td>
                                                <td><?php echo htmlspecialchars($c['name']); ?></td>
                                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                                <td><?php echo htmlspecialchars($c['subject'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($c['created_at'] ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">Không có dữ liệu</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<?php include __DIR__ . '/partial/footer.php'; ?>
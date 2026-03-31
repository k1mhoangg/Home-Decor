<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>HomeDecor Admin</title>

    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    <!-- Optional local styles -->
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .page-header {
            position: sticky;
            top: 56px;
            z-index: 998;
            background: #fff;
            padding: 1rem 0;
            margin-bottom: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="antialiased">
    <div class="page">

        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="/admin">
                    <img src="/favicon.ico" class="navbar-brand-image" alt="logo"
                        style="width:28px;height:28px;object-fit:contain">
                    <span class="navbar-brand-text ms-2">HomeDecor Admin</span>
                </a>

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">
                                <span class="nav-link-icon"><i class="ti ti-layout-dashboard"></i></span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/home-settings">
                                <span class="nav-link-icon"><i class="ti ti-home"></i></span>
                                <span class="nav-link-title">Quản lý trang chủ</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">
                                <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                                <span class="nav-link-title">Quản lý người dùng</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/contacts">
                                <span class="nav-link-icon"><i class="ti ti-mail"></i></span>
                                <span class="nav-link-title">Quản lý liên hệ</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="/admin/about">
                                <span class="nav-link-icon"><i class="ti ti-info-circle"></i></span>
                                <span class="nav-link-title">Quản lý trang giới thiệu</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/contact-settings">
                                <span class="nav-link-icon"><i class="ti ti-address-book"></i></span>
                                <span class="nav-link-title">Quản lý trang liên hệ</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/products">
                                <span class="nav-link-icon"><i class="ti ti-box"></i></span>
                                <span class="nav-link-title">Quản lý sản phẩm</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/news">
                                <span class="nav-link-icon"><i class="ti ti-box"></i></span>
                                <span class="nav-link-title">Quản lý tin tức</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/faqs">
                                <span class="nav-link-icon"><i class="ti ti-question-mark"></i></span>
                                <span class="nav-link-title">Quản lý trang hỏi/đáp</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/orders">
                                <span class="nav-link-icon"><i class="ti ti-receipt"></i></span>
                                <span class="nav-link-title">Quản lý đơn hàng</span>
                            </a>
                        </li>

                        <!-- Placeholder menu group for future features -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <span class="nav-link-icon"><i class="ti ti-tool"></i></span>
                                <span class="nav-link-title">Tính năng khác</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Báo cáo (coming soon)</a>
                                <a class="dropdown-item" href="#">Cấu hình (coming soon)</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Page wrapper start -->
        <div class="page-wrapper">
            <!-- top navbar will be rendered in individual views (dashboard shows overview) -->
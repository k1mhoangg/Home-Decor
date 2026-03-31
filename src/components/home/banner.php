<?php
$bannersData = $banners ?? [];
$showBanner = $homeSettings['show_banner'] ?? true;

if (!$showBanner || empty($bannersData))
    return;
?>
<section class="banner-section">
    <!-- bg-banner as background, visible from lg up -->
    <section class="banner hidden lg:block bg-center bg-cover py-12"
        style="background-image: url('images/bg-banner.jpg');">
        <div class="w-[90%] mx-auto">
            <!-- Expanding panels: 4 items, each expands on hover -->
            <div class="flex w-full h-[60vh] overflow-hidden rounded-xl shadow-lg">
                <!-- Panel 1 -->
                <?php foreach ($bannersData as $banner): ?>
                    <a href="<?php echo htmlspecialchars($banner['link'] ?? '/'); ?>"
                        class="flex-1 relative bg-center bg-cover transition-all duration-500 ease-in-out hover:flex-[3]"
                        style="background-image: url('<?php echo htmlspecialchars($banner['image_url']); ?>');">
                        <div
                            class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                            <h2 class="text-white text-2xl lg:text-3xl font-bold">
                                <?php echo htmlspecialchars($banner['title'] ?? ''); ?>
                            </h2>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</section>
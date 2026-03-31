<?php
$companyName = $settings['company_name'] ?? 'Công Ty Cổ Phần Kiến Trúc Nội Thất Home Decor';
$companyShortName = $settings['company_short_name'] ?? 'HOME DECOR VIỆT NAM';
$companySlogan = $settings['company_slogan'] ?? 'Homedecorvn';
$introContent = $settings['intro_content'] ?? '';
$hotline = $settings['hotline'] ?? '0904706666';
$email = $settings['email'] ?? 'homedecor0383@gmail.com';

$introParagraphs = array_filter(array_map('trim', explode("\n\n", $introContent)));
?>

<section class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Liên hệ</h1>
    <h2 class="text-2xl font-semibold mb-2"><?php echo htmlspecialchars($companyName); ?> -
        <?php echo htmlspecialchars($companySlogan); ?>
    </h2>
    <h3 class="text-xl font-semibold mb-4"><?php echo htmlspecialchars($companyShortName); ?></h3>

    <?php foreach ($introParagraphs as $paragraph): ?>
        <p class="mb-4"><?php echo nl2br(htmlspecialchars($paragraph)); ?></p>
    <?php endforeach; ?>
</section>

<?php if (!empty($activities)): ?>
    <section class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-2">NHỮNG HOẠT ĐỘNG CHÍNH CỦA <?php echo htmlspecialchars($companyShortName); ?>
        </h2>
        <p><strong>Bao gồm:</strong></p>
        <ul class="list-disc list-inside space-y-1">
            <?php foreach ($activities as $activity): ?>
                <li><?php echo htmlspecialchars($activity); ?></li>
            <?php endforeach; ?>
        </ul>

        <p class="mt-4">
            <?php echo htmlspecialchars($companyName); ?> luôn sẵn sàng lắng nghe ý kiến đóng góp của quý khách hàng. Hãy
            liên hệ với chúng tôi bất cứ khi nào bạn cần đến.
        </p>

        <?php if (!empty($services)): ?>
            <p class="mt-4"><strong>Một số dịch vụ khác:</strong></p>
            <ul class="list-disc list-inside space-y-1">
                <?php foreach ($services as $service): ?>
                    <li><?php echo htmlspecialchars($service); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<section class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-2">Liên Hệ Với Chúng Tôi</h2>
    <p><strong>Hotline:</strong> <a href="tel:<?php echo htmlspecialchars($hotline); ?>"
            class="text-blue-600 hover:underline"><?php echo htmlspecialchars($hotline); ?></a></p>
    <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($email); ?>"
            class="text-blue-600 hover:underline"><?php echo htmlspecialchars($email); ?></a></p>
    <?php if (!empty($settings['address'])): ?>
        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($settings['address']); ?></p>
    <?php endif; ?>
    <?php if (!empty($settings['working_hours'])): ?>
        <p><strong>Giờ làm việc:</strong> <?php echo htmlspecialchars($settings['working_hours']); ?></p>
    <?php endif; ?>
</section>

<?php
$mapEmbed = $settings['map_embed'] ?? '';
$mapAddress = $settings['map_address'] ?? $settings['address'] ?? '';

if (empty($mapEmbed) && !empty($mapAddress)) {
    $encodedAddress = urlencode($mapAddress);
    $mapEmbed = '<iframe src="https://maps.google.com/maps?q=' . $encodedAddress . '&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
}

if (!empty($mapEmbed)):
    ?>
    <section class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Bản đồ</h2>
        <div class="w-full aspect-video rounded-lg overflow-hidden shadow-md">
            <?php echo $mapEmbed; ?>
        </div>
    </section>
<?php endif; ?>
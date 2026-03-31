<?php
$introContent = $settings['intro_content'] ?? '';
$introParagraphs = !empty($introContent) ? explode("\n", trim($introContent)) : [];
?>
<section class= "py-16 px-4 md:px-20">
  <div class="max-w-4xl mx-auto">

    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center md:text-left">
      Cảm ơn quý khách đã quan tâm HomeDecor!
    </h2>

    <?php if (!empty($introParagraphs)): ?>
      <?php foreach ($introParagraphs as $index => $paragraph): ?>
        <?php if (trim($paragraph)): ?>
          <p class="text-justify text-sm md:text-base leading-relaxed <?php echo $index > 0 ? 'mt-4' : ''; ?>">
            <?php echo htmlspecialchars(trim($paragraph)); ?>
          </p>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-justify text-sm md:text-base leading-relaxed">
        HomeDecor là công ty nội thất hoạt động chuyên nghiệp trong lĩnh vực thiết kế nội thất và thi công nội thất trọn gói khắp từ Bắc tới Nam bao gồm cả Hà Nội, TP. Hồ Chí Minh và các tỉnh thành khác trên cả nước.
      </p>
    <?php endif; ?>

  </div>
</section>
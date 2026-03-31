<?php
$decorContent = $settings['decor_content'] ?? '';
$decorParagraphs = !empty($decorContent) ? explode("\n", trim($decorContent)) : [];
?>
<section class="py-16 px-4 md:px-20 bg-white">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-stretch gap-8">

    <!-- Carousel Ảnh -->
    <div class="flex-1 order-1 md:order-2 flex items-stretch">
      <?php if (!empty($decorImages) && count($decorImages) >= 2): ?>
        <section id="carousel" class="relative w-full h-full overflow-hidden">

          <!-- Slides wrapper -->
          <div id="slides" class="flex transition-transform duration-500 ease-in-out h-full">
            <?php foreach ($decorImages as $index => $img): ?>
              <div class="carousel-slide min-w-full h-full">
                <img src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="Slide <?php echo $index + 1; ?>" class="w-full h-full object-cover rounded-lg">
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Prev/Next Buttons -->
          <button id="prevBtn" class="absolute top-1/2 left-2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 font-bold py-2 px-3 rounded-full shadow-md">‹</button>
          <button id="nextBtn" class="absolute top-1/2 right-2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 font-bold py-2 px-3 rounded-full shadow-md">›</button>

          <!-- Indicators -->
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <?php foreach ($decorImages as $index => $img): ?>
              <button class="indicator w-3 h-3 bg-gray-800 rounded-full bg-opacity-40 scale-100 transition-all <?php echo $index === 0 ? 'bg-opacity-100' : ''; ?>" data-index="<?php echo $index; ?>"></button>
            <?php endforeach; ?>
          </div>

        </section>
      <?php else: ?>
        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
          <p class="text-gray-500">Cần ít nhất 2 ảnh để hiển thị carousel</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Nội dung -->
    <div class="flex-1 flex flex-col justify-center order-2 md:order-1">
      <h3 class="text-2xl md:text-3xl font-bold mb-6 text-center md:text-left">
        NỘI THẤT TRANG TRÍ
      </h3>

      <div class="space-y-4 text-justify text-sm md:text-base leading-relaxed">
        <?php if (!empty($decorParagraphs)): ?>
          <?php foreach ($decorParagraphs as $paragraph): ?>
            <?php if (trim($paragraph)): ?>
              <p><?php echo htmlspecialchars(trim($paragraph)); ?></p>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <p>HomeDecor mang đến những giải pháp nội thất trang trí hiện đại, tinh tế và phù hợp với từng không gian sống.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>

<?php
$visionImage = $settings['vision_image'] ?? 'images/sample.jpg';
$visionContent = $settings['vision_content'] ?? '';
$missionContent = $settings['mission_content'] ?? '';
$valuesContent = $settings['values_content'] ?? '';

// Tách nội dung thành các đoạn văn dựa trên dấu xuống dòng
$visionParagraphs = !empty($visionContent) ? explode("\n", trim($visionContent)) : [];
$missionParagraphs = !empty($missionContent) ? explode("\n", trim($missionContent)) : [];
$valuesParagraphs = !empty($valuesContent) ? explode("\n", trim($valuesContent)) : [];
?>
<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-stretch gap-8">

    <!-- Hình ảnh -->
    <div class="flex-1 flex items-stretch">
      <img src="<?php echo htmlspecialchars($visionImage); ?>" alt="Vision Mission Values" class="w-full h-full object-cover rounded-lg">
    </div>

    <!-- Nội dung -->
    <div class="flex-1 flex flex-col justify-center space-y-6">

      <h3 class="text-2xl md:text-3xl font-bold mb-6 text-center md:text-left">
        TẦM NHÌN, SỨ MỆNH, GIÁ TRỊ CỐT LÕI
      </h3>

      <div class="flex flex-col gap-4">

        <!-- Card Tầm nhìn -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl hover:bg-yellow-100 
                    transition-all duration-300 cursor-pointer transform hover:-translate-y-2">
          <h4 class="text-xl font-semibold mb-2 relative">
            TẦM NHÌN
            <span class="block h-0.5 bg-gray-300 w-full mt-2"></span>
          </h4>
          <div class="text-sm md:text-base leading-relaxed space-y-2">
            <?php if (!empty($visionParagraphs)): ?>
              <?php foreach ($visionParagraphs as $paragraph): ?>
                <?php if (trim($paragraph)): ?>
                  <p><?php echo htmlspecialchars(trim($paragraph)); ?></p>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p><?php echo htmlspecialchars($visionContent); ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Card Sứ mệnh -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl hover:bg-yellow-100
                    transition-all duration-300 cursor-pointer transform hover:-translate-y-2">
          <h4 class="text-xl font-semibold mb-2 relative">
            SỨ MỆNH
            <span class="block h-0.5 bg-gray-300 w-full mt-2"></span>
          </h4>
          <div class="text-sm md:text-base leading-relaxed space-y-2">
            <?php if (!empty($missionParagraphs)): ?>
              <?php foreach ($missionParagraphs as $paragraph): ?>
                <?php if (trim($paragraph)): ?>
                  <p><?php echo htmlspecialchars(trim($paragraph)); ?></p>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p><?php echo htmlspecialchars($missionContent); ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Card Giá trị cốt lõi -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl hover:bg-yellow-100
                    transition-all duration-300 cursor-pointer transform hover:-translate-y-2">
          <h4 class="text-xl font-semibold mb-2 relative">
            GIÁ TRỊ CỐT LÕI
            <span class="block h-0.5 bg-gray-300 w-full mt-2"></span>
          </h4>
          <div class="text-sm md:text-base leading-relaxed space-y-2">
            <?php if (!empty($valuesParagraphs)): ?>
              <?php foreach ($valuesParagraphs as $paragraph): ?>
                <?php if (trim($paragraph)): ?>
                  <p><?php echo htmlspecialchars(trim($paragraph)); ?></p>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p><?php echo htmlspecialchars($valuesContent); ?></p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

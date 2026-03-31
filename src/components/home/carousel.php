<?php
$showCarousel = $homeSettings['show_carousel'] ?? true;
if (!$showCarousel)
    return;

$slidesData = $slides ?? [];
if (empty($slidesData)) {
    $slidesData = [
        ['image_url' => 'images/living_room.jpg', 'title' => 'Thiết kế phòng khách', 'subtitle' => 'Phong cách hiện đại, tối giản'],
        ['image_url' => 'images/bed_room.jpg', 'title' => 'Phòng ngủ ấm cúng', 'subtitle' => 'Tối ưu ánh sáng và tiện nghi'],
    ];
}
?>
<section class="w-full mx-auto px-4 mt-6">
    <div class="relative group">
        <!-- Slides wrapper -->
        <div id="carousel" class="overflow-hidden relative w-full m-0 p-0">
            <div id="slides" class="flex transition-transform duration-500 ease-in-out w-full m-0 p-0">
                <!-- Slide 1 -->
                <?php foreach ($slidesData as $slide): ?>
                    <div class="carousel-slide flex-none w-full relative">
                        <img src="<?php echo htmlspecialchars($slide['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($slide['title'] ?? ''); ?>"
                            class="w-full h-56 sm:h-72 md:h-96 lg:h-[500px] object-cover rounded-lg shadow-md">
                        <div class="absolute left-4 bottom-6 bg-black bg-opacity-40 text-white px-3 py-2 rounded z-10">
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($slide['title'] ?? ''); ?></h3>
                            <p class="text-sm"><?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?></p>
                            <?php if (!empty($slide['button_text'])): ?>
                                <a href="<?php echo htmlspecialchars($slide['button_link'] ?? '/'); ?>"
                                    class="inline-block mt-2 px-4 py-1 bg-yellow-500 text-black text-sm rounded hover:bg-yellow-400">
                                    <?php echo htmlspecialchars($slide['button_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Prev Button -->
        <button id="prevBtn" aria-label="Previous" class="absolute left-2 top-1/2 transform -translate-y-1/2 
         bg-white text-gray-800 rounded-full p-2 shadow
         bg-opacity-90 hover:bg-opacity-100
         block sm:group-hover:block">
            <i class="fa fa-chevron-left"></i>
        </button>

        <!-- Next Button -->
        <button id="nextBtn" aria-label="Next" class="absolute right-2 top-1/2 transform -translate-y-1/2 
         bg-white text-gray-800 rounded-full p-2 shadow
         bg-opacity-90 hover:bg-opacity-100
         block sm:group-hover:block">
            <i class="fa fa-chevron-right"></i>
        </button>


        <!-- Indicators -->
        <div id="indicators" class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2">
            <?php foreach ($slidesData as $i => $s): ?>
                <button
                    class="indicator w-3 h-3 rounded-full <?php echo $i === 0 ? 'bg-white bg-opacity-60' : 'bg-white bg-opacity-40'; ?>"
                    data-index="<?php echo $i; ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
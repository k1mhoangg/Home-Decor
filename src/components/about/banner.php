<?php
$bannerImage = $settings['banner_image'] ?? 'images/sample.jpg';
?>
<section 
  class="relative h-[400px] flex items-center justify-center bg-center bg-cover bg-no-repeat" 
  style="background-image: url('<?php echo htmlspecialchars($bannerImage); ?>'); background-size: cover;"
>

  <div class="backdrop-blur-md bg-white/10 px-8 py-4 rounded-xl">
    <h1 class="text-white text-4xl md:text-5xl font-bold text-center">
      Về <span class="font-bold text-yellow-500">HomeDecor</span>
    </h1>
  </div>

</section>

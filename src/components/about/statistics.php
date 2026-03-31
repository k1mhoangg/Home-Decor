<?php
$customersTarget = $settings['customers_target'] ?? 986;
$yearsTarget = $settings['years_target'] ?? 10;
$projectsTarget = $settings['projects_target'] ?? 300;
$loyalCustomersTarget = $settings['loyal_customers_target'] ?? 50;
?>
<section id="stats" class="relative bg-yellow-500 py-16 text-white text-center overflow-hidden">
  <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
    
    <div>
      <h3 class="text-4xl font-bold"><span class="counter" data-target="<?php echo htmlspecialchars($customersTarget); ?>">0</span>+</h3>
      <p class="mt-2 uppercase text-sm font-semibold">Khách hàng đã tư vấn</p>
    </div>
    
    <div>
      <h3 class="text-4xl font-bold"><span class="counter" data-target="<?php echo htmlspecialchars($yearsTarget); ?>">0</span>+</h3>
      <p class="mt-2 uppercase text-sm font-semibold">Năm kinh nghiệm</p>
    </div>
    
    <div>
      <h3 class="text-4xl font-bold"><span class="counter" data-target="<?php echo htmlspecialchars($projectsTarget); ?>">0</span>+</h3>
      <p class="mt-2 uppercase text-sm font-semibold">Dự án đã triển khai</p>
    </div>
    
    <div>
      <h3 class="text-4xl font-bold"><span class="counter" data-target="<?php echo htmlspecialchars($loyalCustomersTarget); ?>">0</span>+</h3>
      <p class="mt-2 uppercase text-sm font-semibold">Khách hàng thân thiết</p>
    </div>

  </div>
</section>

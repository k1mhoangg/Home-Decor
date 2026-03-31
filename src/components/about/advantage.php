<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">

    <!-- Tiêu đề ở giữa -->
    <div class="md:col-span-3 flex flex-col justify-center items-center text-center order-1 w-full">
      <h2 class="text-2xl md:text-3xl font-bold">
        ƯU THẾ NỔI BẬT
      </h2>
    </div>

    <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 order-2">
      <?php if (!empty($advantages)): ?>
        <?php foreach ($advantages as $advantage): ?>
          <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-start gap-4">
              <i class="fa-solid fa-circle-check text-blue-600 text-2xl mt-1"></i>
              <p><?php echo htmlspecialchars($advantage['content']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="md:col-span-3 text-center text-gray-500">
          <p>Chưa có ưu thế nào được thêm vào.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

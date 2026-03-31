<?php
// Check if user is logged in
use Core\Session;
Session::start();
$current = $_SESSION['user'] ?? null;
?>

<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-5xl mx-auto">
    <div class="text-center mb-10">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        Đặt câu hỏi
      </h2>
      <p class="max-w-2xl mx-auto">
        Bạn có câu hỏi nào khác? Hãy gửi câu hỏi của bạn và chúng tôi sẽ trả lời sớm nhất có thể.
      </p>
    </div>

    <?php if ($current): ?>
      <!-- Form for logged-in users -->
      <form method="POST" action="/faq" class="w-full max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm">
        <div class="mb-6">
          <label for="question" class="block text-sm font-semibold mb-2">
            Câu hỏi của bạn <span class="text-red-500">*</span>
          </label>
          <textarea 
            id="question" 
            name="question" 
            rows="4" 
            placeholder="Nhập câu hỏi của bạn tại đây..."
            required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
          ></textarea>
        </div>

        <div class="flex justify-end">
          <button 
            type="submit"
            class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200"
          >
            <i class="fa-solid fa-paper-plane mr-2"></i> Gửi câu hỏi
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
  
</section>


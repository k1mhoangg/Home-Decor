<!-- Tab Navigation -->
<section class="py-8 px-6 bg-white border-b border-gray-200">
  <div class="max-w-5xl mx-auto">
    <div class="flex flex-wrap gap-4 border-b border-gray-300">
      <?php
      $currentTab = $tab ?? 'faq';
      $currentUser = $_SESSION['user'] ?? null;
      ?>
      
      <!-- Tab 1: Câu hỏi thường gặp -->
      <a 
        href="/faq?tab=faq" 
        class="px-6 py-3 font-semibold text-gray-700 border-b-2 transition-colors duration-200 <?php echo $currentTab === 'faq' ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-blue-600'; ?>"
      >
        <i class="fa-solid fa-question-circle mr-2"></i>
        Câu hỏi thường gặp
      </a>
      
      <!-- Tab 2: Câu hỏi của tôi -->
      <a 
        href="/faq?tab=my-questions" 
        class="px-6 py-3 font-semibold border-b-2 transition-colors duration-200 <?php 
          if ($currentTab === 'my-questions') {
            echo 'border-blue-600 text-blue-600';
          } else {
            echo $currentUser ? 'text-gray-700 border-transparent hover:text-blue-600' : 'text-gray-400 border-transparent hover:text-gray-500';
          }
        ?>"
        title="<?php echo $currentUser ? '' : 'Vui lòng đăng nhập để xem câu hỏi của bạn'; ?>"
      >
        <i class="fa-solid fa-user-circle mr-2"></i>
        Câu hỏi của tôi
      </a>
      
      <!-- Tab 3: Đặt câu hỏi -->
      <a 
        href="/faq?tab=ask" 
        class="px-6 py-3 font-semibold border-b-2 transition-colors duration-200 <?php 
          if ($currentTab === 'ask') {
            echo 'border-blue-600 text-blue-600';
          } else {
            echo $currentUser ? 'text-gray-700 border-transparent hover:text-blue-600' : 'text-gray-400 border-transparent hover:text-gray-500';
          }
        ?>"
        title="<?php echo $currentUser ? '' : 'Vui lòng đăng nhập để đặt câu hỏi'; ?>"
      >
        <i class="fa-solid fa-plus-circle mr-2"></i>
        Đặt câu hỏi
      </a>
    </div>
  </div>
</section>
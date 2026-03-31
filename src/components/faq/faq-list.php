<?php
$currentTab = $tab ?? 'faq';
$isMyQuestionsTab = isset($isMyQuestions) && $isMyQuestions;
$searchQuery = isset($searchDisplay) ? $searchDisplay : sanitizeInput($_GET['search'] ?? '');
$currentUser = $_SESSION['user'] ?? null;
?>

<section class="py-16 px-6 bg-gray-50">
  <div class="max-w-5xl mx-auto">
    <div class="text-center mb-10">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">
        <?php echo $isMyQuestionsTab ? 'Câu hỏi của tôi' : 'Câu hỏi thường gặp'; ?>
      </h2>
      <p class="max-w-2xl mx-auto">
        <?php if ($isMyQuestionsTab): ?>
          Danh sách các câu hỏi bạn đã gửi và trạng thái trả lời của chúng.
        <?php else: ?>
          Giải đáp nhanh những thắc mắc phổ biến của khách hàng về dịch vụ thiết kế
          và thi công nội thất tại HomeDecor.
        <?php endif; ?>
      </p>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
      <form method="GET" action="/faq" class="flex gap-2">
        <input type="hidden" name="tab" value="<?php echo htmlspecialchars($currentTab); ?>">
        <input 
          type="text" 
          name="search" 
          value="<?php echo htmlspecialchars($searchQuery); ?>" 
          placeholder="Tìm kiếm câu hỏi..." 
          class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
        <button 
          type="submit" 
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
        >
          <i class="fa-solid fa-search"></i> Tìm kiếm
        </button>
        <?php if (!empty($searchQuery)): ?>
          <a 
            href="/faq?tab=<?php echo htmlspecialchars($currentTab); ?>" 
            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center"
          >
            <i class="fa-solid fa-times mr-2"></i> Xóa
          </a>
        <?php endif; ?>
      </form>
    </div>

    <!-- FAQ list -->
    <?php if (empty($faqs)): ?>
      <div class="bg-white rounded-xl shadow-sm p-8 text-center">
        <p class="text-lg">
          <?php echo !empty($searchQuery) ? 'Không tìm thấy câu hỏi nào phù hợp với từ khóa của bạn.' : ($isMyQuestionsTab ? 'Bạn chưa có câu hỏi nào.' : 'Hiện chưa có câu hỏi nào.'); ?>
        </p>
      </div>
    <?php else: ?>
      <div class="space-y-4 mb-8">
        <?php foreach ($faqs as $faq): ?>
          <?php 
          $answer = $faq->getAnswer();
          $hasNoAnswer = empty($answer);
          // Nếu đang ở tab "Câu hỏi của tôi" và chưa có câu trả lời → dùng màu khác
          $cardClass = 'group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200';
          if ($isMyQuestionsTab && $hasNoAnswer) {
            $cardClass = 'group bg-yellow-50 border-2 border-yellow-300 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200';
          }
          ?>
          <details class="<?php echo $cardClass; ?>">
            <summary
              class="flex items-center justify-between cursor-pointer px-6 py-4 text-left font-medium">
              <div class="flex items-center gap-3 flex-1">
                <?php if ($isMyQuestionsTab && $hasNoAnswer): ?>
                  <span class="flex-shrink-0">
                    <i class="fa-solid fa-clock text-yellow-600" title="Chưa có câu trả lời"></i>
                  </span>
                <?php endif; ?>
                <span><?php echo htmlspecialchars($faq->getQuestion()); ?></span>
              </div>
              <span
                class="ml-4 flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 text-gray-500 group-open:rotate-180 transition-transform duration-200">
                <i class="fa-solid fa-chevron-down text-xs"></i>
              </span>
            </summary>
            <div class="px-6 pb-4 pt-1 leading-relaxed">
              <?php 
              if (empty($answer)): 
              ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                  <p class="text-yellow-800">
                    <i class="fa-solid fa-clock mr-2"></i>
                    Câu hỏi này đang được xem xét và sẽ được trả lời sớm nhất có thể.
                  </p>
                </div>
              <?php else: ?>
                <?php echo nl2br(htmlspecialchars($answer)); ?>
              <?php endif; ?>
            </div>
          </details>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if (isset($pagination) && $pagination->hasPages()): ?>
        <?php
        $current = $pagination->getCurrentPage();
        $total = $pagination->getTotalPages();
        $baseUrl = '/faq?tab=' . urlencode($currentTab);
        ?>
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-gray-200">
          <div class="flex gap-2">
            <?php if ($current > 1): ?>
              <?php
              $params = ['tab' => $currentTab];
              if (!empty($searchQuery)) $params['search'] = $searchQuery;
              $params['page'] = $current - 1;
              $href = '/faq?' . http_build_query($params);
              ?>
              <a 
                href="<?php echo $href; ?>"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
              >
                Trước
              </a>
            <?php endif; ?>

            <?php for ($i = max(1, $current - 2); $i <= min($total, $current + 2); $i++): ?>
              <?php
              $params = ['tab' => $currentTab];
              if (!empty($searchQuery)) $params['search'] = $searchQuery;
              if ($i > 1) $params['page'] = $i;
              $href = '/faq?' . http_build_query($params);
              ?>
              <a 
                href="<?php echo $href; ?>"
                class="px-4 py-2 <?php echo ($i === $current) ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg transition-colors duration-200"
              >
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>

            <?php if ($current < $total): ?>
              <?php
              $params = ['tab' => $currentTab];
              if (!empty($searchQuery)) $params['search'] = $searchQuery;
              $params['page'] = $current + 1;
              $href = '/faq?' . http_build_query($params);
              ?>
              <a 
                href="<?php echo $href; ?>"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"
              >
                Sau
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<form class="w-full max-w-4xl mx-auto bg-white p-6 rounded-md shadow-sm" method="POST" action="/contact">
    <div class="grid grid-cols-5 gap-4 items-center mb-4">
        <label for="name" class="col-span-1 font-bold">Họ tên</label>
        <input type="text" id="name" name="name" placeholder="Họ tên*"
            class="col-span-4 border border-gray-300 rounded-sm px-3 py-2 w-full focus:outline-none focus:ring-1 focus:ring-gray-400">
    </div>

    <div class="grid grid-cols-5 gap-4 items-center mb-4">
        <label for="email" class="col-span-1 font-bold">Email</label>
        <input type="email" id="email" name="email" placeholder="Email*"
            class="col-span-4 border border-gray-300 rounded-sm px-3 py-2 w-full focus:outline-none focus:ring-1 focus:ring-gray-400"
            required>
    </div>

    <div class="grid grid-cols-5 gap-4 items-center mb-4">
        <label for="phone" class="col-span-1 font-bold">Điện thoại</label>
        <input type="text" id="phone" name="phone" placeholder="Số điện thoại"
            class="col-span-4 border border-gray-300 rounded-sm px-3 py-2 w-full focus:outline-none focus:ring-1 focus:ring-gray-400">
    </div>

    <div class="grid grid-cols-5 gap-4 items-center mb-4">
        <label for="subject" class="col-span-1 font-bold">Tiêu đề</label>
        <input type="text" id="subject" name="subject" placeholder="Tiêu đề (ví dụ: Yêu cầu báo giá)"
            class="col-span-4 border border-gray-300 rounded-sm px-3 py-2 w-full focus:outline-none focus:ring-1 focus:ring-gray-400">
    </div>

    <div class="grid grid-cols-5 gap-4 items-start mb-4">
        <label for="message" class="col-span-1 font-bold">Nội dung</label>
        <textarea id="message" name="message" rows="5" placeholder="Lời nhắn"
            class="col-span-4 border border-gray-300 rounded-sm px-3 py-2 w-full focus:outline-none focus:ring-1 focus:ring-gray-400 resize-none"
            required></textarea>
    </div>

    <div class="grid grid-cols-5 gap-4">
        <div class="col-span-1"></div>
        <button type="submit"
            class="col-span-2 bg-gray-800 text-white px-6 py-2 text-sm font-semibold hover:bg-gray-700 transition">
            GỬI NHẬN XÉT
        </button>
    </div>
</form>
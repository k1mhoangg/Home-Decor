// Khi người dùng cuộn xuống, header sẽ đổi màu nhẹ
window.addEventListener("scroll", function () {
  const header = document.querySelector("header");
  if (window.scrollY > 50) {
    header.style.backgroundColor = "#111";
    header.style.boxShadow = "0 4px 12px rgba(0,0,0,0.5)";
  } else {
    header.style.backgroundColor = "#000";
    header.style.boxShadow = "0 2px 10px rgba(0,0,0,0.4)";
  }
});

// Hiệu ứng click vào icon tìm kiếm
const searchIcon = document.querySelector(".fa-search");
const searchInput = document.querySelector(".others input");

if (searchIcon && searchInput) {
  searchIcon.addEventListener("click", () => {
    searchInput.classList.toggle("show");
    searchInput.focus();
  });
}

// Hamburger Menu Toggle
const hamburgerBtn = document.querySelector(".hamburger-btn");
const mobileMenu = document.querySelector(".mobile-menu");

if (hamburgerBtn && mobileMenu) {
  hamburgerBtn.addEventListener("click", () => {
    hamburgerBtn.classList.toggle("active");
    mobileMenu.classList.toggle("active");
  });

  // Đóng menu khi click bên ngoài
  document.addEventListener("click", (e) => {
    if (
      !hamburgerBtn.contains(e.target) &&
      !mobileMenu.contains(e.target) &&
      mobileMenu.classList.contains("active")
    ) {
      hamburgerBtn.classList.remove("active");
      mobileMenu.classList.remove("active");
    }
  });

  // Đóng menu khi click vào link trong menu
  const mobileMenuLinks = mobileMenu.querySelectorAll("a");
  mobileMenuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      hamburgerBtn.classList.remove("active");
      mobileMenu.classList.remove("active");
    });
  });
}

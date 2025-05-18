<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navbar Dinamis</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- AOS Library CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- SwiperJS CSS (jika diperlukan) -->
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <style>
    /* Dynamic Navbar Background */
    .navbar-transparent {
      background-color: rgba(16, 185, 129, 0.2);
      transition: background-color 0.3s ease;
    }
    .navbar-colored {
      background: linear-gradient(90deg, #34D399, #10B981);
      transition: background-color 0.3s ease;
    }
    /* Styling warna link sesuai state navbar */
    .navbar-transparent a {
      color: #ffffff;
      transition: color 0.3s ease;
    }
    .navbar-transparent a:hover {
      color: #059669;
    }
    .navbar-colored a {
      color: #ffffff;
      transition: color 0.3s ease;
    }
    .navbar-colored a:hover {
      color: #f3f4f6;
    }
    /* Animasi klik sederhana */
    @keyframes clickAnimation {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    .animate-click {
      animation: clickAnimation 0.3s ease;
    }
    /* Smooth scrolling */
    html { scroll-behavior: smooth; }
    /* Efek parallax untuk section */
    .parallax { background-attachment: fixed; background-size: cover; background-position: center; }
  </style>
</head>
<body class="overflow-x-hidden">
  <!-- Navbar -->
  <header class="fixed w-full z-50">
    <nav id="navbar" class="navbar-transparent border-b border-gray-200 py-2.5">
      <!-- Gunakan div dengan w-full agar navbar memenuhi lebar layar -->
      <div class="w-full flex items-center justify-between px-4 h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <a href="#home" onclick="AOS.refresh();" class="text-xl font-bold">AgroSmart</a>
        </div>
        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-4">
          <a href="#home" onclick="toggleMenu()" class="transition-colors text-xl">Home</a>
          <a href="#features" onclick="toggleMenu()" class="transition-colors text-xl">Fitur</a>
          <a href="#demo" onclick="toggleMenu()" class="transition-colors text-xl">Demo</a>
          <a href="#about" onclick="toggleMenu()" class="transition-colors text-xl">Tentang</a>
          <a href="#contact" onclick="toggleMenu()" class="transition-colors text-xl">Kontak</a>
          <a href="/login" onclick="toggleMenu()" class="px-3 py-1 border rounded transition-colors">Login</a>
        </div>
        <!-- Mobile Menu Button -->
        <div class="md:hidden mr-2">
          <button id="mobile-menu-btn" class="text-gray-800 focus:outline-none" aria-expanded="false" aria-label="Toggle navigation">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>
      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-white shadow-md md:hidden">
        <div class="px-4 pt-2 pb-2 space-y-1">
          <a href="#home" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium transition-colors">Home</a>
          <a href="#features" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium transition-colors">Fitur</a>
          <a href="#demo" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium transition-colors">Demo</a>
          <a href="#about" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium transition-colors">Tentang</a>
          <a href="#contact" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium transition-colors">Kontak</a>
          <a href="/login" onclick="toggleMenu()" class="block px-3 py-2 rounded-md text-base font-medium border transition-colors text-center">Login</a>
        </div>
      </div>
    </nav>
  </header>

  <!-- Hero Section (Static) -->
  <section id="home" class="relative h-screen parallax" style="background-image: url('pertanian.jpg');" data-aos="fade-in">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-green-600 opacity-20"></div>
    <div class="container mx-auto h-full flex flex-col items-center justify-center relative z-10 text-center px-4">
      <h1 class="text-4xl md:text-6xl font-bold text-white">Optimalkan Pertanian Anda</h1>
      <p class="mt-4 text-lg md:text-2xl text-white max-w-2xl">
        Pantau lahan dan terima rekomendasi cerdas berbasis data real-time untuk mendukung keputusan pertanian Anda.
      </p>
      <a href="#demo" onclick="AOS.refresh();" class="mt-6 inline-block px-8 py-3 bg-green-700 hover:bg-green-800 text-white font-semibold rounded transition-colors">Mulai Sekarang</a>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-8 md:py-16 bg-cover bg-center relative" style="background-image: url('https://img.freepik.com/free-photo/indonesian-agriculture2.jpg');" data-aos="fade-up">
    <div class="bg-green-600 bg-opacity-60 py-8 md:py-16">
      <div class="container mx-auto px-4">
        <div class="text-center text-white">
          <h2 class="text-3xl md:text-4xl font-bold">Fitur Utama AgroSmart</h2>
          <p class="mt-4 text-base md:text-lg max-w-2xl mx-auto">
            Pantau data pertanian dengan dashboard interaktif dan dapatkan rekomendasi cerdas berbasis data historis dan cuaca.
          </p>
        </div>
        <div class="mt-8 md:mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Feature 1 -->
          <div class="bg-white bg-opacity-90 p-4 md:p-6 rounded shadow text-center" data-aos="flip-left">
            <img src="https://img.freepik.com/free-photo/dashboard-monitoring-agriculture.jpg" alt="Dashboard Monitoring" class="w-full h-40 object-cover rounded mb-3" loading="lazy">
            <h3 class="text-xl font-bold mb-1">Dashboard Monitoring</h3>
            <p class="text-sm md:text-base">Visualisasi interaktif pertumbuhan tanaman dan histori aktivitas lahan.</p>
          </div>
          <!-- Feature 2 -->
          <div class="bg-white bg-opacity-90 p-4 md:p-6 rounded shadow text-center" data-aos="flip-left" data-aos-delay="100">
            <img src="https://img.freepik.com/free-photo/farming-recommendation.jpg" alt="Rekomendasi Budidaya" class="w-full h-40 object-cover rounded mb-3" loading="lazy">
            <h3 class="text-xl font-bold mb-1">Rekomendasi Budidaya</h3>
            <p class="text-sm md:text-base">Sistem cerdas memberikan saran berdasarkan data historis dan cuaca.</p>
          </div>
          <!-- Feature 3 -->
          <div class="bg-white bg-opacity-90 p-4 md:p-6 rounded shadow text-center" data-aos="flip-left" data-aos-delay="200">
            <img src="https://img.freepik.com/free-photo/weather-api-agriculture.jpg" alt="Integrasi API Cuaca" class="w-full h-40 object-cover rounded mb-3" loading="lazy">
            <h3 class="text-xl font-bold mb-1">Integrasi API Cuaca</h3>
            <p class="text-sm md:text-base">Data cuaca real-time untuk mendukung keputusan pertanian Anda.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Demo & Showcase Section -->
  <section id="demo" class="py-8 md:py-16 bg-gray-100" data-aos="fade-up">
    <div class="container mx-auto px-4">
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-green-700">Demo Interaktif</h2>
        <p class="mt-4 text-base md:text-lg max-w-2xl mx-auto">
          Lihat simulasi data real-time dari dashboard dan rekomendasi kami.
        </p>
      </div>
      <div id="demoData" class="mt-6 bg-white p-4 md:p-6 rounded shadow text-center">
        <p class="text-gray-700">Mengambil data dari backend...</p>
      </div>
    </div>
  </section>

  <!-- Testimonial Section -->
  <section id="testimonial" class="py-8 md:py-16 bg-cover bg-center relative" style="background-image: url('https://img.freepik.com/free-photo/indonesian-agriculture1.jpg');" data-aos="fade-up">
    <div class="bg-green-600 bg-opacity-60 py-8 md:py-16">
      <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Apa Kata Mereka?</h2>
        <p class="text-base md:text-lg mb-6 max-w-2xl mx-auto">
          Dengarkan pengalaman petani dan pakar yang telah menggunakan AgroSmart untuk meningkatkan produktivitas.
        </p>
        <!-- Swiper Container untuk Testimonial -->
        <div class="swiper-container max-w-4xl mx-10">
          <div class="swiper-wrapper">
            <!-- Testimonial Slide 1 -->
            <div class="swiper-slide flex justify-center mx-2 w-full">
              <div class="bg-white rounded-lg shadow-xl p-4 md:p-6 w-full">
                <p class="italic text-gray-700 text-sm md:text-base">
                  "זוהי עובדה מבוססת שדעתו של הקורא תהיה מוסחת על ידי טקטס קריא כאשר הוא יביט בפריסתו."
                </p>
                <p class="mt-2 font-bold text-green-600 text-sm md:text-base">- ipsum, Petani</p>
              </div>
            </div>
            <!-- Testimonial Slide 2 -->
            <div class="swiper-slide flex justify-center mx-2 w-full">
              <div class="bg-white rounded-lg shadow-xl p-4 md:p-6 w-full">
                <p class="italic text-gray-700 text-sm md:text-base">
                  "זוהי עובדה מבוססת שדעתו של הקורא תהיה מוסחת על ידי טקטס קריא כאשר הוא יביט בפריסתו. Mempunyai distribusi huruf yang seimbang."
                </p>
                <p class="mt-2 font-bold text-green-600 text-sm md:text-base">- Lorem, Pakar Pertanian</p>
              </div>
            </div>
            <!-- Testimonial Slide 3 -->
            <div class="swiper-slide flex justify-center mx-2 w-full">
              <div class="bg-white rounded-lg shadow-xl p-4 md:p-6 w-full">
                <p class="italic text-gray-700 text-sm md:text-base">
                  "זוהי עובדה מבוססת שדעתו של הקורא תהיה מוסחת על ידי טקטס קריא כאשר הוא יביט בפריסתו. Mempunyai distribusi huruf yang seimbang."
                </p>
                <p class="mt-2 font-bold text-green-600 text-sm md:text-base">- Lorem, Pakar Pertanian</p>
              </div>
            </div>
          </div>
          <!-- Swiper Pagination & Navigation -->
          <div class="swiper-pagination"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-8 md:py-16 bg-white" data-aos="zoom-in">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-green-700">Tentang AgroSmart</h2>
      <p class="mt-4 text-base md:text-lg max-w-2xl mx-auto">
        AgroSmart dikembangkan untuk mengoptimalkan pertanian melalui teknologi modern dan data real-time. Kami menyajikan solusi terpadu mulai dari dashboard monitoring, rekomendasi budidaya, hingga integrasi API cuaca, guna mendukung petani membuat keputusan yang tepat.
      </p>
      <div class="mt-4">
        <p class="text-gray-700">Tim kami terdiri dari profesional di bidang teknik informasi.</p>
        <p class="text-gray-700 mt-1">Teknologi yang digunakan: Laravel, Tailwind CSS, Laravel Passport (OAuth2 & PKCE), dan integrasi API eksternal.</p>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-8 md:py-16 bg-gray-200" data-aos="fade-up">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl md:text-4xl font-bold text-green-700">Kontak Kami</h2>
      <p class="mt-4 text-base md:text-lg max-w-2xl mx-auto">
        Untuk informasi lebih lanjut, silakan hubungi kami melalui:
      </p>
      <div class="mt-4">
        <p class="text-gray-700"><i class="fas fa-envelope text-green-600"></i> Email: minuq98@agrosmart.com</p>
        <p class="text-gray-700 mt-1"><i class="fas fa-phone-alt text-green-600"></i> Telepon: +62 123 4567 890</p>
        <p class="text-gray-700 mt-1"><i class="fas fa-map-marker-alt text-green-600"></i> Alamat: Jl. Contoh No. 123, Jakarta, Indonesia</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-4 md:py-6 bg-green-700">
    <div class="container mx-auto px-4 text-center text-white">
      &copy; 2025 AgroSmart. All rights reserved.
    </div>
  </footer>

  <!-- AOS Library JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <!-- SwiperJS Library JS -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <!-- Script untuk animasi, toggle mobile menu, dan dynamic navbar -->
  <script>
    // Inisialisasi AOS
    AOS.init({
      duration: 1000,
      once: false
    });

    // Inisialisasi Swiper Slider
    var swiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });

    // Toggle Mobile Menu
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      const expanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
      mobileMenuBtn.setAttribute('aria-expanded', !expanded);
    });

    // Ubah background navbar saat scroll
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.remove('navbar-transparent');
        navbar.classList.add('navbar-colored');
      } else {
        navbar.classList.remove('navbar-colored');
        navbar.classList.add('navbar-transparent');
      }
    });

    // Tambahkan animasi klik pada setiap link navbar
    document.querySelectorAll('nav a').forEach(link => {
      link.addEventListener('click', function() {
        this.classList.add('animate-click');
        setTimeout(() => {
          this.classList.remove('animate-click');
        }, 300);
      });
    });
  </script>
</body>
</html>

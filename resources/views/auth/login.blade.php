<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login & Registration</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- AOS Library CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Font Awesome CDN -->
</head>

<body class="bg-gray-100">
  <!-- Container for Login & Registration Form -->
  <div class="flex flex-col md:flex-row w-full max-w-5xl mx-auto my-16 shadow-lg rounded-lg overflow-hidden">
    <!-- Image Column (Hidden on mobile) -->
    <div class="hidden md:block md:w-1/2">
      <img src="https://img.freepik.com/free-photo/beautiful-strawberry-garden-sunrise-doi-ang-khang-chiang-mai-thailand_335224-761.jpg" alt="Agricultural Field" class="w-full h-full object-cover">
    </div>
    <!-- Form Column -->
    <div class="w-full md:w-1/2 p-8">
      <!-- Form Header, changes according to tab -->
      <h2 id="form-header" class="mb-6 text-2xl font-bold text-green-600 text-center">Login</h2>
      @if ($errors->any())
      <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        @foreach ($errors->all() as $error)
        <p class="mb-1">{{ $error }}</p>
        @endforeach
      </div>
      @endif

      @if (isset($data))
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
        <p class="mb-1">{{ $data['message'] }}</p>
      </div>
      @endif
      <!-- Tab Navigation -->
      <div class="flex justify-center mb-6 border-b border-gray-300">
        <button id="tab-login" class="px-4 py-2 border-b-2 border-green-600 font-semibold text-green-600 focus:outline-none">Login</button>
        <button id="tab-register" class="px-4 py-2 border-b-2 border-transparent font-semibold text-gray-600 hover:text-green-600 focus:outline-none">Register</button>
      </div>
      <!-- Login Form -->
      <div id="form-login">
        <form method="POST" action="{{ route('login.submit') }}">
          <!-- CSRF token for Laravel -->
          @csrf

          <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" id="email" name="email" autocomplete="email" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="password" class="block text-gray-700">Password:</label>
            <input type="password" id="password" name="password" autocomplete="current-password" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>

          <button type="submit" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Login</button>
        </form>
        <div class="mt-4 text-center">
          <p class="text-gray-600">Or login with:</p>
          <div class="mt-2 flex space-x-4 justify-center">
            <!-- Google Button -->
            <a href="{{ route('platform.redirect') }}?platform=google" class="flex items-center bg-white border border-green-300 p-2 rounded hover:bg-green-900 transform transition duration-200 ease-in-out hover:scale-105 active:scale-95">
              <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/24px-Google_%22G%22_logo.svg.png" alt="Google Logo" class="w-6 h-6 mr-2">
              <span class="text-green-600 text-sm">Google</span>
            </a>
            <!-- Github Button -->
            <a href="{{ route('platform.redirect') }}?platform=github" class="flex items-center bg-white border border-green-300 p-2 rounded hover:bg-green-100 transform transition duration-200 ease-in-out hover:scale-105 active:scale-95">
              <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Octicons-mark-github.svg" alt="Github Logo" class="w-6 h-6 mr-2">
              <span class="text-green-600 text-sm">Github</span>
            </a>
          </div>
        </div>
      </div>
      <!-- Registration Form (Initially hidden) -->
      <div id="form-register" class="hidden">
        <form method="POST" action="/register">
          @csrf
          <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
            <p class="mb-1">Please ensure the verification email comes from {{ env("MAIL_FROM_ADDRESS") }}</p>
          </div>
          <!-- CSRF token for Laravel -->
          <div class="mb-4">
            <label for="name" class="block text-gray-700">Full Name:</label>
            <input type="text" id="name" name="name" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="email_reg" class="block text-gray-700">Email:</label>
            <input type="email" id="email_reg" autocomplete="email" name="email" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="password_reg" class="block text-gray-700">Password:</label>
            <input type="password" id="password_reg" autocomplete="new-password" name="password" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="password_confirm" class="block text-gray-700">Confirm Password:</label>
            <input type="password" id="password_confirm" autocomplete="new-password" name="password_confirmation" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="address" class="block text-gray-700">Address:</label>
            <input type="text" id="address" name="address" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <div class="mb-4">
            <label for="contact" class="block text-gray-700">Contact:</label>
            <input type="text" id="contact" name="contact" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-600" required>
          </div>
          <button type="submit" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Register</button>
        </form>
      </div>
    </div>
  </div>

  <!-- AOS Library JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <!-- JavaScript for Toggle Tab Login & Registration -->
  <script>
    AOS.init({
      duration: 1000,
      once: false
    });

    const tabLogin = document.getElementById('tab-login');
    const tabRegister = document.getElementById('tab-register');
    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');
    const formHeader = document.getElementById('form-header');

    tabLogin.addEventListener('click', () => {
      tabLogin.classList.add('border-b-2', 'border-green-600', 'text-green-600');
      tabRegister.classList.remove('border-b-2', 'border-green-600', 'text-green-600');
      tabRegister.classList.add('text-gray-600');
      formHeader.innerText = "Login";
      formLogin.classList.remove('hidden');
      formRegister.classList.add('hidden');
    });

    tabRegister.addEventListener('click', () => {
      tabRegister.classList.add('border-b-2', 'border-green-600', 'text-green-600');
      tabLogin.classList.remove('border-b-2', 'border-green-600', 'text-green-600');
      tabLogin.classList.add('text-gray-600');
      formHeader.innerText = "Register";
      formRegister.classList.remove('hidden');
      formLogin.classList.add('hidden');
    });
  </script>
</body>

</html>

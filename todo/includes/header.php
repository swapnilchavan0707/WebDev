<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FlowTrack - Organize Your Day</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:"Times New Roman", Times, serif;
        }

        .card-hover:hover{
            transform:translateY(-8px);
            transition:0.3s ease;
        }

        @keyframes pop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .hide-scrollbar {
                scrollbar-width: none;       /* Firefox */
                -ms-overflow-style: none;    /* IE/Edge */
        }

        .hide-scrollbar::-webkit-scrollbar {
                display: none;               /* Chrome, Safari */
        }

    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="sticky top-0 bg-white/95 backdrop-blur-md shadow-lg z-50">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        <a href="index.php" class="flex items-center gap-3">

            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                <i class="fa-solid fa-bullseye text-white text-lg"></i>
            </div>

            <div>
                <h1 class="text-3xl font-bold text-blue-600">FlowTrack</h1>
                <p class="text-xs text-gray-500">A Product of Nexora Technologies</p>
            </div>

        </a>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-8 text-lg font-medium">

            <a href="index.php" class="text-gray-700 hover:text-blue-600">Home</a>
            <a href="about.php" class="text-gray-700 hover:text-blue-600">About</a>
            <a href="services.php" class="text-gray-700 hover:text-blue-600">Services</a>
            <a href="contact.php" class="text-gray-700 hover:text-blue-600">Contact</a>

        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3">

            <button onclick="openLogin()"
                class="px-5 py-2 text-blue-600 font-semibold hover:text-blue-800 transition">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                Sign In
            </button>

            <button onclick="openRegister()"
                class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-user-plus mr-2"></i>
                Sign Up
            </button>

        </div>

    </div>

</nav>

<!-- MAIN -->
<main class="flex-grow max-w-7xl mx-auto w-full px-6 py-12">

<!-- ================= LOGIN MODAL ================= -->
<div id="loginModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     onclick="if(event.target === this) closeLogin()">

    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto animate-[pop_0.2s_ease]"
         style="animation: pop 0.2s ease;">

        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center mb-3">
                <i class="fa-solid fa-right-to-bracket text-blue-600 text-xl"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
            <p class="text-gray-500 text-sm">Sign in to continue FlowTrack</p>
        </div>

        <div class="space-y-4">

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-400"></i>
                <input id="loginEmail" type="email"
                    class="w-full pl-10 p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Email Address">
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400"></i>
                <input id="loginPassword" type="password"
                    class="w-full pl-10 p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Password">
            </div>

        </div>

        <p id="loginMsg" class="text-sm mt-3 text-center"></p>

        <button onclick="loginUser()"
            class="w-full mt-5 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-lg">
            Login
        </button>

        <button onclick="closeLogin()" class="w-full mt-3 text-gray-500 text-sm hover:text-gray-700">
            Close
        </button>

    </div>
</div>

<!-- ================= REGISTER MODAL ================= -->
<div id="registerModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     onclick="if(event.target === this) closeRegister()">

    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto hide-scrollbar animate-[pop_0.2s_ease]"
         style="animation: pop 0.2s ease;">

        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto bg-green-100 rounded-2xl flex items-center justify-center mb-3">
                <i class="fa-solid fa-user-plus text-green-600 text-xl"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
            <p class="text-gray-500 text-sm">Join FlowTrack as a Customer</p>
        </div>

        <div class="space-y-3">

            <input id="firstName" placeholder="First Name"
                class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none">

            <input id="lastName" placeholder="Last Name"
                class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none">

            <input id="mobile" placeholder="Mobile Number"
                class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none">

            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3 top-3 text-gray-400"></i>
                <input id="email" type="email"
                    class="w-full pl-10 p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Email Address">
            </div>

            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-3 text-gray-400"></i>
                <input id="password" type="password"
                    class="w-full pl-10 p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Password">
            </div>

            <input id="repassword" type="password"
                class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none"
                placeholder="Re-enter Password">

        </div>

        <p id="registerMsg" class="text-sm mt-3 text-center"></p>

        <button onclick="registerUser()"
            class="w-full mt-5 bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition shadow-lg">
            Register
        </button>

        <button onclick="closeRegister()" class="w-full mt-3 text-gray-500 text-sm hover:text-gray-700">
            Close
        </button>

    </div>
</div>

<!-- ================= JS AUTH ================= -->
<script>

function openLogin(){
    document.getElementById("loginModal").classList.remove("hidden");
}

function closeLogin(){
    document.getElementById("loginModal").classList.add("hidden");
}

function openRegister(){
    document.getElementById("registerModal").classList.remove("hidden");
}

function closeRegister(){
    document.getElementById("registerModal").classList.add("hidden");
}

/* LOGIN */
function loginUser(){

    let email = document.getElementById("loginEmail").value;
    let password = document.getElementById("loginPassword").value;

    if(!email || !password){
        document.getElementById("loginMsg").innerHTML =
            "<span class='text-red-600'>Please fill all fields</span>";
        return;
    }

    fetch("auth/login.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "email=" + encodeURIComponent(email) +
              "&password=" + encodeURIComponent(password)
    })
    .then(res => res.text())
    .then(data => {

        if(data.trim() === "success"){
            document.getElementById("loginMsg").innerHTML =
                "<span class='text-green-600'>Login Successful</span>";

            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 800);

        } else {
            document.getElementById("loginMsg").innerHTML =
                "<span class='text-red-600'>Invalid Credentials</span>";
        }

    });
}

/* REGISTER */
function registerUser(){

    let f = document.getElementById("firstName").value;
    let l = document.getElementById("lastName").value;
    let m = document.getElementById("mobile").value;
    let e = document.getElementById("email").value;
    let p = document.getElementById("password").value;
    let rp = document.getElementById("repassword").value;

    if(!f || !l || !m || !e || !p || !rp){
        document.getElementById("registerMsg").innerHTML =
            "<span class='text-red-600'>All fields are required</span>";
        return;
    }

    if(p !== rp){
        document.getElementById("registerMsg").innerHTML =
            "<span class='text-red-600'>Passwords do not match</span>";
        return;
    }

    fetch("auth/register.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body:
            "first=" + encodeURIComponent(f) +
            "&last=" + encodeURIComponent(l) +
            "&mobile=" + encodeURIComponent(m) +
            "&email=" + encodeURIComponent(e) +
            "&password=" + encodeURIComponent(p)
    })
    .then(res => res.text())
    .then(data => {

        if(data.trim() === "success"){
            document.getElementById("registerMsg").innerHTML =
                "<span class='text-green-600'>Registered Successfully</span>";
        } else {
            document.getElementById("registerMsg").innerHTML =
                "<span class='text-red-600'>Error occurred</span>";
        }

    });

}

</script>
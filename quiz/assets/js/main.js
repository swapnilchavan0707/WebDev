"use strict";

document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener("click", function () {
            mobileMenu.classList.toggle("hidden");
        });
    }

});

function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: "smooth" });
    }
}

function showAlert(message, type = "success") {

    const alertBox = document.createElement("div");

    alertBox.className = `
        fixed top-5 right-5 px-6 py-3 rounded-lg text-white shadow-lg z-50
        ${type === "success" ? "bg-green-600" : "bg-red-600"}
    `;

    alertBox.innerText = message;

    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.remove();
    }, 3000);
}

document.addEventListener("contextmenu", function (e) {
    // Uncomment below if you want to disable right click
    // e.preventDefault();
});

document.addEventListener("copy", function (e) {
    // Uncomment if needed
    // e.preventDefault();
});

console.log("Quizora JS Loaded Successfully!");
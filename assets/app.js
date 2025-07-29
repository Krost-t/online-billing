import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

const btn = document.querySelector(".bi-list");
const nav = document.querySelector(".nav-place-mobile");
const closeBtn = document.querySelector(".bi-x-lg");
const navLinks = document.querySelector(".bi-house");

btn.addEventListener("click", () => {
    nav.style.display = "flex";
});

closeBtn.addEventListener("click", () => {
    nav.style.display = "none";
});

navLinks.addEventListener("click", () => {
    window.location.href = "/";
});

document.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", function (event) {
        event.preventDefault();
        window.location.href = link.href;
    });
});



document
    .querySelector(".logo-accueil")
    .addEventListener("click", function (event) {
        event.preventDefault();
        window.location.href = "/";
    });

document.querySelector("#logOut").addEventListener("click", function (event) {
    event.preventDefault();
    window.location.href = "/deconnexion";
});

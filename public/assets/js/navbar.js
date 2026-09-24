document.addEventListener("DOMContentLoaded", () => {

    const searchToggle = document.getElementById("searchToggle");
    const searchContainer = document.getElementById("searchContainer");

    const mobileToggle = document.getElementById("mobileToggle");
    const mobileMenu = document.getElementById("mobileMenu");

    /* ==============================
       RECHERCHE
       ============================== */

    searchToggle?.addEventListener("click", () => {
        searchContainer.classList.toggle("show");

        if (searchContainer.classList.contains("show")) {
            document.getElementById("productSearch")?.focus();
        }
    });

    /* ==============================
       MENU MOBILE
       ============================== */

    mobileToggle?.addEventListener("click", () => {

        mobileMenu.classList.toggle("show");

        const icon = mobileToggle.querySelector("i");

        if (mobileMenu.classList.contains("show")) {
            icon.classList.remove("bi-list");
            icon.classList.add("bi-x-lg");
        } else {
            icon.classList.remove("bi-x-lg");
            icon.classList.add("bi-list");
        }

    });

    /* ==============================
       RECHERCHE PRODUITS
       ============================== */

    const searchForm = document.querySelector(".search-box");

    searchForm?.addEventListener("submit", (e) => {

        e.preventDefault();

        const search = document
            .getElementById("productSearch")
            .value
            .trim();

        if (search !== "") {
            window.location.href =
                `products.html?search=${encodeURIComponent(search)}`;
        }

    });

    /* ==============================
       COMPTEUR PANIER
       ============================== */

    updateCartCount();

});


function updateCartCount() {

    const cartCount = document.getElementById("cartCount");

    if (!cartCount) return;

    const cart = JSON.parse(
        localStorage.getItem("akatsukiCart")
    ) || [];

    const total = cart.reduce(
        (sum, product) => sum + (product.quantity || 1),
        0
    );

    cartCount.textContent = total;
}
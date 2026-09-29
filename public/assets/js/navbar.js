document.addEventListener("DOMContentLoaded", () => {

    const searchToggle = document.getElementById("searchToggle");
    const searchContainer = document.getElementById("searchContainer");

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
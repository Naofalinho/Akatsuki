document.addEventListener("DOMContentLoaded", () => {

    // Afficher / cacher le mot de passe
    document.querySelectorAll(".password-toggle").forEach(button => {

        button.addEventListener("click", () => {

            const targetId = button.dataset.target;
            const input = document.getElementById(targetId);
            const icon = button.querySelector("i");

            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");

            } else {

                input.type = "password";

                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        });

    });


    // Vérification des mots de passe
    const form = document.getElementById("registerForm");

    form?.addEventListener("submit", (event) => {

        event.preventDefault();

        const password =
            document.getElementById("password").value;

        const confirmation =
            document.getElementById("password_confirmation").value;

        const message =
            document.getElementById("registerMessage");


        if (password !== confirmation) {

            message.textContent =
                "Les mots de passe ne correspondent pas.";

            return;
        }

        message.textContent = "";

        alert("Inscription validée !");

        // Plus tard :
        // Envoi vers Laravel / Livewire
    });

});
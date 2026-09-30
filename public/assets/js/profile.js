(() => {
    let profileObserver;

    function initializeProfileNavigation() {
        const page = document.querySelector(".ak-profile-page");

        if (!page || page.dataset.profileNavigationReady === "true") return;

        const links = [...page.querySelectorAll('.profile-menu a[href^="#"]')];
        const sections = links
            .map((link) => document.getElementById(link.hash.slice(1)))
            .filter(Boolean);

        if (links.length === 0 || sections.length === 0) return;

        page.dataset.profileNavigationReady = "true";
        profileObserver?.disconnect();

        const setActiveLink = (section) => {
            links.forEach((link) => {
                link.classList.toggle("active", link.hash === `#${section.id}`);
            });
        };

        links.forEach((link) => {
            link.addEventListener("click", () => {
                const section = document.getElementById(link.hash.slice(1));
                if (section) setActiveLink(section);
            });
        });

        const initialSection = sections.find((section) => `#${section.id}` === window.location.hash)
            || sections.find((section) => links.find((link) => link.hash === `#${section.id}`)?.classList.contains("active"))
            || sections[0];

        setActiveLink(initialSection);

        if ("IntersectionObserver" in window) {
            profileObserver = new IntersectionObserver((entries) => {
                const visibleSection = entries
                    .filter((entry) => entry.isIntersecting)
                    .sort((first, second) => first.boundingClientRect.top - second.boundingClientRect.top)[0];

                if (visibleSection) setActiveLink(visibleSection.target);
            }, {
                rootMargin: "-15% 0px -65% 0px",
                threshold: 0,
            });

            sections.forEach((section) => profileObserver.observe(section));
        }
    }

    document.addEventListener("DOMContentLoaded", initializeProfileNavigation);
    document.addEventListener("livewire:navigated", initializeProfileNavigation);

    if (document.readyState !== "loading") initializeProfileNavigation();
})();
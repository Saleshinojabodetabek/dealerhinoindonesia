document.addEventListener("DOMContentLoaded", () => {
  // ==========================
  // HAMBURGER MENU
  // ==========================
  const hamburger = document.querySelector(".hamburger-menu");
  const navLinks = document.querySelector(".nav.links");

  if (hamburger && navLinks) {
    // Toggle menu saat klik hamburger
    hamburger.addEventListener("click", (e) => {
      e.stopPropagation();
      navLinks.classList.toggle("active");
    });

    // Tutup menu saat klik di luar menu atau hamburger
    document.addEventListener("click", (e) => {
      if (!navLinks.contains(e.target) && !hamburger.contains(e.target)) {
        navLinks.classList.remove("active");
      }
    });

    // Tutup menu saat klik salah satu link nav
    navLinks.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        navLinks.classList.remove("active");
      });
    });
  }

  // ==========================
  // SLIDE BANNER
  // ==========================
  const slides = document.querySelectorAll(".slide");
  if (slides.length > 0) {
    let currentSlide = 0;

    const showSlide = (index) => {
      slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === index);
      });
    };

    const nextSlide = () => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    };

    setInterval(nextSlide, 4000);
  }

  // ==========================
  // SMOOTH SCROLL
  // ==========================
  document.querySelectorAll('nav a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (e) => {
      e.preventDefault();
      const targetID = anchor.getAttribute("href").substring(1);
      const target = document.getElementById(targetID);

      if (target) {
        const headerOffset = 130;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition =
          elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: "smooth",
        });
      }
    });
  });

  // ==========================
  // FADE IN / FADE OUT ON SCROLL
  // ==========================
  const faders = document.querySelectorAll(".fade-element");
  const observerOptions = {
    threshold: 0.1, // minimal 10% terlihat
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      } else {
        entry.target.classList.remove("visible");
      }
    });
  }, observerOptions);

  faders.forEach((el) => observer.observe(el));
});

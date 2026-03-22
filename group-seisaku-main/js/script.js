// Intro animation and greetings logic
const greetingsData = [
  { text: "नमस्ते", className: "nepali" },
  { text: "Hello", className: "" },
  { text: "こんにちは", className: "" },
  { text: "дравствуйте", className: "" },
  { text: "Xin chào", className: "" },
  { text: "你好", className: "" },
  { text: "হ্যালো", className: "bengali" },
  { text: "Сайн байна уу", className: "" }
];

function createGreetingLine(text, className) {
  return new Promise((resolve) => {
    const container = document.getElementById('animatedText');
    if (!container) return resolve();
    const line = document.createElement('div');
    line.className = 'greeting ' + className;
    const textSpan = document.createElement('span');
    line.appendChild(textSpan);
    const cursor = document.createElement('span');
    cursor.className = 'cursor';
    line.appendChild(cursor);
    container.appendChild(line);
    let i = 0;
    function typeNext() {
      if (i < text.length) {
        textSpan.textContent += text[i++];
        setTimeout(typeNext, 80);
      } else {
        setTimeout(() => { cursor.remove(); resolve(); }, 800);
      }
    }
    typeNext();
  });
}

async function runIntro() {
  for (const g of greetingsData) {
    await createGreetingLine(g.text, g.className);
  }
  const intro = document.getElementById('intro');
  if (intro) {
    intro.style.opacity = 0;
    setTimeout(() => {
      intro.style.display = 'none';
    }, 1000);
  }
}

window.addEventListener('DOMContentLoaded', () => {
  // Loader and intro logic
  const loader = document.querySelector('.loader');
  if (loader) loader.style.display = '';
  setTimeout(() => {
    if (loader) loader.style.display = 'none';
    runIntro();
  }, 1000);
});
document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll(".slide");
  const prevBtn = document.querySelector(".prev");
  const nextBtn = document.querySelector(".next");
  let current = 0;
  let timer;

  // Show slide by index
  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove("active");
      slide.style.display = i === index ? "block" : "none";
    });
  }

  // Move to next slide
  function nextSlide() {
    current = (current + 1) % slides.length;
    showSlide(current);
  }

  // Move to previous slide
  function prevSlide() {
    current = (current - 1 + slides.length) % slides.length;
    showSlide(current);
  }

  // Reset timer when button clicked
  function resetTimer() {
    clearInterval(timer);
    startAutoSlide();
  }

  // Auto slide every 4 seconds
  function startAutoSlide() {
    timer = setInterval(nextSlide, 4000);
  }

  // Event listeners for buttons
  if (nextBtn && prevBtn) {
    nextBtn.addEventListener("click", () => {
      nextSlide();
      resetTimer();
    });

    prevBtn.addEventListener("click", () => {
      prevSlide();
      resetTimer();
    });
  }

  // Init
  showSlide(current);
  startAutoSlide();
});

// Hamburger Menu Toggle
const hamburger = document.getElementById("hamburger");
const nav = document.getElementById("nav");

hamburger.addEventListener("click", () => {
  nav.classList.toggle("active");
});

  // Toggle menu on hamburger click
  hamburger.addEventListener("click", function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const isActive = hamburger.classList.contains("active");
    
    if (isActive) {
      hamburger.classList.remove("active");
      nav.classList.remove("active");
    } else {
      hamburger.classList.add("active");
      nav.classList.add("active");
    }
  });
  
  // Close menu when clicking on a link
  const navLinks = nav.querySelectorAll("a");
  navLinks.forEach(link => {
    link.addEventListener("click", () => {
      hamburger.classList.remove("active");
      nav.classList.remove("active");
    });
  });
  
  // Close menu when clicking outside (but not on the hamburger)
  document.addEventListener("click", function(e) {
    const isClickInsideNav = nav.contains(e.target);
    const isClickOnHamburger = hamburger.contains(e.target);
    
    if (!isClickInsideNav && !isClickOnHamburger && nav.classList.contains("active")) {
      hamburger.classList.remove("active");
      nav.classList.remove("active");
    }
  });
  
  // Prevent nav clicks from closing menu immediately
  nav.addEventListener("click", function(e) {
    e.stopPropagation();
  });

// Initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHamburgerMenu);
} else {
  initHamburgerMenu();
}
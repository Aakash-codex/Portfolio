// Typing effect for hero section
class TypeWriter {
  constructor(txtElement, speed = 100) {
    this.txtElement = txtElement;
    this.speed = parseInt(speed, 10);
    this.currentIndex = 0;
    this.type();
  }

  type() {
    const texts = [
      "Hi,",
      "I am ",
      '<span class="aakashlight"><a href="index.html">Aakash</a></span>',
      "Frontend Developer"
    ];

    if (this.currentIndex < texts.length) {
      const currentText = texts[this.currentIndex];
      
      // Add line breaks between sections
      if (this.currentIndex > 0) {
        this.txtElement.innerHTML += "<br />";
      }
      
      // If it's HTML content (like the Aakash link), add it instantly
      if (currentText.includes('<span')) {
        this.txtElement.innerHTML += currentText;
        this.currentIndex++;
        setTimeout(() => this.type(), this.speed);
      } else {
        // Type out regular text character by character
        this.typeText(currentText);
      }
    }
  }

  typeText(text) {
    let charIndex = 0;
    
    const typeChar = () => {
      if (charIndex < text.length) {
        const currentContent = this.txtElement.innerHTML;
        this.txtElement.innerHTML = currentContent + text.charAt(charIndex);
        charIndex++;
        setTimeout(typeChar, this.speed);
      } else {
        this.currentIndex++;
        setTimeout(() => this.type(), this.speed);
      }
    };

    typeChar();
  }
}

// Init On DOM Load
document.addEventListener('DOMContentLoaded', () => {
  const txtElement = document.querySelector('.typed-text');
  new TypeWriter(txtElement, 100);  // 100ms typing speed

  // Get all sections
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('nav a');

  // Add click event listeners to navigation links
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      if (link.getAttribute('href').startsWith('#')) {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        const targetSection = document.querySelector(targetId);
        if (targetSection) {
          targetSection.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });

  // Update active state on scroll
  function updateActiveLink() {
    const scrollPosition = window.scrollY + 100; // Offset for header

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionId = section.getAttribute('id');
      
      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${sectionId}`) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  // Initial check and scroll event listener
  updateActiveLink();
  window.addEventListener('scroll', updateActiveLink);

  // Go to top button functionality
  function handleGoToTop() {
    const goTopBtn = document.querySelector('.gotop');
    
    // Show/hide button based on scroll position
    function toggleGoTopButton() {
      if (window.scrollY > 300) {
        goTopBtn.classList.add('show');
      } else {
        goTopBtn.classList.remove('show');
      }
    }

    // Smooth scroll to top
    goTopBtn.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Initial check and scroll event listener
    toggleGoTopButton();
    window.addEventListener('scroll', toggleGoTopButton);
  }

  // Initialize go to top functionality
  handleGoToTop();

  // Contact Form floating labels
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    const formFields = document.querySelectorAll('.form-group input, .form-group textarea');
    formFields.forEach(field => {
      field.addEventListener('focus', () => {
        const label = field.nextElementSibling;
        if (label) {
          label.style.top = '-20px';
          label.style.fontSize = '0.9rem';
          label.style.color = '#02b7f3';
        }
      });

      field.addEventListener('blur', () => {
        const label = field.nextElementSibling;
        if (label && !field.value) {
          label.style.top = '10px';
          label.style.fontSize = '1rem';
          label.style.color = '#666';
        }
      });
    });
  }
});
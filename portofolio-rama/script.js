// Modern Portfolio JavaScript

// Smooth scroll for navigation with offset for fixed header
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    const headerHeight = document.querySelector('header').offsetHeight;
    const targetPosition = target.offsetTop - headerHeight;
    
    window.scrollTo({
      top: targetPosition,
      behavior: 'smooth'
    });
  });
});

// Enhanced filter functionality
const filterBtns = document.querySelectorAll('.filter-btn');
const projectCards = document.querySelectorAll('.project-card');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    // Remove active class from all buttons
    filterBtns.forEach(b => b.classList.remove('active'));
    // Add active class to clicked button
    btn.classList.add('active');
    
    // Get filter value
    const filterValue = btn.textContent.toLowerCase();
    
    // Filter projects with animation
    projectCards.forEach((card, index) => {
      setTimeout(() => {
        if (filterValue === 'semua' || card.dataset.category === filterValue) {
          card.style.display = 'flex';
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          
          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 50);
        } else {
          card.style.opacity = '0';
          card.style.transform = 'translateY(-20px)';
          setTimeout(() => {
            card.style.display = 'none';
          }, 300);
        }
      }, index * 100);
    });
  });
});

// Intersection Observer for animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

// Skill bars animation
const skillBars = document.querySelectorAll('.skill-fill');
const skillObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const skillFill = entry.target;
      const width = skillFill.getAttribute('data-width');
      
      setTimeout(() => {
        skillFill.style.width = width;
      }, 200);
      
      skillObserver.unobserve(skillFill);
    }
  });
}, observerOptions);

skillBars.forEach(bar => {
  skillObserver.observe(bar);
});

// Fade in animation for elements
const fadeElements = document.querySelectorAll('.service-card, .project-card, .stat');
const fadeObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, observerOptions);

fadeElements.forEach(element => {
  element.style.opacity = '0';
  element.style.transform = 'translateY(30px)';
  element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  fadeObserver.observe(element);
});

// Header scroll effect
let lastScrollY = window.scrollY;
const header = document.querySelector('header');

window.addEventListener('scroll', () => {
  const currentScrollY = window.scrollY;
  
  if (currentScrollY > 100) {
    header.style.background = 'rgba(255, 255, 255, 0.95)';
    header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
  } else {
    header.style.background = 'rgba(255, 255, 255, 0.95)';
    header.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
  }
  
  // Hide/show header based on scroll direction
  if (currentScrollY > lastScrollY && currentScrollY > 200) {
    header.style.transform = 'translateY(-100%)';
  } else {
    header.style.transform = 'translateY(0)';
  }
  
  lastScrollY = currentScrollY;
});

// Enhanced form submission with validation (only for contact form)
const contactForm = document.querySelector('.contact form');

if (contactForm) {
  const formInputs = contactForm.querySelectorAll('.form-control');

  // Add floating label effect
  formInputs.forEach(input => {
    input.addEventListener('focus', () => {
      input.style.borderColor = '#4A90E2';
      input.style.boxShadow = '0 0 0 3px rgba(74, 144, 226, 0.1)';
    });
    
    input.addEventListener('blur', () => {
      input.style.borderColor = '#e2e8f0';
      input.style.boxShadow = 'none';
    });
  });

  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    // Simple validation
    let isValid = true;
    formInputs.forEach(input => {
      if (!input.value.trim()) {
        input.style.borderColor = '#e53e3e';
        isValid = false;
      } else {
        input.style.borderColor = '#38a169';
      }
    });
    
    if (isValid) {
      // Show success message
      const submitBtn = contactForm.querySelector('.submit-btn');
      const originalText = submitBtn.textContent;
      
      submitBtn.textContent = 'Mengirim...';
      submitBtn.disabled = true;
      
      setTimeout(() => {
        submitBtn.textContent = 'Pesan Terkirim! ';
        submitBtn.style.background = 'linear-gradient(135deg, #38a169, #48bb78)';
        
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
          submitBtn.style.background = 'linear-gradient(135deg, #4A90E2, #667eea)';
          contactForm.reset();
          
          // Reset input borders
          formInputs.forEach(input => {
            input.style.borderColor = '#e2e8f0';
          });
        }, 2000);
      }, 1000);
    }
  });
}

// Parallax effect for hero section
window.addEventListener('scroll', () => {
  const scrolled = window.pageYOffset;
  const heroImage = document.querySelector('.hero-image-container');
  const floatingElements = document.querySelectorAll('.floating-element');
  
  if (heroImage) {
    heroImage.style.transform = `perspective(1000px) rotateY(-5deg) translateY(${scrolled * 0.1}px)`;
  }
  
  floatingElements.forEach((element, index) => {
    const speed = 0.05 + (index * 0.02);
    element.style.transform = `translateY(${scrolled * speed}px)`;
  });
});

// Add loading animation
window.addEventListener('load', () => {
  const heroContent = document.querySelector('.hero-content');
  const heroImage = document.querySelector('.hero-image');
  
  setTimeout(() => {
    heroContent.style.opacity = '1';
    heroContent.style.transform = 'translateY(0)';
  }, 200);
  
  setTimeout(() => {
    heroImage.style.opacity = '1';
    heroImage.style.transform = 'translateY(0)';
  }, 400);
});

// Initialize loading state
document.addEventListener('DOMContentLoaded', () => {
  const heroContent = document.querySelector('.hero-content');
  const heroImage = document.querySelector('.hero-image');
  
  heroContent.style.opacity = '0';
  heroContent.style.transform = 'translateY(30px)';
  heroContent.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
  
  heroImage.style.opacity = '0';
  heroImage.style.transform = 'translateY(30px)';
  heroImage.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
});

// Add project categories (for filter functionality)
document.addEventListener('DOMContentLoaded', () => {
  const projects = document.querySelectorAll('.project-card');
  projects[0].dataset.category = 'mobile app';
  projects[1].dataset.category = 'web design';
  projects[2].dataset.category = 'ui design'; // Tambahkan kategori untuk proyek ketiga
});

// Typing effect for hero title (optional enhancement)
function typeWriter(element, text, speed = 100) {
  let i = 0;
  element.innerHTML = '';
  
  function type() {
    if (i < text.length) {
      element.innerHTML += text.charAt(i);
      i++;
      setTimeout(type, speed);
    }
  }
  
  type();
}

// Login Modal functionality
const loginModal = document.getElementById('loginModal');
const loginBtn = document.getElementById('loginBtn');
const registerModal = document.getElementById('registerModal');
const postModal = document.getElementById('postModal');
const closeButtons = document.querySelectorAll('.close');

// Function to open modal
function openModal(modal) {
  modal.style.display = 'flex';
}

// Function to close modal
function closeModal(modal) {
  modal.style.display = 'none';
}

// Login button click
if (loginBtn) {
  loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openModal(loginModal);
  });
}

// Close buttons
closeButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    closeModal(loginModal);
    closeModal(registerModal);
    closeModal(postModal);
  });
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === loginModal) closeModal(loginModal);
  if (e.target === registerModal) closeModal(registerModal);
  if (e.target === postModal) closeModal(postModal);
});

// Toggle between login and register modals
const goToRegister = document.getElementById('goToRegister');
const goToLogin = document.getElementById('goToLogin');

if (goToRegister) {
  goToRegister.addEventListener('click', (e) => {
    e.preventDefault();
    closeModal(loginModal);
    openModal(registerModal);
  });
}

if (goToLogin) {
  goToLogin.addEventListener('click', (e) => {
    e.preventDefault();
    closeModal(registerModal);
    openModal(loginModal);
  });
}

// Blog post functionality
const addPostBtn = document.getElementById('addPostBtn');
const editButtons = document.querySelectorAll('.edit-post');
const deleteButtons = document.querySelectorAll('.delete-post');
const postForm = document.getElementById('postForm');

// Add new post
if (addPostBtn) {
  addPostBtn.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Artikel Baru';
    postForm.reset();
    document.getElementById('postId').value = '';
    openModal(postModal);
  });
}

// Edit post
if (editButtons) {
  editButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const postId = this.getAttribute('data-id');
      const postElement = this.closest('.blog-post');
      const title = postElement.querySelector('.blog-title').textContent;
      const excerpt = postElement.querySelector('.blog-excerpt').textContent;
      
      document.getElementById('modalTitle').textContent = 'Edit Artikel';
      document.getElementById('postId').value = postId;
      document.getElementById('postTitle').value = title;
      document.getElementById('postExcerpt').value = excerpt;
      
      openModal(postModal);
    });
  });
}

// Delete post
if (deleteButtons) {
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const postId = this.getAttribute('data-id');
      
      if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        // Send delete request
        const formData = new FormData();
        formData.append('id', postId);
        
        fetch('delete_post.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Remove post from DOM
            document.querySelector(`.blog-post[data-id="${postId}"]`).remove();
            alert('Artikel berhasil dihapus');
          } else {
            alert('Gagal menghapus artikel: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menghapus artikel');
        });
      }
    });
  });
}

// Uncomment to enable typing effect
// window.addEventListener('load', () => {
//   const heroTitle = document.querySelector('.hero-highlight');
//   if (heroTitle) {
//     const originalText = heroTitle.textContent;
//     setTimeout(() => {
//       typeWriter(heroTitle, originalText, 80);
//     }, 1000);
//   }
// });
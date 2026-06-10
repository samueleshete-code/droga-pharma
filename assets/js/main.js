/* ============================================
   DROGA PHARMA PLC - Main JavaScript
   Version: 2.0 | Modern Healthcare
   ============================================ */

'use strict';

// ─── Navbar ───────────────────────────────────────────────────────────────────
const navbar = document.getElementById('mainNavbar');
const navToggler = document.querySelector('.nav-toggler');
const navMenu = document.querySelector('.nav-menu');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar?.classList.add('scrolled');
  } else {
    navbar?.classList.remove('scrolled');
  }
});

navToggler?.addEventListener('click', () => {
  navMenu?.classList.toggle('open');
  const icon = navToggler.querySelector('i');
  if (icon) {
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
  }
});

// Close menu on link click
document.querySelectorAll('.nav-link').forEach(link => {
  link.addEventListener('click', () => {
    navMenu?.classList.remove('open');
    const icon = navToggler?.querySelector('i');
    if (icon) { icon.classList.add('fa-bars'); icon.classList.remove('fa-times'); }
  });
});

// Active nav link on scroll
const sections = document.querySelectorAll('section[id]');
window.addEventListener('scroll', () => {
  const scrollY = window.scrollY + 100;
  sections.forEach(section => {
    const top = section.offsetTop;
    const height = section.offsetHeight;
    const id = section.getAttribute('id');
    const link = document.querySelector(`.nav-link[href="#${id}"]`);
    if (scrollY >= top && scrollY < top + height) {
      document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
      link?.classList.add('active');
    }
  });
});

// ─── Animated Counters ────────────────────────────────────────────────────────
function animateCounter(el) {
  const target = parseInt(el.getAttribute('data-target'), 10);
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    el.textContent = Math.floor(current).toLocaleString();
  }, 16);
}

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
      entry.target.classList.add('counted');
      animateCounter(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('[data-target]').forEach(el => counterObserver.observe(el));

// ─── AOS Init ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60
    });
  }

  // ─── Hero Swiper ──────────────────────────────────────────────────────────
  if (typeof Swiper !== 'undefined' && document.querySelector('.hero-swiper')) {
    new Swiper('.hero-swiper', {
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      effect: 'fade',
      fadeEffect: { crossFade: true },
      pagination: { el: '.swiper-pagination', clickable: true },
      speed: 1000
    });
  }

  // ─── Partners Swiper ──────────────────────────────────────────────────────
  if (typeof Swiper !== 'undefined' && document.querySelector('.partners-swiper')) {
    new Swiper('.partners-swiper', {
      loop: true,
      autoplay: { delay: 2500, disableOnInteraction: false },
      slidesPerView: 2,
      spaceBetween: 30,
      breakpoints: {
        576: { slidesPerView: 3 },
        768: { slidesPerView: 4 },
        992: { slidesPerView: 5 },
        1200: { slidesPerView: 6 }
      }
    });
  }

  // ─── Testimonials Swiper ──────────────────────────────────────────────────
  if (typeof Swiper !== 'undefined' && document.querySelector('.testimonials-swiper')) {
    new Swiper('.testimonials-swiper', {
      loop: true,
      autoplay: { delay: 4000, disableOnInteraction: false },
      slidesPerView: 1,
      spaceBetween: 30,
      pagination: { el: '.testimonials-pagination', clickable: true },
      breakpoints: {
        768: { slidesPerView: 2 },
        1200: { slidesPerView: 3 }
      }
    });
  }

  // ─── Smooth Scroll ────────────────────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ─── Back to Top ──────────────────────────────────────────────────────────
  const backTop = document.getElementById('backToTop');
  window.addEventListener('scroll', () => {
    backTop?.classList.toggle('visible', window.scrollY > 400);
  });
  backTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  // ─── Newsletter Form ──────────────────────────────────────────────────────
  const newsletterForm = document.getElementById('newsletterForm');
  newsletterForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = newsletterForm.querySelector('input[type="email"]').value;
    const btn = newsletterForm.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
      const apiPath = window.location.pathname.includes('/pages/') ? '../api/newsletter.php' : 'api/newsletter.php';
      const res = await fetch(apiPath, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, csrf: document.querySelector('meta[name="csrf"]')?.content })
      });
      const data = await res.json();
      showToast(data.success ? 'success' : 'error', data.message);
      if (data.success) newsletterForm.reset();
    } catch {
      showToast('error', 'Something went wrong. Please try again.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
  });

  // ─── Contact Form ─────────────────────────────────────────────────────────
  const contactForm = document.getElementById('contactForm');
  contactForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = contactForm.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';

    const formData = new FormData(contactForm);
    // Determine correct API path based on current page depth
    const isInPages = window.location.pathname.includes('/pages/');
    const apiPath = isInPages ? '../api/contact.php' : 'api/contact.php';

    try {
      const res = await fetch(apiPath, { method: 'POST', body: formData });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();
      showToast(data.success ? 'success' : 'error', data.message);
      if (data.success) contactForm.reset();
    } catch (err) {
      showToast('error', 'Failed to send message. Please try again. (' + err.message + ')');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  });

  // ─── Product Search / Filter ──────────────────────────────────────────────
  const productSearch = document.getElementById('productSearch');
  const categoryFilters = document.querySelectorAll('[data-filter]');
  const productCards = document.querySelectorAll('.product-card');

  function filterProducts() {
    const query = productSearch?.value.toLowerCase() || '';
    const activeFilter = document.querySelector('[data-filter].active')?.getAttribute('data-filter') || 'all';

    productCards.forEach(card => {
      const name = card.querySelector('.product-name')?.textContent.toLowerCase() || '';
      const cat = card.getAttribute('data-category') || '';
      const matchSearch = name.includes(query);
      const matchFilter = activeFilter === 'all' || cat === activeFilter;
      card.style.display = matchSearch && matchFilter ? '' : 'none';
    });
  }

  productSearch?.addEventListener('input', filterProducts);
  categoryFilters.forEach(btn => {
    btn.addEventListener('click', () => {
      categoryFilters.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      filterProducts();
    });
  });

  // ─── Job Search ───────────────────────────────────────────────────────────
  const jobSearch = document.getElementById('jobSearch');
  const jobCards = document.querySelectorAll('.job-card');
  jobSearch?.addEventListener('input', () => {
    const q = jobSearch.value.toLowerCase();
    jobCards.forEach(card => {
      const title = card.querySelector('.job-title')?.textContent.toLowerCase() || '';
      card.style.display = title.includes(q) ? '' : 'none';
    });
  });

  // ─── Lazy Load Images ─────────────────────────────────────────────────────
  const lazyImages = document.querySelectorAll('img[data-src]');
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.getAttribute('data-src');
        img.removeAttribute('data-src');
        imageObserver.unobserve(img);
      }
    });
  });
  lazyImages.forEach(img => imageObserver.observe(img));

  // ─── Preloader ────────────────────────────────────────────────────────────
  const preloader = document.getElementById('preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.classList.add('fade-out');
      setTimeout(() => preloader.remove(), 600);
    });
  }
});

// ─── Toast Notification ───────────────────────────────────────────────────────
function showToast(type, message) {
  const existing = document.querySelector('.toast-notification');
  existing?.remove();

  const toast = document.createElement('div');
  toast.className = `toast-notification toast-${type}`;
  toast.innerHTML = `
    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
    <span>${message}</span>
    <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
  `;
  document.body.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 4000);
}

// ─── Typed Text Effect ────────────────────────────────────────────────────────
function typeWriter(el, texts, speed = 80, pause = 2000) {
  let textIndex = 0, charIndex = 0, isDeleting = false;

  function type() {
    const current = texts[textIndex];
    el.textContent = isDeleting ? current.substring(0, charIndex--) : current.substring(0, charIndex++);

    if (!isDeleting && charIndex === current.length + 1) {
      isDeleting = true;
      setTimeout(type, pause);
      return;
    }
    if (isDeleting && charIndex === 0) {
      isDeleting = false;
      textIndex = (textIndex + 1) % texts.length;
    }
    setTimeout(type, isDeleting ? speed / 2 : speed);
  }
  type();
}

const typedEl = document.getElementById('typedText');
if (typedEl) {
  typeWriter(typedEl, [
    'Pharmaceutical Distribution',
    'Medical Equipment Supply',
    'Healthcare Innovation',
    'Laboratory Solutions'
  ]);
}

// ─── Glassmorphism card tilt ──────────────────────────────────────────────────
document.querySelectorAll('.tilt-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width - 0.5) * 12;
    const y = ((e.clientY - rect.top) / rect.height - 0.5) * -12;
    card.style.transform = `perspective(800px) rotateX(${y}deg) rotateY(${x}deg) translateY(-4px)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
  });
});

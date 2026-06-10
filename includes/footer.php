<!-- ═══ FOOTER ════════════════════════════════════════════════════════════════ -->
<?php if (!isset($root)) $root = '../'; // fallback if footer included without header ?>
<footer class="site-footer" role="contentinfo">

  <!-- Footer Top -->
  <div class="footer-top">
    <div class="container">
      <div class="row g-5">

        <!-- Brand Column -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand">
            <img src="<?= $root ?>assets/images/logo-white.png" alt="Droga Pharma PLC" width="150" class="footer-logo"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span style="display:none; color:#fff; font-size:1.3rem; font-weight:700; font-family:'Poppins',sans-serif;">Droga Pharma PLC</span>
            <p class="footer-tagline">Transforming Healthcare Across Africa through innovation, quality, and trusted partnerships.</p>
            <div class="footer-social">
              <a href="#" aria-label="LinkedIn" class="social-link"><i class="fab fa-linkedin-in"></i></a>
              <a href="#" aria-label="Twitter" class="social-link"><i class="fab fa-twitter"></i></a>
              <a href="#" aria-label="Facebook" class="social-link"><i class="fab fa-facebook-f"></i></a>
              <a href="#" aria-label="YouTube" class="social-link"><i class="fab fa-youtube"></i></a>
              <a href="https://wa.me/251911234567" aria-label="WhatsApp" class="social-link"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 col-6">
          <div class="footer-widget">
            <h5 class="footer-widget-title">Company</h5>
            <ul class="footer-links">
              <li><a href="<?= SITE_URL ?>/pages/about.php">About Us</a></li>
              <li><a href="<?= SITE_URL ?>/pages/about.php#leadership">Leadership</a></li>
              <li><a href="<?= SITE_URL ?>/pages/about.php#csr">CSR</a></li>
              <li><a href="<?= SITE_URL ?>/pages/research.php">Research</a></li>
              <li><a href="<?= SITE_URL ?>/pages/careers.php">Careers</a></li>
              <li><a href="<?= SITE_URL ?>/pages/news.php">News</a></li>
            </ul>
          </div>
        </div>

        <!-- Products -->
        <div class="col-lg-2 col-md-6 col-6">
          <div class="footer-widget">
            <h5 class="footer-widget-title">Products</h5>
            <ul class="footer-links">
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=pharmaceuticals">Pharmaceuticals</a></li>
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=medical-devices">Medical Devices</a></li>
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=diagnostics">Diagnostics</a></li>
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=laboratory">Laboratory</a></li>
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=surgical">Surgical</a></li>
              <li><a href="<?= SITE_URL ?>/pages/products.php?cat=orthopedic">Orthopedic</a></li>
            </ul>
          </div>
        </div>

        <!-- Contact + Newsletter -->
        <div class="col-lg-4 col-md-6">
          <div class="footer-widget">
            <h5 class="footer-widget-title">Get In Touch</h5>
            <ul class="footer-contact-list">
              <li><i class="fas fa-map-marker-alt"></i><span>Bole Road, Addis Ababa, Ethiopia</span></li>
              <li><i class="fas fa-phone-alt"></i><a href="tel:+251111234567">+251 11 123 4567</a></li>
              <li><i class="fas fa-envelope"></i><a href="mailto:info@drogapharma.com">info@drogapharma.com</a></li>
              <li><i class="fas fa-clock"></i><span>Mon – Fri: 8:00 AM – 6:00 PM</span></li>
            </ul>
            <div class="footer-newsletter">
              <p class="newsletter-label">Subscribe to our newsletter</p>
              <form id="newsletterForm" class="newsletter-form" novalidate>
                <input type="email" placeholder="Your email address" required aria-label="Email for newsletter">
                <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container">
      <div class="footer-bottom-inner">
        <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="<?= SITE_URL ?>/pages/contact.php">Contact</a>
          <a href="<?= SITE_URL ?>/sitemap.xml">Sitemap</a>
        </div>
      </div>
    </div>
  </div>

</footer>
<!-- ═══ END FOOTER ════════════════════════════════════════════════════════════ -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<!-- ✅ JS loaded via relative path -->
<script src="<?= $root ?>assets/js/main.js"></script>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>

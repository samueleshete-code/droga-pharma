<?php
require_once '../includes/config.php';
$pageTitle = 'Contact Us';
$pageDesc  = 'Get in touch with Droga Pharma PLC – contact our team for product inquiries, partnerships, and healthcare solutions.';
require_once '../includes/header.php';
$csrf = generateCSRF();
?>

<section class="page-hero" style="background-image: url('../assets/images/contact-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-envelope"></i> Contact</span>
      <h1 class="text-white mt-3">Get In Touch</h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Contact</li>
      </ol>
    </div>
  </div>
</section>

<section class="contact-section">
  <div class="container">
    <div class="row g-5">

      <!-- Contact Info -->
      <div class="col-lg-4" data-aos="fade-right">
        <div class="contact-info-panel">
          <h3>Contact Information</h3>
          <p class="text-muted mb-4">Reach out to us through any of the following channels. Our team is ready to assist you.</p>

          <div class="contact-info-items">
            <div class="contact-info-item">
              <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <strong>Head Office</strong>
                <p>Bole Road, Kirkos Sub-City<br>Addis Ababa, Ethiopia</p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
              <div>
                <strong>Phone</strong>
                <p><a href="tel:+251111234567">+251 11 123 4567</a><br>
                   <a href="tel:+251911234567">+251 91 123 4567</a></p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="ci-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <strong>Email</strong>
                <p><a href="mailto:info@drogapharma.com">info@drogapharma.com</a><br>
                   <a href="mailto:sales@drogapharma.com">sales@drogapharma.com</a></p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="ci-icon"><i class="fas fa-clock"></i></div>
              <div>
                <strong>Working Hours</strong>
                <p>Monday – Friday: 8:00 AM – 6:00 PM<br>Saturday: 9:00 AM – 1:00 PM</p>
              </div>
            </div>
          </div>

          <!-- Department Contacts -->
          <div class="dept-contacts mt-4">
            <h5>Department Contacts</h5>
            <div class="dept-item"><span>Sales & Products</span><a href="tel:+251111234568">+251 11 123 4568</a></div>
            <div class="dept-item"><span>Technical Support</span><a href="tel:+251111234569">+251 11 123 4569</a></div>
            <div class="dept-item"><span>Careers</span><a href="mailto:hr@drogapharma.com">hr@drogapharma.com</a></div>
            <div class="dept-item"><span>Media Inquiries</span><a href="mailto:media@drogapharma.com">media@drogapharma.com</a></div>
          </div>

          <!-- WhatsApp -->
          <a href="https://wa.me/251911234567" class="btn btn-accent w-100 mt-4" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
          </a>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8" data-aos="fade-left">
        <div class="contact-form-panel">
          <h3>Send Us a Message</h3>
          <p class="text-muted mb-4">Fill out the form below and our team will get back to you within 24 hours.</p>

          <form id="contactForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="firstName">First Name *</label>
                  <input type="text" id="firstName" name="first_name" class="form-control-custom" placeholder="John" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lastName">Last Name *</label>
                  <input type="text" id="lastName" name="last_name" class="form-control-custom" placeholder="Doe" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email">Email Address *</label>
                  <input type="email" id="email" name="email" class="form-control-custom" placeholder="john@example.com" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phone">Phone Number</label>
                  <input type="tel" id="phone" name="phone" class="form-control-custom" placeholder="+251 91 123 4567">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="organization">Organization</label>
                  <input type="text" id="organization" name="organization" class="form-control-custom" placeholder="Hospital / Company name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="subject">Subject *</label>
                  <select id="subject" name="subject" class="form-control-custom" required>
                    <option value="">Select a subject</option>
                    <option value="Product Inquiry">Product Inquiry</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Careers">Careers</option>
                    <option value="General Inquiry">General Inquiry</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="message">Message *</label>
                  <textarea id="message" name="message" class="form-control-custom" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                  <i class="fas fa-paper-plane"></i> Send Message
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>

    <!-- Google Map -->
    <div class="map-section mt-5" data-aos="fade-up">
      <div class="map-wrapper">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3940.5!2d38.7578!3d9.0054!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOcKwMDAnMTkuNCJOIDM4wrA0NSczNi4xIkU!5e0!3m2!1sen!2set!4v1234567890"
          width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade" title="Droga Pharma Location">
        </iframe>
      </div>
    </div>

  </div>
</section>

<?php require_once '../includes/footer.php'; ?>

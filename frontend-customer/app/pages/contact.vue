<template>
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>Contact Us</h1>
      <p>We're here to help. Get in touch with our support team.</p>
    </div>
    
    <div class="static-content glass-panel">
      <div class="contact-grid">
        <div class="contact-info">
          <h2>Get in Touch</h2>
          <p>Have questions about your order, our products, or anything else? We'd love to hear from you.</p>
          
          <div class="info-item">
            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
            <div>
              <h3>Phone</h3>
              <p>076 153 7772</p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <h3>Email</h3>
              <p>info@maneeshafashion.lk</p>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <h3>Headquarters</h3>
              <p>No 78/1 Senanayake Rd,<br>North Seeduwa, Seeduwa</p>
            </div>
          </div>
          
          <div class="social-links mt-6">
            <h3>Follow Us</h3>
            <div class="flex gap-4 mt-2">
              <a href="https://www.facebook.com/manishafashion/" target="_blank" class="social-icon"><i class="fa-brands fa-facebook"></i></a>
              <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
              <a href="#" class="social-icon"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
          </div>
        </div>
        
        <div class="contact-form">
          <h2>Send us a Message</h2>
          <form @submit.prevent="submitForm">
            <div class="form-group">
              <label>Your Name</label>
              <input type="text" class="form-input" placeholder="John Doe" required />
            </div>
            <div class="form-group">
              <label>Your Email</label>
              <input type="email" class="form-input" placeholder="john@example.com" required />
            </div>
            <div class="form-group">
              <label>Subject</label>
              <input type="text" class="form-input" placeholder="How can we help?" required />
            </div>
            <div class="form-group">
              <label>Message</label>
              <textarea class="form-input" rows="5" placeholder="Your message here..." required></textarea>
            </div>
            <button type="submit" class="submit-btn">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const submitForm = () => {
  alert('Thank you for your message! Our team will get back to you shortly.');
}
</script>

<style scoped>
.static-page-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
.static-header { padding: 40px; text-align: center; border-radius: var(--radius-lg); margin-bottom: 30px; }
.static-header h1 { font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 10px; }
.static-content { padding: 40px; border-radius: var(--radius-lg); }
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
@media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
.contact-info h2, .contact-form h2 { font-size: 1.6rem; margin-bottom: 20px; color: var(--text-dark-primary); }
body.dark-mode .contact-info h2, body.dark-mode .contact-form h2 { color: var(--text-light-primary); }
.contact-info > p { color: var(--text-dark-secondary); margin-bottom: 30px; }
body.dark-mode .contact-info > p { color: var(--text-light-secondary); }

.info-item { display: flex; gap: 15px; margin-bottom: 25px; }
.info-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(212, 175, 55, 0.1); color: var(--primary-gold); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.info-item h3 { font-size: 1rem; color: var(--text-dark-primary); margin-bottom: 5px; }
body.dark-mode .info-item h3 { color: var(--text-light-primary); }
.info-item p { color: var(--text-dark-secondary); font-size: 0.9rem; }
body.dark-mode .info-item p { color: var(--text-light-secondary); }

.social-links h3 { font-size: 1.1rem; margin-bottom: 10px; color: var(--text-dark-primary); }
body.dark-mode .social-links h3 { color: var(--text-light-primary); }
.flex { display: flex; }
.gap-4 { gap: 16px; }
.mt-2 { margin-top: 8px; }
.mt-6 { margin-top: 24px; }
.social-icon { width: 40px; height: 40px; border-radius: 50%; background: var(--bg-light-surface); border: 1px solid var(--bg-light-border); display: flex; align-items: center; justify-content: center; color: var(--text-dark-primary); font-size: 1.2rem; transition: all 0.3s; }
body.dark-mode .social-icon { background: var(--bg-dark-surface); border-color: var(--bg-dark-border); color: var(--text-light-primary); }
.social-icon:hover { background: var(--primary-gold); color: #fff; border-color: var(--primary-gold); transform: translateY(-3px); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; color: var(--text-dark-primary); }
body.dark-mode .form-group label { color: var(--text-light-primary); }
.form-input { width: 100%; padding: 12px 15px; border-radius: var(--radius-md); border: 1px solid var(--bg-light-border); background: var(--bg-light); color: var(--text-dark-primary); font-family: inherit; font-size: 0.95rem; transition: border-color 0.3s; outline: none; }
body.dark-mode .form-input { border-color: var(--bg-dark-border); background: #0f1013; color: var(--text-light-primary); }
.form-input:focus { border-color: var(--primary-gold); }

.submit-btn { width: 100%; padding: 14px; background: var(--primary-gold); color: #fff; border: none; border-radius: var(--radius-md); font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
.submit-btn:hover { background: #b8962e; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); }
</style>

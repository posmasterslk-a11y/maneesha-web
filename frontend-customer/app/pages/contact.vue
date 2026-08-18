<script setup>
useSeoMeta({
  title: 'Contact Us | Maneesha Fashion Sri Lanka',
  description: 'Get in touch with Maneesha Fashion for inquiries about our premium clothing, custom tailoring, and orders. We are located in Colombo, Sri Lanka.',
  keywords: 'contact Maneesha Fashion, Maneesha Fashion Colombo, Sri Lanka fashion store contact, custom tailoring inquiries',
  ogTitle: 'Contact Us | Maneesha Fashion Sri Lanka',
  ogDescription: 'Get in touch with Maneesha Fashion for inquiries about our premium clothing, custom tailoring, and orders.',
  ogUrl: 'https://maneesha.posmasters.lk/contact',
  ogType: 'website'
})

const submitForm = () => {
  alert('Thank you for your message! We aim to reply within 24 hours.');
}
</script>

<template>
  <div class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
      <svg class="floral-deco-left" viewBox="0 0 200 600" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10,200 C10,100 150,20 180,0 C160,60 100,160 20,200 Z" fill="url(#pinkGrad)" opacity="0.3"/>
        <path d="M30,340 C10,140 120,60 140,40 C120,100 80,280 30,340 Z" fill="url(#pinkGrad)" opacity="0.4"/>
        <defs>
          <linearGradient id="pinkGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f9a8d4" />
            <stop offset="100%" stop-color="#f472b6" />
          </linearGradient>
        </defs>
      </svg>

      <div class="container contact-hero-inner">
        <div class="hero-content">
          <div class="subtitle-wrap">
            <span class="pink-subtitle">GET IN TOUCH</span>
            <div class="divider-hearts left-align">
              <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
            </div>
          </div>
          
          <h1 class="main-title">
            <span class="dark-serif">We'd Love to</span><br>
            <span class="dark-serif">Hear</span> <span class="pink-serif">From You</span>
          </h1>
          
          <p class="hero-desc">
            Have a question, need styling advice, or want to place a custom order? We're here to help you every step of the way.
          </p>
        </div>
        
        <div class="hero-image-wrap">
          <svg class="floral-deco-right" viewBox="0 0 150 300" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path d="M140,150 C140,75 35,15 20,0 C35,45 80,120 140,150 Z" fill="url(#pinkGrad)" opacity="0.5"/>
          </svg>
          <div class="image-curve">
            <img src="/images/contact.jpg" alt="Maneesha Fashion Studio" class="hero-img" />
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Info & Form -->
    <section class="contact-details container">
      <div class="contact-grid">
        
        <!-- Info Card -->
        <div class="info-card glass-panel">
          <div class="card-header text-center">
            <h2 class="dark-serif text-2xl">Contact Information</h2>
            <div class="divider-hearts my-2">
              <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
            </div>
          </div>
          
          <div class="info-list">
            <div class="info-item">
              <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
              <div class="info-text">
                <h3>Visit Our Studio</h3>
                <p>No 78/1 Senanayake Rd,<br>North Seeduwa, Seeduwa.</p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
              <div class="info-text">
                <h3>Call Us</h3>
                <p>+94 76 153 7772</p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
              <div class="info-text">
                <h3>Email Us</h3>
                <p>info@maneeshafashion.lk</p>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class="fa-regular fa-clock"></i></div>
              <div class="info-text">
                <h3>Business Hours</h3>
                <p>Monday – Saturday<br>9:00 AM – 6:00 PM<br>Closed on Sundays</p>
              </div>
            </div>
            
            <div class="info-item mt-6">
              <div class="info-icon"><i class="fa-solid fa-share-nodes"></i></div>
              <div class="info-text">
                <h3>Follow Us</h3>
                <div class="social-circles">
                  <a href="https://www.facebook.com/manishafashion/" target="_blank" class="social-circle"><i class="fa-brands fa-facebook-f"></i></a>
                  <a href="#" class="social-circle"><i class="fa-brands fa-instagram"></i></a>
                  <a href="#" class="social-circle"><i class="fa-brands fa-tiktok"></i></a>
                  <a href="#" class="social-circle"><i class="fa-brands fa-pinterest-p"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Card -->
        <div class="form-card glass-panel">
          <svg class="floral-deco-form" viewBox="0 0 100 300" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path d="M80,150 C80,75 15,15 0,0 C15,45 40,120 80,150 Z" fill="url(#pinkGrad)" opacity="0.3"/>
             <path d="M100,250 C60,180 10,120 0,105 C20,150 50,220 100,250 Z" fill="url(#pinkGrad)" opacity="0.4"/>
          </svg>
          
          <div class="card-header text-center z-10 relative">
            <h2 class="dark-serif text-2xl">Send Us a Message</h2>
            <div class="divider-hearts my-2">
              <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
            </div>
          </div>
          
          <form @submit.prevent="submitForm" class="contact-form z-10 relative">
            <div class="form-row">
              <input type="text" class="custom-input" placeholder="Your Name*" required />
              <input type="email" class="custom-input" placeholder="Your Email*" required />
            </div>
            <input type="tel" class="custom-input" placeholder="Phone Number" />
            <input type="text" class="custom-input" placeholder="Subject" />
            <textarea class="custom-textarea" rows="5" placeholder="Your Message*" required></textarea>
            
            <div class="text-center mt-6">
              <button type="submit" class="btn-shop-pink">
                <i class="fa-regular fa-paper-plane mr-2"></i> SEND MESSAGE
              </button>
              <p class="reply-note mt-4">We aim to reply to all inquiries within 24 hours.</p>
            </div>
          </form>
        </div>
        
      </div>
    </section>

    <!-- Map Section -->
    <section class="map-section container mb-20">
      <div class="map-container glass-panel">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15837.766326683839!2d79.873264!3d7.132223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2f1b40280b15b%3A0x6e268925357cd355!2sSeeduwa!5e0!3m2!1sen!2slk!4v1690000000000!5m2!1sen!2slk" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        
        <div class="map-marker-card">
          <div class="marker-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div class="marker-info">
            <h4 class="dark-serif">Maneesha Fashion Studio</h4>
            <p>No 78/1 Senanayake Rd,<br>North Seeduwa, Seeduwa.</p>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Poppins:wght@300;400;500;600&display=swap');

.contact-page {
  font-family: 'Poppins', sans-serif;
  color: #334155;
  background-color: #fff;
  overflow: hidden;
}

body.dark-mode .contact-page {
  background-color: #0f1115;
  color: #cbd5e1;
}

/* Common Styles */
.dark-serif { color: #0f172a; font-weight: 600; font-family: 'Playfair Display', serif; }
body.dark-mode .dark-serif { color: #f8fafc; }

.pink-serif { color: #f472b6; font-weight: 600; font-family: 'Playfair Display', serif; }

.pink-subtitle {
  color: #f472b6; font-weight: 600; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;
}

.subtitle-wrap { display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 15px; }

.divider-hearts { display: flex; align-items: center; justify-content: center; width: 60px; margin-top: 5px; margin-left: auto; margin-right: auto;}
.divider-hearts.left-align { margin-left: 0; margin-right: 0; }
.divider-hearts .line { height: 1px; background-color: #fca5a5; flex: 1; }

/* Hero Section */
.contact-hero { position: relative; background: linear-gradient(135deg, #fffafa 0%, #fff0f5 100%); padding: 80px 0 100px; }
body.dark-mode .contact-hero { background: linear-gradient(135deg, #181518 0%, #1f1a1d 100%); }

.floral-deco-left { position: absolute; left: -20px; top: 30%; transform: translateY(-50%); width: 250px; z-index: 1; pointer-events: none; }
.floral-deco-right { position: absolute; right: -50px; top: 10%; width: 200px; z-index: 10; pointer-events: none;}

.contact-hero-inner { display: flex; align-items: center; gap: 50px; position: relative; z-index: 2; }
.hero-content { flex: 1; padding-left: 50px; }

.main-title { font-size: 4rem; line-height: 1.1; margin-bottom: 10px; }
.hero-desc { font-size: 1.05rem; line-height: 1.8; color: #475569; max-width: 500px; margin-top: 25px; }
body.dark-mode .hero-desc { color: #94a3b8; }

.hero-image-wrap { flex: 1.2; position: relative; display: flex; justify-content: flex-end; }
.image-curve { width: 100%; max-width: 650px; height: 600px; border-radius: 350px 30px 30px 350px; overflow: hidden; box-shadow: -20px 20px 60px rgba(0,0,0,0.1); position: relative; z-index: 2; }
.hero-img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }

/* Contact Details */
.contact-details { padding: 50px 20px; position: relative; z-index: 3; margin-top: -60px; }
.contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }

.info-card, .form-card { background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid #fdf2f8; position: relative; overflow: hidden;}
body.dark-mode .info-card, body.dark-mode .form-card { background: #1e293b; border-color: #334155; }

.card-header { margin-bottom: 30px; }

.info-list { display: flex; flex-direction: column; gap: 25px; }
.info-item { display: flex; gap: 20px; align-items: flex-start; }
.info-icon { width: 45px; height: 45px; border-radius: 50%; background: #fdf2f8; color: #f472b6; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; border: 1px solid #fbcfe8; flex-shrink: 0; }
body.dark-mode .info-icon { background: #2d1b24; border-color: #83526c; }

.info-text h3 { font-family: 'Playfair Display', serif; font-weight: 600; color: #0f172a; margin-bottom: 5px; font-size: 1.1rem; }
body.dark-mode .info-text h3 { color: #f1f5f9; }
.info-text p { color: #64748b; font-size: 0.95rem; line-height: 1.6; }
body.dark-mode .info-text p { color: #94a3b8; }

.social-circles { display: flex; gap: 12px; margin-top: 5px; }
.social-circle { width: 35px; height: 35px; border-radius: 50%; border: 1px solid #fbcfe8; display: flex; justify-content: center; align-items: center; color: #f472b6; transition: all 0.3s ease; }
body.dark-mode .social-circle { border-color: #83526c; }
.social-circle:hover { background: #f472b6; color: #fff; transform: translateY(-2px); }

/* Form Styles */
.floral-deco-form { position: absolute; right: -10px; bottom: 20px; width: 120px; z-index: 1; pointer-events: none; }
.contact-form { display: flex; flex-direction: column; gap: 20px; }
.form-row { display: flex; gap: 20px; }
.custom-input, .custom-textarea { width: 100%; padding: 15px 20px; border-radius: 12px; border: 1px solid #f1f5f9; background: #fafafa; font-family: 'Poppins', sans-serif; font-size: 0.95rem; color: #334155; transition: border-color 0.3s; outline: none; }
body.dark-mode .custom-input, body.dark-mode .custom-textarea { background: #0f1115; border-color: #334155; color: #cbd5e1; }
.custom-input:focus, .custom-textarea:focus { border-color: #fbcfe8; }
body.dark-mode .custom-input:focus, body.dark-mode .custom-textarea:focus { border-color: #83526c; }

.btn-shop-pink { background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%); color: white; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.95rem; padding: 15px 40px; border-radius: 30px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(244, 114, 182, 0.4); transition: all 0.3s ease; }
.btn-shop-pink:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(244, 114, 182, 0.5); }
.reply-note { font-size: 0.85rem; color: #94a3b8; }

/* Map Section */
.map-section { margin-top: 30px; }
.map-container { height: 400px; border-radius: 20px; overflow: hidden; position: relative; border: 1px solid #fdf2f8; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
body.dark-mode .map-container { border-color: #334155; }

.map-marker-card { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 15px 25px; border-radius: 50px; display: flex; align-items: center; gap: 15px; box-shadow: 0 15px 30px rgba(244, 114, 182, 0.2); z-index: 10; min-width: 300px; }
body.dark-mode .map-marker-card { background: #1e293b; box-shadow: 0 15px 30px rgba(0,0,0,0.4); }

.marker-icon { background: #f472b6; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; }
.marker-info h4 { font-size: 1rem; margin-bottom: 2px; }
.marker-info p { font-size: 0.8rem; color: #64748b; line-height: 1.4; margin: 0;}
body.dark-mode .marker-info p { color: #94a3b8; }

/* Responsive */
@media (max-width: 1024px) {
  .contact-grid { grid-template-columns: 1fr; }
  .contact-hero-inner { flex-direction: column; text-align: center; }
  .hero-content { padding-left: 0; display: flex; flex-direction: column; align-items: center; }
  .divider-hearts.left-align { margin-left: auto; margin-right: auto; }
  .image-curve { height: 450px; border-radius: 100px 30px 100px 30px; }
}

@media (max-width: 640px) {
  .main-title { font-size: 3rem; }
  .form-row { flex-direction: column; }
  .contact-details { margin-top: -30px; }
  .info-card, .form-card { padding: 25px 20px; }
  .map-container { height: 300px; }
  .map-marker-card { min-width: 260px; padding: 10px 15px; flex-direction: column; text-align: center; border-radius: 20px;}
}
</style>

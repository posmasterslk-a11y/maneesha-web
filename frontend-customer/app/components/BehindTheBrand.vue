<template>
  <section class="behind-brand-section">
    <!-- Left Decorative Floral -->
    <svg class="floral-deco-left" viewBox="0 0 200 400" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M10,200 C10,100 150,20 180,0 C160,60 100,160 20,200 Z" fill="url(#brandPinkGrad)" opacity="0.3"/>
      <path d="M30,240 C10,140 120,60 140,40 C120,100 80,180 30,240 Z" fill="url(#brandPinkGrad)" opacity="0.4"/>
      <path d="M60,300 C20,220 140,120 170,100 C140,160 100,240 60,300 Z" fill="url(#brandPinkGrad)" opacity="0.5"/>
      <defs>
        <linearGradient id="brandPinkGrad" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stop-color="#f9a8d4" />
          <stop offset="100%" stop-color="#f472b6" />
        </linearGradient>
      </defs>
    </svg>

    <div class="brand-container">
      
      <!-- Left Content -->
      <div class="brand-content">
        <div class="brand-header">
          <span class="badge-text">BEHIND THE BRAND</span>
          <div class="badge-line-center"></div>
        </div>
        
        <h2 class="main-title text-center">
          <span class="dark-serif">Crafted with</span> <span class="pink-serif">Passion</span>
        </h2>
        
        <p class="brand-desc text-center">
          Every stitch, every cut, and every detail is guided by a passion for perfection.<br>
          At Maneesha Fashion, we believe in bringing your dream outfits to life<br>
          with a personal touch.
        </p>
        
        <div class="features-grid">
          <!-- Feature 1 -->
          <div class="feature-col">
            <div class="icon-circle">
              <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <h4 class="feature-title">Passionate Craftsmanship</h4>
            <p class="feature-desc">Every piece is thoughtfully designed and handcrafted with love and precision.</p>
          </div>
          
          <div class="feature-divider-vert"></div>
          
          <!-- Feature 2 -->
          <div class="feature-col">
            <div class="icon-circle">
              <i class="fa-solid fa-layer-group"></i>
            </div>
            <h4 class="feature-title">Premium Fabrics</h4>
            <p class="feature-desc">We use carefully selected fabrics to ensure comfort, quality and elegance.</p>
          </div>
          
          <div class="feature-divider-vert"></div>
          
          <!-- Feature 3 -->
          <div class="feature-col">
            <div class="icon-circle">
              <i class="fa-solid fa-scissors"></i>
            </div>
            <h4 class="feature-title">Tailored to You</h4>
            <p class="feature-desc">Custom fits and personal styling to make you look and feel your best.</p>
          </div>
          
          <div class="feature-divider-vert"></div>
          
          <!-- Feature 4 -->
          <div class="feature-col">
            <div class="icon-circle">
              <i class="fa-regular fa-heart"></i>
            </div>
            <h4 class="feature-title">Made for You</h4>
            <p class="feature-desc">Outfits that reflect your unique style, made with care and attention.</p>
          </div>
        </div>

        <div class="promise-banner">
          <div class="promise-inner">
            <i class="fa-solid fa-shirt banner-icon"></i>
            <span class="promise-text">
              Every detail is a promise of quality, every outfit is a reflection of you. <i class="fa-solid fa-heart text-pink-400"></i>
            </span>
          </div>
        </div>
      </div>
      
      <!-- Right Image Container -->
      <div class="brand-image-wrapper">
        <svg class="floral-deco-right" viewBox="0 0 150 300" fill="none" xmlns="http://www.w3.org/2000/svg">
           <path d="M140,150 C140,75 35,15 20,0 C35,45 80,120 140,150 Z" fill="url(#brandPinkGrad)" opacity="0.4"/>
           <path d="M120,200 C80,120 10,60 0,45 C20,90 60,160 120,200 Z" fill="url(#brandPinkGrad)" opacity="0.5"/>
        </svg>

        <div class="image-frame">
          <div class="image-inner">
            <img src="/images/designer-1.jpeg" alt="Maneesha Fashion Quality" class="model-img" />
          </div>
        </div>
      </div>
      
    </div>
  </section>
</template>

<script setup>
// Component logic if needed
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Poppins:wght@300;400;500;600&display=swap');

.behind-brand-section {
  position: relative;
  background: linear-gradient(135deg, #fdfbfb 0%, #fff0f5 100%);
  padding: 60px 40px;
  border-radius: 30px;
  margin: 60px auto;
  max-width: 1300px;
  box-shadow: 0 15px 50px rgba(0,0,0,0.04);
  overflow: hidden;
  border: 1px solid rgba(244, 114, 182, 0.1);
}

body.dark-mode .behind-brand-section {
  background: linear-gradient(135deg, #1a1a1f 0%, #251e23 100%);
  border-color: rgba(244, 114, 182, 0.15);
}

.floral-deco-left {
  position: absolute;
  left: -40px;
  top: 50%;
  transform: translateY(-50%);
  width: 150px;
  height: auto;
  z-index: 1;
  pointer-events: none;
}

.floral-deco-right {
  position: absolute;
  right: -20px;
  top: -20px;
  width: 120px;
  height: auto;
  z-index: 10;
  pointer-events: none;
}

.brand-container {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 40px;
  z-index: 2;
}

.brand-content {
  flex: 1.6;
  min-width: 350px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.brand-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 15px;
}

.badge-text {
  color: #f472b6;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 2px;
}

.badge-line-center {
  height: 2px;
  width: 40px;
  background-color: #fca5a5; /* soft pink line */
  margin-top: 8px;
  position: relative;
}
.badge-line-center::before {
  content: '';
  position: absolute;
  top: -2px;
  left: 50%;
  transform: translateX(-50%);
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: #f472b6;
}

.main-title {
  font-family: 'Playfair Display', serif;
  font-size: 3.2rem;
  line-height: 1.2;
  margin-bottom: 20px;
}

.dark-serif {
  color: #1e293b;
  font-weight: 600;
}
body.dark-mode .dark-serif {
  color: #f1f5f9;
}

.pink-serif {
  color: #f472b6;
  font-weight: 600;
}

.brand-desc {
  font-family: 'Poppins', sans-serif;
  color: #475569;
  font-size: 1.05rem;
  line-height: 1.8;
  margin-bottom: 50px;
  max-width: 700px;
}
body.dark-mode .brand-desc {
  color: #cbd5e1;
}

/* Features Grid */
.features-grid {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 15px;
  width: 100%;
  margin-bottom: 40px;
}

.feature-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 0 10px;
}

.icon-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: 1.5px solid #fbcfe8;
  background-color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 15px;
  transition: all 0.3s ease;
}
body.dark-mode .icon-circle {
  background-color: #33272e;
  border-color: #83526c;
}

.icon-circle i {
  color: #f472b6;
  font-size: 24px;
}

.feature-col:hover .icon-circle {
  background-color: #fdf2f8;
  transform: translateY(-5px);
  box-shadow: 0 10px 15px rgba(244, 114, 182, 0.2);
}
body.dark-mode .feature-col:hover .icon-circle {
  background-color: #4a2f40;
}

.feature-title {
  font-family: 'Playfair Display', serif;
  color: #0f172a;
  font-weight: 600;
  font-size: 1.15rem;
  margin-bottom: 8px;
}
body.dark-mode .feature-title {
  color: #f8fafc;
}

.feature-desc {
  font-family: 'Poppins', sans-serif;
  color: #64748b;
  font-size: 0.85rem;
  line-height: 1.6;
}
body.dark-mode .feature-desc {
  color: #94a3b8;
}

.feature-divider-vert {
  width: 1px;
  height: 80px;
  background-color: #f1f5f9;
  margin-top: 10px;
}
body.dark-mode .feature-divider-vert {
  background-color: #334155;
}

/* Promise Banner */
.promise-banner {
  width: 100%;
  padding: 2px;
  background: linear-gradient(90deg, transparent 0%, #fbcfe8 50%, transparent 100%);
  border-radius: 12px;
}
body.dark-mode .promise-banner {
  background: linear-gradient(90deg, transparent 0%, #83526c 50%, transparent 100%);
}

.promise-inner {
  background: #fdf2f8;
  padding: 15px 30px;
  border-radius: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
}
body.dark-mode .promise-inner {
  background: #2d1b24;
}

.banner-icon {
  color: #f472b6;
  font-size: 1.5rem;
  opacity: 0.8;
}

.promise-text {
  font-family: 'Playfair Display', serif;
  font-style: italic;
  color: #334155;
  font-size: 1.15rem;
  font-weight: 500;
}
body.dark-mode .promise-text {
  color: #cbd5e1;
}

/* Right Image */
.brand-image-wrapper {
  flex: 1;
  min-width: 300px;
  display: flex;
  justify-content: center;
  position: relative;
}

.image-frame {
  position: relative;
  background: transparent;
  padding: 8px;
  border: 1px solid #fbcfe8;
  border-radius: 150px 30px 30px 30px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.05);
  max-width: 400px;
  width: 100%;
}
body.dark-mode .image-frame {
  border-color: #83526c;
}

.image-inner {
  position: relative;
  overflow: hidden;
  border-radius: 140px 22px 22px 22px;
  height: 550px;
  width: 100%;
}

.model-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.7s ease;
}

.image-inner:hover .model-img {
  transform: scale(1.04);
}

@media (max-width: 1100px) {
  .features-grid {
    flex-wrap: wrap;
  }
  .feature-col {
    flex: 1 1 40%;
    margin-bottom: 20px;
  }
  .feature-divider-vert {
    display: none;
  }
}

@media (max-width: 868px) {
  .brand-container {
    flex-direction: column;
  }
  .brand-content {
    order: 1;
  }
  .brand-image-wrapper {
    order: 2;
    margin-top: 30px;
  }
  .image-inner {
    height: 450px;
  }
  .main-title {
    font-size: 2.6rem;
  }
}

@media (max-width: 576px) {
  .behind-brand-section {
    padding: 40px 20px;
  }
  .feature-col {
    flex: 1 1 100%;
  }
  .image-inner {
    height: 380px;
  }
  .promise-inner {
    flex-direction: column;
    text-align: center;
  }
}
</style>

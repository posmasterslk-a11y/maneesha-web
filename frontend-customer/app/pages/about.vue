<script setup>
useSeoMeta({
  title: 'About Maneesha Fashion | Premium Women\'s Wear in Sri Lanka',
  description: 'Learn about Maneesha Fashion, Sri Lanka\'s premier destination for bespoke women\'s clothing. Discover our passion for tailoring elegant sarees, lehengas, and unique frocks.',
  keywords: 'about Maneesha Fashion, Sri Lankan fashion brand, bespoke tailoring Sri Lanka, premium ladies wear boutique, custom clothing brand',
  ogTitle: 'About Maneesha Fashion | Premium Women\'s Wear in Sri Lanka',
  ogDescription: 'Learn about Maneesha Fashion, Sri Lanka\'s premier destination for bespoke women\'s clothing. Discover our passion for tailoring elegant sarees, lehengas, and unique frocks.',
  ogUrl: 'https://maneesha.posmasters.lk/about',
  ogType: 'website'
})
</script>

<template>
  <div class="about-page">
    
    <!-- Hero Section -->
    <section class="about-hero">
      <svg class="floral-deco-left" viewBox="0 0 200 600" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10,200 C10,100 150,20 180,0 C160,60 100,160 20,200 Z" fill="url(#pinkGrad)" opacity="0.3"/>
        <path d="M30,340 C10,140 120,60 140,40 C120,100 80,280 30,340 Z" fill="url(#pinkGrad)" opacity="0.4"/>
        <path d="M60,450 C20,320 140,120 170,100 C140,160 100,340 60,450 Z" fill="url(#pinkGrad)" opacity="0.5"/>
        <defs>
          <linearGradient id="pinkGrad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#f9a8d4" />
            <stop offset="100%" stop-color="#f472b6" />
          </linearGradient>
        </defs>
      </svg>
      
      <div class="container about-hero-inner">
        <div class="hero-content">
          <div class="subtitle-wrap">
            <span class="pink-subtitle">ABOUT US</span>
            <div class="divider-hearts">
              <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
            </div>
          </div>
          
          <h1 class="main-title">
            <span class="dark-serif">Where Elegance</span><br>
            <span class="pink-serif">Meets</span> <span class="dark-serif">You</span>
          </h1>
          
          <div class="divider-hearts left-align">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
          </div>
          
          <p class="hero-desc">
            At Maneesha Fashion, we believe that fashion is more than just clothing — it's a reflection of who you are. Every stitch, every cut, and every detail is thoughtfully designed to bring elegance, comfort, and confidence into your everyday moments.
          </p>
          
          <NuxtLink to="#story" class="btn-shop-pink mt-4">
            READ OUR STORY <i class="fa-solid fa-arrow-right"></i>
          </NuxtLink>
        </div>
        
        <div class="hero-image-wrap">
          <svg class="floral-deco-right" viewBox="0 0 150 300" fill="none" xmlns="http://www.w3.org/2000/svg">
             <path d="M140,150 C140,75 35,15 20,0 C35,45 80,120 140,150 Z" fill="url(#pinkGrad)" opacity="0.5"/>
             <path d="M120,200 C80,120 10,60 0,45 C20,90 60,160 120,200 Z" fill="url(#pinkGrad)" opacity="0.6"/>
          </svg>
          <div class="image-curve">
            <img src="/images/designer-2.jpeg" alt="Maneesha Fashion Elegance" class="hero-img" />
          </div>
        </div>
      </div>
    </section>
    
    <!-- Our Story Section -->
    <section id="story" class="story-section container">
      <div class="story-image-wrap">
        <img src="/images/designer-1.jpeg" alt="Sewing process" class="story-img" />
        <div class="handmade-stamp">
          <div class="stamp-inner">
             <span class="stamp-text-circle">HANDMADE WITH LOVE • </span>
             <i class="fa-regular fa-heart stamp-icon"></i>
          </div>
        </div>
      </div>
      
      <div class="story-content">
        <div class="subtitle-wrap left-align">
          <span class="pink-subtitle">OUR STORY</span>
          <div class="divider-hearts">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
          </div>
        </div>
        
        <h2 class="section-title">
          <span class="dark-serif">From Passion to Perfection,</span><br>
          <span class="dark-serif">For</span> <span class="pink-serif">You</span>
        </h2>
        
        <div class="divider-hearts left-align mb-4">
          <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
        </div>
        
        <p class="story-desc">
          What started as a small passion for design has grown into a brand built on love, dedication, and the trust of our amazing customers. Each outfit is handmade with care by our skilled team, using the finest fabrics to ensure premium quality and lasting comfort.
        </p>
        
        <div class="story-features">
          <div class="feature-row">
            <div class="icon-circle-sm">
              <i class="fa-solid fa-person-dress"></i>
            </div>
            <div class="feature-text">
              <h4>Made with Care</h4>
              <p>Every outfit is handmade with attention to every little detail.</p>
            </div>
          </div>
          <div class="feature-row">
            <div class="icon-circle-sm">
              <i class="fa-regular fa-gem"></i>
            </div>
            <div class="feature-text">
              <h4>Quality You Can Trust</h4>
              <p>We use carefully selected fabrics and ensure the highest quality in every piece.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Stats Banner -->
    <section class="stats-section container">
      <div class="stats-banner">
        <div class="stat-item">
          <i class="fa-regular fa-face-smile stat-icon"></i>
          <h3 class="stat-number">5K+</h3>
          <h4 class="stat-title">Happy Customers</h4>
          <p class="stat-desc">served with love</p>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <i class="fa-solid fa-person-dress stat-icon"></i>
          <h3 class="stat-number">500+</h3>
          <h4 class="stat-title">Unique Designs</h4>
          <p class="stat-desc">crafted with care</p>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <i class="fa-solid fa-pen-nib stat-icon"></i>
          <h3 class="stat-number">8+</h3>
          <h4 class="stat-title">Years of Experience</h4>
          <p class="stat-desc">in fashion design</p>
        </div>
        <div class="stat-divider"></div>
        <div class="stat-item">
          <i class="fa-regular fa-heart stat-icon"></i>
          <h3 class="stat-number">100%</h3>
          <h4 class="stat-title">Passion & Dedication</h4>
          <p class="stat-desc">in everything we do</p>
        </div>
      </div>
    </section>
    
    <!-- Values Section -->
    <section class="values-section container">
      <div class="values-header text-center">
        <div class="subtitle-wrap justify-center">
          <span class="pink-subtitle">OUR VALUES</span>
          <div class="divider-hearts">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-2 text-xs"></i><span class="line"></span>
          </div>
        </div>
        <h2 class="section-title text-center">
          <span class="dark-serif">What</span> <span class="pink-serif">We</span> <span class="dark-serif">Stand For</span>
        </h2>
      </div>
      
      <div class="values-grid">
        <div class="value-card glass-panel">
          <div class="value-icon">
            <i class="fa-solid fa-award"></i>
          </div>
          <h4>Quality</h4>
          <div class="divider-hearts my-2">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
          </div>
          <p>We never compromise on quality. Every fabric and detail is carefully chosen.</p>
        </div>
        
        <div class="value-card glass-panel">
          <div class="value-icon">
            <i class="fa-solid fa-hand-holding-heart"></i>
          </div>
          <h4>Integrity</h4>
          <div class="divider-hearts my-2">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
          </div>
          <p>Honesty and transparency are the foundation of our brand and relationships.</p>
        </div>
        
        <div class="value-card glass-panel">
          <div class="value-icon">
            <i class="fa-solid fa-lightbulb"></i>
          </div>
          <h4>Creativity</h4>
          <div class="divider-hearts my-2">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
          </div>
          <p>We embrace creativity to design unique outfits that make you stand out.</p>
        </div>
        
        <div class="value-card glass-panel">
          <div class="value-icon">
            <i class="fa-solid fa-users"></i>
          </div>
          <h4>Customer First</h4>
          <div class="divider-hearts my-2">
            <span class="line"></span><i class="fa-solid fa-heart text-pink-300 mx-1 text-xs" style="font-size: 8px;"></i><span class="line"></span>
          </div>
          <p>Our customers are at the heart of everything we do. Your happiness matters most.</p>
        </div>
      </div>
    </section>
    
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Poppins:wght@300;400;500;600&display=swap');

.about-page {
  font-family: 'Poppins', sans-serif;
  color: #334155;
  background-color: #fff;
  overflow: hidden;
}

body.dark-mode .about-page {
  background-color: #0f1115;
  color: #cbd5e1;
}

/* Common Styles */
.dark-serif {
  color: #0f172a;
  font-weight: 600;
}
body.dark-mode .dark-serif { color: #f8fafc; }

.pink-serif {
  color: #f472b6;
  font-weight: 600;
}

.pink-subtitle {
  color: #f472b6;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.9rem;
  letter-spacing: 2px;
  text-transform: uppercase;
}

.subtitle-wrap {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin-bottom: 15px;
}

.subtitle-wrap.justify-center {
  align-items: center;
}

.divider-hearts {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  margin-top: 5px;
}

.divider-hearts.left-align {
  justify-content: flex-start;
}

.divider-hearts .line {
  height: 1px;
  background-color: #fca5a5;
  flex: 1;
}

/* Hero Section */
.about-hero {
  position: relative;
  background: linear-gradient(135deg, #fffafa 0%, #fff0f5 100%);
  padding: 80px 0 100px;
}

body.dark-mode .about-hero {
  background: linear-gradient(135deg, #181518 0%, #1f1a1d 100%);
}

.floral-deco-left {
  position: absolute;
  left: -20px;
  top: 50%;
  transform: translateY(-50%);
  width: 250px;
  z-index: 1;
}

.about-hero-inner {
  display: flex;
  align-items: center;
  gap: 50px;
  position: relative;
  z-index: 2;
}

.hero-content {
  flex: 1;
  padding-left: 50px;
}

.main-title {
  font-family: 'Playfair Display', serif;
  font-size: 4.5rem;
  line-height: 1.1;
  margin-bottom: 10px;
}

.hero-desc {
  font-size: 1.05rem;
  line-height: 1.8;
  color: #475569;
  max-width: 500px;
  margin-top: 20px;
  margin-bottom: 30px;
}

body.dark-mode .hero-desc { color: #94a3b8; }

.btn-shop-pink {
  background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%);
  color: white;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  padding: 15px 35px;
  border-radius: 30px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  box-shadow: 0 10px 20px rgba(244, 114, 182, 0.4);
  transition: all 0.3s ease;
}

.btn-shop-pink:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 25px rgba(244, 114, 182, 0.5);
}

.hero-image-wrap {
  flex: 1.2;
  position: relative;
  display: flex;
  justify-content: flex-end;
}

.floral-deco-right {
  position: absolute;
  right: -50px;
  top: 10%;
  width: 200px;
  z-index: 10;
}

.image-curve {
  width: 100%;
  max-width: 650px;
  height: 700px;
  border-radius: 350px 30px 30px 350px;
  overflow: hidden;
  box-shadow: -20px 20px 60px rgba(0,0,0,0.1);
  position: relative;
  z-index: 2;
}

.hero-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
}

/* Story Section */
.story-section {
  display: flex;
  align-items: center;
  gap: 80px;
  padding: 100px 20px;
}

.story-image-wrap {
  flex: 1;
  position: relative;
}

.story-img {
  width: 100%;
  height: 550px;
  object-fit: cover;
  border-radius: 30px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

.handmade-stamp {
  position: absolute;
  bottom: -30px;
  left: -30px;
  width: 140px;
  height: 140px;
  background: #fff;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 10px 30px rgba(244, 114, 182, 0.2);
  border: 4px solid #fbcfe8;
  animation: rotate 20s linear infinite;
}

body.dark-mode .handmade-stamp {
  background: #1e293b;
  border-color: #83526c;
}

@keyframes rotate {
  100% { transform: rotate(360deg); }
}

.stamp-inner {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.stamp-icon {
  font-size: 2rem;
  color: #f472b6;
  position: absolute;
}

.story-content {
  flex: 1;
}

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.8rem;
  line-height: 1.2;
}

.story-desc {
  font-size: 1rem;
  line-height: 1.8;
  color: #475569;
  margin-bottom: 40px;
}

body.dark-mode .story-desc { color: #94a3b8; }

.story-features {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.feature-row {
  display: flex;
  align-items: center;
  gap: 20px;
}

.icon-circle-sm {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: 1px solid #fbcfe8;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

body.dark-mode .icon-circle-sm {
  border-color: #83526c;
  background-color: #2d1b24;
}

.icon-circle-sm i {
  color: #f472b6;
  font-size: 1.5rem;
}

.feature-row:hover .icon-circle-sm {
  background-color: #fdf2f8;
  transform: scale(1.1);
}

.feature-text h4 {
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 5px;
}

body.dark-mode .feature-text h4 { color: #f1f5f9; }

.feature-text p {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.5;
}

body.dark-mode .feature-text p { color: #94a3b8; }

/* Stats Section */
.stats-section {
  padding: 20px;
  margin-bottom: 80px;
}

.stats-banner {
  background: linear-gradient(135deg, #fff0f5 0%, #fdf2f8 100%);
  border-radius: 20px;
  padding: 40px;
  display: flex;
  justify-content: space-around;
  align-items: center;
  box-shadow: 0 10px 30px rgba(244, 114, 182, 0.1);
  flex-wrap: wrap;
  gap: 20px;
}

body.dark-mode .stats-banner {
  background: linear-gradient(135deg, #2d1b24 0%, #1f1a1d 100%);
}

.stat-item {
  text-align: center;
  flex: 1;
  min-width: 150px;
}

.stat-icon {
  font-size: 2.2rem;
  color: #f472b6;
  margin-bottom: 15px;
}

.stat-number {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 5px;
}

body.dark-mode .stat-number { color: #f8fafc; }

.stat-title {
  font-weight: 600;
  font-size: 1rem;
  color: #334155;
  margin-bottom: 2px;
}

body.dark-mode .stat-title { color: #cbd5e1; }

.stat-desc {
  font-size: 0.85rem;
  color: #64748b;
}

body.dark-mode .stat-desc { color: #94a3b8; }

.stat-divider {
  width: 1px;
  height: 80px;
  background-color: #fbcfe8;
}

body.dark-mode .stat-divider { background-color: #83526c; }

/* Values Section */
.values-section {
  padding: 40px 20px 100px;
}

.values-header {
  margin-bottom: 60px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.values-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

.value-card {
  background: #fff;
  border-radius: 20px;
  padding: 40px 20px;
  text-align: center;
  border: 1px solid #f8fafc;
  box-shadow: 0 10px 40px rgba(0,0,0,0.03);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
}

body.dark-mode .value-card {
  background: #1e293b;
  border-color: #334155;
}

.value-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 50px rgba(244, 114, 182, 0.1);
  border-color: #fbcfe8;
}

body.dark-mode .value-card:hover {
  border-color: #83526c;
}

.value-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
  color: #f472b6;
  font-size: 2rem;
  background: #fdf2f8;
}

body.dark-mode .value-icon {
  background: #2d1b24;
}

.value-card h4 {
  font-weight: 600;
  font-size: 1.2rem;
  color: #0f172a;
}

body.dark-mode .value-card h4 { color: #f1f5f9; }

.value-card p {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.6;
  margin-top: 10px;
}

body.dark-mode .value-card p { color: #94a3b8; }

/* Responsive */
@media (max-width: 1024px) {
  .about-hero-inner {
    flex-direction: column;
    text-align: center;
  }
  
  .hero-content {
    padding-left: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  
  .hero-desc {
    margin: 20px auto 30px;
  }
  
  .divider-hearts.left-align {
    justify-content: center;
  }
  
  .image-curve {
    border-radius: 100px 30px 100px 30px;
    height: 500px;
  }
  
  .story-section {
    flex-direction: column;
  }
  
  .story-content {
    order: -1;
  }
  
  .values-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .stat-divider {
    display: none;
  }
}

@media (max-width: 640px) {
  .main-title {
    font-size: 3rem;
  }
  
  .section-title {
    font-size: 2.2rem;
  }
  
  .values-grid {
    grid-template-columns: 1fr;
  }
  
  .image-curve {
    height: 400px;
    border-radius: 60px 20px 60px 20px;
  }
  
  .story-img {
    height: 350px;
  }
  
  .handmade-stamp {
    width: 100px;
    height: 100px;
    left: -15px;
    bottom: -15px;
  }
  
  .stamp-text-circle {
    font-size: 0.6rem;
  }
}
</style>

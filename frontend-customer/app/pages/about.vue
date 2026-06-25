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
  <div class="static-page-container">
    <div class="static-header glass-panel">
      <h1>About Us</h1>
      <p>Discover the story behind Maneesha Fashion</p>
    </div>
    
    <div class="static-content glass-panel">
      <div class="about-grid">
        <div class="about-text">
          <h2>Our Story</h2>
          <p>Founded with a passion for elegant and high-quality clothing, Maneesha Fashion has grown to become a premier destination for fashion enthusiasts in Sri Lanka. We believe that fashion is an expression of individuality, and our collections are curated to empower you to express your unique style.</p>
          
          <h2 class="mt-8">Our Mission</h2>
          <p>Our mission is to provide premium quality, trendy, and comfortable apparel that caters to diverse tastes. We are committed to ethical sourcing, sustainable practices, and delivering an exceptional shopping experience to every customer.</p>
          
          <h2 class="mt-8">Why Choose Us?</h2>
          <ul>
            <li><i class="fa-solid fa-check text-gold"></i> Premium Quality Fabrics</li>
            <li><i class="fa-solid fa-check text-gold"></i> Exclusive Designs</li>
            <li><i class="fa-solid fa-check text-gold"></i> Island-wide Delivery</li>
            <li><i class="fa-solid fa-check text-gold"></i> Excellent Customer Support</li>
          </ul>
        </div>
        <div class="about-image">
          <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80" alt="Maneesha Fashion Store" class="rounded-image" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.static-page-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
.static-header { padding: 40px; text-align: center; border-radius: var(--radius-lg); margin-bottom: 30px; }
.static-header h1 { font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 10px; }
.static-content { padding: 40px; border-radius: var(--radius-lg); }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
@media (max-width: 768px) { .about-grid { grid-template-columns: 1fr; } }
.about-text h2 { font-size: 1.6rem; margin-bottom: 15px; color: var(--text-dark-primary); }
body.dark-mode .about-text h2 { color: var(--text-light-primary); }
.about-text p { color: var(--text-dark-secondary); line-height: 1.7; margin-bottom: 20px; }
body.dark-mode .about-text p { color: var(--text-light-secondary); }
.about-text ul { list-style: none; padding: 0; }
.about-text li { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; color: var(--text-dark-secondary); }
body.dark-mode .about-text li { color: var(--text-light-secondary); }
.text-gold { color: var(--primary-gold); }
.mt-8 { margin-top: 30px; }
.rounded-image { width: 100%; height: auto; border-radius: var(--radius-lg); object-fit: cover; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
</style>

<template>
  <section class="premium-collection-section">
    <!-- Decorative background elements -->
    <div class="bg-dots top-right"></div>
    <div class="bg-dots bottom-left"></div>
    
    <div class="premium-container">
      
      <!-- Left Content -->
      <div class="premium-content">
        <div class="premium-badge">
          <span class="badge-text">Premium Collection</span>
          <div class="badge-line"></div>
        </div>
        
        <h2 class="main-title">
          <span class="dark-serif">Why Choose Our</span><br>
          <span class="pink-italic">Premium</span> <span class="dark-serif">Collection</span>
        </h2>
        
        <div class="title-star">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z" fill="#D4AF37"/>
          </svg>
        </div>
        
        <p class="premium-desc">
          At Frocks Designer, we believe in creating outfits that make you feel confident, elegant, and uniquely you. Each piece in our collection is crafted with the finest fabrics and attention to detail.
        </p>
        
        <div class="features-list">
          <!-- Feature 1 -->
          <div class="feature-item">
            <div class="feature-icon-wrapper">
              <div class="icon-circle">
                <i class="fa-solid fa-person-dress" style="color: white; font-size: 20px;"></i>
              </div>
            </div>
            <div class="feature-text">
              <h4 class="feature-title">Premium Fit</h4>
              <p class="feature-desc">Flattering fits crafted for every body.</p>
            </div>
          </div>
          
          <div class="feature-divider"></div>
          
          <!-- Feature 2 -->
          <div class="feature-item">
            <div class="feature-icon-wrapper">
              <div class="icon-circle">
                 <i class="fa-solid fa-hands-holding-circle" style="color: white; font-size: 20px;"></i>
              </div>
            </div>
            <div class="feature-text">
              <h4 class="feature-title">Finest Fabrics & Comfort</h4>
              <p class="feature-desc">Carefully selected fabrics for all-day comfort.</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Image Container (Product Slider) -->
      <div class="premium-image-wrapper">
        <svg class="floral-deco" viewBox="0 0 100 200" fill="none" xmlns="http://www.w3.org/2000/svg">
           <path d="M10,100 C10,50 80,10 90,0 C80,30 50,80 10,100 Z" fill="url(#pinkGrad)" />
           <path d="M15,120 C5,70 60,30 70,20 C60,50 40,90 15,120 Z" fill="url(#pinkGrad)" opacity="0.8"/>
           <path d="M30,150 C10,110 70,60 85,50 C70,80 50,120 30,150 Z" fill="url(#pinkGrad)" opacity="0.9"/>
           <path d="M45,180 C20,140 80,90 95,80 C80,110 60,150 45,180 Z" fill="url(#pinkGrad)" />
           <defs>
             <linearGradient id="pinkGrad" x1="0" y1="0" x2="1" y2="1">
               <stop offset="0%" stop-color="#f9a8d4" />
               <stop offset="100%" stop-color="#f472b6" />
             </linearGradient>
           </defs>
        </svg>

        <div class="image-frame">
          <div class="image-inner">
            <template v-if="products && products.length > 0">
              <TransitionGroup name="fade">
                <div 
                  v-for="(prod, idx) in products" 
                  :key="prod.id"
                  v-show="currentSlide === idx"
                  class="slide-wrapper"
                >
                  <img 
                    v-if="prod.main_image" 
                    :src="prod.main_image.replace('http://', 'https://')" 
                    :alt="prod.name" 
                    class="model-img" 
                  />
                  <div v-else class="placeholder-img model-img">
                    <i class="fa-solid fa-shirt" style="font-size: 3rem; color: #fff;"></i>
                  </div>
                  
                  <div class="image-overlay-content">
                    <p class="overlay-text">{{ prod.name }} <br><span class="overlay-italic">LKR {{ formatNumber(prod.base_price) }}</span></p>
                    <NuxtLink :to="`/product/${prod.slug}`" class="btn-shop-pink">
                      SHOP NOW <i class="fa-solid fa-arrow-right"></i>
                    </NuxtLink>
                  </div>
                </div>
              </TransitionGroup>
            </template>
            <template v-else>
              <img src="/images/premium_model.jpg" alt="Premium Collection Dress" class="model-img" />
              <div class="image-overlay-content">
                <p class="overlay-text">Step into elegance with <br><span class="overlay-italic">our latest collection.</span></p>
                <NuxtLink to="/shop" class="btn-shop-pink">
                  SHOP NOW <i class="fa-solid fa-arrow-right"></i>
                </NuxtLink>
              </div>
            </template>
          </div>
        </div>
      </div>
      
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  products: {
    type: Array,
    default: () => []
  }
})

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const currentSlide = ref(0)
let slideInterval = null

onMounted(() => {
  if (props.products.length > 1) {
    slideInterval = setInterval(() => {
      currentSlide.value = (currentSlide.value + 1) % props.products.length
    }, 4000)
  }
})

onUnmounted(() => {
  if (slideInterval) clearInterval(slideInterval)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Poppins:wght@300;400;500;600&display=swap');

.premium-collection-section {
  position: relative;
  background-color: #fafafa;
  padding: 80px 20px;
  overflow: hidden;
  border-radius: 24px;
  margin: 40px auto;
  max-width: 1200px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.03);
}

body.dark-mode .premium-collection-section {
  background-color: #121315;
}

.bg-dots {
  position: absolute;
  width: 150px;
  height: 150px;
  background-image: radial-gradient(#e5e7eb 2px, transparent 2px);
  background-size: 15px 15px;
  opacity: 0.6;
}
body.dark-mode .bg-dots {
  background-image: radial-gradient(#333 2px, transparent 2px);
}
.bg-dots.top-right {
  top: 10px;
  right: 10px;
}
.bg-dots.bottom-left {
  bottom: 10px;
  left: 10px;
}

.premium-container {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  gap: 40px;
  z-index: 2;
}

.premium-content {
  flex: 1;
  min-width: 300px;
  max-width: 500px;
  padding: 20px;
}

.premium-badge {
  margin-bottom: 20px;
}

.badge-text {
  color: #f472b6;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.9rem;
  letter-spacing: 0.5px;
}

.badge-line {
  height: 2px;
  width: 30px;
  background-color: #f472b6;
  margin-top: 5px;
}

.main-title {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  line-height: 1.1;
  margin-bottom: 15px;
}

.dark-serif {
  color: #0f172a;
  font-weight: 700;
}
body.dark-mode .dark-serif {
  color: #f1f5f9;
}

.pink-italic {
  color: #f472b6;
  font-style: italic;
  font-weight: 600;
}

.title-star {
  margin: 15px 0 25px 0;
}

.premium-desc {
  font-family: 'Poppins', sans-serif;
  color: #64748b;
  font-size: 1rem;
  line-height: 1.7;
  margin-bottom: 40px;
}
body.dark-mode .premium-desc {
  color: #94a3b8;
}

.features-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 20px;
}

.icon-circle {
  width: 55px;
  height: 55px;
  border-radius: 50%;
  background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%);
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 8px 20px rgba(244, 114, 182, 0.4);
}

.feature-title {
  font-family: 'Poppins', sans-serif;
  color: #0f172a;
  font-weight: 600;
  font-size: 1.1rem;
  margin-bottom: 4px;
}
body.dark-mode .feature-title {
  color: #f1f5f9;
}

.feature-desc {
  font-family: 'Poppins', sans-serif;
  color: #64748b;
  font-size: 0.95rem;
}
body.dark-mode .feature-desc {
  color: #94a3b8;
}

.feature-divider {
  height: 1px;
  width: 100%;
  background-color: #e2e8f0;
  margin: 5px 0;
}
body.dark-mode .feature-divider {
  background-color: #334155;
}

.premium-image-wrapper {
  flex: 1;
  min-width: 320px;
  display: flex;
  justify-content: center;
  position: relative;
  padding: 20px;
}

.floral-deco {
  position: absolute;
  left: -20px;
  bottom: 20px;
  width: 120px;
  height: auto;
  z-index: 10;
  pointer-events: none;
  filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.1));
}

.image-frame {
  position: relative;
  background: #fff;
  padding: 15px;
  border-radius: 120px 40px 120px 40px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.08);
  max-width: 450px;
  width: 100%;
}
body.dark-mode .image-frame {
  background: #1e293b;
  box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

.image-inner {
  position: relative;
  overflow: hidden;
  border-radius: 110px 30px 110px 30px;
  height: 600px;
  width: 100%;
}

.slide-wrapper {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.model-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.placeholder-img {
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
}

.image-inner:hover .model-img {
  transform: scale(1.05);
}

.image-overlay-content {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 60px 30px 30px;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.overlay-text {
  font-family: 'Playfair Display', serif;
  color: #fff;
  font-size: 1.3rem;
  margin-bottom: 20px;
}

.overlay-italic {
  font-style: italic;
  color: #fce7f3;
}

.btn-shop-pink {
  background: linear-gradient(135deg, #f9a8d4 0%, #f472b6 100%);
  color: white;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  padding: 12px 30px;
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

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@media (max-width: 992px) {
  .premium-container {
    flex-direction: column;
  }
  
  .premium-content {
    max-width: 100%;
  }
  
  .main-title {
    font-size: 2.5rem;
  }
  
  .image-inner {
    height: 500px;
  }
}

@media (max-width: 576px) {
  .premium-collection-section {
    padding: 40px 15px;
  }
  
  .main-title {
    font-size: 2rem;
  }
  
  .image-frame {
    padding: 10px;
    border-radius: 80px 20px 80px 20px;
  }
  
  .image-inner {
    height: 400px;
    border-radius: 75px 15px 75px 15px;
  }
}
</style>

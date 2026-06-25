<template>
  <div class="homepage">
    <!-- Hero Banner Section -->
    <section class="hero-section glass-panel">
      <div class="hero-content container">
        <span class="hero-subtitle animate-slide-left">Premium ready-to-wear clothing</span>
        <h1 class="luxury-title animate-fade-up">
          Elegant Stitches <br />
          Crafted For <span class="gold-gradient-text">Your Shape</span>
        </h1>
        <p class="hero-desc animate-fade-up">
          At Maneesha Fashion, we offer a carefully curated collection of premium clothing. Choose from our standard premium sizes for a perfect fit.
        </p>
        <div class="hero-ctas animate-fade-up">
          <NuxtLink to="/shop" class="btn-premium btn-gold">Explore Collection</NuxtLink>
          <NuxtLink to="/orders" class="btn-premium secondary-btn">Track Order</NuxtLink>
        </div>
      </div>
      
      <!-- Designer Focus Visual -->
      <div class="hero-visual">
        <div class="ambient-glow"></div>
        <div class="designer-hero-images">
          <div class="img-wrapper img-1 animate-slide-left">
            <img src="/images/designer-1.jpeg" alt="Maneesha Fashion Designer" />
          </div>
          <div class="img-wrapper img-2 animate-fade-up" style="animation-delay: 0.3s;">
            <img src="/images/designer-2.jpeg" alt="Maneesha Fashion Studio" />
          </div>
        </div>
      </div>
    </section>

    <!-- Exclusive New Arrivals Section -->
    <section class="featured-products container mb-20" v-if="exclusiveProducts && exclusiveProducts.length > 0" style="margin-top: -20px;">
      <div class="section-header-row">
        <div>
          <h2 class="luxury-title">Exclusive <span class="gold-gradient-text">New Arrivals</span></h2>
          <p>Be the first to wear our latest handcrafted designs.</p>
        </div>
        <NuxtLink to="/shop" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></NuxtLink>
      </div>

      <div class="products-grid">
        <div v-for="prod in exclusiveProducts" :key="prod.id" class="product-card glass-panel">
          <div class="product-img-wrapper">
            <div class="product-tag" style="background: var(--primary-gold); color: #fff;">New</div>
            <img v-if="prod.main_image" :src="prod.main_image.replace('http://', 'https://')" :alt="prod.name" class="product-real-img" />
            <div v-else class="product-visual-placeholder" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%)">
              <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="hover-overlay">
              <NuxtLink :to="`/product/${prod.slug}`" class="btn-premium quick-view-btn">View Details &amp; Buy</NuxtLink>
            </div>
          </div>

          <div class="product-details">
            <span class="product-cat">{{ prod.category_name }}</span>
            <h3 class="luxury-title">{{ prod.name }}</h3>
            <p class="product-desc">{{ prod.short_description }}</p>
            
            <div class="price-row">
              <div class="price-info">
                <span class="price-label">Starts from</span>
                <span class="product-price">LKR {{ formatNumber(prod.base_price) }}</span>
              </div>
              <NuxtLink :to="`/product/${prod.slug}`" class="add-to-cart-quick" aria-label="Add to cart">
                <i class="fa-solid fa-circle-chevron-right"></i>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products container" v-if="products && products.length > 0">
      <div class="section-header-row">
        <div>
          <h2 class="luxury-title">Featured <span class="gold-gradient-text">Creations</span></h2>
          <p>Hand-selected popular styles ready to buy.</p>
        </div>
        <NuxtLink to="/shop" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></NuxtLink>
      </div>

      <div class="products-grid">
        <div v-for="prod in products" :key="prod.id" class="product-card glass-panel">
          <div class="product-img-wrapper">
            <div class="product-tag" v-if="prod.is_featured">Featured</div>
            <!-- Real image or fallback gradient -->
            <img v-if="prod.main_image" :src="prod.main_image.replace('http://', 'https://')" :alt="prod.name" class="product-real-img" />
            <div v-else class="product-visual-placeholder" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%)">
              <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="hover-overlay">
              <NuxtLink :to="`/product/${prod.slug}`" class="btn-premium quick-view-btn">View Details &amp; Buy</NuxtLink>
            </div>
          </div>

          <div class="product-details">
            <span class="product-cat">{{ prod.category_name }}</span>
            <h3 class="luxury-title">{{ prod.name }}</h3>
            <p class="product-desc">{{ prod.short_description }}</p>
            
            <div class="price-row">
              <div class="price-info">
                <span class="price-label">Starts from</span>
                <span class="product-price">LKR {{ formatNumber(prod.base_price) }}</span>
              </div>
              <NuxtLink :to="`/product/${prod.slug}`" class="add-to-cart-quick" aria-label="Add to cart">
                <i class="fa-solid fa-circle-chevron-right"></i>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories Showcase -->
    <section class="categories-section container">
      <div class="section-header">
        <h2 class="luxury-title">Browse Our <span class="gold-gradient-text">Categories</span></h2>
        <p>Curated designs ready to be stitched by hand.</p>
      </div>

      <div class="categories-grid">
        <div v-for="cat in categories" :key="cat.id" class="category-card glass-panel" @click="navigateToCategory(cat.slug)">
          <div class="category-overlay"></div>
          <div class="category-icon">
            <i :class="cat.icon"></i>
          </div>
          <div class="category-info">
            <h3 class="luxury-title">{{ cat.name }}</h3>
            <span>{{ cat.products_count ?? cat.itemCount ?? 0 }} Designs</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Values / Stitching story -->
    <section class="stitching-story glass-panel">
      <div class="container story-grid">
        <div class="story-text">
          <span class="gold-gradient-text text-uppercase font-bold">The Craftsmanship</span>
          <h2 class="luxury-title">Why Choose Our Premium Collection</h2>
          <p>We believe fashion is about individuality. At Maneesha Fashion, we respect your style. When you purchase, select your exact size from our premium standard measurements. Our carefully crafted apparel is inspected and prepared for unmatched luxury comfort.</p>
          
          <div class="highlights-row">
            <div class="hl-item">
              <i class="fa-solid fa-ruler-combined"></i>
              <div>
                <h5>Perfect Fit</h5>
                <p>Fashion changes, true style remains.</p>
              </div>
            </div>
            <div class="hl-item">
              <i class="fa-solid fa-building-columns"></i>
              <div>
                <h5>Secure Bank Deposit</h5>
                <p>Direct bank transfers for your convenience.</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="story-canvas product-slider-canvas glass-panel">
          <div v-if="popularProducts.length === 0" class="stitching-diagram">
            <div class="line vertical"></div>
            <div class="line horizontal"></div>
            <i class="fa-solid fa-ruler-horizontal diag-icon"></i>
          </div>
          <div v-else>
            <TransitionGroup name="fade">
              <div v-for="(prod, idx) in popularProducts" :key="prod.id" 
                   v-show="currentStorySlide === idx"
                   class="story-slide-wrapper">
                <img v-if="prod.main_image" :src="prod.main_image.replace('http://', 'https://')" :alt="prod.name" class="story-product-img" />
                <div v-else class="product-visual-placeholder story-placeholder">
                  <i class="fa-solid fa-shirt"></i>
                </div>
                <div class="story-product-overlay">
                  <h4 class="luxury-title text-white text-2xl mb-1">{{ prod.name }}</h4>
                  <p class="text-gray-200 mb-4 font-semibold">LKR {{ formatNumber(prod.base_price) }}</p>
                  <NuxtLink :to="`/product/${prod.slug}`" class="btn-premium btn-gold btn-sm">Shop Now</NuxtLink>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </div>
    </section>

    <!-- Meet the Designer Section (Simplified text) -->
    <section class="meet-designer-section container mb-20 glass-panel" style="text-align: center; max-width: 800px; margin: 0 auto 80px auto; padding: 40px;">
        <span class="gold-gradient-text text-uppercase font-bold">Behind The Brand</span>
        <h2 class="luxury-title mt-2">Crafted with <span class="gold-gradient-text">Passion</span></h2>
        <p class="mt-4" style="color: var(--text-dark-secondary);">
          Every stitch, every cut, and every detail is guided by a passion for perfection. 
          At Maneesha Fashion, we believe in bringing your dream outfits to life with a personal touch.
        </p>
    </section>

    <!-- Popular / Most Viewed Products Section -->
    <section class="popular-products container mb-20" v-if="popularProducts.length > 0">
      <div class="section-header">
        <h2 class="luxury-title">Most <span class="gold-gradient-text">Viewed</span></h2>
        <p>Our customers' favorite picks.</p>
      </div>

      <div class="products-grid">
        <div v-for="prod in popularProducts" :key="prod.id" class="product-card glass-panel">
          <div class="product-img-wrapper">
            <div class="product-tag" v-if="prod.views > 0"><i class="fa-solid fa-fire text-orange-500 mr-1"></i> {{ prod.views }} Views</div>
            <!-- Real image or fallback gradient -->
            <img v-if="prod.main_image" :src="prod.main_image.replace('http://', 'https://')" :alt="prod.name" class="product-real-img" />
            <div v-else class="product-visual-placeholder" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%)">
              <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="hover-overlay">
              <NuxtLink :to="`/product/${prod.slug}`" class="btn-premium quick-view-btn">View Details &amp; Buy</NuxtLink>
            </div>
          </div>

          <div class="product-details">
            <span class="product-cat">{{ prod.category_name }}</span>
            <h3 class="luxury-title">{{ prod.name }}</h3>
            <p class="product-desc">{{ prod.short_description }}</p>
            
            <div class="price-row">
              <div class="price-info">
                <span class="price-label">Starts from</span>
                <span class="product-price">LKR {{ formatNumber(prod.base_price) }}</span>
              </div>
              <NuxtLink :to="`/product/${prod.slug}`" class="add-to-cart-quick" aria-label="Add to cart">
                <i class="fa-solid fa-circle-chevron-right"></i>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const config = useRuntimeConfig()
const API = config.public.apiBase
const router = useRouter()

// Add SEO Meta Tags
useHead({
  title: 'Maneesha Fashion - Elegant Stitches Crafted For Your Shape',
  meta: [
    { name: 'description', content: 'Maneesha Fashion offers a carefully curated collection of premium clothing. We specialize in bespoke standard sizes for a perfect fit, including sarees, lehengas, frocks, and wedding collections.' },
    { name: 'keywords', content: 'Maneesha Fashion, premium clothing, bespoke clothing, online fashion store, Sri Lanka fashion, designer wear, lehengas, sarees, frocks' }
  ]
})

const categoryIcons = {
  'sarees':           'fa-solid fa-person-dress',
  'frocks':           'fa-solid fa-person-dress',
  'blouses':          'fa-solid fa-shirt',
  'lehengas':         'fa-solid fa-crown',
  'tops-tunics':      'fa-solid fa-user-tie',
  'wedding-collection': 'fa-solid fa-heart',
}

// Fetch all data in parallel using Nuxt SSR
const [
  { data: rawCategories },
  { data: rawFeaturedProducts },
  { data: rawPopularProducts },
  { data: rawHeroSlides },
  { data: rawExclusiveProducts }
] = await Promise.all([
  useFetch(`${API}/categories`),
  useFetch(`${API}/products?featured=1`),
  useFetch(`${API}/products/popular`),
  useFetch(`${API}/products?hero_slider=1`),
  useFetch(`${API}/products?sort=latest`)
])

const categories = computed(() => {
  if (!rawCategories.value) return []
  return rawCategories.value.map(c => ({
    ...c,
    icon: categoryIcons[c.slug] || 'fa-solid fa-shirt',
    itemCount: c.products_count ?? 0,
  }))
})

const featuredCategory = computed(() => {
  return categories.value.find(c => c.is_featured) || null
})

const products = computed(() => rawFeaturedProducts.value?.data || rawFeaturedProducts.value || [])
const popularProducts = computed(() => rawPopularProducts.value?.data || rawPopularProducts.value || [])
const heroSlides = computed(() => rawHeroSlides.value?.data || rawHeroSlides.value || [])

const exclusiveProducts = computed(() => {
  const data = rawExclusiveProducts.value?.data || rawExclusiveProducts.value || []
  return data.slice(0, 4) // Show top 4 newest
})

const currentSlide = ref(0)
let slideInterval = null

const currentStorySlide = ref(0)
let storySlideInterval = null

const startSlider = () => {
  if (slideInterval) clearInterval(slideInterval)
  slideInterval = setInterval(() => {
    currentSlide.value = (currentSlide.value + 1) % heroSlides.value.length
  }, 5000)
}

const startStorySlider = () => {
  if (storySlideInterval) clearInterval(storySlideInterval)
  storySlideInterval = setInterval(() => {
    if (popularProducts.value.length > 0) {
      currentStorySlide.value = (currentStorySlide.value + 1) % popularProducts.value.length
    }
  }, 3500)
}

const navigateToCategory = (slug) => {
  router.push(`/shop?category=${slug}`)
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

onMounted(() => {
  if (heroSlides.value.length > 1) {
    startSlider()
  }
  startStorySlider()
})

onUnmounted(() => {
  if (slideInterval) clearInterval(slideInterval)
  if (storySlideInterval) clearInterval(storySlideInterval)
})
</script>

<style scoped>
/* Hero Styles */
.hero-section {
  position: relative;
  min-height: 80vh;
  margin: 0 20px 60px 20px;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  align-items: center;
}

.hero-content {
  padding: 60px 40px;
  z-index: 10;
}

.hero-subtitle {
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 3px;
  color: var(--primary-gold);
  font-weight: 700;
  display: inline-block;
  margin-bottom: 15px;
}

.hero-section h1 {
  font-size: 3.8rem;
  line-height: 1.1;
  margin-bottom: 25px;
}

.hero-desc {
  font-size: 1.05rem;
  color: var(--text-dark-secondary);
  max-width: 520px;
  margin-bottom: 35px;
}

body.dark-mode .hero-desc {
  color: var(--text-light-secondary);
}

.hero-ctas {
  display: flex;
  gap: 20px;
}

.secondary-btn {
  background: transparent;
  border: 1px solid var(--text-dark-primary);
  color: var(--text-dark-primary);
  box-shadow: none;
}

body.dark-mode .secondary-btn {
  border: 1px solid var(--text-light-primary);
  color: var(--text-light-primary);
}

.secondary-btn:hover {
  background: rgba(0,0,0,0.05);
}

body.dark-mode .secondary-btn:hover {
  background: rgba(255,255,255,0.05);
}

.hero-visual {
  position: relative;
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
}

.ambient-glow {
  position: absolute;
  width: 300px;
  height: 300px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%);
  filter: blur(40px);
}

.visual-badge {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 24px 36px;
  border-radius: var(--radius-lg);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  color: #fff;
  animation: pulse-soft 4s infinite ease-in-out;
  z-index: 2;
}

.visual-badge i {
  font-size: 2.2rem;
  color: var(--primary-gold);
}

.visual-badge span {
  font-family: var(--font-serif);
  font-size: 1.1rem;
  letter-spacing: 0.5px;
}

/* Designer Hero Image Styles */
.designer-hero-images {
  position: relative;
  width: 100%;
  height: 80%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 5;
}

.designer-hero-images .img-wrapper {
  position: absolute;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: 0 15px 35px rgba(0,0,0,0.4);
  border: 2px solid rgba(212,175,55,0.3);
  transition: transform 0.4s ease;
}

.designer-hero-images .img-wrapper:hover {
  transform: scale(1.05);
  z-index: 10;
}

.designer-hero-images .img-1 {
  width: 65%;
  height: 85%;
  left: 5%;
  top: 5%;
  z-index: 2;
}

.designer-hero-images .img-2 {
  width: 55%;
  height: 75%;
  right: 5%;
  bottom: -5%;
  z-index: 3;
}

.designer-hero-images img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Slider Styles */
.hero-slider {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 1;
}

.slide-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.slider-image {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.8; /* Increased opacity to show product better */
  mask-image: linear-gradient(to right, transparent, black 25%);
  -webkit-mask-image: linear-gradient(to right, transparent, black 25%);
}

.slide-content-wrapper {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.slider-overlay {
  position: absolute;
  bottom: 10%;
  right: 10%;
  text-align: right;
  z-index: 10;
}

.slider-product-name {
  font-family: var(--font-serif);
  font-size: 1.8rem;
  color: #fff;
  text-shadow: 0 2px 4px rgba(0,0,0,0.5);
  margin-bottom: 10px;
}

.mt-2 { margin-top: 10px; }
.btn-sm { padding: 8px 16px; font-size: 0.9rem; }

.fade-enter-active,
.fade-leave-active {
  transition: opacity 1.5s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Section Common Headers */
.section-header {
  text-align: center;
  margin-bottom: 45px;
}

.section-header h2 {
  font-size: 2.3rem;
  margin-bottom: 8px;
}

.section-header p {
  color: var(--text-dark-secondary);
}

body.dark-mode .section-header p {
  color: var(--text-light-secondary);
}

.section-header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 45px;
}

.section-header-row h2 {
  font-size: 2.3rem;
  margin-bottom: 8px;
}

.section-header-row p {
  color: var(--text-dark-secondary);
}

body.dark-mode .section-header-row p {
  color: var(--text-light-secondary);
}

.view-all-link {
  font-weight: 600;
  color: var(--primary-gold);
  display: flex;
  align-items: center;
  gap: 8px;
}

.view-all-link:hover {
  letter-spacing: 1px;
}

/* Category Grid */
.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  margin-bottom: 80px;
}

.category-card {
  position: relative;
  padding: 40px 30px;
  text-align: center;
  cursor: pointer;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

.category-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: rgba(212, 175, 55, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  color: var(--primary-gold);
  transition: var(--transition-smooth);
}

.category-card:hover .category-icon {
  background: var(--primary-gold);
  color: #fff;
  transform: scale(1.1);
}

.category-card h3 {
  font-size: 1.15rem;
  font-weight: 600;
}

.category-card span {
  font-size: 0.8rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .category-card span {
  color: var(--text-light-secondary);
}

/* Products Grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 30px;
  margin-bottom: 80px;
}

.mb-20 {
  margin-bottom: 80px;
}



/* Stitching Story */
.stitching-story {
  margin: 0 20px 80px 20px;
  padding: 60px 40px;
}

.story-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 40px;
  align-items: center;
}

.story-text h2 {
  font-size: 2.3rem;
  margin: 15px 0 20px 0;
}

.story-text p {
  font-size: 0.95rem;
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .story-text p {
  color: var(--text-light-secondary);
}

.highlights-row {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.hl-item {
  display: flex;
  gap: 15px;
  align-items: flex-start;
}

.hl-item i {
  font-size: 1.4rem;
  color: var(--primary-gold);
  margin-top: 3px;
}

.hl-item h5 {
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 4px;
}

.hl-item p {
  font-size: 0.8rem;
  margin-bottom: 0;
}

.story-canvas {
  height: 350px;
  background: linear-gradient(135deg, #131518 0%, #08090a 100%);
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255,255,255,0.05);
}

.stitching-diagram {
  position: relative;
  width: 80%;
  height: 80%;
  border: 1px dashed rgba(212,175,55,0.25);
  display: flex;
  align-items: center;
  justify-content: center;
}

.line {
  position: absolute;
  background: rgba(212,175,55,0.15);
}

.line.vertical {
  width: 1px;
  height: 100%;
  top: 0;
  left: 50%;
}

.line.horizontal {
  height: 1px;
  width: 100%;
  left: 0;
  top: 50%;
}

.diag-icon {
  font-size: 3rem;
  color: var(--primary-gold);
  opacity: 0.6;
}

/* Responsive */
@media (max-width: 991px) {
  .hero-section {
    grid-template-columns: 1fr;
    min-height: auto;
  }
  
  .hero-visual {
    height: 300px;
  }
  
  .story-grid {
    grid-template-columns: 1fr;
  }
  
  .story-canvas {
    height: 250px;
  }
}

@media (max-width: 768px) {
  .hero-section h1 {
    font-size: 2.6rem;
  }
  
  .hero-content {
    padding: 40px 20px;
  }
  
  .hero-ctas {
    flex-direction: column;
    width: 100%;
    gap: 15px;
  }
  
  .hero-ctas .btn-premium {
    width: 100%;
    text-align: center;
  }
  
  .stitching-story {
    padding: 40px 20px;
  }
  
  .story-text h2 {
    font-size: 1.8rem;
  }
  
  .designer-text h2 {
    font-size: 1.8rem;
  }
}

/* Designer Section */
.meet-designer-section {
  padding: 60px 40px;
  border-radius: var(--radius-lg);
  margin-top: 40px;
}

.designer-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: center;
}

.designer-text h2 {
  font-size: 2.3rem;
  margin-bottom: 20px;
}

.designer-text p {
  font-size: 1.05rem;
  line-height: 1.6;
  color: var(--text-dark-secondary);
}
body.dark-mode .designer-text p {
  color: var(--text-light-secondary);
}

.stat-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 15px;
  background: rgba(212,175,55,0.1);
  border-radius: 30px;
  border: 1px solid rgba(212,175,55,0.3);
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--primary-gold);
}

.designer-images {
  position: relative;
  height: 450px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.designer-images .img-wrapper {
  position: absolute;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
  transition: transform 0.3s ease;
}

.designer-images .img-wrapper:hover {
  transform: scale(1.02);
  z-index: 10;
}

.designer-images .img-1 {
  width: 60%;
  height: 80%;
  left: 0;
  top: 0;
  z-index: 2;
  border: 3px solid rgba(255,255,255,0.1);
}

.designer-images .img-2 {
  width: 55%;
  height: 70%;
  right: 0;
  bottom: 0;
  z-index: 3;
  border: 3px solid rgba(255,255,255,0.1);
}

.designer-images img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

@media (max-width: 991px) {
  .designer-grid {
    grid-template-columns: 1fr;
  }
  .designer-images {
    height: 350px;
    margin-top: 30px;
  }
}

/* Story Product Slider Styles */
.product-slider-canvas {
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.story-slide-wrapper {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.story-product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.story-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.story-product-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 30px;
  color: white;
}

.story-product-overlay .luxury-title {
  color: white;
  text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.story-product-overlay .btn-premium {
  align-self: flex-start;
}
</style>

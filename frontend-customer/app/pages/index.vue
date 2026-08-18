<template>
  <div class="homepage">
    <!-- Hero Banner Section (Slider) -->
    <section class="hero-section hero-slider-section">
      <div class="hero-slider-bg">
        <Transition name="hero-fade">
          <div 
            :key="currentHeroSlide"
            class="hero-slide-item"
          >
            <img :src="`/slider/${sliderImages[currentHeroSlide].img}`" class="slider-bg-img" alt="Hero Banner" />
            
            <div class="hero-content-overlay container">
              <div class="hero-content-box animate-slide-left text-glow">
                <span class="hero-subtitle">{{ sliderImages[currentHeroSlide].subtitle }}</span>
                <h1 class="luxury-title">
                  <span class="dark-text">{{ sliderImages[currentHeroSlide].titleTop }}</span>
                  <span class="pink-text">{{ sliderImages[currentHeroSlide].titleBottom }}</span>
                </h1>
                <p class="hero-desc">
                  {{ sliderImages[currentHeroSlide].desc }}
                </p>
                <div class="hero-ctas">
                  <NuxtLink to="/shop" class="btn-shop-now">SHOP NOW</NuxtLink>
                </div>
              </div>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Slider Controls -->
      <button class="slider-nav-btn prev-btn" @click="prevHeroSlide" aria-label="Previous Slide">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="slider-nav-btn next-btn" @click="nextHeroSlide" aria-label="Next Slide">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      <!-- Slider Pagination -->
      <div class="slider-pagination">
        <span 
          v-for="(slide, index) in sliderImages" 
          :key="index"
          class="slider-dot"
          :class="{ active: currentHeroSlide === index }"
          @click="currentHeroSlide = index"
        ></span>
      </div>
    </section>

    <!-- Features Banner -->
    <section class="features-banner container">
      <div class="feature-item">
        <i class="fa-solid fa-truck-fast feature-icon"></i>
        <div class="feature-text">
          <h4>ISLAND WIDE DELIVERY</h4>
          <p>Delivered to your doorstep</p>
        </div>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-award feature-icon"></i>
        <div class="feature-text">
          <h4>PREMIUM QUALITY</h4>
          <p>Finest fabrics & stitching</p>
        </div>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-headset feature-icon"></i>
        <div class="feature-text">
          <h4>CUSTOMER SUPPORT</h4>
          <p>Always here to help you</p>
        </div>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-shield-halved feature-icon"></i>
        <div class="feature-text">
          <h4>SECURE PAYMENT</h4>
          <p>100% protected</p>
        </div>
      </div>
    </section>

    <!-- Categories Showcase -->
    <section class="categories-section container">
      <div class="section-header">
        <h2 class="luxury-title">Browse Our <span class="gold-gradient-text">Categories</span></h2>
        <p>Curated designs ready to be stitched by hand.</p>
      </div>

      <div class="category-slider-wrapper">
        <button class="cat-nav-btn cat-prev" @click="scrollCategories(-300)" aria-label="Previous Category">
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="categories-grid" ref="categoriesGridRef" @mouseenter="pauseCategoryScroll" @mouseleave="resumeCategoryScroll" @touchstart="pauseCategoryScroll" @touchend="resumeCategoryScroll">
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

        <button class="cat-nav-btn cat-next" @click="scrollCategories(300)" aria-label="Next Category">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
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
            <div class="product-tag" v-if="getTotalStock(prod) > 0" style="background: var(--primary-gold); color: #fff;">New</div>
            <div class="product-tag" v-if="getTotalStock(prod) <= 0" style="background: var(--accent-error); color: #fff; left: auto; right: 15px;">Out of Stock</div>
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
            <div class="product-tag" v-if="getTotalStock(prod) <= 0" style="background: var(--accent-error); color: #fff; left: auto; right: 15px;">Out of Stock</div>
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



    <!-- Why Choose Our Premium Collection -->
    <PremiumCollection :products="popularProducts" />

    <!-- Meet the Designer Section / Behind the Brand -->
    <BehindTheBrand />

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
            <div class="product-tag" v-if="getTotalStock(prod) <= 0" style="background: var(--accent-error); color: #fff; left: auto; right: 15px;">Out of Stock</div>
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

useSeoMeta({
  title: 'Maneesha Fashion - Premium Women\'s Clothing & Bespoke Fashion in Sri Lanka',
  description: 'Discover the latest trends in women\'s fashion at Maneesha Fashion. Shop premium quality sarees, lehengas, bespoke frocks, and custom-tailored dresses online in Sri Lanka. Experience perfect fits and elegant styles.',
  keywords: 'Maneesha Fashion, online clothing store Sri Lanka, women\'s fashion Sri Lanka, buy sarees online, bespoke dresses, custom fit clothing, tailored frocks, lehengas Sri Lanka, premium ladies wear, designer clothing',
  ogTitle: 'Maneesha Fashion - Premium Women\'s Clothing & Bespoke Fashion in Sri Lanka',
  ogDescription: 'Shop premium quality sarees, lehengas, bespoke frocks, and custom-tailored dresses online in Sri Lanka. Experience perfect fits and elegant styles at Maneesha Fashion.',
  ogImage: '/images/hero-banner.jpg',
  ogUrl: 'https://maneesha.posmasters.lk/',
  ogType: 'website',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Maneesha Fashion - Premium Women\'s Clothing in Sri Lanka',
  twitterDescription: 'Discover the latest trends in women\'s fashion at Maneesha Fashion. Shop premium sarees, lehengas, and bespoke frocks online.',
  twitterImage: '/images/hero-banner.jpg'
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

const sliderImages = [
  { img: '1.jpeg', subtitle: 'New Season', titleTop: 'TIMELESS', titleBottom: 'ELEGANCE', desc: 'Discover chic styles and premium quality crafted for the modern woman.' },
  { img: '2.webp', subtitle: 'Latest Trends', titleTop: 'MODERN', titleBottom: 'CLASSICS', desc: 'Elevate your everyday wardrobe with our exclusive new arrivals.' },
  { img: '3.webp', subtitle: 'Bespoke Fit', titleTop: 'PERFECT', titleBottom: 'SILHOUETTE', desc: 'Custom-tailored fashion designed to celebrate your unique shape.' },
  { img: '4.webp', subtitle: 'Luxury Wear', titleTop: 'BOLD', titleBottom: 'STATEMENTS', desc: 'Make an impression with our meticulously crafted premium pieces.' },
  { img: '5.webp', subtitle: 'Signature Collection', titleTop: 'FLAWLESS', titleBottom: 'BEAUTY', desc: 'Step out in confidence with our signature dresses and frocks.' }
]
const currentHeroSlide = ref(0)
let heroSlideInterval = null

const currentStorySlide = ref(0)
let storySlideInterval = null

const startHeroSlider = () => {
  if (heroSlideInterval) clearInterval(heroSlideInterval)
  heroSlideInterval = setInterval(() => {
    currentHeroSlide.value = (currentHeroSlide.value + 1) % sliderImages.length
  }, 5000)
}

const prevHeroSlide = () => {
  currentHeroSlide.value = (currentHeroSlide.value - 1 + sliderImages.length) % sliderImages.length
  startHeroSlider()
}

const nextHeroSlide = () => {
  currentHeroSlide.value = (currentHeroSlide.value + 1) % sliderImages.length
  startHeroSlider()
}

const startStorySlider = () => {
  if (storySlideInterval) clearInterval(storySlideInterval)
  storySlideInterval = setInterval(() => {
    if (popularProducts.value.length > 0) {
      currentStorySlide.value = (currentStorySlide.value + 1) % popularProducts.value.length
    }
  }, 3500)
}

const categoriesGridRef = ref(null)
let categoryScrollInterval = null

const scrollCategories = (amount) => {
  if (categoriesGridRef.value) {
    categoriesGridRef.value.scrollBy({ left: amount, behavior: 'smooth' })
  }
}

const startCategoryScroll = () => {
  if (categoryScrollInterval) clearInterval(categoryScrollInterval)
  categoryScrollInterval = setInterval(() => {
    if (categoriesGridRef.value) {
      const { scrollLeft, scrollWidth, clientWidth } = categoriesGridRef.value
      if (scrollLeft + clientWidth >= scrollWidth - 10) {
        categoriesGridRef.value.scrollTo({ left: 0, behavior: 'smooth' })
      } else {
        categoriesGridRef.value.scrollBy({ left: 265, behavior: 'smooth' })
      }
    }
  }, 3000)
}

const pauseCategoryScroll = () => {
  if (categoryScrollInterval) clearInterval(categoryScrollInterval)
}

const resumeCategoryScroll = () => {
  startCategoryScroll()
}

const navigateToCategory = (slug) => {
  router.push(`/shop?category=${slug}`)
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const getTotalStock = (prod) => {
  if (prod.variants && prod.variants.length > 0) {
    return prod.variants.reduce((sum, v) => sum + v.stock, 0)
  }
  return prod.stock || 0
}

onMounted(() => {
  startHeroSlider()
  startStorySlider()
  startCategoryScroll()
})

onUnmounted(() => {
  if (heroSlideInterval) clearInterval(heroSlideInterval)
  if (storySlideInterval) clearInterval(storySlideInterval)
  if (categoryScrollInterval) clearInterval(categoryScrollInterval)
})
</script>

<style scoped>
/* Hero Styles */
.hero-section {
  position: relative;
  min-height: 80vh;
  margin: 0;
  overflow: hidden;
  display: flex;
  align-items: center;
  margin-bottom: 60px;
}

.hero-slider-bg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.hero-slide-item {
  position: absolute !important;
  top: 0 !important;
  left: 0 !important;
  width: 100% !important;
  height: 100% !important;
}

.slider-bg-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center 15%;
}

.hero-content-overlay {
  position: absolute;
  inset: 0;
  z-index: 10;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: flex-start;
}

.hero-content-box {
  background: transparent;
  padding: 0;
  max-width: 600px;
  border: none;
  box-shadow: none;
  backdrop-filter: none;
  margin-left: 0;
}

body.dark-mode .hero-content-box {
  background: transparent;
  border: none;
}

.hero-subtitle {
  font-family: 'Great Vibes', cursive;
  font-size: 3.5rem;
  color: var(--primary-gold);
  font-weight: 400;
  display: inline-block;
  margin-bottom: 0px;
  text-transform: none;
  letter-spacing: normal;
  text-shadow: 2px 2px 10px rgba(0,0,0,0.4);
}

.hero-content-box h1 {
  font-family: 'Playfair Display', serif;
  font-size: 5rem;
  line-height: 1.1;
  margin-bottom: 25px;
  text-transform: uppercase;
}

.hero-content-box h1 .dark-text {
  color: #2c2c2c;
  display: block;
  text-shadow: 2px 2px 12px rgba(255,255,255,0.7);
}

.hero-content-box h1 .pink-text {
  color: var(--primary-gold);
  display: block;
  text-shadow: 2px 2px 10px rgba(0,0,0,0.4);
}

body.dark-mode .hero-content-box h1 .dark-text {
  color: #f1f1f1;
}

.hero-desc {
  font-size: 1.1rem;
  color: #4a4a4a;
  margin-bottom: 35px;
  max-width: 400px;
  font-weight: 600;
  text-shadow: 1px 1px 8px rgba(255,255,255,0.8);
}

body.dark-mode .hero-desc {
  color: #b0b0b0;
}

.hero-ctas {
  display: flex;
}

.btn-shop-now {
  background: var(--primary-gold);
  color: #fff;
  padding: 15px 40px;
  font-weight: 600;
  letter-spacing: 2px;
  transition: all 0.3s ease;
  display: inline-block;
}

.btn-shop-now:hover {
  background: var(--primary-gold-dark);
  transform: translateY(-2px);
}

/* Slider Controls */
.slider-nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #fff;
  border: none;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  color: #333;
  cursor: pointer;
  z-index: 20;
  transition: all 0.3s ease;
}

.slider-nav-btn:hover {
  background: #f1f1f1;
}

.prev-btn {
  left: 30px;
}

.next-btn {
  right: 30px;
}

.slider-pagination {
  position: absolute;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 20;
}

.slider-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  transition: all 0.3s ease;
}

.slider-dot.active {
  background: var(--primary-gold);
}

/* Features Banner */
.features-banner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 30px 0;
  border-bottom: 1px solid rgba(0,0,0,0.05);
  margin-bottom: 60px;
  flex-wrap: wrap;
  gap: 20px;
}

body.dark-mode .features-banner {
  border-bottom: 1px solid rgba(255,255,255,0.05);
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 15px;
}

.feature-icon {
  font-size: 2.2rem;
  color: #f472b6;
}

.feature-text h4 {
  font-size: 0.9rem;
  font-weight: 700;
  margin-bottom: 2px;
  color: var(--text-dark-primary);
}

body.dark-mode .feature-text h4 {
  color: var(--text-light-primary);
}

.feature-text p {
  font-size: 0.85rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .feature-text p {
  color: var(--text-light-secondary);
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

.hero-fade-enter-active,
.hero-fade-leave-active {
  transition: opacity 1.5s ease-in-out;
}

.hero-fade-enter-from,
.hero-fade-leave-to {
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

/* Category Slider Wrapper */
.category-slider-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.cat-nav-btn {
  position: absolute;
  top: calc(50% - 40px);
  transform: translateY(-50%);
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: #fff;
  border: 1px solid rgba(212, 175, 55, 0.3);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  color: var(--primary-gold);
  cursor: pointer;
  z-index: 20;
  transition: all 0.3s ease;
}

body.dark-mode .cat-nav-btn {
  background: #1e293b;
  border-color: rgba(212, 175, 55, 0.2);
}

.cat-nav-btn:hover {
  background: var(--primary-gold);
  color: #fff;
}

.cat-prev { left: -20px; }
.cat-next { right: -20px; }

/* Category Grid Slider */
.categories-grid {
  display: flex;
  overflow-x: auto;
  gap: 25px;
  padding-bottom: 20px;
  margin-bottom: 80px;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  width: 100%;
  scrollbar-width: none; /* Firefox */
}

.categories-grid::-webkit-scrollbar {
  display: none; /* Hide standard scrollbar for cleaner look */
}

.category-card {
  flex: 0 0 240px;
  scroll-snap-align: start;
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
    min-height: 600px; /* Fixed collapsing slider height on tablet */
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
  .hero-section {
    min-height: 500px;
    padding: 0;
  }

  .hero-subtitle {
    font-size: 2.5rem;
  }

  .hero-content-box h1 {
    font-size: 3rem;
  }
  
  .hero-desc {
    font-size: 1rem;
    margin-bottom: 25px;
  }
  
  .hero-ctas {
    flex-direction: column;
    width: 100%;
    gap: 15px;
  }
  
  .btn-shop-now {
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
  object-position: center 10%;
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

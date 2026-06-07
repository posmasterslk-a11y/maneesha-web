<template>
  <div v-if="product" class="product-detail-page container">
    <div class="breadcrumb">
      <NuxtLink to="/">Home</NuxtLink> <i class="fa-solid fa-angle-right"></i> 
      <NuxtLink to="/shop">Shop</NuxtLink> <i class="fa-solid fa-angle-right"></i> 
      <span>{{ product.name }}</span>
    </div>

    <div class="detail-grid">
      <!-- Left Visual Preview -->
      <div class="product-gallery glass-panel">
        <div class="main-preview" :style="{ background: product.colorTheme }">
          <img v-if="currentImage" :src="currentImage" :alt="product.name" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);" />
          <i v-else class="fa-solid fa-shirt"></i>
        </div>
        
        <!-- Thumbnails -->
        <div v-if="allImages.length > 1" class="gallery-thumbnails">
          <div 
            v-for="(img, idx) in allImages" 
            :key="idx" 
            :class="['thumbnail', { active: currentImage === img }]"
            @click="setCurrentImage(img)"
          >
            <img :src="img" :alt="product.name + ' ' + idx" />
          </div>
        </div>

        <div class="preview-highlights">
          <div class="hl-item"><i class="fa-solid fa-gem"></i> Premium Quality</div>
          <div class="hl-item"><i class="fa-solid fa-sheet-plastic"></i> Best Fabrics</div>
          <div class="hl-item"><i class="fa-solid fa-award"></i> Guaranteed</div>
        </div>
      </div>

      <!-- Right Selection Details -->
      <div class="product-config glass-panel">
        <span class="category-tag">{{ product.category_name }}</span>
        <h1 class="luxury-title">{{ product.name }}</h1>
        
        <!-- Live dynamic price projection based on active selection -->
        <div class="price-container">
          <span class="price-label">Price for selected size:</span>
          <span class="active-price">LKR {{ formatNumber(activePrice) }}</span>
        </div>

        <p class="desc">{{ product.description || product.short_description }}</p>

        <!-- Size Variant Selector -->
        <div class="config-section">
          <h4 class="config-label">1. Select Fitting Size:</h4>
          <div class="sizes-grid">
            <button 
              v-for="varnt in product.variants" 
              :key="varnt.id" 
              :class="['size-btn', { active: selectedSize === varnt.size }]"
              @click="selectSize(varnt)"
            >
              <span class="size-name">{{ varnt.size }}</span>
              <span class="price-mod" v-if="varnt.price > product.base_price">+ LKR {{ varnt.price - product.base_price }}</span>
            </button>
          </div>
        </div>



        <!-- Quantity & Buy Now -->
        <div class="action-section" v-if="activeStock > 0">
          <div class="qty-counter">
            <button @click="changeQty(-1)" class="qty-btn" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
            <span class="qty-num">{{ quantity }}</span>
            <button @click="changeQty(1)" class="qty-btn" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
          </div>

          <button @click="handleAddToCart" class="btn-premium flex-1" style="background: transparent; border: 2px solid var(--primary-gold); color: inherit;">
            <i class="fa-solid fa-cart-plus mr-2"></i> Add to Cart
          </button>

          <button @click="handleBuyNow" class="btn-premium btn-gold flex-1">
            <i class="fa-solid fa-bolt mr-2"></i> Buy Now
          </button>
        </div>

        <!-- Stock indicators -->
        <div v-if="activeStock > 0" class="stock-info">
          <i class="fa-solid fa-circle-check text-success"></i>
          <span>{{ activeStock }} In stock. Orders dispatched within 3-4 working days.</span>
        </div>
        <div v-else class="stock-info" style="color: var(--accent-error);">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span style="font-weight: 700;">Out of Stock!</span>
        </div>
      </div>
    </div>

    <!-- Custom Notification Dialog -->
    <div :class="['modal-overlay', { open: notifyDialog.isOpen }]" @click.self="closeNotify">
      <div class="notify-modal">
        <div class="modal-body text-center py-4">
          <i v-if="notifyDialog.type === 'success'" class="fa-solid fa-circle-check text-success mb-3" style="font-size: 3rem;"></i>
          <i v-else class="fa-solid fa-circle-xmark text-danger mb-3" style="font-size: 3rem;"></i>
          
          <h4 class="luxury-title mb-2">{{ notifyDialog.title }}</h4>
          <p class="mb-4">{{ notifyDialog.message }}</p>
          <div class="flex justify-center">
            <button @click="closeNotify" class="btn-premium btn-gold">Okay</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()

const addToCart = inject('addToCart')

const slugParam = route.params.slug

// Fetch product data using SSR
const { data: productData, error } = await useFetch(`${config.public.apiBase}/products/${slugParam}`)

if (error.value || !productData.value) {
  if (process.client) {
    router.push('/shop')
  }
}

const product = ref(productData.value)
const loading = ref(false)

if (product.value) {
  product.value.colorTheme = product.value.colorTheme || 'linear-gradient(135deg, #2b2d42 0%, #8d99ae 100%)'
  
  // Set SEO tags for the product page
  useHead({
    title: `${product.value.name} - Maneesha Fashion`,
    meta: [
      { name: 'description', content: product.value.short_description || product.value.description },
      { property: 'og:title', content: `${product.value.name} - Maneesha Fashion` },
      { property: 'og:description', content: product.value.short_description || product.value.description },
      { property: 'og:image', content: product.value.main_image }
    ]
  })
}

const selectedSize = ref('')
if (product.value?.variants && product.value.variants.length > 0) {
  selectedSize.value = product.value.variants[0].size
}

const quantity = ref(1)

const activePrice = computed(() => {
  if (!product.value) return 0
  const variant = product.value.variants?.find(v => v.size === selectedSize.value)
  return variant && Number(variant.price) > 0 ? Number(variant.price) : Number(product.value.base_price)
})

const activeStock = computed(() => {
  if (!product.value) return 0
  const variant = product.value.variants?.find(v => v.size === selectedSize.value)
  return variant ? variant.stock : product.value.stock
})

const selectSize = (variant) => {
  selectedSize.value = variant.size
  const newStock = variant.stock !== undefined ? variant.stock : product.value.stock
  if (quantity.value > newStock) {
    quantity.value = newStock > 0 ? newStock : 1
  }
}

const changeQty = (val) => {
  const next = quantity.value + val
  if (next >= 1 && next <= activeStock.value) quantity.value = next
}

const notifyDialog = ref({
  isOpen: false,
  type: 'success', 
  title: '',
  message: '',
  onClose: null
})

const openNotify = (type, title, message, onClose = null) => {
  notifyDialog.value = { isOpen: true, type, title, message, onClose }
}

const closeNotify = () => {
  if (notifyDialog.value.onClose) {
    notifyDialog.value.onClose()
  }
  notifyDialog.value.isOpen = false
}

const handleAddToCart = () => {
  addToCart(product.value, selectedSize.value, activePrice.value, quantity.value, null)
  openNotify('success', 'Added to Cart', `${product.value.name} has been added to your cart successfully.`)
}

const handleBuyNow = () => {
  addToCart(product.value, selectedSize.value, activePrice.value, quantity.value, null)
  router.push('/checkout')
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Gallery logic
const currentImage = ref(product.value?.main_image || '')
const allImages = computed(() => {
  if (!product.value) return []
  const imgs = []
  if (product.value.main_image) imgs.push(product.value.main_image)
  if (product.value.gallery_images && Array.isArray(product.value.gallery_images)) {
    product.value.gallery_images.forEach(img => {
      imgs.push(img.url)
    })
  }
  return imgs
})

const setCurrentImage = (imgUrl) => {
  currentImage.value = imgUrl
}
</script>

<style scoped>
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85rem;
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .breadcrumb {
  color: var(--text-light-secondary);
}

.detail-grid {
  display: grid;
  grid-template-columns: 1.1fr 1.2fr;
  gap: 40px;
  align-items: start;
}

.product-gallery {
  padding: 24px;
}

.main-preview {
  aspect-ratio: 4/5;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 8rem;
  color: rgba(255, 255, 255, 0.45);
  box-shadow: inset 0 0 50px rgba(0,0,0,0.1);
}

.gallery-thumbnails {
  display: flex;
  gap: 12px;
  margin-top: 20px;
  overflow-x: auto;
  padding-bottom: 5px;
}

.gallery-thumbnails .thumbnail {
  width: 70px;
  height: 90px;
  border-radius: 8px;
  border: 2px solid transparent;
  cursor: pointer;
  overflow: hidden;
  opacity: 0.7;
  transition: all 0.2s;
  flex-shrink: 0;
}

.gallery-thumbnails .thumbnail:hover {
  opacity: 1;
}

.gallery-thumbnails .thumbnail.active {
  opacity: 1;
  border-color: var(--primary-gold);
}

.gallery-thumbnails .thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.preview-highlights {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-top: 20px;
}

.preview-highlights .hl-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
  text-align: center;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--text-dark-secondary);
}

body.dark-mode .preview-highlights .hl-item {
  color: var(--text-light-secondary);
}

.preview-highlights .hl-item i {
  font-size: 1.2rem;
  color: var(--primary-gold);
}

/* Config Section */
.product-config {
  padding: 40px;
}

.category-tag {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--primary-gold);
  margin-bottom: 8px;
  display: inline-block;
}

.product-config h1 {
  font-size: 2.4rem;
  margin-bottom: 20px;
}

.price-container {
  display: flex;
  flex-direction: column;
  background: rgba(212,175,55,0.06);
  border-left: 3px solid var(--primary-gold);
  padding: 15px 20px;
  border-radius: 0 var(--radius-md) var(--radius-md) 0;
  margin-bottom: 25px;
}

.price-label {
  font-size: 0.75rem;
  color: var(--text-dark-secondary);
  text-transform: uppercase;
  font-weight: 600;
}

body.dark-mode .price-label {
  color: var(--text-light-secondary);
}

.active-price {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text-dark-primary);
}

body.dark-mode .active-price {
  color: var(--text-light-primary);
}

.desc {
  font-size: 0.95rem;
  color: var(--text-dark-secondary);
  margin-bottom: 35px;
}

body.dark-mode .desc {
  color: var(--text-light-secondary);
}

.config-section {
  margin-bottom: 30px;
}

.config-label {
  font-size: 0.9rem;
  font-weight: 700;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.sizes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 12px;
}

.size-btn {
  background: transparent;
  border: 1px solid var(--bg-light-border);
  color: var(--text-dark-primary);
  border-radius: var(--radius-md);
  padding: 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  cursor: pointer;
  transition: var(--transition-smooth);
}

body.dark-mode .size-btn {
  border: 1px solid var(--bg-dark-border);
  color: var(--text-light-primary);
}

.size-btn:hover {
  border-color: var(--primary-gold);
}

.size-btn.active {
  border-color: var(--primary-gold);
  background: rgba(212, 175, 55, 0.05);
  box-shadow: 0 0 0 2px var(--primary-gold);
}

.size-name {
  font-weight: 700;
  font-size: 0.95rem;
}

.price-mod {
  font-size: 0.7rem;
  color: var(--primary-gold-dark);
  font-weight: 600;
}

/* Custom Stitching Form */
.custom-stitch-form {
  background: rgba(255, 255, 255, 0.5);
  border: 1px solid var(--primary-gold-light);
  padding: 24px;
  border-radius: var(--radius-md);
  margin-bottom: 30px;
}

body.dark-mode .custom-stitch-form {
  background: rgba(0,0,0,0.15);
}

.form-title {
  display: flex;
  gap: 15px;
  align-items: flex-start;
  margin-bottom: 20px;
}

.form-title i {
  font-size: 1.4rem;
  color: var(--primary-gold);
  margin-top: 3px;
}

.form-title h5 {
  font-size: 0.95rem;
  font-weight: 700;
}

.form-title p {
  font-size: 0.75rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .form-title p {
  color: var(--text-light-secondary);
}

.form-inputs-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.form-inputs-row .form-group {
  margin-bottom: 0;
}

/* Quantity & Action Section */
.action-section {
  display: flex;
  gap: 20px;
  margin-bottom: 25px;
}

.qty-counter {
  display: flex;
  align-items: center;
  border: 1px solid var(--bg-light-border);
  border-radius: var(--radius-md);
  background: var(--bg-light-surface);
}

body.dark-mode .qty-counter {
  border: 1px solid var(--bg-dark-border);
  background: var(--bg-dark-surface);
}

.qty-btn {
  background: transparent;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  color: inherit;
  cursor: pointer;
  transition: var(--transition-smooth);
}

.qty-btn:hover {
  color: var(--primary-gold);
}

.qty-num {
  width: 30px;
  text-align: center;
  font-weight: 700;
}

.flex-1 {
  flex: 1;
}

.mr-2 {
  margin-right: 8px;
}

.stock-info {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.8rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .stock-info {
  color: var(--text-light-secondary);
}

.text-success {
  color: var(--accent-success);
}

/* Responsive adjustments */
@media (max-width: 991px) {
  .detail-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .product-config {
    padding: 24px;
  }
  
  .form-inputs-row {
    grid-template-columns: 1fr;
    gap: 12px;
  }
}

/* Modal Styling */
.modal-overlay {
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(5px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}
.modal-overlay.open {
  opacity: 1;
  visibility: visible;
}
.notify-modal {
  background: var(--bg-light);
  border: 1px solid var(--border-light);
  width: 90%;
  max-width: 450px;
  border-radius: var(--radius-lg);
  padding: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  transform: translateY(20px);
  transition: transform 0.3s ease;
}
body.dark-mode .notify-modal {
  background: var(--bg-dark-card);
  border-color: var(--border-dark);
}
.modal-overlay.open .notify-modal {
  transform: translateY(0);
}
.text-success { color: #10b981; }
.text-danger { color: #ef4444; }
.py-4 { padding-top: 30px; padding-bottom: 30px; }
.mb-2 { margin-bottom: 10px; }
.mb-3 { margin-bottom: 15px; }
.mb-4 { margin-bottom: 25px; }
.flex { display: flex; }
.justify-center { justify-content: center; }
</style>

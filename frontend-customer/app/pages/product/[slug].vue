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
          <img v-if="product.main_image" :src="product.main_image" :alt="product.name" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);" />
          <i v-else class="fa-solid fa-shirt"></i>
        </div>
        <div class="preview-highlights">
          <div class="hl-item"><i class="fa-solid fa-scissors"></i> Hand Cut</div>
          <div class="hl-item"><i class="fa-solid fa-sheet-plastic"></i> Premium Linens</div>
          <div class="hl-item"><i class="fa-solid fa-award"></i> Quality Stitched</div>
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
        <div class="action-section">
          <div class="qty-counter">
            <button @click="changeQty(-1)" class="qty-btn" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
            <span class="qty-num">{{ quantity }}</span>
            <button @click="changeQty(1)" class="qty-btn" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
          </div>

          <button @click="handleBuyNow" class="btn-premium btn-gold flex-1">
            <i class="fa-solid fa-bolt mr-2"></i> Buy Now
          </button>
        </div>

        <!-- Stock indicators -->
        <div class="stock-info">
          <i class="fa-solid fa-circle-check text-success"></i>
          <span>Ready to sew. Orders dispatched within 3-4 working days.</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, inject, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const addToCart = inject('addToCart')

const product = ref(null)
const selectedSize = ref('')
const quantity = ref(1)
const loading = ref(true)

const activePrice = computed(() => {
  if (!product.value) return 0
  const variant = product.value.variants?.find(v => v.size === selectedSize.value)
  return variant ? variant.price : product.value.base_price
})

const selectSize = (variant) => {
  selectedSize.value = variant.size
}

const changeQty = (val) => {
  const next = quantity.value + val
  if (next >= 1 && next <= 10) quantity.value = next
}

const handleBuyNow = () => {
  addToCart(product.value, selectedSize.value, activePrice.value, quantity.value, null)
  router.push('/checkout')
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

onMounted(async () => {
  const slugParam = route.params.slug
  try {
    const res = await fetch(`http://localhost:8000/api/products/${slugParam}`)
    if (res.ok) {
      const data = await res.json()
      product.value = data
      if (data.variants && data.variants.length > 0) {
        selectedSize.value = data.variants[0].size
      }
      product.value.colorTheme = product.value.colorTheme || 'linear-gradient(135deg, #2b2d42 0%, #8d99ae 100%)'
    } else {
      router.push('/shop')
    }
  } catch (error) {
    console.error(error)
    router.push('/shop')
  } finally {
    loading.value = false
  }
})
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
</style>

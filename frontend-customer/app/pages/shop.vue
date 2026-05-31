<template>
  <div class="shop-page container">
    <div class="shop-header">
      <h1 class="luxury-title">The <span class="gold-gradient-text">Collection</span></h1>
      <p>Select your favorite design. We will cut and stitch it perfectly to fit your shape.</p>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="toolbar glass-panel">
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" v-model="searchQuery" placeholder="Search clothes, blouses..." class="search-input" />
      </div>

      <div class="category-filters">
        <button
          :class="['filter-btn', { active: activeCategory === 'all' }]"
          @click="setCategory('all')"
        >All</button>
        <button
          v-for="cat in categories"
          :key="cat.id"
          :class="['filter-btn', { active: activeCategory === cat.slug }]"
          @click="setCategory(cat.slug)"
        >
          {{ cat.name }}
        </button>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-grid">
      <div v-for="n in 6" :key="n" class="skeleton-card glass-panel"></div>
    </div>

    <!-- Products Catalog Grid -->
    <div v-else-if="products.length > 0" class="catalog-grid">
      <div v-for="prod in products" :key="prod.id" class="product-card glass-panel">
        <div class="product-img-wrapper">
          <div class="product-tag" v-if="prod.is_featured">Featured</div>
          <img v-if="prod.main_image" :src="prod.main_image" :alt="prod.name" class="product-real-img" />
          <div v-else class="product-visual-placeholder">
            <i class="fa-solid fa-shirt"></i>
          </div>
          <div class="hover-overlay">
            <NuxtLink :to="`/product/${prod.slug}`" class="btn-premium quick-view-btn">Add to Cart</NuxtLink>
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
    
    <!-- Load More -->
    <div class="load-more-container" v-if="currentPage < lastPage">
      <button @click="loadMore" class="btn-premium btn-gold mt-4" :disabled="loadingMore">
        {{ loadingMore ? 'Loading...' : 'Load More' }}
      </button>
    </div>

    <div v-else class="empty-catalog glass-panel">
      <i class="fa-solid fa-folder-open"></i>
      <h3>No items found matching your filters.</h3>
      <p>Try clearing your search query or selecting another clothing category.</p>
      <button @click="resetFilters" class="btn-premium btn-gold mt-4">Reset Filters</button>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

const API = 'http://localhost:8000/api'
const route = useRoute()

const searchQuery    = ref('')
const activeCategory = ref('all')
const products       = ref([])
const categories     = ref([])
const loading        = ref(true)
const loadingMore    = ref(false)
const currentPage    = ref(1)
const lastPage       = ref(1)

// Fetch categories for filter buttons
const fetchCategories = async () => {
  try {
    const r = await fetch(`${API}/categories`)
    categories.value = await r.json()
  } catch (e) { console.error(e) }
}

// Fetch products (with optional category filter)
const fetchProducts = async (page = 1, append = false) => {
  if (append) {
    loadingMore.value = true
  } else {
    loading.value = true
  }
  
  try {
    let url = `${API}/products`
    const params = new URLSearchParams()
    if (activeCategory.value !== 'all') params.set('category', activeCategory.value)
    if (searchQuery.value.trim())        params.set('search', searchQuery.value.trim())
    params.set('page', page)
    if (params.toString())               url += '?' + params.toString()

    const r = await fetch(url)
    const data = await r.json()
    
    if (append) {
      products.value = [...products.value, ...(data.data || data)]
    } else {
      products.value = data.data || data
    }
    
    currentPage.value = data.current_page || 1
    lastPage.value = data.last_page || 1
    
  } catch (e) { console.error(e) }
  
  loading.value = false
  loadingMore.value = false
}

const loadMore = () => {
  if (currentPage.value < lastPage.value) {
    fetchProducts(currentPage.value + 1, true)
  }
}

const setCategory = (slug) => {
  activeCategory.value = slug
}

const resetFilters = () => {
  searchQuery.value    = ''
  activeCategory.value = 'all'
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

// Debounce search to avoid too many requests
let searchTimer = null
watch(searchQuery, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchProducts(1, false), 400)
})

watch(activeCategory, () => fetchProducts(1, false))

onMounted(async () => {
  await fetchCategories()
  // Pre-select category from URL param
  if (route.query.category) {
    activeCategory.value = route.query.category.toString()
  }
  await fetchProducts(1, false)
})
</script>

<style scoped>
.shop-header {
  text-align: center;
  margin-bottom: 50px;
}

.shop-header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
}

.shop-header p {
  color: var(--text-dark-secondary);
}

body.dark-mode .shop-header p {
  color: var(--text-light-secondary);
}

/* Toolbar Filters */
.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  gap: 20px;
  margin-bottom: 40px;
}

.search-box {
  display: flex;
  align-items: center;
  background: var(--bg-light);
  border: 1px solid var(--bg-light-border);
  padding: 10px 18px;
  border-radius: var(--radius-md);
  flex: 1;
  max-width: 400px;
}

body.dark-mode .search-box {
  background: var(--bg-dark);
  border: 1px solid var(--bg-dark-border);
}

.search-box i {
  color: var(--text-dark-secondary);
  margin-right: 12px;
}

body.dark-mode .search-box i {
  color: var(--text-light-secondary);
}

.search-input {
  background: transparent;
  width: 100%;
  color: inherit;
  font-size: 0.9rem;
}

.category-filters {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.filter-btn {
  background: transparent;
  color: var(--text-dark-primary);
  border: 1px solid var(--bg-light-border);
  padding: 10px 20px;
  font-size: 0.85rem;
  font-weight: 600;
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: var(--transition-smooth);
}

body.dark-mode .filter-btn {
  color: var(--text-light-primary);
  border: 1px solid var(--bg-dark-border);
}

.filter-btn:hover {
  border-color: var(--primary-gold);
  color: var(--primary-gold);
}

.filter-btn.active {
  background: var(--primary-gold);
  color: #fff;
  border-color: var(--primary-gold);
  box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
}

/* Catalog Grid */
.catalog-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
}

/* Empty State */
.empty-catalog {
  padding: 80px 40px;
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.empty-catalog i {
  font-size: 4rem;
  color: var(--primary-gold);
  margin-bottom: 25px;
}

.empty-catalog h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
}

.empty-catalog p {
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .empty-catalog p {
  color: var(--text-light-secondary);
}

.mt-4 {
  margin-top: 20px;
}

/* Real product image */
.product-real-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.product-card:hover .product-real-img {
  transform: scale(1.05);
}

/* Loading skeleton */
.loading-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
}
.skeleton-card {
  height: 380px;
  animation: shimmer 1.5s infinite;
  background: linear-gradient(90deg, rgba(255,255,255,0.03) 25%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0.03) 75%);
  background-size: 200% 100%;
}
@keyframes shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
.load-more-container {
  display: flex;
  justify-content: center;
  margin-top: 40px;
}
</style>

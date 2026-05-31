<template>
  <div class="admin-products">
    <div class="header-row animate-fade-up">
      <h3 class="luxury-title">Apparel Catalog</h3>
      <button @click="openAddModal" class="btn-admin"><i class="fa-solid fa-plus mr-2"></i> Add New Product</button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-state">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading products...
    </div>

    <!-- Products Grid -->
    <div v-else-if="products.length > 0" class="products-dashboard-grid animate-fade-up">
      <div v-for="prod in products" :key="prod.id" class="product-crud-card glass-panel">
        <!-- Product Image -->
        <div class="card-preview">
          <img v-if="prod.main_image" :src="prod.main_image" :alt="prod.name" class="card-img" />
          <div v-else class="card-placeholder">
            <i class="fa-solid fa-shirt"></i>
          </div>
          <span class="featured-badge" v-if="prod.is_featured">⭐ Featured</span>
          <span class="inactive-badge" v-if="!prod.is_active">Hidden</span>
        </div>

        <div class="card-body">
          <span class="category-tag">{{ prod.category_name }}</span>
          <h4>{{ prod.name }}</h4>
          <p class="desc">{{ prod.short_description }}</p>

          <div class="price-stock-row">
            <span class="price-badge">LKR {{ formatNumber(prod.base_price) }}</span>
            <span class="stock-badge">Stock: {{ prod.stock }}</span>
          </div>

          <div class="sizing-variants-preview" v-if="prod.variants?.length">
            <h5>Size Variants ({{ prod.variants.length }})</h5>
            <div v-for="v in prod.variants" :key="v.id" class="variant-row">
              <span><strong>{{ v.size }}</strong></span>
              <span>LKR {{ formatNumber(v.price) }}</span>
              <span>Qty: {{ v.stock }}</span>
            </div>
          </div>

          <div class="card-actions">
            <button @click="editProduct(prod)" class="btn-admin btn-admin-secondary"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
            <button @click="deleteProduct(prod.id)" class="btn-admin btn-admin-danger"><i class="fa-regular fa-trash-can"></i> Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="empty-state glass-panel">
      <i class="fa-solid fa-box-open"></i>
      <p>No products yet. Add your first product!</p>
    </div>

    <AdminPagination 
      v-if="products.length > 0"
      :current-page="currentPage" 
      :last-page="lastPage" 
      @page-change="fetchProducts" 
    />

    <!-- Add/Edit Modal -->
    <div :class="['modal-overlay', { open: isModalOpen }]" @click.self="closeModal">
      <div class="pm-modal">

        <!-- Left Panel: Image Upload -->
        <div class="pm-left">
          <div class="pm-image-area" @click="triggerFileInput">
            <img v-if="imagePreview" :src="imagePreview" alt="Preview" class="pm-preview-img" />
            <img v-else-if="formData.main_image" :src="formData.main_image" alt="Current" class="pm-preview-img" />
            <div v-else class="pm-upload-prompt">
              <div class="pm-upload-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
              </div>
              <p>Drop your image here</p>
              <small>JPG, PNG, WEBP · Max 5MB</small>
            </div>
            <div class="pm-image-overlay">
              <i class="fa-solid fa-camera"></i>
              <span>Change Photo</span>
            </div>
          </div>
          <input ref="fileInput" type="file" accept="image/*" @change="onImageChange" style="display:none" />

          <!-- Toggle Switches -->
          <div class="pm-toggles">
            <label class="pm-toggle-item">
              <div class="pm-toggle-label">
                <i class="fa-solid fa-eye"></i>
                <span>Active on Store</span>
              </div>
              <div class="pm-switch" :class="{ on: formData.is_active }" @click="formData.is_active = !formData.is_active">
                <div class="pm-switch-thumb"></div>
              </div>
            </label>
            <label class="pm-toggle-item">
              <div class="pm-toggle-label">
                <i class="fa-solid fa-star"></i>
                <span>Featured</span>
              </div>
              <div class="pm-switch" :class="{ on: formData.is_featured }" @click="formData.is_featured = !formData.is_featured">
                <div class="pm-switch-thumb"></div>
              </div>
            </label>
            <label class="pm-toggle-item">
              <div class="pm-toggle-label">
                <i class="fa-solid fa-images"></i>
                <span>Hero Slider</span>
              </div>
              <div class="pm-switch" :class="{ on: formData.in_hero_slider }" @click="formData.in_hero_slider = !formData.in_hero_slider">
                <div class="pm-switch-thumb"></div>
              </div>
            </label>
          </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="pm-right">
          <div class="pm-header">
            <div>
              <p class="pm-header-label">{{ isEditing ? 'Editing Product' : 'New Product' }}</p>
              <h4 class="pm-title">{{ isEditing ? formData.name || 'Edit Product' : 'Add New Product' }}</h4>
            </div>
            <button @click="closeModal" class="pm-close-btn"><i class="fa-solid fa-xmark"></i></button>
          </div>

          <form @submit.prevent="saveProduct" enctype="multipart/form-data" style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
            <div class="pm-form-scroll">

              <div class="pm-section-label">Basic Info</div>
              <div class="form-grid-2">
                <div class="form-group">
                  <label class="form-label">Product Name *</label>
                  <input type="text" v-model="formData.name" class="form-input" required placeholder="e.g. Silk Georgette Saree" />
                </div>
                <div class="form-group">
                  <label class="form-label">Category *</label>
                  <select v-model="formData.category_id" class="form-input" required>
                    <option value="">— Select Category —</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  </select>
                </div>
              </div>

              <div class="form-grid-2">
                <div class="form-group">
                  <label class="form-label">Base Price (LKR) *</label>
                  <div class="input-prefix-wrap">
                    <span class="input-prefix">Rs.</span>
                    <input type="number" v-model.number="formData.base_price" class="form-input has-prefix" required min="0" step="0.01" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Total Stock *</label>
                  <input type="number" v-model.number="formData.stock" class="form-input" required min="0" />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Short Description</label>
                <input type="text" v-model="formData.short_description" class="form-input" placeholder="One-line summary shown on cards" />
              </div>

              <div class="form-group">
                <label class="form-label">Full Description</label>
                <textarea v-model="formData.description" class="form-input" rows="2" placeholder="Detailed product description..."></textarea>
              </div>

              <div class="form-grid-2">
                <div class="form-group">
                  <label class="form-label">Fabric</label>
                  <input type="text" v-model="formData.fabric" class="form-input" placeholder="e.g. Silk, Cotton" />
                </div>
                <div class="form-group">
                  <label class="form-label">Care Instructions</label>
                  <input type="text" v-model="formData.care_instructions" class="form-input" placeholder="e.g. Dry clean only" />
                </div>
              </div>

              <!-- Size Variants -->
              <div class="pm-section-label mt-8">
                <i class="fa-solid fa-ruler-combined mr-1"></i> Size Variants & Pricing
                <button type="button" @click="addVariant" class="pm-add-size-btn">+ Add Size</button>
              </div>
              <div v-for="(v, idx) in formData.variants" :key="idx" class="vnt-row">
                <div class="form-group mb-0">
                  <label class="form-label mini">Size</label>
                  <input type="text" v-model="v.size" class="form-input mini" placeholder="e.g. M" required />
                </div>
                <div class="form-group mb-0">
                  <label class="form-label mini">Price (LKR)</label>
                  <input type="number" v-model.number="v.price" class="form-input mini" required min="0" />
                </div>
                <div class="form-group mb-0">
                  <label class="form-label mini">Stock</label>
                  <input type="number" v-model.number="v.stock" class="form-input mini" required min="0" />
                </div>
                <button type="button" @click="removeVariant(idx)" class="remove-vnt-btn"><i class="fa-solid fa-times"></i></button>
              </div>
              <p v-if="!formData.variants.length" class="no-variants-hint">No size variants added. Add at least one size.</p>

            </div>

            <div v-if="errorMsg" class="error-banner"><i class="fa-solid fa-triangle-exclamation"></i> {{ errorMsg }}</div>

            <div class="pm-footer">
              <button type="button" @click="closeModal" class="btn-admin btn-admin-secondary">Cancel</button>
              <button type="submit" :disabled="saving" class="btn-admin pm-save-btn">
                <span v-if="saving"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...</span>
                <span v-else><i class="fa-regular fa-floppy-disk mr-2"></i>Save Product</span>
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const API = 'http://localhost:8000/api'
const token = () => localStorage.getItem('maneesha-admin-token') || ''

const products    = ref([])
const categories  = ref([])
const currentPage = ref(1)
const lastPage    = ref(1)
const loading     = ref(true)
const saving      = ref(false)
const errorMsg    = ref('')

const isModalOpen = ref(false)
const isEditing   = ref(false)
const editingId   = ref(null)

const fileInput    = ref(null)
const imageFile    = ref(null)
const imagePreview = ref('')

const defaultVariants = () => [
  { size: 'XS',     price: 0, stock: 5  },
  { size: 'S',      price: 0, stock: 10 },
  { size: 'M',      price: 0, stock: 10 },
  { size: 'L',      price: 0, stock: 8  },
  { size: 'XL',     price: 0, stock: 5  },
  { size: 'XXL',    price: 0, stock: 3  },
  { size: 'Custom', price: 0, stock: 99 },
]

const formData = ref({
  name: '', category_id: '', base_price: 2000, stock: 0,
  short_description: '', description: '', fabric: '',
  care_instructions: '', is_active: true, is_featured: false, in_hero_slider: false,
  main_image: '', variants: defaultVariants()
})

// ── API Helpers ────────────────────────────────────────────────────────────
const authHeaders = () => ({
  'Authorization': `Bearer ${token()}`,
  'Accept': 'application/json',
})

const fetchProducts = async (page = 1) => {
  loading.value = true
  try {
    const r = await fetch(`${API}/admin/products?page=${page}`, { headers: authHeaders() })
    const data = await r.json()
    products.value = data.data
    currentPage.value = data.current_page
    lastPage.value = data.last_page
  } catch (e) {
    errorMsg.value = 'Failed to load products.'
  }
  loading.value = false
}

const fetchCategories = async () => {
  const r = await fetch(`${API}/admin/categories?all=true`, { headers: authHeaders() })
  categories.value = await r.json()
}

// ── Image Handling ─────────────────────────────────────────────────────────
const triggerFileInput = () => fileInput.value?.click()

const onImageChange = (e) => {
  const file = e.target.files[0]
  if (!file) return
  if (file.size > 5 * 1024 * 1024) {
    errorMsg.value = 'Image too large. Maximum 5MB allowed.'
    return
  }
  imageFile.value  = file
  imagePreview.value = URL.createObjectURL(file)
}

// ── Modal ──────────────────────────────────────────────────────────────────
const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  imageFile.value = null
  imagePreview.value = ''
  formData.value = {
    name: '', category_id: '', base_price: 2000, stock: 0,
    short_description: '', description: '', fabric: '',
    care_instructions: '', is_active: true, is_featured: false, in_hero_slider: false,
    main_image: '', variants: defaultVariants()
  }
  errorMsg.value = ''
  isModalOpen.value = true
}

const editProduct = (prod) => {
  isEditing.value = true
  editingId.value = prod.id
  imageFile.value = null
  imagePreview.value = ''
  formData.value = {
    name:              prod.name,
    category_id:       prod.category_id,
    base_price:        prod.base_price,
    stock:             prod.stock,
    short_description: prod.short_description || '',
    description:       prod.description || '',
    fabric:            prod.fabric || '',
    care_instructions: prod.care_instructions || '',
    is_active:         prod.is_active,
    is_featured:       prod.is_featured,
    in_hero_slider:    prod.in_hero_slider,
    main_image:        prod.main_image || '',
    variants: prod.variants?.length ? prod.variants.map(v => ({
      size: v.size, price: v.price, stock: v.stock
    })) : defaultVariants()
  }
  errorMsg.value = ''
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  imagePreview.value = ''
  imageFile.value = null
}

// ── Variants ───────────────────────────────────────────────────────────────
const addVariant    = () => formData.value.variants.push({ size: '', price: formData.value.base_price, stock: 5 })
const removeVariant = (idx) => formData.value.variants.splice(idx, 1)

// ── Save ───────────────────────────────────────────────────────────────────
const saveProduct = async () => {
  saving.value   = true
  errorMsg.value = ''

  try {
    // Build FormData for multipart (required for image upload)
    const fd = new FormData()
    fd.append('name',              formData.value.name)
    fd.append('category_id',       formData.value.category_id || '')
    fd.append('base_price',        formData.value.base_price)
    fd.append('stock',             formData.value.stock)
    fd.append('short_description', formData.value.short_description || '')
    fd.append('description',       formData.value.description || '')
    fd.append('fabric',            formData.value.fabric || '')
    fd.append('care_instructions', formData.value.care_instructions || '')
    fd.append('is_active',         formData.value.is_active ? '1' : '0')
    fd.append('is_featured',       formData.value.is_featured ? '1' : '0')
    fd.append('in_hero_slider',    formData.value.in_hero_slider ? '1' : '0')
    fd.append('variants',          JSON.stringify(formData.value.variants))

    if (imageFile.value) fd.append('main_image', imageFile.value)

    const url = isEditing.value
      ? `${API}/admin/products/${editingId.value}`
      : `${API}/admin/products`

    const r = await fetch(url, {
      method: 'POST', // Always POST — backend detects update via route
      headers: { 'Authorization': `Bearer ${token()}`, 'Accept': 'application/json' },
      body: fd,
    })

    const data = await r.json()

    if (!r.ok) {
      errorMsg.value = data.message || Object.values(data.errors || {}).flat().join(', ')
      saving.value   = false
      return
    }

    await fetchProducts(currentPage.value)
    closeModal()
  } catch (e) {
    errorMsg.value = 'Failed to save product.'
  }

  saving.value = false
}

// ── Delete ─────────────────────────────────────────────────────────────────
const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return
  try {
    await fetch(`${API}/admin/products/${id}`, {
      method: 'DELETE', headers: authHeaders()
    })
    await fetchProducts(currentPage.value)
  } catch (e) {
    alert('Failed to delete product')
  }
}

const formatNumber = (n) => Number(n).toLocaleString('en-US', { minimumFractionDigits: 2 })

onMounted(() => {
  fetchCategories()
  fetchProducts(1)
})
</script>

<style scoped>
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}
.header-row h3 { font-size: 1.4rem; }

.loading-state, .empty-state {
  text-align: center;
  padding: 60px;
  color: var(--admin-text-secondary);
}
.loading-state i, .empty-state i { font-size: 2.5rem; color: var(--admin-gold); margin-bottom: 15px; display: block; }

.products-dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 25px;
}

.product-crud-card { overflow: hidden; display: flex; flex-direction: column; }

.card-preview {
  position: relative;
  height: 200px;
  overflow: hidden;
  background: #0f172a;
}
.card-img { width: 100%; height: 100%; object-fit: cover; }
.card-placeholder {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  font-size: 4rem; color: rgba(255,255,255,0.2);
}
.featured-badge {
  position: absolute; top: 10px; left: 10px;
  background: var(--admin-gold); color: #000;
  font-size: 0.65rem; font-weight: 700;
  padding: 4px 10px; border-radius: 20px;
}
.inactive-badge {
  position: absolute; top: 10px; right: 10px;
  background: rgba(239,68,68,0.85); color: #fff;
  font-size: 0.65rem; font-weight: 700;
  padding: 4px 10px; border-radius: 20px;
}

.card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }

.category-tag {
  font-size: 0.68rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px;
  color: var(--admin-gold); margin-bottom: 6px; display: block;
}
.card-body h4 { font-size: 1.05rem; margin-bottom: 6px; }
.desc {
  font-size: 0.78rem; color: var(--admin-text-secondary);
  margin-bottom: 12px;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

.price-stock-row {
  display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap;
}
.price-badge {
  background: rgba(212,175,55,0.15); color: var(--admin-gold);
  font-weight: 700; font-size: 0.85rem;
  padding: 4px 12px; border-radius: 20px;
}
.stock-badge {
  background: rgba(100,200,100,0.1); color: #4ade80;
  font-size: 0.8rem; padding: 4px 12px; border-radius: 20px;
}

.sizing-variants-preview {
  background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.06);
  padding: 12px; border-radius: 8px; margin-bottom: 16px; flex: 1;
}
.sizing-variants-preview h5 {
  font-size: 0.7rem; text-transform: uppercase;
  color: var(--admin-text-secondary); margin-bottom: 8px;
}
.variant-row {
  display: flex; justify-content: space-between;
  font-size: 0.73rem; padding: 4px 0;
  border-bottom: 1px solid rgba(255,255,255,0.05);
}
.variant-row:last-child { border-bottom: none; }
.card-actions { display: flex; gap: 10px; }
.card-actions button { flex: 1; padding: 8px; font-size: 0.8rem; }

/* ── Modern Product Modal ────────────────────────────────────── */
.pm-modal {
  display: flex;
  width: min(900px, 96vw);
  height: min(88vh, 700px);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.25);
  background: #fff;
  animation: modal-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modal-pop {
  from { opacity: 0; transform: scale(0.92) translateY(20px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}

/* Left Panel */
.pm-left {
  width: 240px;
  flex-shrink: 0;
  background: linear-gradient(160deg, #0ea5e9 0%, #7c3aed 100%);
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
  overflow: hidden;
}

.pm-image-area {
  flex: 1;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  min-height: 260px;
}

.pm-preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.pm-upload-prompt {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  color: rgba(255,255,255,0.85);
  padding: 24px;
  text-align: center;
}

.pm-upload-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  border: 2px dashed rgba(255,255,255,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #fff;
  margin-bottom: 4px;
}

.pm-upload-prompt p {
  font-size: 0.9rem;
  font-weight: 600;
  color: #fff;
}

.pm-upload-prompt small {
  font-size: 0.72rem;
  opacity: 0.65;
}

.pm-image-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  color: #fff;
  font-size: 0.82rem;
  font-weight: 600;
  opacity: 0;
  transition: opacity 0.2s;
}

.pm-image-area:hover .pm-image-overlay { opacity: 1; }
.pm-image-overlay i { font-size: 1.5rem; }

/* Toggles (left panel bottom) */
.pm-toggles {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: rgba(0,0,0,0.15);
}

.pm-toggle-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
}

.pm-toggle-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  font-weight: 500;
  color: rgba(255,255,255,0.9);
}

.pm-toggle-label i {
  font-size: 0.9rem;
  width: 16px;
  text-align: center;
  color: rgba(255,255,255,0.7);
}

.pm-switch {
  width: 40px;
  height: 22px;
  border-radius: 11px;
  background: rgba(255,255,255,0.2);
  position: relative;
  transition: background 0.25s;
  flex-shrink: 0;
}

.pm-switch.on { background: #10b981; }

.pm-switch-thumb {
  position: absolute;
  top: 3px;
  left: 3px;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #fff;
  transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.pm-switch.on .pm-switch-thumb { transform: translateX(18px); }

/* Right Panel */
.pm-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.pm-header {
  padding: 24px 28px 16px;
  border-bottom: 1px solid var(--admin-border);
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
}

.pm-header-label {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--primary-blue);
  margin-bottom: 2px;
}

.pm-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--admin-text-primary);
  line-height: 1.2;
}

.pm-close-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid var(--admin-border);
  background: transparent;
  color: var(--admin-text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
}

.pm-close-btn:hover {
  background: #fee2e2;
  color: #ef4444;
  border-color: #fca5a5;
}

.pm-form-scroll {
  flex: 1;
  overflow-y: auto;
  padding: 20px 28px;
  min-height: 0;
}

.pm-section-label {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--admin-text-muted);
  margin-bottom: 12px;
  margin-top: 4px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.pm-section-label.mt-8 { margin-top: 20px; }

.pm-add-size-btn {
  font-size: 0.72rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
  border: 1px solid var(--admin-border);
  background: var(--admin-bg);
  color: var(--admin-text-secondary);
  cursor: pointer;
  transition: all 0.2s;
}

.pm-add-size-btn:hover {
  background: var(--primary-blue);
  color: #fff;
  border-color: var(--primary-blue);
}

.pm-footer {
  padding: 16px 28px;
  border-top: 1px solid var(--admin-border);
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  background: var(--admin-bg);
}

.pm-save-btn {
  background: linear-gradient(135deg, #0ea5e9, #7c3aed);
  min-width: 140px;
}

.pm-save-btn:hover {
  background: linear-gradient(135deg, #0284c7, #6d28d9);
}

/* Input with prefix (Rs.) */
.input-prefix-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.input-prefix {
  position: absolute;
  left: 12px;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--admin-text-secondary);
  pointer-events: none;
}

.form-input.has-prefix {
  padding-left: 36px;
}

/* Form Grid */
.form-grid-2 {
  display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 4px;
}

/* Variants */
.variants-section {
  background: var(--admin-bg);
  border: 1px solid var(--admin-border);
  border-radius: 10px; padding: 14px; margin-top: 4px;
}
.variants-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;
}
.variants-header h5 { font-size: 0.82rem; font-weight: 700; color: var(--admin-text-secondary); }
.btn-sm { font-size: 0.75rem !important; padding: 6px 14px !important; }

.vnt-row {
  display: grid; grid-template-columns: 90px 1fr 1fr auto;
  gap: 10px; align-items: flex-end; margin-bottom: 10px;
  padding-bottom: 10px; border-bottom: 1px solid var(--admin-border);
}
.vnt-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

.form-label.mini { font-size: 0.65rem; margin-bottom: 3px; }
.form-input.mini { padding: 6px 10px; font-size: 0.8rem; }
.mb-0 { margin-bottom: 0; }

.remove-vnt-btn {
  background: #fee2e2; border: none;
  color: #ef4444; border-radius: 6px; padding: 6px 10px;
  cursor: pointer; transition: background 0.2s;
}
.remove-vnt-btn:hover { background: #fca5a5; }

.no-variants-hint {
  text-align: center; color: var(--admin-text-muted);
  font-size: 0.8rem; padding: 12px;
}

.error-banner {
  background: #fee2e2; border: 1px solid #fca5a5;
  color: #dc2626; padding: 10px 16px; border-radius: 8px;
  font-size: 0.82rem; margin: 0 28px 12px; display: flex; gap: 8px; align-items: center;
}

.mr-1 { margin-right: 4px; }
.mr-2 { margin-right: 8px; }
.ml-2 { margin-left: 8px; }

</style>

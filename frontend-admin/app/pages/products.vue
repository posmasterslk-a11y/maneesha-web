<template>
  <UDashboardPanel id="products">
    <template #header>
      <UDashboardNavbar title="Products">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Apparel Catalog</h3>
          <UButton icon="i-lucide-plus" color="primary" @click="openAddModal">Add New Product</UButton>
        </div>


        <!-- Loading -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary-500 mb-4" />
          <p class="text-gray-500">Loading products...</p>
        </div>

        <!-- Products Grid -->
        <div v-else-if="products.length > 0" class="flex flex-col gap-6">
          <UPageGrid class="lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <UCard v-for="prod in products" :key="prod.id" class="flex flex-col h-full overflow-hidden" :ui="{ body: { padding: '' }, footer: { padding: 'p-4' } }">
              <!-- Product Image -->
              <div class="relative h-40 bg-gray-100 dark:bg-gray-800">
                <img v-if="prod.main_image" :src="getImageUrl(prod.main_image)" :alt="prod.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                  <UIcon name="i-lucide-shirt" class="w-12 h-12" />
                </div>
                <UBadge v-if="prod.is_featured" color="amber" class="absolute top-2 left-2" icon="i-lucide-star">Featured</UBadge>
                <UBadge v-if="!prod.is_active" color="red" class="absolute top-2 right-2">Hidden</UBadge>
              </div>

              <div class="p-4 flex-1 flex flex-col">
                <span class="text-xs font-bold text-primary uppercase tracking-wider mb-1">{{ prod.category_name }}</span>
                <h4 class="font-bold text-lg mb-2">{{ prod.name }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">{{ prod.short_description }}</p>

                <div class="flex gap-2 mb-4 flex-wrap">
                  <UBadge color="gray" variant="soft" size="md">LKR {{ formatNumber(prod.base_price) }}</UBadge>
                  <UBadge color="emerald" variant="soft" size="md">Stock: {{ prod.stock }}</UBadge>
                </div>

              </div>

              <template #footer>
                <div class="flex gap-2">
                  <UButton class="flex-1 justify-center" color="gray" variant="solid" icon="i-lucide-edit" @click="editProduct(prod)">Edit</UButton>
                  <UButton class="flex-1 justify-center" color="red" variant="soft" icon="i-lucide-trash" @click="deleteProduct(prod.id)">Delete</UButton>
                </div>
              </template>
            </UCard>
          </UPageGrid>
          
          <AdminPagination :current-page="currentPage" :last-page="lastPage" @page-change="fetchProducts" />
        </div>

        <div v-else class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
          <UIcon name="i-lucide-package-open" class="w-16 h-16 text-gray-300 mb-4" />
          <p class="text-gray-500 font-medium">No products yet. Add your first product!</p>
        </div>

        <!-- Add/Edit Modal -->
        <UModal v-model:open="isModalOpen" :ui="{ content: 'sm:max-w-5xl w-full' }">
          <template #content>
          <div class="flex flex-col md:flex-row h-full max-h-[85vh]">
            <!-- Left Panel: Image Upload -->
            <div class="w-full md:w-64 bg-gradient-to-br from-primary-500 to-purple-600 flex flex-col flex-shrink-0">
              <div class="flex-1 relative min-h-[200px] cursor-pointer group" @click="triggerFileInput">
                <img v-if="imagePreview" :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                <img v-else-if="formData.main_image" :src="getImageUrl(formData.main_image)" alt="Current" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-white p-6 text-center">
                  <div class="w-16 h-16 rounded-full border-2 border-dashed border-white/40 bg-white/10 flex items-center justify-center mb-2">
                    <UIcon name="i-lucide-upload-cloud" class="w-8 h-8" />
                  </div>
                  <p class="font-semibold text-sm">Drop your image here</p>
                  <span class="text-xs opacity-75 mt-1">JPG, PNG, WEBP · Max 5MB</span>
                </div>
                <div class="absolute inset-0 bg-black/40 flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity flex">
                  <UIcon name="i-lucide-camera" class="w-8 h-8 mb-1" />
                  <span class="text-sm font-semibold">Change Photo</span>
                </div>
              </div>
              <input ref="fileInput" type="file" accept="image/*" @change="onImageChange" class="hidden" />

              <!-- Toggle Switches -->
              <div class="p-4 bg-black/20 flex flex-col gap-4">
                <div class="flex items-center justify-between text-white">
                  <div class="flex items-center gap-2 text-sm font-medium">
                    <UIcon name="i-lucide-eye" class="w-4 h-4 opacity-75" />
                    <span>Active on Store</span>
                  </div>
                  <USwitch v-model="formData.is_active" color="emerald" />
                </div>
                <div class="flex items-center justify-between text-white">
                  <div class="flex items-center gap-2 text-sm font-medium">
                    <UIcon name="i-lucide-star" class="w-4 h-4 opacity-75" />
                    <span>Featured</span>
                  </div>
                  <USwitch v-model="formData.is_featured" color="amber" />
                </div>
                <div class="flex items-center justify-between text-white">
                  <div class="flex items-center gap-2 text-sm font-medium">
                    <UIcon name="i-lucide-image" class="w-4 h-4 opacity-75" />
                    <span>Hero Slider</span>
                  </div>
                  <USwitch v-model="formData.in_hero_slider" color="primary" />
                </div>
              </div>
            </div>

            <!-- Right Panel: Form -->
            <div class="flex-1 flex flex-col bg-white dark:bg-gray-900 overflow-hidden">
              <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-start">
                <div>
                  <p class="text-xs font-bold text-primary uppercase tracking-wider mb-1">{{ isEditing ? 'Editing Product' : 'New Product' }}</p>
                  <h4 class="text-xl font-bold">{{ isEditing ? formData.name || 'Edit Product' : 'Add New Product' }}</h4>
                </div>
                <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeModal" />
              </div>

              <form @submit.prevent="saveProduct" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 overflow-y-auto flex-1 space-y-6">
                  <div>
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Basic Info</h5>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                      <UFormField label="Product Name *" required>
                        <UInput v-model="formData.name" placeholder="e.g. Silk Georgette Saree" required />
                      </UFormField>
                      <UFormField label="Category *" required>
                        <select v-model="formData.category_id" class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-md block w-full p-2 h-8" required>
                          <option value="">— Select Category —</option>
                          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                      </UFormField>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                      <UFormField label="Base Price (LKR) *" required>
                        <UInput v-model.number="formData.base_price" type="number" min="0" step="0.01" required>
                          <template #leading>
                            <span class="text-gray-500 dark:text-gray-400 text-xs">Rs.</span>
                          </template>
                        </UInput>
                      </UFormField>
                      <UFormField label="Total Stock *" required>
                        <UInput v-model.number="formData.stock" type="number" min="0" required :disabled="formData.variants && formData.variants.length > 0" />
                      </UFormField>
                    </div>
                    <UFormField label="Short Description" class="mb-4 w-full">
                      <UInput v-model="formData.short_description" placeholder="One-line summary shown on cards" class="w-full" />
                    </UFormField>
                    <UFormField label="Full Description" class="mb-4 w-full">
                      <UTextarea v-model="formData.description" :rows="3" placeholder="Detailed product description..." class="w-full" />
                    </UFormField>
                    <div class="grid grid-cols-2 gap-4">
                      <UFormField label="Fabric">
                        <UInput v-model="formData.fabric" placeholder="e.g. Silk, Cotton" />
                      </UFormField>
                      <UFormField label="Care Instructions">
                        <UInput v-model="formData.care_instructions" placeholder="e.g. Dry clean only" />
                      </UFormField>
                    </div>
                  </div>

                  <!-- Gallery Images -->
                  <div>
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1">
                      <UIcon name="i-lucide-images" class="w-4 h-4" /> Gallery Images (Max 4)
                    </h5>
                    <div class="grid grid-cols-4 gap-4 mb-4">
                      <!-- Existing/Preview Images -->
                      <div v-for="(img, idx) in galleryPreviews" :key="idx" class="relative aspect-square bg-gray-100 dark:bg-gray-800 rounded-md overflow-hidden group border border-gray-200 dark:border-gray-700">
                        <img :src="img.url" class="w-full h-full object-cover" />
                        <button type="button" @click.stop="removeGalleryImage(idx)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                          <UIcon name="i-lucide-x" class="w-4 h-4" />
                        </button>
                        <span v-if="img.isNew" class="absolute bottom-0 left-0 right-0 bg-blue-500/80 text-white text-[10px] text-center py-0.5">NEW</span>
                      </div>
                      
                      <!-- Upload Button -->
                      <div v-if="galleryPreviews.length < 4" class="relative aspect-square bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" @click="triggerGalleryInput">
                        <UIcon name="i-lucide-plus" class="w-6 h-6 text-gray-400 mb-1" />
                        <span class="text-xs text-gray-500">Add Photo</span>
                        <input ref="galleryInput" type="file" accept="image/*" multiple @change="onGalleryChange" class="hidden" />
                      </div>
                    </div>
                  </div>

                  <!-- Size Variants -->
                  <div>
                    <div class="flex justify-between items-center mb-3">
                      <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                        <UIcon name="i-lucide-ruler" class="w-4 h-4" /> Size Variants & Pricing
                      </h5>
                      <UButton size="xs" variant="soft" color="primary" @click="addVariant">+ Add Size</UButton>
                    </div>
                    <div class="space-y-2">
                      <div v-for="(v, idx) in formData.variants" :key="idx" class="flex gap-2 items-start">
                        <UFormField label="Size" class="flex-1">
                          <UInput v-model="v.size" placeholder="e.g. M" required />
                        </UFormField>
                        <UFormField label="Price (LKR)" class="flex-1">
                          <UInput v-model.number="v.price" type="number" min="0" required />
                        </UFormField>
                        <UFormField label="Stock" class="flex-1">
                          <UInput v-model.number="v.stock" type="number" min="0" :max="formData.stock" required />
                        </UFormField>
                        <div class="pt-6">
                          <UButton color="red" variant="ghost" icon="i-lucide-x" @click="removeVariant(idx)" />
                        </div>
                      </div>
                      <p v-if="formData.variants.reduce((sum, v) => sum + (Number(v.stock) || 0), 0) > formData.stock" class="text-sm text-red-500 font-semibold mt-2 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                        <UIcon name="i-lucide-alert-triangle" class="w-4 h-4 inline-block align-text-bottom mr-1" />
                        Warning: Total sizes stock ({{ formData.variants.reduce((sum, v) => sum + (Number(v.stock) || 0), 0) }}) exceeds the Total Product Stock ({{ formData.stock }}). Please adjust.
                      </p>
                      <p v-if="!formData.variants.length" class="text-sm text-gray-500 italic mt-2">No size variants added. Add at least one size.</p>
                    </div>
                  </div>
                </div>

                <div v-if="errorMsg" class="p-3 mx-6 mb-4 bg-red-100 text-red-700 rounded-md flex items-center gap-2 text-sm">
                  <UIcon name="i-lucide-alert-triangle" class="w-5 h-5" /> {{ errorMsg }}
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3 bg-gray-50 dark:bg-gray-900">
                  <UButton color="gray" variant="solid" @click="closeModal">Cancel</UButton>
                  <UButton type="submit" color="primary" :loading="saving" :icon="saving ? '' : 'i-lucide-save'">
                    {{ saving ? 'Saving...' : 'Save Product' }}
                  </UButton>
                </div>
              </form>
            </div>
          </div>
          </template>
        </UModal>
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const config = useRuntimeConfig()
const API = config.public.apiBase
const token = () => (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token')) || ''

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

const galleryInput    = ref(null)
const galleryFiles    = ref([]) // Array of new File objects
const galleryPreviews = ref([]) // Array of objects: { url, isNew, path? }
const retainedGallery = ref([]) // Array of paths from existing images

const defaultVariants = () => [
  { size: 'XS',     price: 0, stock: 0 },
  { size: 'S',      price: 0, stock: 0 },
  { size: 'M',      price: 0, stock: 0 },
  { size: 'L',      price: 0, stock: 0 },
  { size: 'XL',     price: 0, stock: 0 },
  { size: 'XXL',    price: 0, stock: 0 },
  { size: 'Custom', price: 0, stock: 0 },
]

const formData = ref({
  name: '', category_id: '', base_price: 2000, stock: 0,
  short_description: '', description: '', fabric: '',
  care_instructions: '', is_active: true, is_featured: false, in_hero_slider: false,
  main_image: '', variants: defaultVariants()
})

watch(formData, (newVal) => {
  if (newVal.variants && newVal.variants.length > 0) {
    const totalVariantStock = newVal.variants.reduce((sum, v) => sum + (Number(v.stock) || 0), 0)
    if (newVal.stock !== totalVariantStock) {
      newVal.stock = totalVariantStock
    }
  }

  if (!isEditing.value) {
    localStorage.setItem('maneesha-product-draft', JSON.stringify(newVal))
  }
}, { deep: true })

// ── API Helpers ────────────────────────────────────────────────────────────
const authHeaders = () => ({
  'Authorization': `Bearer ${token()}`,
  'Accept': 'application/json',
})

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `https://api-maneesha.posmasters.lk/storage/${path}`;
}

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

const triggerGalleryInput = () => galleryInput.value?.click()

const onGalleryChange = (e) => {
  const files = Array.from(e.target.files)
  if (!files.length) return
  
  let added = 0;
  for (const file of files) {
    if (galleryPreviews.value.length >= 4) break;
    if (file.size > 5 * 1024 * 1024) {
      errorMsg.value = 'One or more images are too large. Max 5MB per image.'
      continue;
    }
    galleryFiles.value.push(file)
    galleryPreviews.value.push({
      url: URL.createObjectURL(file),
      isNew: true,
      fileIndex: galleryFiles.value.length - 1
    })
    added++;
  }
  
  if (added > 0) e.target.value = ''; // Reset input
}

const removeGalleryImage = (idx) => {
  const item = galleryPreviews.value[idx];
  if (item.isNew) {
    // Remove from galleryFiles
    galleryFiles.value[item.fileIndex] = null; // Mark as null, we'll filter out later
  } else {
    // Remove from retainedGallery
    retainedGallery.value = retainedGallery.value.filter(path => path !== item.path);
  }
  galleryPreviews.value.splice(idx, 1);
}

// ── Modal ──────────────────────────────────────────────────────────────────
const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  imageFile.value = null
  imagePreview.value = ''
  galleryFiles.value = []
  galleryPreviews.value = []
  retainedGallery.value = []
  const draft = localStorage.getItem('maneesha-product-draft')
  if (draft) {
    try {
      formData.value = JSON.parse(draft)
    } catch(e) {
      formData.value = {
        name: '', category_id: '', base_price: 2000, stock: 0,
        short_description: '', description: '', fabric: '',
        care_instructions: '', is_active: true, is_featured: false, in_hero_slider: false,
        main_image: '', variants: defaultVariants()
      }
    }
  } else {
    formData.value = {
      name: '', category_id: '', base_price: 2000, stock: 0,
      short_description: '', description: '', fabric: '',
      care_instructions: '', is_active: true, is_featured: false, in_hero_slider: false,
      main_image: '', variants: defaultVariants()
    }
  }
  errorMsg.value = ''
  isModalOpen.value = true
}

const editProduct = (prod) => {
  isEditing.value = true
  editingId.value = prod.id
  imageFile.value = null
  imagePreview.value = ''
  
  galleryFiles.value = []
  galleryPreviews.value = []
  retainedGallery.value = []
  
  if (prod.gallery_images && Array.isArray(prod.gallery_images)) {
    prod.gallery_images.forEach(img => {
      retainedGallery.value.push(img.path);
      galleryPreviews.value.push({ url: img.url, isNew: false, path: img.path });
    });
  }

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
  galleryFiles.value = []
  galleryPreviews.value = []
  retainedGallery.value = []
}

// ── Variants ───────────────────────────────────────────────────────────────
const addVariant    = () => formData.value.variants.push({ size: '', price: formData.value.base_price, stock: 0 })
const removeVariant = (idx) => formData.value.variants.splice(idx, 1)

// ── Save ───────────────────────────────────────────────────────────────────
const saveProduct = async () => {
  saving.value   = true
  errorMsg.value = ''

  const totalVariantStock = formData.value.variants.reduce((sum, v) => sum + (Number(v.stock) || 0), 0)
  if (formData.value.variants.length > 0 && totalVariantStock !== formData.value.stock) {
    formData.value.stock = totalVariantStock
  }

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
    fd.append('retained_gallery_images', JSON.stringify(retainedGallery.value))

    if (imageFile.value) fd.append('main_image', imageFile.value)
    
    // Append valid gallery files
    galleryFiles.value.forEach(file => {
      if (file) fd.append('gallery_images[]', file)
    })

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
    if (!isEditing.value) {
      localStorage.removeItem('maneesha-product-draft')
    }
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

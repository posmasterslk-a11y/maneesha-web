<template>
  <UDashboardPanel id="categories">
    <template #header>
      <UDashboardNavbar title="Categories">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
          <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Category Management</h3>
          <UButton icon="i-lucide-plus" color="primary" @click="openAddModal">Add Category</UButton>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary-500 mb-4" />
          <p class="text-gray-500">Loading categories...</p>
        </div>

        <!-- Categories Table -->
        <UCard v-else :ui="{ body: { padding: '' } }">
          <UTable :data="categories" :columns="[
            { id: 'id', header: '#' },
            { id: 'name', header: 'Name' },
            { id: 'slug', header: 'Slug' },
            { id: 'products', header: 'Products' },
            { id: 'featured', header: 'Featured' },
            { id: 'status', header: 'Status' },
            { id: 'actions', header: 'Actions' }
          ]">
            <template #id-cell="{ row }">
              <span class="text-gray-500 text-xs">{{ row.original.id }}</span>
            </template>
            
            <template #name-cell="{ row }">
              <div class="flex flex-col">
                <span class="font-bold">{{ row.original.name }}</span>
                <span v-if="row.original.description" class="text-xs text-gray-500 truncate max-w-xs">{{ row.original.description }}</span>
              </div>
            </template>
            
            <template #slug-cell="{ row }">
              <code class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-1 rounded text-xs">{{ row.original.slug }}</code>
            </template>
            
            <template #products-cell="{ row }">
              <UBadge color="emerald" variant="soft" size="sm">{{ row.original.products_count ?? 0 }} items</UBadge>
            </template>

            <template #featured-cell="{ row }">
              <UIcon v-if="row.original.is_featured" name="i-lucide-star" class="w-5 h-5 text-amber-500" />
              <span v-else class="text-gray-400">-</span>
            </template>
            
            <template #status-cell="{ row }">
              <UBadge :color="row.original.is_active ? 'emerald' : 'red'" variant="soft">
                {{ row.original.is_active ? 'Active' : 'Hidden' }}
              </UBadge>
            </template>
            
            <template #actions-cell="{ row }">
              <div class="flex gap-2">
                <UButton size="xs" color="gray" variant="solid" icon="i-lucide-edit" @click="editCategory(row.original)">Edit</UButton>
                <UButton size="xs" color="red" variant="soft" icon="i-lucide-trash" @click="deleteCategory(row.original.id)" />
              </div>
            </template>
            
            <template #empty>
              <div class="flex flex-col items-center justify-center py-12">
                <UIcon name="i-lucide-tags" class="w-12 h-12 text-gray-400 mb-4" />
                <p class="text-gray-500">No categories yet. Add your first category!</p>
              </div>
            </template>
          </UTable>
          
          <div class="p-4 border-t border-gray-200 dark:border-gray-800" v-if="categories.length > 0">
            <AdminPagination :current-page="currentPage" :last-page="lastPage" @page-change="fetchCategories" />
          </div>
        </UCard>

        <!-- Add/Edit Modal -->
        <UModal v-model:open="isModalOpen" :ui="{ content: 'sm:max-w-2xl w-full' }">
          <template #content>
          <div class="flex flex-col md:flex-row h-full max-h-[85vh]">
            <!-- Left Panel -->
            <div class="w-full md:w-56 bg-gradient-to-br from-emerald-500 to-primary-500 flex flex-col items-center justify-center p-6 text-center text-white shrink-0">
              <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-4">
                <UIcon name="i-lucide-tags" class="w-8 h-8" />
              </div>
              <h3 class="font-bold text-lg mb-2">{{ isEditing ? 'Edit Category' : 'New Category' }}</h3>
              <p class="text-sm text-white/80 mb-6">Categories help customers browse your collection easily. Give it a clear, descriptive name.</p>
              
              <div class="w-full pt-4 border-t border-white/20 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-medium">
                  <UIcon name="i-lucide-eye" class="w-4 h-4 opacity-80" />
                  <span>Active</span>
                </div>
                <USwitch v-model="formData.is_active" color="emerald" />
              </div>
              <div class="w-full pt-4 mt-2 border-t border-white/20 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-medium">
                  <UIcon name="i-lucide-star" class="w-4 h-4 text-amber-300 opacity-100" />
                  <span>Featured</span>
                </div>
                <USwitch v-model="formData.is_featured" color="amber" />
              </div>
            </div>

            <!-- Right Panel: Form -->
            <div class="flex-1 flex flex-col bg-white dark:bg-gray-900 overflow-hidden">
              <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-start">
                <div>
                  <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-1">{{ isEditing ? 'Editing Category' : 'New Category' }}</p>
                  <h4 class="text-xl font-bold">{{ isEditing ? formData.name || 'Edit Category' : 'Add New Category' }}</h4>
                </div>
                <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeModal" />
              </div>

              <form @submit.prevent="saveCategory" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-6 space-y-4 flex-1 overflow-y-auto">
                  <UFormField label="Category Name *" required>
                    <UInput v-model="formData.name" placeholder="e.g. Sarees, Frocks, Blouses" @input="autoSlug" required />
                  </UFormField>
                  <UFormField label="Slug (URL key)">
                    <UInput v-model="formData.slug" placeholder="auto-generated" class="font-mono text-sm" />
                    <template #help>Used in URLs. Auto-filled from name.</template>
                  </UFormField>
                  <UFormField label="Description">
                    <UTextarea v-model="formData.description" :rows="3" placeholder="Brief description shown on the storefront..." />
                  </UFormField>
                  <UFormField label="Sort Order">
                    <UInput v-model.number="formData.sort_order" type="number" min="0" placeholder="0" />
                    <template #help>Lower numbers appear first.</template>
                  </UFormField>
                </div>

                <div v-if="errorMsg" class="p-3 mx-6 mb-4 bg-red-100 text-red-700 rounded-md flex items-center gap-2 text-sm">
                  <UIcon name="i-lucide-alert-triangle" class="w-5 h-5" /> {{ errorMsg }}
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3 bg-gray-50 dark:bg-gray-900">
                  <UButton color="gray" variant="solid" @click="closeModal">Cancel</UButton>
                  <UButton type="submit" color="primary" :loading="saving" :icon="saving ? '' : 'i-lucide-save'">
                    {{ saving ? 'Saving...' : 'Save Category' }}
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
import { ref, onMounted } from 'vue'

const config = useRuntimeConfig()
const API = config.public.apiBase
const token = () => localStorage.getItem('maneesha-admin-token') || ''
const authHeaders = () => ({ 'Authorization': `Bearer ${token()}`, 'Accept': 'application/json', 'Content-Type': 'application/json' })

const categories  = ref([])
const currentPage = ref(1)
const lastPage    = ref(1)
const loading     = ref(true)
const saving      = ref(false)
const errorMsg    = ref('')
const isModalOpen = ref(false)
const isEditing   = ref(false)
const editingId   = ref(null)

const formData = ref({ name: '', slug: '', description: '', sort_order: 0, is_active: true, is_featured: false })

// Auto-generate slug from name
const autoSlug = () => {
  formData.value.slug = formData.value.name
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

const fetchCategories = async (page = 1) => {
  loading.value = true
  try {
    const r = await fetch(`${API}/admin/categories?page=${page}`, { headers: authHeaders() })
    const data = await r.json()
    categories.value = data.data
    currentPage.value = data.current_page
    lastPage.value = data.last_page
  } catch (e) { console.error(e) }
  loading.value = false
}

const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  formData.value  = { name: '', slug: '', description: '', sort_order: 0, is_active: true, is_featured: false }
  errorMsg.value  = ''
  isModalOpen.value = true
}

const editCategory = (cat) => {
  isEditing.value = true
  editingId.value = cat.id
  formData.value  = {
    name:        cat.name,
    slug:        cat.slug,
    description: cat.description || '',
    sort_order:  cat.sort_order || 0,
    is_active:   cat.is_active,
    is_featured: cat.is_featured,
  }
  errorMsg.value  = ''
  isModalOpen.value = true
}

const closeModal = () => { isModalOpen.value = false }

const saveCategory = async () => {
  saving.value   = true
  errorMsg.value = ''

  try {
    const url    = isEditing.value ? `${API}/admin/categories/${editingId.value}` : `${API}/admin/categories`
    const method = isEditing.value ? 'PUT' : 'POST'

    const r    = await fetch(url, { method, headers: authHeaders(), body: JSON.stringify(formData.value) })
    const data = await r.json()

    if (!r.ok) {
      errorMsg.value = data.message || Object.values(data.errors || {}).flat().join(', ')
      saving.value   = false
      return
    }

    await fetchCategories(currentPage.value)
    closeModal()
  } catch (e) {
    errorMsg.value = 'Network error. Check that the backend is running.'
  }

  saving.value = false
}

const deleteCategory = async (id) => {
  if (!confirm('Delete this category? Products in it will become uncategorized.')) return
  await fetch(`${API}/admin/categories/${id}`, { method: 'DELETE', headers: authHeaders() })
  await fetchCategories(currentPage.value)
}

onMounted(() => fetchCategories(1))
</script>

<style scoped>
.header-row {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;
}
.header-row h3 { font-size: 1.4rem; }

.loading-state, .empty-state {
  text-align: center; padding: 60px; color: var(--admin-text-secondary);
}
.loading-state i, .empty-state i { font-size: 2.5rem; color: var(--admin-gold); margin-bottom: 12px; display: block; }

.table-wrapper { overflow-x: auto; }

.admin-table {
  width: 100%; border-collapse: collapse; font-size: 0.875rem;
}
.admin-table th {
  text-align: left; padding: 14px 16px;
  font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;
  color: var(--admin-text-secondary);
  border-bottom: 1px solid rgba(255,255,255,0.06);
}
.admin-table td {
  padding: 14px 16px;
  border-bottom: 1px solid rgba(255,255,255,0.04);
  vertical-align: middle;
}
.admin-table tbody tr:hover { background: rgba(255,255,255,0.02); }
.admin-table tbody tr:last-child td { border-bottom: none; }

.id-col { color: var(--admin-text-secondary); font-size: 0.75rem; }
.desc-small { font-size: 0.72rem; color: var(--admin-text-secondary); margin-top: 3px; }

.slug-code {
  background: rgba(212,175,55,0.1); color: var(--admin-gold);
  padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-family: monospace;
}

.count-badge {
  background: rgba(100,200,100,0.1); color: #4ade80;
  font-size: 0.75rem; padding: 3px 10px; border-radius: 12px;
}

.status-pill {
  font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 12px;
}
.status-pill.active   { background: rgba(34,197,94,0.15);  color: #4ade80; }
.status-pill.inactive { background: rgba(239,68,68,0.15);  color: #f87171; }

.actions-col { display: flex; gap: 8px; align-items: center; }
.btn-sm { font-size: 0.75rem !important; padding: 5px 12px !important; }

/* ── Modern Category Modal ───────────────────────────────────── */
.cm-modal {
  display: flex;
  width: min(720px, 96vw);
  height: min(80vh, 560px);
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

.cm-left {
  width: 220px;
  flex-shrink: 0;
  background: linear-gradient(160deg, #10b981 0%, #0ea5e9 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 32px 24px;
  text-align: center;
  gap: 16px;
}

.cm-icon-wrap {
  width: 72px;
  height: 72px;
  background: rgba(255,255,255,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: #fff;
  margin-bottom: 4px;
}

.cm-side-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #fff;
}

.cm-side-desc {
  font-size: 0.78rem;
  color: rgba(255,255,255,0.8);
  line-height: 1.5;
  flex: 1;
}

.cm-toggle-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  cursor: pointer;
  padding-top: 16px;
  border-top: 1px solid rgba(255,255,255,0.2);
}

.cm-toggle-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  font-weight: 500;
  color: rgba(255,255,255,0.9);
}
.cm-toggle-label i { color: rgba(255,255,255,0.7); }

.cm-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.cm-form-body {
  flex: 1;
  overflow-y: auto;
  padding: 20px 28px;
  min-height: 0;
}

/* Shared pm- classes used in categories too */
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
  color: #10b981;
  margin-bottom: 2px;
}
.pm-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--admin-text-primary);
}
.pm-close-btn {
  width: 32px; height: 32px;
  border-radius: 8px;
  border: 1px solid var(--admin-border);
  background: transparent;
  color: var(--admin-text-secondary);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
}
.pm-close-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }
.pm-footer {
  padding: 16px 28px;
  border-top: 1px solid var(--admin-border);
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  background: var(--admin-bg);
}
.pm-save-btn {
  background: linear-gradient(135deg, #10b981, #0ea5e9);
  min-width: 140px;
}
.pm-save-btn:hover {
  background: linear-gradient(135deg, #059669, #0284c7);
}
.pm-switch {
  width: 40px; height: 22px; border-radius: 11px;
  background: rgba(255,255,255,0.2);
  position: relative; transition: background 0.25s; flex-shrink: 0;
}
.pm-switch.on { background: #fff; }
.pm-switch.on .pm-switch-thumb { transform: translateX(18px); background: #10b981; }
.pm-switch-thumb {
  position: absolute; top: 3px; left: 3px;
  width: 16px; height: 16px; border-radius: 50%; background: rgba(255,255,255,0.6);
  transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

/* Table styles */
.slug-input { font-family: monospace; font-size: 0.85rem; }
.hint { font-size: 0.68rem; color: var(--admin-text-secondary); margin-top: 4px; display: block; }
.error-banner {
  background: #fee2e2; border: 1px solid #fca5a5;
  color: #dc2626; padding: 10px 16px; border-radius: 8px;
  font-size: 0.82rem; margin: 0 28px 12px; display: flex; gap: 8px; align-items: center;
}

.mr-2 { margin-right: 8px; }
.ml-2 { margin-left: 8px; }
</style>

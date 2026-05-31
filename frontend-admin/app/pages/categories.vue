<template>
  <div class="admin-categories">
    <div class="header-row animate-fade-up">
      <h3 class="luxury-title">Category Management</h3>
      <button @click="openAddModal" class="btn-admin">
        <i class="fa-solid fa-plus mr-2"></i> Add Category
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-state">
      <i class="fa-solid fa-spinner fa-spin"></i> Loading categories...
    </div>

    <!-- Categories Table -->
    <div v-else class="table-wrapper glass-panel animate-fade-up">
      <table class="admin-table" v-if="categories.length > 0">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories" :key="cat.id">
            <td class="id-col">{{ cat.id }}</td>
            <td>
              <strong>{{ cat.name }}</strong>
              <p v-if="cat.description" class="desc-small">{{ cat.description }}</p>
            </td>
            <td><code class="slug-code">{{ cat.slug }}</code></td>
            <td>
              <span class="count-badge">{{ cat.products_count ?? 0 }} items</span>
            </td>
            <td>
              <span :class="['status-pill', cat.is_active ? 'active' : 'inactive']">
                {{ cat.is_active ? 'Active' : 'Hidden' }}
              </span>
            </td>
            <td class="actions-col">
              <button @click="editCategory(cat)" class="btn-admin btn-admin-secondary btn-sm">
                <i class="fa-regular fa-pen-to-square"></i> Edit
              </button>
              <button @click="deleteCategory(cat.id)" class="btn-admin btn-admin-danger btn-sm">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else class="empty-state">
        <i class="fa-solid fa-tags"></i>
        <p>No categories yet. Add your first category!</p>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div :class="['modal-overlay', { open: isModalOpen }]" @click.self="closeModal">
      <div class="cm-modal">

        <!-- Left Panel -->
        <div class="cm-left">
          <div class="cm-icon-wrap">
            <i class="fa-solid fa-tags"></i>
          </div>
          <h3 class="cm-side-title">{{ isEditing ? 'Edit Category' : 'New Category' }}</h3>
          <p class="cm-side-desc">Categories help customers browse your collection easily. Give it a clear, descriptive name.</p>

          <!-- Active Toggle -->
          <div class="cm-toggle-item">
            <div class="cm-toggle-label">
              <i class="fa-solid fa-eye"></i>
              <span>Active on Store</span>
            </div>
            <div class="pm-switch" :class="{ on: formData.is_active }" @click="formData.is_active = !formData.is_active">
              <div class="pm-switch-thumb"></div>
            </div>
          </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="cm-right">
          <div class="pm-header">
            <div>
              <p class="pm-header-label">{{ isEditing ? 'Editing Category' : 'New Category' }}</p>
              <h4 class="pm-title">{{ isEditing ? formData.name || 'Edit Category' : 'Add New Category' }}</h4>
            </div>
            <button @click="closeModal" class="pm-close-btn"><i class="fa-solid fa-xmark"></i></button>
          </div>

          <form @submit.prevent="saveCategory">
            <div class="cm-form-body">

              <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" v-model="formData.name" class="form-input" required placeholder="e.g. Sarees, Frocks, Blouses" @input="autoSlug" />
              </div>

              <div class="form-group">
                <label class="form-label">Slug (URL key)</label>
                <input type="text" v-model="formData.slug" class="form-input slug-input" placeholder="auto-generated" />
                <small class="hint">Used in URLs. Auto-filled from name.</small>
              </div>

              <div class="form-group">
                <label class="form-label">Description</label>
                <textarea v-model="formData.description" class="form-input" rows="3" placeholder="Brief description shown on the storefront..."></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" v-model.number="formData.sort_order" class="form-input" min="0" placeholder="0" />
                <small class="hint">Lower numbers appear first.</small>
              </div>

            </div>

            <div v-if="errorMsg" class="error-banner"><i class="fa-solid fa-triangle-exclamation"></i> {{ errorMsg }}</div>

            <div class="pm-footer">
              <button type="button" @click="closeModal" class="btn-admin btn-admin-secondary">Cancel</button>
              <button type="submit" :disabled="saving" class="btn-admin pm-save-btn">
                <span v-if="saving"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...</span>
                <span v-else><i class="fa-regular fa-floppy-disk mr-2"></i>Save Category</span>
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

const API   = 'http://localhost:8000/api'
const token = () => localStorage.getItem('maneesha-admin-token') || ''
const authHeaders = () => ({ 'Authorization': `Bearer ${token()}`, 'Accept': 'application/json', 'Content-Type': 'application/json' })

const categories  = ref([])
const loading     = ref(true)
const saving      = ref(false)
const errorMsg    = ref('')
const isModalOpen = ref(false)
const isEditing   = ref(false)
const editingId   = ref(null)

const formData = ref({ name: '', slug: '', description: '', sort_order: 0, is_active: true })

// Auto-generate slug from name
const autoSlug = () => {
  formData.value.slug = formData.value.name
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
}

const fetchCategories = async () => {
  loading.value = true
  try {
    const r = await fetch(`${API}/admin/categories`, { headers: authHeaders() })
    categories.value = await r.json()
  } catch (e) { console.error(e) }
  loading.value = false
}

const openAddModal = () => {
  isEditing.value = false
  editingId.value = null
  formData.value  = { name: '', slug: '', description: '', sort_order: 0, is_active: true }
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

    await fetchCategories()
    closeModal()
  } catch (e) {
    errorMsg.value = 'Network error. Check that the backend is running.'
  }

  saving.value = false
}

const deleteCategory = async (id) => {
  if (!confirm('Delete this category? Products in it will become uncategorized.')) return
  await fetch(`${API}/admin/categories/${id}`, { method: 'DELETE', headers: authHeaders() })
  await fetchCategories()
}

onMounted(fetchCategories)
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

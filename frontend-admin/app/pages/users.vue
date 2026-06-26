<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar title="User Management" badge="Admins/Staff">
        <template #right>
          <UButton label="New User" trailing-icon="i-lucide-plus" @click="openCreateModal" />
        </template>
      </UDashboardNavbar>

      <div class="p-4">
        <UTable :data="users" :columns="columns" :loading="pending">
          <template #role-cell="{ row }">
            <UBadge :color="getRoleColor(row.original.role)" variant="subtle">{{ getRoleName(row.original.role) }}</UBadge>
          </template>
          
          <template #actions-cell="{ row }">
            <UDropdownMenu :items="getItems(row.original)">
              <UButton color="neutral" variant="ghost" icon="i-lucide-more-horizontal" />
            </UDropdownMenu>
          </template>
        </UTable>
      </div>
    </UDashboardPanel>

    <!-- User Modal -->
    <UModal v-model:open="isModalOpen">
      <template #content>
        <UCard>
          <template #header>
            <h3 class="text-lg font-semibold">{{ isEditing ? 'Edit User' : 'Create User' }}</h3>
          </template>

          <form @submit.prevent="saveUser" class="flex flex-col gap-5 p-2">
            <div class="flex flex-col gap-1.5">
              <label class="text-sm font-bold text-gray-700 dark:text-gray-200">Full Name</label>
              <UInput v-model="form.name" required placeholder="John Doe" icon="i-lucide-user" size="md" />
            </div>
            
            <div class="flex flex-col gap-1.5">
              <label class="text-sm font-bold text-gray-700 dark:text-gray-200">Email Address</label>
              <UInput v-model="form.email" type="email" required placeholder="john@example.com" icon="i-lucide-mail" size="md" />
            </div>

            <div class="flex flex-col gap-1.5 relative">
              <label class="text-sm font-bold text-gray-700 dark:text-gray-200">Account Role</label>
              <div class="relative flex items-center">
                <UIcon name="i-lucide-shield" class="absolute left-3 w-5 h-5 text-gray-400 pointer-events-none" />
                <select v-model="form.role" required class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-md focus:ring-primary-500 focus:border-primary-500 block appearance-none outline-none transition-colors">
                  <option v-for="role in roleOptions" :key="role.value" :value="role.value">{{ role.label }}</option>
                </select>
                <UIcon name="i-lucide-chevron-down" class="absolute right-3 w-4 h-4 text-gray-500 pointer-events-none" />
              </div>
            </div>

            <div class="flex flex-col gap-1.5">
              <label class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ isEditing ? 'Password (leave blank to keep current)' : 'Password' }}</label>
              <UInput v-model="form.password" type="password" :required="!isEditing" placeholder="••••••••" icon="i-lucide-lock" size="md" />
            </div>
            
            <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
              <UButton label="Cancel" color="gray" variant="soft" @click="isModalOpen = false" class="px-6 font-bold" />
              <UButton type="submit" :label="isEditing ? 'Save Changes' : 'Create User'" color="primary" :loading="saving" class="px-6 font-bold shadow-md" />
            </div>
          </form>
        </UCard>
      </template>
    </UModal>
  </UDashboardPage>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const adminRole = inject('adminRole')
const toast = useToast()
const config = useRuntimeConfig()
const API = config.public.apiBase

const users = ref([])
const pending = ref(true)
const isModalOpen = ref(false)
const isEditing = ref(false)
const saving = ref(false)

const form = ref({
  id: null,
  name: '',
  email: '',
  role: 'inventory',
  password: ''
})

const columns = [
  { accessorKey: 'name', header: 'Name' },
  { accessorKey: 'email', header: 'Email' },
  { accessorKey: 'role', header: 'Role' },
  { accessorKey: 'created_at', header: 'Created At' },
  { id: 'actions' }
]

const roleOptions = [
  { label: 'Super Admin', value: 'admin' },
  { label: 'Stock Manager', value: 'inventory' },
  { label: 'Order Manager', value: 'sales' }
]

const getRoleName = (role) => {
  const r = roleOptions.find(opt => opt.value === role)
  return r ? r.label : role
}

const getRoleColor = (role) => {
  if (role === 'admin') return 'red'
  if (role === 'inventory') return 'green'
  if (role === 'sales') return 'blue'
  return 'neutral'
}

const getItems = (row) => [
  [{
    label: 'Edit',
    icon: 'i-lucide-pencil',
    onSelect: () => openEditModal(row)
  }],
  [{
    label: 'Delete',
    icon: 'i-lucide-trash-2',
    color: 'error',
    onSelect: () => deleteUser(row.id)
  }]
]

const fetchUsers = async () => {
  pending.value = true
  const token = localStorage.getItem('maneesha-admin-token')
  try {
    const res = await fetch(`${API}/admin/users`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    if (res.ok) {
      users.value = await res.json()
    } else {
      toast.add({ title: 'Error fetching users', color: 'error' })
    }
  } catch (e) {
    console.error(e)
  }
  pending.value = false
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = { id: null, name: '', email: '', role: 'inventory', password: '' }
  isModalOpen.value = true
}

const openEditModal = (user) => {
  isEditing.value = true
  form.value = { id: user.id, name: user.name, email: user.email, role: user.role, password: '' }
  isModalOpen.value = true
}

const saveUser = async () => {
  saving.value = true
  const token = localStorage.getItem('maneesha-admin-token')
  
  const payload = {
    name: form.value.name,
    email: form.value.email,
    role: form.value.role
  }
  if (form.value.password) {
    payload.password = form.value.password
  }

  const url = isEditing.value ? `${API}/admin/users/${form.value.id}` : `${API}/admin/users`
  const method = isEditing.value ? 'PUT' : 'POST'

  try {
    const res = await fetch(url, {
      method,
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })
    
    if (res.ok) {
      toast.add({ title: `User ${isEditing.value ? 'updated' : 'created'} successfully`, color: 'success' })
      isModalOpen.value = false
      fetchUsers()
    } else {
      const err = await res.json()
      toast.add({ title: err.message || 'Error saving user', color: 'error' })
    }
  } catch (e) {
    toast.add({ title: 'Network error', color: 'error' })
  }
  saving.value = false
}

const deleteUser = async (id) => {
  if (!confirm('Are you sure you want to delete this user?')) return
  
  const token = localStorage.getItem('maneesha-admin-token')
  try {
    const res = await fetch(`${API}/admin/users/${id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    })
    
    if (res.ok) {
      toast.add({ title: 'User deleted successfully', color: 'success' })
      fetchUsers()
    } else {
      const err = await res.json()
      toast.add({ title: err.message || 'Error deleting user', color: 'error' })
    }
  } catch (e) {
    toast.add({ title: 'Network error', color: 'error' })
  }
}

onMounted(() => {
  if (adminRole.value !== 'admin') {
    toast.add({ title: 'Unauthorized access', color: 'error' })
    router.push('/')
    return
  }
  fetchUsers()
})
</script>

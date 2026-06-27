<template>
  <UDashboardPanel id="bank-accounts">
    <template #header>
      <UDashboardNavbar title="Bank Accounts">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton icon="i-lucide-plus" color="primary" @click="openModal()">
            Add Account
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <div>
          <p class="text-gray-500 dark:text-gray-400">Manage bank accounts available for customer payments.</p>
        </div>

        <UTable :data="accounts" :columns="columns" :loading="isLoading">
          <template #is_active-cell="{ row }">
            <UToggle v-model="row.original.is_active" @change="toggleActive(row.original)" />
          </template>
          <template #actions-cell="{ row }">
            <div class="flex gap-2">
              <UButton icon="i-lucide-pencil" size="sm" color="gray" variant="ghost" @click="openModal(row.original)" />
              <UButton icon="i-lucide-trash" size="sm" color="red" variant="ghost" @click="deleteAccount(row.original.id)" />
            </div>
          </template>
        </UTable>

        <!-- Form Modal -->
        <UModal v-model:open="isModalOpen">
          <template #content>
            <UCard :ui="{ divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
              <template #header>
                <div class="flex items-center justify-between">
                  <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                    {{ editingId ? 'Edit Bank Account' : 'Add Bank Account' }}
                  </h3>
                  <UButton color="gray" variant="ghost" icon="i-lucide-x" class="-my-1" @click="isModalOpen = false" />
                </div>
              </template>
              
              <form @submit.prevent="saveAccount" class="space-y-4">
                <UFormField label="Bank Name" required>
                  <UInput v-model="formData.bank_name" placeholder="e.g. Commercial Bank PLC" required />
                </UFormField>
                
                <UFormField label="Account Name" required>
                  <UInput v-model="formData.account_name" placeholder="e.g. Maneesha Fashion Store" required />
                </UFormField>
                
                <UFormField label="Account Number" required>
                  <UInput v-model="formData.account_number" placeholder="e.g. 100023456789" required />
                </UFormField>

                <UFormField label="Branch" required>
                  <UInput v-model="formData.branch" placeholder="e.g. Nugegoda Branch" required />
                </UFormField>

                <div class="flex justify-end gap-3 mt-4">
                  <UButton color="gray" variant="ghost" @click="isModalOpen = false">Cancel</UButton>
                  <UButton type="submit" color="primary" :loading="isSaving">Save</UButton>
                </div>
              </form>
            </UCard>
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
const toast = useToast()

const accounts = ref([])
const isLoading = ref(true)
const isModalOpen = ref(false)
const isSaving = ref(false)
const editingId = ref(null)

const columns = [
  { accessorKey: 'bank_name', header: 'Bank Name' },
  { accessorKey: 'account_name', header: 'Account Name' },
  { accessorKey: 'account_number', header: 'Account Number' },
  { accessorKey: 'branch', header: 'Branch' },
  { accessorKey: 'is_active', header: 'Active' },
  { id: 'actions' }
]

const formData = ref({
  bank_name: '',
  account_name: '',
  account_number: '',
  branch: '',
  is_active: true
})

const fetchAccounts = async () => {
  isLoading.value = true
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    const response = await $fetch(`${API}/admin/bank-accounts`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    accounts.value = response
  } catch (error) {
    toast.add({ title: 'Error fetching accounts', color: 'red', icon: 'i-lucide-alert-circle' })
  } finally {
    isLoading.value = false
  }
}

const openModal = (account = null) => {
  if (account) {
    editingId.value = account.id
    formData.value = { ...account }
  } else {
    editingId.value = null
    formData.value = {
      bank_name: '',
      account_name: '',
      account_number: '',
      branch: '',
      is_active: true
    }
  }
  isModalOpen.value = true
}

const saveAccount = async () => {
  isSaving.value = true
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    const method = editingId.value ? 'PUT' : 'POST'
    const url = editingId.value 
      ? `${API}/admin/bank-accounts/${editingId.value}` 
      : `${API}/admin/bank-accounts`
      
    await $fetch(url, {
      method,
      headers: { Authorization: `Bearer ${token}` },
      body: formData.value
    })
    
    toast.add({ title: 'Success', description: 'Account saved', color: 'green' })
    isModalOpen.value = false
    fetchAccounts()
  } catch (error) {
    toast.add({ title: 'Error saving account', description: error.message, color: 'red' })
  } finally {
    isSaving.value = false
  }
}

const deleteAccount = async (id) => {
  if (!confirm('Are you sure you want to delete this bank account?')) return
  
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    await $fetch(`${API}/admin/bank-accounts/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` }
    })
    toast.add({ title: 'Deleted', color: 'green' })
    fetchAccounts()
  } catch (error) {
    toast.add({ title: 'Error deleting account', color: 'red' })
  }
}

const toggleActive = async (account) => {
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    await $fetch(`${API}/admin/bank-accounts/${account.id}/toggle`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${token}` }
    })
    toast.add({ title: 'Status updated', color: 'green' })
  } catch (error) {
    // Revert visually on error
    account.is_active = !account.is_active
    toast.add({ title: 'Error updating status', color: 'red' })
  }
}

onMounted(() => {
  fetchAccounts()
})
</script>

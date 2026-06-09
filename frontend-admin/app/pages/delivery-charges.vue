<template>
  <UDashboardPage>
    <UDashboardPanel grow id="delivery-charges">
      <UDashboardNavbar title="Delivery Charges">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton icon="i-lucide-save" color="primary" :loading="isSaving" @click="saveCharges">
            Save Changes
          </UButton>
        </template>
      </UDashboardNavbar>

      <div class="p-4 flex-1 overflow-y-auto">
        <div class="mb-6">
          <p class="text-gray-500 dark:text-gray-400">Manage delivery fees for all 25 districts in Sri Lanka.</p>
        </div>

        <div v-if="isLoading" class="flex justify-center py-10">
          <UIcon name="i-lucide-loader-2" class="animate-spin text-4xl text-primary" />
        </div>

        <UCard v-else class="max-w-4xl w-full">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="district in districts" :key="district" class="flex flex-col">
              <label class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ district }}</label>
              <UInput 
                type="number" 
                v-model="charges[district]" 
                icon="i-lucide-coins" 
                placeholder="0.00" 
                min="0"
              >
                <template #leading>
                  <span class="text-gray-500 dark:text-gray-400 text-xs mr-1">LKR</span>
                </template>
              </UInput>
            </div>
          </div>
        </UCard>
      </div>
    </UDashboardPanel>
  </UDashboardPage>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const config = useRuntimeConfig()
const API = config.public.apiBase
const toast = useToast()

const isLoading = ref(true)
const isSaving = ref(false)

const districts = [
  'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
  'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
  'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
  'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
  'Moneragala', 'Ratnapura', 'Kegalle'
]

const charges = ref({})

const fetchCharges = async () => {
  try {
    const response = await $fetch(`${API}/settings/delivery-charges`)
    charges.value = response
  } catch (error) {
    toast.add({ title: 'Error fetching charges', color: 'red', icon: 'i-lucide-alert-circle' })
  } finally {
    isLoading.value = false
  }
}

const saveCharges = async () => {
  isSaving.value = true
  try {
    const token = useCookie('admin_token').value
    await $fetch(`${API}/admin/settings/delivery-charges`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}` },
      body: { charges: charges.value }
    })
    toast.add({ title: 'Success', description: 'Delivery charges saved successfully', color: 'green', icon: 'i-lucide-check-circle' })
  } catch (error) {
    toast.add({ title: 'Error saving charges', description: error.message, color: 'red', icon: 'i-lucide-alert-circle' })
  } finally {
    isSaving.value = false
  }
}

onMounted(() => {
  fetchCharges()
})
</script>

<template>
  <UDashboardPanel id="hero-sliders">
    <template #header>
      <UDashboardNavbar title="Hero Sliders">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton icon="i-lucide-plus" color="primary" @click="openModal()">
            Add Slide
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <div>
          <p class="text-gray-500 dark:text-gray-400">Manage the hero banner slides shown on the customer homepage.</p>
        </div>

        <div v-if="isLoading" class="flex flex-col items-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary-500 mb-4" />
        </div>

        <div v-else-if="slides.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <UCard v-for="slide in slides" :key="slide.id" class="flex flex-col h-full overflow-hidden" :ui="{ body: { padding: '' }, footer: { padding: 'p-4' } }">
            <div class="relative h-48 bg-gray-100 dark:bg-gray-800">
              <img :src="slide.image_url" class="w-full h-full object-cover" />
              <UBadge v-if="slide.is_active" color="emerald" class="absolute top-2 left-2">Active</UBadge>
              <UBadge v-else color="gray" class="absolute top-2 left-2">Hidden</UBadge>
            </div>
            
            <template #footer>
              <div class="flex gap-2">
                <UButton class="flex-1 justify-center" :color="slide.is_active ? 'gray' : 'emerald'" variant="solid" @click="toggleActive(slide)">
                  {{ slide.is_active ? 'Hide' : 'Show' }}
                </UButton>
                <UButton class="flex-1 justify-center" color="blue" variant="soft" icon="i-lucide-edit" @click="openEditModal(slide)">
                  Edit
                </UButton>
                <UButton class="flex-1 justify-center" color="red" variant="soft" icon="i-lucide-trash" @click="deleteSlide(slide.id)">
                  Delete
                </UButton>
              </div>
            </template>
          </UCard>
        </div>
        
        <div v-else class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
          <UIcon name="i-lucide-image" class="w-16 h-16 text-gray-300 mb-4" />
          <p class="text-gray-500 font-medium">No slides yet. Add your first hero slide!</p>
        </div>

        <!-- Add/Edit Modal -->
        <UModal v-model:open="isModalOpen" prevent-close>
          <template #content>
            <UCard :ui="{ divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
              <template #header>
                <div class="flex items-center justify-between">
                  <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                    {{ formData.id ? 'Edit Hero Slide Text' : 'Add Hero Slide' }}
                  </h3>
                  <UButton color="gray" variant="ghost" icon="i-lucide-x" class="-my-1" @click="isModalOpen = false" />
                </div>
              </template>
              
              <form @submit.prevent="saveSlide" class="space-y-4">
                <div v-if="!formData.id" class="flex flex-col items-center gap-4">
                  <div class="relative w-full h-48 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center cursor-pointer group" @click="$refs.fileInput.click()">
                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                    <div v-else class="flex flex-col items-center text-gray-500">
                      <UIcon name="i-lucide-upload-cloud" class="w-8 h-8 mb-2" />
                      <span>Click to upload image</span>
                      <span class="text-xs mt-1">Recommended size: 1920x800</span>
                    </div>
                  </div>
                  <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onImageChange" />
                </div>
                
                <!-- Text Fields -->
                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Subtitle (Top script text)">
                    <UInput v-model="formData.subtitle" placeholder="e.g. New Season" class="w-full" />
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Title Top">
                    <UInput v-model="formData.title_top" placeholder="e.g. TIMELESS" class="w-full" />
                  </UFormField>
                  <UFormField label="Title Bottom (Colored)">
                    <UInput v-model="formData.title_bottom" placeholder="e.g. ELEGANCE" class="w-full" />
                  </UFormField>
                </div>
                <UFormField label="Description">
                  <UTextarea v-model="formData.desc" placeholder="Brief description text..." class="w-full" :rows="3" />
                </UFormField>
                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Button Text">
                    <UInput v-model="formData.btn_text" placeholder="e.g. SHOP NOW" class="w-full" />
                  </UFormField>
                  <UFormField label="Button Link">
                    <UInput v-model="formData.btn_link" placeholder="e.g. /shop" class="w-full" />
                  </UFormField>
                </div>

                <UFormField label="Show Text Overlay on Slide">
                  <UToggle v-model="formData.show_text" />
                </UFormField>

                <div class="flex justify-end gap-3 mt-4">
                  <UButton color="gray" variant="ghost" @click="isModalOpen = false">Cancel</UButton>
                  <UButton type="submit" color="primary" :loading="isSaving" :disabled="!formData.id && !imageFile">{{ formData.id ? 'Save Changes' : 'Upload Slide' }}</UButton>
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

const slides = ref([])
const isLoading = ref(true)
const isModalOpen = ref(false)
const isSaving = ref(false)

const fileInput = ref(null)
const imageFile = ref(null)
const imagePreview = ref('')

const formData = ref({
  id: null,
  subtitle: '',
  title_top: '',
  title_bottom: '',
  desc: '',
  btn_text: 'SHOP NOW',
  btn_link: '/shop',
  show_text: true
})

const fetchSlides = async () => {
  isLoading.value = true
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    const response = await $fetch(`${API}/admin/hero-slides`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    slides.value = response
  } catch (error) {
    toast.add({ title: 'Error fetching slides', color: 'red', icon: 'i-lucide-alert-circle' })
  } finally {
    isLoading.value = false
  }
}

const openModal = () => {
  formData.value = {
    id: null,
    subtitle: '',
    title_top: '',
    title_bottom: '',
    desc: '',
    btn_text: 'SHOP NOW',
    btn_link: '/shop',
    show_text: true
  }
  imageFile.value = null
  imagePreview.value = ''
  isModalOpen.value = true
}

const openEditModal = (slide) => {
  formData.value = {
    id: slide.id,
    subtitle: slide.subtitle || '',
    title_top: slide.title_top || '',
    title_bottom: slide.title_bottom || '',
    desc: slide.desc || '',
    btn_text: slide.btn_text || 'SHOP NOW',
    btn_link: slide.btn_link || '/shop',
    show_text: slide.show_text === undefined ? true : !!slide.show_text
  }
  imageFile.value = null // image won't be edited
  isModalOpen.value = true
}

const onImageChange = (e) => {
  const file = e.target.files[0]
  if (!file) return
  if (file.size > 5 * 1024 * 1024) {
    toast.add({ title: 'Image too large', description: 'Maximum 5MB allowed', color: 'red' })
    return
  }
  imageFile.value = file
  imagePreview.value = URL.createObjectURL(file)
}

const saveSlide = async () => {
  if (!formData.value.id && !imageFile.value) return
  
  isSaving.value = true
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    
    if (formData.value.id) {
      // Update text fields only
      await $fetch(`${API}/admin/hero-slides/${formData.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${token}` },
        body: formData.value
      })
      toast.add({ title: 'Success', description: 'Slide text updated', color: 'green' })
    } else {
      // Upload new slide
      const fd = new FormData()
      fd.append('image', imageFile.value)
      
      // Append text fields if any
      const keys = ['subtitle', 'title_top', 'title_bottom', 'desc', 'btn_text', 'btn_link', 'show_text']
      keys.forEach(k => {
        if (formData.value[k] !== undefined && formData.value[k] !== null) {
          fd.append(k, formData.value[k])
        }
      })
        
      await $fetch(`${API}/admin/hero-slides`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` }, // Do not set Content-Type; browser sets it with boundary
        body: fd
      })
      toast.add({ title: 'Success', description: 'Slide uploaded', color: 'green' })
    }
    
    isModalOpen.value = false
    fetchSlides()
  } catch (error) {
    toast.add({ title: 'Error saving slide', description: error.message, color: 'red' })
  } finally {
    isSaving.value = false
  }
}

const deleteSlide = async (id) => {
  if (!confirm('Are you sure you want to delete this slide?')) return
  
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    await $fetch(`${API}/admin/hero-slides/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` }
    })
    toast.add({ title: 'Deleted', color: 'green' })
    fetchSlides()
  } catch (error) {
    toast.add({ title: 'Error deleting slide', color: 'red' })
  }
}

const toggleActive = async (slide) => {
  try {
    const token = (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token'))
    const response = await $fetch(`${API}/admin/hero-slides/${slide.id}/toggle`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${token}` }
    })
    slide.is_active = response.is_active
    toast.add({ title: 'Status updated', color: 'green' })
  } catch (error) {
    toast.add({ title: 'Error updating status', color: 'red' })
  }
}

onMounted(() => {
  fetchSlides()
})
</script>

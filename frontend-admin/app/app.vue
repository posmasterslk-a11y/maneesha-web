<template>
  <UApp>
    <NuxtLoadingIndicator />
    <NuxtLayout>
      <NuxtPage @authenticated="handleAuth" />
    </NuxtLayout>
  </UApp>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, provide } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const colorMode = useColorMode()
const color = computed(() => colorMode.value === 'dark' ? '#1b1718' : 'white')

useHead({
  meta: [
    { charset: 'utf-8' },
    { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    { key: 'theme-color', name: 'theme-color', content: color as any }
  ],
  link: [
    { rel: 'icon', href: '/favicon.ico' }
  ],
  htmlAttrs: {
    lang: 'en'
  }
})

const router = useRouter()
const route  = useRoute()

const isAuthenticated = ref(false)
const adminName = ref('Admin')

provide('isAuthenticated', isAuthenticated)
provide('adminName', adminName)

const handleAuth = () => {
  isAuthenticated.value = true
  adminName.value = localStorage.getItem('maneesha-admin-name') || 'Admin'
}

provide('isAuthenticated', isAuthenticated)

onMounted(() => {
  if (typeof window !== 'undefined') {
    const auth  = localStorage.getItem('maneesha-admin-auth')
    const token = localStorage.getItem('maneesha-admin-token')

    if (auth === 'true' && token) {
      isAuthenticated.value = true
      adminName.value = localStorage.getItem('maneesha-admin-name') || 'Admin'
    } else {
      localStorage.removeItem('maneesha-admin-auth')
      localStorage.removeItem('maneesha-admin-token')
      isAuthenticated.value = false
      router.push('/login')
    }
  }
})
</script>

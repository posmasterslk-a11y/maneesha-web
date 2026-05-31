<template>
  <div class="admin-app-root">
    <!-- If not authenticated, let the login page occupy the whole viewport -->
    <div v-if="!isAuthenticated">
      <NuxtPage @authenticated="handleAuth" />
    </div>

    <!-- Authenticated Layout Structure -->
    <div v-else class="admin-layout">
      <!-- Executive Sidebar -->
      <aside class="admin-sidebar">
        <div>
          <div class="sidebar-logo">
            <i class="fa-solid fa-scissors"></i>
            <span>Maneesha <span>Admin</span></span>
          </div>

          <nav class="sidebar-nav">
            <NuxtLink to="/" class="sidebar-link" active-class="active" exact>
              <i class="fa-solid fa-chart-line"></i> Dashboard
            </NuxtLink>
            <NuxtLink to="/products" class="sidebar-link" active-class="active">
              <i class="fa-solid fa-shirt"></i> Products
            </NuxtLink>
            <NuxtLink to="/categories" class="sidebar-link" active-class="active">
              <i class="fa-solid fa-tags"></i> Categories
            </NuxtLink>
            <NuxtLink to="/orders" class="sidebar-link" active-class="active">
              <i class="fa-solid fa-cart-shopping"></i> Orders
            </NuxtLink>
          </nav>
        </div>

        <div class="sidebar-footer">
          <div class="admin-profile">
            <div class="admin-avatar">{{ adminName.charAt(0).toUpperCase() }}</div>
            <div class="flex-1">
              <span class="admin-name">{{ adminName }}</span>
              <span class="admin-role">Store Admin</span>
            </div>
            <button @click="logout" class="icon-btn" aria-label="Logout">
              <i class="fa-solid fa-right-from-bracket"></i>
            </button>
          </div>
        </div>
      </aside>

      <!-- Main Admin Panel Area -->
      <main class="admin-main">

        <section class="admin-content">
          <NuxtPage />
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, provide } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route  = useRoute()

const isAuthenticated = ref(false)
const adminName = ref('Admin')

const pageTitle = computed(() => {
  if (route.path === '/')           return 'Store Overview & Orders'
  if (route.path === '/products')   return 'Product Catalog'
  if (route.path === '/categories') return 'Category Management'
  if (route.path === '/orders')     return 'Order Management'
  return 'Maneesha Fashion Admin'
})

const handleAuth = () => {
  isAuthenticated.value = true
  adminName.value = localStorage.getItem('maneesha-admin-name') || 'Admin'
}

const logout = () => {
  isAuthenticated.value = false
  localStorage.removeItem('maneesha-admin-auth')
  localStorage.removeItem('maneesha-admin-token')
  localStorage.removeItem('maneesha-admin-name')
  router.push('/login')
}

provide('isAuthenticated', isAuthenticated)

onMounted(() => {
  if (typeof window !== 'undefined') {
    const auth  = localStorage.getItem('maneesha-admin-auth')
    const token = localStorage.getItem('maneesha-admin-token')

    // Require BOTH auth flag AND a valid token — clear stale old sessions
    if (auth === 'true' && token) {
      isAuthenticated.value = true
      adminName.value = localStorage.getItem('maneesha-admin-name') || 'Admin'
    } else {
      // Clear any stale data from old hardcoded login
      localStorage.removeItem('maneesha-admin-auth')
      localStorage.removeItem('maneesha-admin-token')
      isAuthenticated.value = false
      router.push('/login')
    }
  }
})
</script>

<style>
@import '~/assets/css/main.css';

.admin-app-root {
  min-height: 100vh;
}

.flex-1 {
  flex: 1;
}

.icon-btn {
  background: transparent;
  color: #94a3b8;
  cursor: pointer;
  font-size: 1.15rem;
  transition: var(--transition-smooth);
}

.icon-btn:hover {
  color: var(--accent-error);
}
</style>

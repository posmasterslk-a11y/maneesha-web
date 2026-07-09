<template>
  <div class="login-page">
    <div class="login-card glass-panel animate-fade-up">
      <div class="login-header">
        <i class="fa-solid fa-scissors login-icon"></i>
        <h2 class="luxury-title">Maneesha <span>Admin</span></h2>
        <p>Enter your workshop administrative credentials to manage clothes, orders, and dispatches.</p>
      </div>

      <form @submit.prevent="handleLogin" class="login-form">
        <div v-if="errorMsg" class="error-banner">
          <i class="fa-solid fa-triangle-exclamation"></i> {{ errorMsg }}
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" v-model="email" class="form-input" required autocomplete="email" />
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" v-model="password" class="form-input" required placeholder="••••••••" autocomplete="current-password" />
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;">
          <input type="checkbox" id="rememberMe" v-model="rememberMe" style="accent-color: var(--admin-gold);" />
          <label for="rememberMe" class="form-label" style="margin-bottom: 0; cursor: pointer;">Remember me on this device</label>
        </div>

        <button type="submit" :disabled="isLoading" class="btn-admin btn-block">
          <span v-if="isLoading"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Verifying...</span>
          <span v-else>Authorize & Enter <i class="fa-solid fa-key ml-2"></i></span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

definePageMeta({
  layout: false
})

const router = useRouter()
const route = useRoute()

const email    = ref('')
const password = ref('')
const rememberMe = ref(false)
const isLoading = ref(false)
const errorMsg  = ref('')

onMounted(() => {
  if (route.query.reason === 'duplicate') {
    errorMsg.value = 'Another person logged into this user account. You have been disconnected.'
    // Optional: remove query param from url without reloading
    router.replace({ query: {} })
  }
})

const handleLogin = async () => {
  isLoading.value = true
  errorMsg.value  = ''

  try {
    const config = useRuntimeConfig()
    const res = await fetch(`${config.public.apiBase}/admin/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })

    const data = await res.json()

    if (res.ok && data.token) {
      const storage = rememberMe.value ? localStorage : sessionStorage
      storage.setItem('maneesha-admin-auth', 'true')
      storage.setItem('maneesha-admin-token', data.token)
      storage.setItem('maneesha-admin-name', data.user?.name || 'Admin')
      storage.setItem('maneesha-admin-role', data.user?.role || 'admin')
      router.push('/')
    } else {
      errorMsg.value = data.message || 'Invalid email or password.'
    }
  } catch (e) {
    console.error(e)
    errorMsg.value = 'Error: ' + e.message + ' (Check console for details)'
  }

  isLoading.value = false
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
  padding: 20px;
}

.login-card {
  width: 100%;
  max-width: 440px;
  padding: 40px;
  background: rgba(30, 41, 59, 0.7);
  border: 1px solid rgba(255,255,255,0.08);
  box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
  color: #fff;
  border-radius: var(--radius-md);
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.login-icon {
  font-size: 3rem;
  color: var(--admin-gold);
  margin-bottom: 15px;
}

.login-header h2 {
  font-size: 2rem;
  color: #fff;
}

.login-header h2 span {
  color: var(--admin-gold);
}

.login-header p {
  font-size: 0.8rem;
  color: #94a3b8;
  margin-top: 8px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  color: #94a3b8;
  display: block;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border-radius: var(--radius-md, 8px);
  border: 1px solid rgba(255,255,255,0.1);
  background: rgba(15, 23, 42, 0.6);
  color: #fff;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--admin-gold);
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
}

.btn-admin {
  background: var(--admin-gold, #d4af37);
  color: #000;
  font-weight: 700;
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: var(--radius-md, 8px);
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1.05rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-admin:hover {
  background: #b8901c;
  transform: translateY(-2px);
}

.btn-block {
  width: 100%;
}

.ml-2 {
  margin-left: 8px;
}

.error-banner {
  background: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #fca5a5;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 8px;
}
</style>

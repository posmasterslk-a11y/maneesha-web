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
          <div class="password-input-wrapper">
            <input :type="showPassword ? 'text' : 'password'" v-model="password" class="form-input" required placeholder="••••••••" autocomplete="current-password" />
            <button type="button" class="password-toggle" @click="showPassword = !showPassword" tabindex="-1">
              <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
          </div>
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
const showPassword = ref(false)
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
  background: #f4f7f6; /* Clean soft white/grey background */
  padding: 20px;
}

.login-card {
  width: 100%;
  max-width: 440px;
  padding: 40px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
  color: #374151;
  border-radius: 12px;
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.login-icon {
  font-size: 3rem;
  color: var(--admin-gold, #d4af37);
  margin-bottom: 15px;
}

.login-header h2 {
  font-size: 2rem;
  color: #111827;
  font-weight: 600;
}

.login-header h2 span {
  color: var(--admin-gold, #d4af37);
}

.login-header p {
  font-size: 0.85rem;
  color: #6b7280;
  margin-top: 8px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  color: #4b5563;
  display: block;
  margin-bottom: 8px;
  font-size: 0.9rem;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #ffffff;
  color: #1f2937;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--admin-gold, #d4af37);
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
}

.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-input-wrapper .form-input {
  padding-right: 40px;
}

.password-toggle {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
  outline: none;
}

.password-toggle:hover, .password-toggle:focus {
  color: #4b5563;
}

.btn-admin {
  background: #111827;
  color: #ffffff;
  font-weight: 600;
  width: 100%;
  padding: 14px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1.05rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-admin:hover {
  background: #1f2937;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-block {
  width: 100%;
}

.ml-2 {
  margin-left: 8px;
}

.error-banner {
  background: #fee2e2;
  border: 1px solid #fca5a5;
  color: #b91c1c;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 8px;
}
</style>

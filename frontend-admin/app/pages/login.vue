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
          <input type="email" v-model="email" class="form-input" required placeholder="admin@maneeshafashion.lk" autocomplete="email" />
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" v-model="password" class="form-input" required placeholder="••••••••" autocomplete="current-password" />
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'

definePageMeta({
  layout: false
})

const router = useRouter()

const email    = ref('')
const password = ref('')
const isLoading = ref(false)
const errorMsg  = ref('')

const handleLogin = async () => {
  isLoading.value = true
  errorMsg.value  = ''

  try {
    const res = await fetch('http://localhost:8000/api/admin/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })

    const data = await res.json()

    if (res.ok && data.token) {
      localStorage.setItem('maneesha-admin-auth', 'true')
      localStorage.setItem('maneesha-admin-token', data.token)
      localStorage.setItem('maneesha-admin-name', data.user?.name || 'Admin')
      router.push('/')
    } else {
      errorMsg.value = data.message || 'Invalid email or password.'
    }
  } catch (e) {
    errorMsg.value = 'Cannot connect to server. Make sure backend is running on port 8000.'
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

.form-label {
  color: #94a3b8;
}

.form-input {
  background: rgba(15, 23, 42, 0.6);
  border-color: rgba(255,255,255,0.1);
  color: #fff;
}

.form-input:focus {
  border-color: var(--admin-gold);
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
}

.btn-admin {
  background: var(--admin-gold);
  color: #000;
  font-weight: 700;
  width: 100%;
  padding: 14px;
}

.btn-admin:hover {
  background: #b8901c;
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

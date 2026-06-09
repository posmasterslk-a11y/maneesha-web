<template>
  <div :class="{ 'dark-mode': isDarkMode }">
    <!-- Desktop Premium Header -->
    <header class="desktop-header glass-panel">
      <div class="header-container">
        <NuxtLink to="/" class="logo-link">
          <img src="/images/maneesha_logo.PNG" alt="Maneesha Fashion Logo" class="header-logo" />
        </NuxtLink>
        
        <nav class="nav-links">
          <NuxtLink to="/" active-class="active-nav">Home</NuxtLink>
          <NuxtLink to="/shop" active-class="active-nav">Shop Collection</NuxtLink>
          <NuxtLink to="/orders" active-class="active-nav">My Orders</NuxtLink>
        </nav>
        
        <div class="header-actions">
          <button @click="toggleTheme" class="icon-btn" aria-label="Toggle theme">
            <i :class="isDarkMode ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
          </button>
          
          <NuxtLink to="/cart" class="cart-btn-desktop">
            <i class="fa-solid fa-bag-shopping"></i>
            <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
          </NuxtLink>
        </div>
      </div>
    </header>

    <!-- Mobile Header -->
    <header class="mobile-header glass-panel">
      <button @click="toggleDrawer" class="menu-btn" aria-label="Open menu">
        <i class="fa-solid fa-bars-staggered"></i>
      </button>
      
      <NuxtLink to="/" class="logo-link">
        <img src="/images/maneesha_logo.PNG" alt="Maneesha Fashion Logo" class="header-logo-mobile" />
      </NuxtLink>
      
      <button @click="toggleTheme" class="icon-btn" aria-label="Toggle theme">
        <i :class="isDarkMode ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
      </button>
    </header>

    <!-- App Drawer (Mobile Sidebar) -->
    <div :class="['app-drawer-overlay', { 'drawer-open': isDrawerOpen }]" @click.self="toggleDrawer">
      <aside class="app-drawer glass-panel">
        <div class="drawer-header">
          <img src="/images/maneesha_logo.PNG" alt="Maneesha Fashion Logo" class="header-logo-drawer" />
          <button @click="toggleDrawer" class="close-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div class="drawer-body">
          <nav class="drawer-nav">
            <NuxtLink to="/" @click="toggleDrawer" active-class="active-drawer-nav">
              <i class="fa-solid fa-house"></i> Home
            </NuxtLink>
            <NuxtLink to="/shop" @click="toggleDrawer" active-class="active-drawer-nav">
              <i class="fa-solid fa-bag-shopping"></i> Shop Collection
            </NuxtLink>
            <NuxtLink to="/cart" @click="toggleDrawer" active-class="active-drawer-nav">
              <i class="fa-solid fa-cart-shopping"></i> Shopping Cart
            </NuxtLink>
            <NuxtLink to="/orders" @click="toggleDrawer" active-class="active-drawer-nav">
              <i class="fa-solid fa-receipt"></i> Track Orders
            </NuxtLink>
          </nav>
          
          <div class="drawer-footer">
            <p>Sri Lankan style, modern fashion.</p>
            <span>Made with premium quality fabrics.</span>
          </div>
        </div>
      </aside>
    </div>

    <!-- Main Content Area -->
    <main class="app-content-wrapper">
      <NuxtPage />
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav glass-panel">
      <NuxtLink to="/" class="nav-item" active-class="active-nav-item">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
      </NuxtLink>
      <NuxtLink to="/shop" class="nav-item" active-class="active-nav-item">
        <i class="fa-solid fa-bag-shopping"></i>
        <span>Shop</span>
      </NuxtLink>
      <NuxtLink to="/cart" class="nav-item cart-item" active-class="active-nav-item">
        <div class="icon-wrapper">
          <i class="fa-solid fa-cart-shopping"></i>
          <span v-if="cartCount > 0" class="cart-badge-bottom">{{ cartCount }}</span>
        </div>
        <span>Cart</span>
      </NuxtLink>
      <NuxtLink to="/orders" class="nav-item" active-class="active-nav-item">
        <i class="fa-solid fa-receipt"></i>
        <span>Orders</span>
      </NuxtLink>
    </nav>

    <!-- Premium Footer (Desktop/Large Screens) -->
    <footer class="app-footer">
      <div class="container footer-grid">
        <div>
          <img src="/images/maneesha_logo.PNG" alt="Maneesha Fashion Logo" class="footer-logo" />
          <p>Hand-selected elegance for your unique style. Standard premium sizes with Sri Lanka's finest quality.</p>
        </div>
        <div>
          <h4>Explore</h4>
          <ul>
            <li><NuxtLink to="/shop">All Clothing</NuxtLink></li>
            <li><NuxtLink to="/orders">Order Tracking</NuxtLink></li>
            <li><NuxtLink to="/about">About Us</NuxtLink></li>
            <li><NuxtLink to="/contact">Contact Us</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h4>Support & Delivery</h4>
          <ul>
            <li>Secure Bank Deposit Integration</li>
            <li><NuxtLink to="/privacy">Privacy Policy</NuxtLink></li>
            <li><NuxtLink to="/terms">Terms & Conditions</NuxtLink></li>
            <li><NuxtLink to="/returns">Return Policy</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h4>Contact & Location</h4>
          <p><i class="fa-solid fa-phone"></i> 076 153 7772</p>
          <p><i class="fa-solid fa-envelope"></i> info@maneeshafashion.lk</p>
          <p style="margin-top: 5px;"><i class="fa-solid fa-location-dot"></i> No 78/1 Senanayake Rd, North Seeduwa, Seeduwa</p>
          <div class="social-links mt-4" style="margin-top: 15px;">
            <a href="https://www.facebook.com/manishafashion/" target="_blank" aria-label="Facebook" style="display: inline-block; padding: 5px; color: var(--primary-gold); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
              <i class="fa-brands fa-facebook" style="font-size: 1.8rem;"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 Maneesha Fashion. All Rights Reserved. Designed for visual excellence. | Web By <a href="https://www.facebook.com/profile.php?id=61590317867191" target="_blank" style="color: var(--primary-gold); font-weight: bold; text-decoration: none; transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">W E B R I X</a></p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const isDarkMode = ref(false)
const isDrawerOpen = ref(false)

// Global state for shopping cart synchronized across pages
const cart = ref([])

const cartCount = computed(() => {
  return cart.value.reduce((total, item) => total + item.quantity, 0)
})

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value
  if (typeof window !== 'undefined') {
    localStorage.setItem('maneesha-theme', isDarkMode.value ? 'dark' : 'light')
    if (isDarkMode.value) {
      document.body.classList.add('dark-mode')
    } else {
      document.body.classList.remove('dark-mode')
    }
  }
}

const toggleDrawer = () => {
  isDrawerOpen.value = !isDrawerOpen.value
}

// Global functions to manage cart provided to child pages
const loadCart = () => {
  if (typeof window !== 'undefined') {
    const saved = localStorage.getItem('maneesha-cart')
    cart.value = saved ? JSON.parse(saved) : []
  }
}

const updateCart = (newCart) => {
  cart.value = newCart
  if (typeof window !== 'undefined') {
    localStorage.setItem('maneesha-cart', JSON.stringify(newCart))
  }
}

const addToCart = (product, selectedSize, price, quantity = 1, image) => {
  loadCart()
  const current = [...cart.value]
  const idx = current.findIndex(item => item.id === product.id && item.size === selectedSize)
  
  if (idx > -1) {
    current[idx].quantity += quantity
  } else {
    current.push({
      id: product.id,
      name: product.name,
      slug: product.slug,
      size: selectedSize,
      price: price,
      quantity: quantity,
      image: image || '/placeholder.jpg'
    })
  }
  updateCart(current)
}

// Provide cart context globally
provide('cart', computed(() => cart.value))
provide('addToCart', addToCart)
provide('updateCart', updateCart)
provide('loadCart', loadCart)

onMounted(() => {
  loadCart()
  if (typeof window !== 'undefined') {
    const theme = localStorage.getItem('maneesha-theme')
    isDarkMode.value = theme === 'dark'
    if (isDarkMode.value) {
      document.body.classList.add('dark-mode')
    }
  }
})
</script>

<style>
@import '~/assets/css/main.css';

/* Header Styles */
.desktop-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 80px;
  z-index: 1000;
  display: flex;
  align-items: center;
  padding: 0 40px;
  border-radius: 0;
  border-bottom: 1px solid var(--bg-light-border);
}

body.dark-mode .desktop-header {
  border-bottom: 1px solid var(--bg-dark-border);
}

.header-container {
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
}

.header-logo {
  height: 45px;
  width: auto;
  display: block;
}

.header-logo-mobile {
  height: 35px;
  width: auto;
  display: block;
}

.header-logo-drawer {
  height: 40px;
  width: auto;
  display: block;
}

.footer-logo {
  height: 80px;
  width: auto;
  margin-bottom: 20px;
  display: block;
}

.logo {
  font-size: 1.6rem;
  letter-spacing: -1px;
}

.nav-links {
  display: flex;
  gap: 30px;
  font-weight: 500;
  font-size: 0.95rem;
}

.nav-links a:hover, .active-nav {
  color: var(--primary-gold);
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.icon-btn {
  background: transparent;
  cursor: pointer;
  font-size: 1.25rem;
  color: inherit;
  transition: var(--transition-smooth);
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

.icon-btn:hover {
  background: rgba(212, 175, 55, 0.1);
  color: var(--primary-gold);
}

.cart-btn-desktop {
  position: relative;
  font-size: 1.35rem;
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: var(--accent-error);
  color: #fff;
  font-size: 0.75rem;
  font-weight: 700;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* Mobile Header */
.mobile-header {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 64px;
  z-index: 1000;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-radius: 0;
}

.menu-btn {
  background: transparent;
  font-size: 1.3rem;
  color: inherit;
  cursor: pointer;
}

/* App Drawer */
.app-drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(4px);
  z-index: 2000;
  opacity: 0;
  pointer-events: none;
  transition: var(--transition-smooth);
}

.app-drawer-overlay.drawer-open {
  opacity: 1;
  pointer-events: all;
}

.app-drawer {
  position: fixed;
  top: 0;
  bottom: 0;
  left: -320px;
  width: 300px;
  z-index: 2001;
  border-radius: 0;
  box-shadow: 10px 0 30px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  transition: var(--transition-smooth);
}

.drawer-open .app-drawer {
  left: 0;
}

.drawer-header {
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  border-bottom: 1px solid var(--bg-light-border);
}

body.dark-mode .drawer-header {
  border-bottom: 1px solid var(--bg-dark-border);
}

.close-btn {
  background: transparent;
  font-size: 1.4rem;
  color: inherit;
  cursor: pointer;
}

.drawer-body {
  flex: 1;
  padding: 30px 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.drawer-nav {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.drawer-nav a {
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: 1.05rem;
  font-weight: 500;
  padding: 12px 18px;
  border-radius: var(--radius-md);
}

.drawer-nav a i {
  width: 24px;
  font-size: 1.15rem;
}

.active-drawer-nav {
  background: rgba(212, 175, 55, 0.1);
  color: var(--primary-gold);
  font-weight: 600 !important;
}

.drawer-footer {
  text-align: center;
  font-size: 0.8rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .drawer-footer {
  color: var(--text-light-secondary);
}

.drawer-footer p {
  font-family: var(--font-serif);
  font-style: italic;
  font-size: 0.95rem;
  margin-bottom: 6px;
}

/* Bottom Mobile Nav */
.mobile-bottom-nav {
  display: none;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: 68px;
  z-index: 1000;
  border-radius: 0;
  box-shadow: 0 -8px 25px rgba(0,0,0,0.05);
  align-items: center;
  justify-content: space-around;
  border-top: 1px solid var(--bg-light-border);
  padding-bottom: 5px;
}

body.dark-mode .mobile-bottom-nav {
  border-top: 1px solid var(--bg-dark-border);
}

.nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--text-dark-secondary);
  transition: var(--transition-smooth);
}

body.dark-mode .nav-item {
  color: var(--text-light-secondary);
}

.nav-item i {
  font-size: 1.25rem;
  margin-bottom: 3px;
}

.active-nav-item {
  color: var(--primary-gold) !important;
}

.cart-item {
  position: relative;
}

.icon-wrapper {
  position: relative;
}

.cart-badge-bottom {
  position: absolute;
  top: -8px;
  right: -10px;
  background: var(--accent-error);
  color: #fff;
  font-size: 0.65rem;
  font-weight: 700;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

/* Content Wrapper */
.app-content-wrapper {
  padding-top: 100px;
  min-height: calc(100vh - 400px);
}

/* Footer */
.app-footer {
  background: #0f1013;
  color: #fff;
  padding: 80px 0 20px 0;
  margin-top: 80px;
  border-top: 1px solid rgba(255,255,255,0.05);
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1.2fr 1.3fr;
  gap: 40px;
  margin-bottom: 60px;
}

.footer-grid h3 {
  font-size: 1.8rem;
  margin-bottom: 15px;
}

.footer-grid h4 {
  font-size: 1.1rem;
  margin-bottom: 20px;
  border-left: 2px solid var(--primary-gold);
  padding-left: 10px;
  text-transform: uppercase;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.footer-grid p {
  color: #a0a5ab;
  font-size: 0.9rem;
}

.footer-grid ul {
  list-style: none;
}

.footer-grid ul li {
  margin-bottom: 12px;
  color: #a0a5ab;
  font-size: 0.9rem;
}

.footer-grid ul li a:hover {
  color: var(--primary-gold);
}

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.05);
  padding-top: 25px;
  text-align: center;
  color: #6a7075;
  font-size: 0.85rem;
}

/* Responsive adjustments */
@media (max-width: 900px) {
  .footer-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 768px) {
  .desktop-header {
    display: none;
  }
  
  .mobile-header {
    display: flex;
  }
  
  .mobile-bottom-nav {
    display: flex;
  }
  
  .app-content-wrapper {
    padding-top: 80px;
    padding-bottom: 80px;
  }
  
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 30px;
  }
  
  .app-footer {
    padding-bottom: 100px;
  }
}
</style>

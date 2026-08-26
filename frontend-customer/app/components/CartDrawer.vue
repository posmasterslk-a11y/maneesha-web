<template>
  <div>
    <!-- Cart Drawer Overlay -->
    <div 
      :class="['cart-drawer-overlay', { 'drawer-open': isCartDrawerOpen }]" 
      @click.self="toggleCartDrawer"
    ></div>

    <!-- Cart Drawer -->
    <aside :class="['cart-drawer glass-panel', { 'drawer-open': isCartDrawerOpen }]">
      <div class="drawer-header">
        <h3 class="luxury-title" style="margin: 0; font-size: 1.5rem;">Your <span class="gold-gradient-text">Cart</span></h3>
        <button @click="toggleCartDrawer" class="close-btn"><i class="fa-solid fa-xmark"></i></button>
      </div>
      
      <div class="drawer-body">
        <div v-if="cart.length > 0" class="cart-items">
          <div v-for="(item, idx) in cart" :key="idx" class="cart-item">
            <div class="item-visual" style="border: 1px solid var(--bg-light-border); overflow: hidden;">
              <img v-if="item.variant_image || item.image" :src="item.variant_image || item.image" alt="Product" style="width: 100%; height: 100%; object-fit: cover;" />
              <i v-else class="fa-solid fa-scissors"></i>
            </div>
            
            <div class="item-info">
              <h4 class="item-name">{{ item.name }}</h4>
              <div class="item-meta">
                <span class="badge">{{ item.size }}</span>
                <span v-if="item.color_name" class="badge">{{ item.color_name }}</span>
              </div>
              <div class="item-price">LKR {{ formatNumber(item.price * item.quantity) }}</div>
              
              <div class="qty-controls">
                <div class="qty-btn-group">
                  <button @click="adjustQty(item, -1)"><i class="fa-solid fa-minus"></i></button>
                  <span>{{ item.quantity }}</span>
                  <button @click="adjustQty(item, 1)"><i class="fa-solid fa-plus"></i></button>
                </div>
                <button @click="removeItem(item)" class="remove-btn"><i class="fa-regular fa-trash-can"></i></button>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="empty-cart-msg">
          <i class="fa-solid fa-bag-shopping" style="font-size: 3rem; color: var(--primary-gold); margin-bottom: 15px;"></i>
          <h4>Your cart is empty</h4>
          <p>Browse our collection to add items.</p>
          <button @click="goToShop" class="btn-premium btn-gold" style="padding: 8px 20px; margin-top: 15px; font-size: 0.9rem;">Shop Now</button>
        </div>
      </div>
      
      <div v-if="cart.length > 0" class="drawer-footer">
        <div class="subtotal">
          <span>Subtotal:</span>
          <span class="gold-gradient-text" style="font-size: 1.3rem; font-weight: 700;">LKR {{ formatNumber(subtotal) }}</span>
        </div>
        <div class="drawer-actions">
          <NuxtLink to="/cart" @click="toggleCartDrawer" class="btn-premium" style="background: transparent; border: 1px solid var(--primary-gold); flex: 1; text-align: center; color: inherit;">View Cart</NuxtLink>
          <button @click="proceedToCheckout" class="btn-premium btn-gold" style="flex: 1;">Checkout</button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { inject, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const cart = inject('cart')
const updateCart = inject('updateCart')
const isCartDrawerOpen = inject('isCartDrawerOpen')
const toggleCartDrawer = inject('toggleCartDrawer')

const subtotal = computed(() => {
  return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const adjustQty = (item, diff) => {
  const current = [...cart.value]
  const idx = current.findIndex(i => i.id === item.id && i.size === item.size && i.color_name === item.color_name)
  if (idx > -1) {
    const nextQty = current[idx].quantity + diff
    if (nextQty >= 1) {
      current[idx].quantity = nextQty
      updateCart(current)
    }
  }
}

const removeItem = (item) => {
  const current = [...cart.value].filter(i => !(i.id === item.id && i.size === item.size && i.color_name === item.color_name))
  updateCart(current)
}

const goToShop = () => {
  toggleCartDrawer()
  router.push('/shop')
}

const proceedToCheckout = () => {
  toggleCartDrawer()
  router.push('/checkout')
}
</script>

<style scoped>
.cart-drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(3px);
  z-index: 3000;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
}

.cart-drawer-overlay.drawer-open {
  opacity: 1;
  pointer-events: all;
}

.cart-drawer {
  position: fixed;
  top: 0;
  bottom: 0;
  right: -400px;
  width: 400px;
  max-width: 100vw;
  z-index: 3001;
  display: flex;
  flex-direction: column;
  transition: right 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  box-shadow: -5px 0 25px rgba(0,0,0,0.1);
  background: var(--bg-light);
}

body.dark-mode .cart-drawer {
  background: var(--bg-dark);
}

.cart-drawer.drawer-open {
  right: 0;
}

.drawer-header {
  height: 70px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-bottom: 1px solid var(--bg-light-border);
}

body.dark-mode .drawer-header {
  border-bottom: 1px solid var(--bg-dark-border);
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  color: inherit;
  cursor: pointer;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s;
}

.close-btn:hover {
  background: rgba(212, 175, 55, 0.1);
  color: var(--primary-gold);
}

.drawer-body {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  display: flex;
  gap: 15px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--bg-light-border);
}

body.dark-mode .cart-item {
  border-bottom: 1px solid var(--bg-dark-border);
}

.item-visual {
  width: 80px;
  height: 90px;
  border-radius: var(--radius-sm);
  background: rgba(212, 175, 55, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: var(--primary-gold);
  flex-shrink: 0;
}

.item-info {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.item-name {
  font-family: var(--font-serif);
  font-size: 1.05rem;
  margin-bottom: 5px;
  line-height: 1.2;
}

.item-meta {
  margin-bottom: 8px;
}

.badge {
  display: inline-block;
  font-size: 0.7rem;
  background: rgba(212, 175, 55, 0.1);
  color: var(--primary-gold-dark);
  padding: 2px 8px;
  border-radius: 4px;
  margin-right: 5px;
  font-weight: 600;
}

.item-price {
  font-size: 0.95rem;
  font-weight: 700;
  margin-bottom: 10px;
}

.qty-controls {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: auto;
}

.qty-btn-group {
  display: flex;
  align-items: center;
  border: 1px solid var(--bg-light-border);
  border-radius: var(--radius-sm);
}

body.dark-mode .qty-btn-group {
  border-color: var(--bg-dark-border);
}

.qty-btn-group button {
  background: transparent;
  border: none;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: inherit;
}

.qty-btn-group button:hover {
  color: var(--primary-gold);
}

.qty-btn-group span {
  width: 20px;
  text-align: center;
  font-size: 0.85rem;
  font-weight: 600;
}

.remove-btn {
  background: transparent;
  border: none;
  color: var(--accent-error);
  cursor: pointer;
  font-size: 1.1rem;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.remove-btn:hover {
  opacity: 1;
}

.empty-cart-msg {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-dark-secondary);
}

body.dark-mode .empty-cart-msg {
  color: var(--text-light-secondary);
}

.empty-cart-msg h4 {
  font-size: 1.2rem;
  margin-bottom: 5px;
  color: var(--text-dark-primary);
}

body.dark-mode .empty-cart-msg h4 {
  color: var(--text-light-primary);
}

.drawer-footer {
  padding: 20px;
  border-top: 1px solid var(--bg-light-border);
  background: rgba(212,175,55,0.03);
}

body.dark-mode .drawer-footer {
  border-top: 1px solid var(--bg-dark-border);
}

.subtotal {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  font-size: 1.1rem;
}

.drawer-actions {
  display: flex;
  gap: 10px;
}
</style>

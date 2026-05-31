<template>
  <div class="cart-page container">
    <div class="cart-header">
      <h1 class="luxury-title">Your Tailor <span class="gold-gradient-text">Cart</span></h1>
      <p>Verify your tailored selections below before proceeding to secure payment and dispatch.</p>
    </div>

    <div v-if="cart.length > 0" class="cart-layout">
      <!-- Left Cart Items List -->
      <div class="cart-items-wrapper glass-panel">
        <div v-for="(item, idx) in cart" :key="idx" class="cart-item-row">
          <!-- HSL Icon Design indicator -->
          <div class="item-visual-preview">
            <i class="fa-solid fa-scissors"></i>
          </div>

          <div class="item-details">
            <h3 class="luxury-title">{{ item.name }}</h3>
            <span class="item-size-badge">{{ item.size }}</span>
            <div class="item-pricing">
              <span class="unit-price">LKR {{ formatNumber(item.price) }} each</span>
            </div>
          </div>

          <div class="item-qty-actions">
            <div class="mini-qty-counter">
              <button @click="adjustQty(item, -1)" class="qty-btn" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
              <span class="qty-num">{{ item.quantity }}</span>
              <button @click="adjustQty(item, 1)" class="qty-btn" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
            </div>
            
            <button @click="removeItem(item)" class="remove-btn" aria-label="Remove item">
              <i class="fa-regular fa-trash-can"></i>
            </button>
          </div>

          <div class="item-total-price">
            LKR {{ formatNumber(item.price * item.quantity) }}
          </div>
        </div>
      </div>

      <!-- Right Summary Panel -->
      <div class="cart-summary glass-panel">
        <h3 class="luxury-title summary-title">Order Summary</h3>
        
        <div class="summary-details">
          <div class="summary-row">
            <span>Stitched Items count</span>
            <span>{{ totalQty }}</span>
          </div>
          <div class="summary-row">
            <span>Subtotal</span>
            <span>LKR {{ formatNumber(subtotal) }}</span>
          </div>
          <div class="summary-row">
            <span>Delivery Fee</span>
            <span class="text-success font-bold">FREE</span>
          </div>
          <hr class="summary-divider" />
          <div class="summary-row total-row">
            <span>Grand Total</span>
            <span class="gold-gradient-text">LKR {{ formatNumber(subtotal) }}</span>
          </div>
        </div>

        <button @click="proceedToCheckout" class="btn-premium btn-gold btn-block">
          Proceed To Checkout <i class="fa-solid fa-chevron-right ml-2"></i>
        </button>

        <div class="delivery-highlight">
          <i class="fa-solid fa-truck-fast"></i>
          <div>
            <h6>Island-wide Delivery</h6>
            <p>Our orders are shipped securely across Sri Lanka via high-speed courier dispatch.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty Cart -->
    <div v-else class="empty-cart glass-panel">
      <i class="fa-solid fa-cart-flat-bed-suitcases"></i>
      <h3>Your tailor cart is empty.</h3>
      <p>Browse our beautiful clothing store and customize custom sizes to add items!</p>
      <NuxtLink to="/shop" class="btn-premium btn-gold mt-4">Go to Collection</NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { computed, inject } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const cart = inject('cart')
const updateCart = inject('updateCart')

const totalQty = computed(() => {
  return cart.value.reduce((total, item) => total + item.quantity, 0)
})

const subtotal = computed(() => {
  return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const adjustQty = (item, diff) => {
  const current = [...cart.value]
  const idx = current.findIndex(i => i.id === item.id && i.size === item.size)
  if (idx > -1) {
    const nextQty = current[idx].quantity + diff
    if (nextQty >= 1) {
      current[idx].quantity = nextQty
      updateCart(current)
    }
  }
}

const removeItem = (item) => {
  const current = [...cart.value].filter(i => !(i.id === item.id && i.size === item.size))
  updateCart(current)
}

const proceedToCheckout = () => {
  router.push('/checkout')
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<style scoped>
.cart-header {
  text-align: center;
  margin-bottom: 50px;
}

.cart-header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
}

.cart-header p {
  color: var(--text-dark-secondary);
}

body.dark-mode .cart-header p {
  color: var(--text-light-secondary);
}

.cart-layout {
  display: grid;
  grid-template-columns: 1.6fr 1fr;
  gap: 40px;
  align-items: start;
}

.cart-items-wrapper {
  padding: 30px;
}

.cart-item-row {
  display: grid;
  grid-template-columns: 80px 2.2fr 1.5fr 1fr;
  gap: 20px;
  align-items: center;
  padding: 24px 0;
  border-bottom: 1px solid var(--bg-light-border);
}

body.dark-mode .cart-item-row {
  border-bottom: 1px solid var(--bg-dark-border);
}

.cart-item-row:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.cart-item-row:first-child {
  padding-top: 0;
}

.item-visual-preview {
  width: 70px;
  height: 70px;
  border-radius: var(--radius-md);
  background: rgba(212, 175, 55, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: var(--primary-gold);
}

.item-details h3 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
}

.item-size-badge {
  display: inline-block;
  background: var(--bg-light);
  color: var(--primary-gold-dark);
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: var(--radius-sm);
  border: 1px solid var(--bg-light-border);
  margin-bottom: 6px;
}

body.dark-mode .item-size-badge {
  background: var(--bg-dark);
  border: 1px solid var(--bg-dark-border);
  color: var(--primary-gold-light);
}

.unit-price {
  font-size: 0.8rem;
  color: var(--text-dark-secondary);
}

body.dark-mode .unit-price {
  color: var(--text-light-secondary);
}

.item-qty-actions {
  display: flex;
  align-items: center;
  gap: 15px;
}

.mini-qty-counter {
  display: flex;
  align-items: center;
  border: 1px solid var(--bg-light-border);
  border-radius: var(--radius-sm);
}

body.dark-mode .mini-qty-counter {
  border: 1px solid var(--bg-dark-border);
}

.mini-qty-counter .qty-btn {
  width: 32px;
  height: 32px;
  font-size: 0.8rem;
}

.mini-qty-counter .qty-num {
  width: 24px;
}

.remove-btn {
  background: transparent;
  color: var(--accent-error);
  font-size: 1.1rem;
  cursor: pointer;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: var(--transition-smooth);
}

.remove-btn:hover {
  background: rgba(230, 57, 70, 0.1);
}

.item-total-price {
  font-weight: 700;
  font-size: 1.1rem;
  text-align: right;
}

/* Summary Panel */
.cart-summary {
  padding: 40px;
}

.summary-title {
  font-size: 1.4rem;
  margin-bottom: 25px;
  border-bottom: 2px solid var(--primary-gold);
  padding-bottom: 10px;
}

.summary-details {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 30px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.95rem;
}

.summary-row span:first-child {
  color: var(--text-dark-secondary);
}

body.dark-mode .summary-row span:first-child {
  color: var(--text-light-secondary);
}

body.dark-mode .summary-row span:last-child {
  color: var(--text-light-primary);
  font-weight: 600;
}

.summary-divider {
  border: none;
  height: 1px;
  background: var(--bg-light-border);
}

body.dark-mode .summary-divider {
  background: var(--bg-dark-border);
}

.total-row {
  font-size: 1.25rem;
  font-weight: 700;
}

.total-row span:first-child {
  color: var(--text-dark-primary) !important;
}

body.dark-mode .total-row span:first-child {
  color: var(--text-light-primary) !important;
}

.btn-block {
  width: 100%;
}

.ml-2 {
  margin-left: 8px;
}

.delivery-highlight {
  display: flex;
  gap: 15px;
  margin-top: 30px;
  background: rgba(212,175,55,0.06);
  padding: 18px;
  border-radius: var(--radius-md);
}

.delivery-highlight i {
  font-size: 1.5rem;
  color: var(--primary-gold);
}

.delivery-highlight h6 {
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 4px;
}

.delivery-highlight p {
  font-size: 0.75rem;
  margin-bottom: 0;
  color: var(--text-dark-secondary);
}

body.dark-mode .delivery-highlight p {
  color: var(--text-light-secondary);
}

/* Empty Cart State */
.empty-cart {
  padding: 80px 40px;
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.empty-cart i {
  font-size: 4rem;
  color: var(--primary-gold);
  margin-bottom: 25px;
}

.empty-cart h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
}

.empty-cart p {
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .empty-cart p {
  color: var(--text-light-secondary);
}

.mt-4 {
  margin-top: 20px;
}

/* Responsive adjustments */
@media (max-width: 991px) {
  .cart-layout {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .cart-items-wrapper {
    padding: 20px 15px;
  }
  
  .cart-item-row {
    grid-template-columns: 1fr;
    text-align: center;
    justify-items: center;
    gap: 12px;
  }
  
  .item-total-price {
    text-align: center;
  }
}
</style>

<template>
  <div class="orders-page container">
    <div class="orders-header">
      <h1 class="luxury-title">Track Your <span class="gold-gradient-text">Orders</span></h1>
      <p>Monitor your orders from the warehouse to your doorstep.</p>
      
      <div class="track-input-group mt-4 animate-fade-up">
        <input 
          type="text" 
          v-model="trackingId" 
          placeholder="Enter your Order ID to track (e.g., MF-2026...)" 
          class="track-input"
          @keyup.enter="loadOrders"
        />
        <button @click="loadOrders" class="btn-premium btn-gold" :disabled="isLoading">
          <i class="fa-solid fa-magnifying-glass" v-if="!isLoading"></i>
          <i class="fa-solid fa-spinner fa-spin" v-else></i> 
          Track
        </button>
      </div>
    </div>

    <div v-if="ordersList.length > 0" class="orders-container">
      <div v-for="order in ordersList" :key="order.orderId" class="order-card glass-panel animate-fade-up">
        <!-- Order Header info -->
        <div class="order-card-header">
          <div>
            <span class="order-ref-label">Order Reference</span>
            <h4 class="order-id">#{{ order.orderId }}</h4>
          </div>
          <div>
            <span class="order-ref-label">Date Placed</span>
            <span class="order-date">{{ order.createdAt }}</span>
          </div>
          <div>
            <span class="order-ref-label">Payment Mode</span>
            <span class="order-pay-method">{{ formatPaymentMethod(order.paymentMethod) }}</span>
          </div>
          <div>
            <span class="order-ref-label">Grand Total</span>
            <strong class="order-total gold-gradient-text">LKR {{ formatNumber(order.totalAmount) }}</strong>
          </div>
        </div>

        <!-- 3D status progress timeline bar -->
        <div class="timeline-wrapper">
          <div class="timeline-progress-bar" :style="{ width: getStatusProgressWidth(order.status) }"></div>
          
          <div class="timeline-nodes">
            <div :class="['node', { active: isNodeActive(order.status, 'pending') }]">
              <div class="node-bullet"><i class="fa-solid fa-circle-notch fa-spin" v-if="order.status === 'pending'"></i><i class="fa-solid fa-check" v-else></i></div>
              <span class="node-label">Pending</span>
            </div>
            
            <div :class="['node', { active: isNodeActive(order.status, 'confirmed') }]">
              <div class="node-bullet"><i class="fa-solid fa-circle-notch fa-spin" v-if="order.status === 'confirmed'"></i><i class="fa-solid fa-check" v-else></i></div>
              <span class="node-label">Confirmed</span>
            </div>
            
            <div :class="['node', { active: isNodeActive(order.status, 'stitching') }]">
              <div class="node-bullet"><i class="fa-solid fa-box" v-if="order.status === 'stitching'"></i><i class="fa-solid fa-check" v-else></i></div>
              <span class="node-label">Processing</span>
            </div>
            
            <div :class="['node', { active: isNodeActive(order.status, 'dispatched') }]">
              <div class="node-bullet"><i class="fa-solid fa-truck-fast" v-if="order.status === 'dispatched'"></i><i class="fa-solid fa-check" v-else></i></div>
              <span class="node-label">Dispatched</span>
            </div>
          </div>
        </div>

        <!-- Tailored items brief list -->
        <div class="order-items-brief">
          <h5>Order Items:</h5>
          <div class="brief-items-grid">
            <div v-for="(item, idx) in order.items" :key="idx" class="brief-item-row">
              <div class="item-title">
                <i class="fa-solid fa-shirt text-gold"></i>
                <div>
                  <strong>{{ item.name }}</strong>
                  <span class="tailor-size">{{ item.size }}</span>
                </div>
              </div>
              <span class="item-qty-price">Qty: {{ item.quantity }} &times; LKR {{ formatNumber(item.price) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-orders glass-panel">
      <i class="fa-solid fa-boxes-packing"></i>
      <h3>No orders found.</h3>
      <p>You have not placed any orders yet under this device.</p>
      <NuxtLink to="/shop" class="btn-premium btn-gold mt-4">Browse Collection</NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const ordersList = ref([])
const trackingId = ref('')
const isLoading = ref(false)

const loadOrders = async () => {
  if (!trackingId.value) return;
  
  isLoading.value = true;
  try {
    const res = await fetch(`https://api-maneesha.posmasters.lk/api/track-orders?order_id=${encodeURIComponent(trackingId.value)}`);
    if (res.ok) {
      const data = await res.json();
      
      // Map API response to frontend format
      ordersList.value = data.map(order => ({
        orderId: order.order_number,
        createdAt: new Date(order.created_at).toLocaleString(),
        paymentMethod: order.payment_method,
        totalAmount: order.total,
        status: order.status,
        items: order.order_items.map(item => ({
          name: item.product_name,
          size: item.size,
          quantity: item.quantity,
          price: item.unit_price
        }))
      }));
    }
  } catch (err) {
    console.error('Failed to load orders', err);
  }
  isLoading.value = false;
}

const formatPaymentMethod = (method) => {
  if (method === 'payhere') return 'PayHere Gateway'
  if (method === 'cod') return 'Cash on Delivery'
  if (method === 'bank_deposit') return 'Direct Bank Deposit'
  return method
}

const getStatusProgressWidth = (status) => {
  if (status === 'pending') return '0%'
  if (status === 'confirmed') return '33%'
  if (status === 'processing' || status === 'stitching') return '66%'
  if (status === 'dispatched' || status === 'delivered' || status === 'completed') return '100%'
  return '0%'
}

const isNodeActive = (activeStatus, nodeStatus) => {
  const normalize = (s) => s === 'stitching' ? 'processing' : (s === 'completed' || s === 'delivered' ? 'dispatched' : s);
  const ranks = { 'pending': 0, 'confirmed': 1, 'processing': 2, 'dispatched': 3 };
  return (ranks[normalize(activeStatus)] || 0) >= (ranks[normalize(nodeStatus)] || 0);
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    const savedOrder = localStorage.getItem('maneesha-customer-order-id');
    if (savedOrder) {
      trackingId.value = savedOrder;
      loadOrders();
    }
  }
})
</script>

<style scoped>
.orders-header {
  text-align: center;
  margin-bottom: 50px;
}

.orders-header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
}

.orders-header p {
  color: var(--text-dark-secondary);
}

body.dark-mode .orders-header p {
  color: var(--text-light-secondary);
}

.track-input-group {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  max-width: 500px;
  margin: 20px auto 0;
}

.track-input {
  flex: 1;
  padding: 14px 20px;
  border-radius: 30px;
  border: 1px solid var(--bg-light-border);
  background: var(--bg-light-surface);
  font-size: 1rem;
  color: var(--text-dark);
  transition: all 0.3s ease;
}

.track-input:focus {
  outline: none;
  border-color: var(--primary-gold);
  box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
}

body.dark-mode .track-input {
  background: var(--bg-dark-surface);
  border-color: var(--bg-dark-border);
  color: var(--text-light);
}

.orders-container {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.order-card {
  padding: 40px;
  overflow: hidden;
}

.order-card-header {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 35px;
  border-bottom: 1px solid var(--bg-light-border);
  padding-bottom: 25px;
}

body.dark-mode .order-card-header {
  border-bottom: 1px solid var(--bg-dark-border);
}

.order-ref-label {
  display: block;
  font-size: 0.75rem;
  text-transform: uppercase;
  font-weight: 600;
  color: var(--text-dark-secondary);
  margin-bottom: 4px;
}

body.dark-mode .order-ref-label {
  color: var(--text-light-secondary);
}

.order-id {
  font-size: 1.25rem;
  font-weight: 700;
}

.order-date, .order-pay-method {
  font-size: 0.95rem;
  font-weight: 600;
}

.order-total {
  font-size: 1.35rem;
  font-weight: 700;
}

/* Timeline Layout */
.timeline-wrapper {
  position: relative;
  margin: 40px 20px 50px 20px;
  height: 4px;
  background: var(--bg-light-border);
  border-radius: 2px;
}

body.dark-mode .timeline-wrapper {
  background: var(--bg-dark-border);
}

.timeline-progress-bar {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  background: linear-gradient(90deg, var(--primary-gold-light) 0%, var(--primary-gold) 100%);
  transition: width 0.6s ease-in-out;
}

.timeline-nodes {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
}

.node {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.node-bullet {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--bg-light-surface);
  border: 2px solid var(--bg-light-border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  color: var(--text-dark-secondary);
  z-index: 10;
  transition: var(--transition-smooth);
}

body.dark-mode .node-bullet {
  background: var(--bg-dark-surface);
  border-color: var(--bg-dark-border);
  color: var(--text-light-secondary);
}

.node.active .node-bullet {
  border-color: var(--primary-gold);
  background: var(--primary-gold);
  color: #fff;
  box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.node-label {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--text-dark-secondary);
}

body.dark-mode .node-label {
  color: var(--text-light-secondary);
}

.node.active .node-label {
  color: var(--primary-gold);
}

/* Bespoke items brief list */
.order-items-brief {
  background: rgba(0,0,0,0.02);
  border-radius: var(--radius-md);
  padding: 24px;
  border: 1px solid var(--bg-light-border);
}

body.dark-mode .order-items-brief {
  background: rgba(255,255,255,0.01);
  border-color: var(--bg-dark-border);
}

.order-items-brief h5 {
  font-size: 0.95rem;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 15px;
  border-left: 2px solid var(--primary-gold);
  padding-left: 10px;
}

.brief-items-grid {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.brief-item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
}

.item-title {
  display: flex;
  align-items: center;
  gap: 15px;
}

.text-gold {
  color: var(--primary-gold);
}

.tailor-size {
  display: block;
  font-size: 0.75rem;
  color: var(--primary-gold-dark);
  font-weight: 600;
  margin-top: 2px;
}

.item-qty-price {
  font-weight: 600;
}

/* Empty State */
.empty-orders {
  padding: 80px 40px;
  text-align: center;
  max-width: 600px;
  margin: 0 auto;
}

.empty-orders i {
  font-size: 4rem;
  color: var(--primary-gold);
  margin-bottom: 25px;
}

.empty-orders h3 {
  font-size: 1.4rem;
  margin-bottom: 10px;
}

.empty-orders p {
  color: var(--text-dark-secondary);
  margin-bottom: 30px;
}

body.dark-mode .empty-orders p {
  color: var(--text-light-secondary);
}

.mt-4 {
  margin-top: 20px;
}

/* Responsive adjustments */
@media (max-width: 900px) {
  .order-card-header {
    grid-template-columns: 1fr 1fr;
    gap: 15px;
  }
}

@media (max-width: 768px) {
  .order-card {
    padding: 24px 15px;
  }
  
  .timeline-nodes .node-label {
    display: none;
  }
  
  .brief-item-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}
</style>

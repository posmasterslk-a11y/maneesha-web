<template>
  <div class="admin-orders">
    <div class="metrics-grid animate-fade-up">
      <div class="metric-card">
        <div class="metric-info">
          <span>Total Orders</span>
          <h3>{{ stats.totalOrders }}</h3>
        </div>
        <div class="metric-icon blue"><i class="fa-solid fa-boxes-stacked"></i></div>
      </div>
      <div class="metric-card">
        <div class="metric-info">
          <span>Pending Orders</span>
          <h3>{{ stats.pendingOrders }}</h3>
        </div>
        <div class="metric-icon yellow"><i class="fa-solid fa-clock-rotate-left"></i></div>
      </div>
      <div class="metric-card">
        <div class="metric-info">
          <span>Dispatched / Done</span>
          <h3>{{ stats.dispatchedOrders }}</h3>
        </div>
        <div class="metric-icon green"><i class="fa-solid fa-truck-fast"></i></div>
      </div>
      <div class="metric-card">
        <div class="metric-info">
          <span>Bank Deposits</span>
          <h3>{{ stats.bankDepositOrders }}</h3>
        </div>
        <div class="metric-icon purple"><i class="fa-solid fa-building-columns"></i></div>
      </div>
      <div class="metric-card">
        <div class="metric-info">
          <span>PayHere</span>
          <h3>{{ stats.payhereOrders }}</h3>
        </div>
        <div class="metric-icon blue"><i class="fa-regular fa-credit-card"></i></div>
      </div>
      <div class="metric-card">
        <div class="metric-info">
          <span>Cash on Delivery</span>
          <h3>{{ stats.codOrders }}</h3>
        </div>
        <div class="metric-icon green"><i class="fa-solid fa-hand-holding-dollar"></i></div>
      </div>
    </div>

    <div class="table-panel animate-fade-up">
      <div class="table-header">
        <h3 class="luxury-title">All Orders</h3>
        <div class="header-actions flex align-center">
          <select v-model="paymentFilter" class="admin-select mr-2">
            <option value="all">All Payment Methods</option>
            <option value="bank_deposit">Bank Deposit</option>
            <option value="cod">Cash on Delivery</option>
            <option value="payhere">PayHere Gateway</option>
          </select>
          <button @click="loadOrders" class="btn-admin btn-admin-secondary"><i class="fa-solid fa-rotate mr-2"></i> Refresh</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="admin-table">
          <thead>
          <tr>
            <th>Ref ID</th>
            <th>Customer Credentials</th>
            <th>Order Items</th>
            <th>Grand Amount</th>
            <th>Payment Channel</th>
            <th>Current Status</th>
            <th>Administrative Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in filteredOrders" :key="order.id">
            <td>
              <div class="cell-flex">
                <strong>#{{ order.order_number }}</strong>
                <small class="time-badge text-muted"><i class="fa-regular fa-clock"></i> {{ new Date(order.created_at).toLocaleString() }}</small>
              </div>
            </td>
            <td @click="openCustomerModal(order)" class="customer-cell cursor-pointer">
              <div class="cell-flex">
                <strong>{{ order.customer_name }}</strong>
                <small class="text-muted"><i class="fa-solid fa-phone"></i> {{ order.customer_phone }}</small>
              </div>
            </td>
            <td>
              <button @click="openItemsModal(order)" class="btn-modern btn-modern-outline">
                <i class="fa-solid fa-list-ul"></i> View Items ({{ order.order_items?.length || 0 }})
              </button>
            </td>
            <td><strong>LKR {{ formatNumber(order.total) }}</strong></td>
            <td>
              <span class="pay-method-badge">{{ formatPaymentMethod(order.payment_method) }}</span>
              <button 
                v-if="order.payment_method === 'bank_deposit' && order.bank_slip_path" 
                @click="openSlipModal(order)"
                class="btn-modern btn-modern-outline mt-2"
              >
                <i class="fa-regular fa-image"></i> Review Slip
              </button>
            </td>
            <td>
              <span :class="['modern-badge', order.status]">{{ order.status }}</span>
            </td>
            <td>
              <div class="actions-wrapper">
                <button 
                  v-if="order.status === 'pending'"
                  @click="updateStatus(order, 'confirmed')"
                  class="btn-modern btn-modern-primary"
                >
                  Confirm
                </button>

                <button 
                  v-if="order.status === 'confirmed'"
                  @click="updateStatus(order, 'processing')"
                  class="btn-modern btn-modern-purple"
                >
                  Process Order
                </button>

                <button 
                  v-if="order.status === 'processing'"
                  @click="updateStatus(order, 'dispatched')"
                  class="btn-modern btn-modern-green"
                >
                  Dispatch Item
                </button>
                
                <button 
                  v-if="order.status === 'dispatched'"
                  @click="updateStatus(order, 'delivered')"
                  class="btn-modern btn-modern-secondary"
                >
                  Close Order
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      </div>

      <div v-if="filteredOrders.length === 0" class="empty-state">
        <i class="fa-solid fa-inbox"></i>
        <p>No orders found for the selected criteria.</p>
      </div>

      <AdminPagination 
        v-if="orders.length > 0"
        :current-page="currentPage" 
        :last-page="lastPage" 
        @page-change="loadOrders" 
      />
    </div>

    <!-- Items Modal -->
    <div :class="['modal-overlay', { open: isItemsModalOpen }]" @click.self="closeItemsModal">
      <div class="om-modal">
        <div class="om-left om-left-items">
          <div class="om-icon-wrap"><i class="fa-solid fa-list-check"></i></div>
          <h3 class="om-side-title">Order Items</h3>
          <p class="om-side-desc">Review the items purchased in this order.</p>
        </div>
        <div class="om-right">
          <div class="pm-header">
            <div>
              <p class="pm-header-label">Items Review</p>
              <h4 class="pm-title">Order #{{ selectedOrderItemsOrder?.order_number }}</h4>
            </div>
            <button @click="closeItemsModal" class="pm-close-btn"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <div class="om-body items-modal-body">
            <table class="admin-table items-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Size</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, idx) in selectedOrderItems" :key="idx">
                  <td><strong>{{ item.product_name }}</strong></td>
                  <td>{{ item.size }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>LKR {{ formatNumber(item.price) }}</td>
                  <td><strong>LKR {{ formatNumber(item.price * item.quantity) }}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="pm-footer">
            <button @click="closeItemsModal" class="btn-admin btn-admin-secondary">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bank Slip Modal -->
    <div :class="['modal-overlay', { open: isSlipModalOpen }]" @click.self="closeSlipModal">
      <div class="om-modal">
        <div class="om-left om-left-bank">
          <div class="om-icon-wrap"><i class="fa-solid fa-building-columns"></i></div>
          <h3 class="om-side-title">Bank Receipt</h3>
          <p class="om-side-desc">Verify the bank transfer receipt against the expected order amount.</p>
          <div class="om-amount-chip">LKR {{ formatNumber(selectedOrder?.total || 0) }}</div>
        </div>
        <div class="om-right">
          <div class="pm-header">
            <div>
              <p class="pm-header-label om-label-bank">Bank Transfer Review</p>
              <h4 class="pm-title">Order #{{ selectedOrder?.order_number }}</h4>
            </div>
            <button @click="closeSlipModal" class="pm-close-btn"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <div class="om-body">
            <p class="om-meta">Customer: <strong>{{ selectedOrder?.customer_name }}</strong></p>
            <div class="receipt-canvas">
              <img v-if="selectedOrder?.bank_slip_path" :src="'http://127.0.0.1:8000/api/orders/' + selectedOrder.id + '/view-slip'" alt="Receipt" class="receipt-image" />
              <div v-else class="receipt-empty"><i class="fa-solid fa-file-image"></i><span>No slip uploaded</span></div>
            </div>
            <p class="receipt-note">Verify the transfer reference and total amount of <strong>LKR {{ formatNumber(selectedOrder?.total || 0) }}</strong> matches bank statements before approving.</p>
          </div>
          <div class="pm-footer">
            <a v-if="selectedOrder?.bank_slip_path" :href="'http://127.0.0.1:8000/api/orders/' + selectedOrder.id + '/download-slip'" download class="btn-admin btn-admin-secondary mr-auto">
              <i class="fa-solid fa-download mr-1"></i> Download
            </a>
            <button @click="closeSlipModal" class="btn-admin btn-admin-secondary">Close</button>
            <button v-if="selectedOrder?.status === 'pending'" @click="approveSlipFromModal" class="btn-admin pm-save-btn om-save-bank">Approve & Confirm</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm Dialog -->
    <div :class="['modal-overlay', { open: confirmDialog.isOpen }]" @click.self="closeConfirm">
      <div class="om-confirm">
        <div class="om-confirm-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h4 class="om-confirm-title">Confirm Action</h4>
        <p class="om-confirm-msg">{{ confirmDialog.message }}</p>
        <div class="om-confirm-actions">
          <button @click="closeConfirm" class="btn-admin btn-admin-secondary">Cancel</button>
          <button @click="executeConfirm" class="btn-admin pm-save-btn">Yes, Proceed</button>
        </div>
      </div>
    </div>

    <!-- Customer Details Modal -->
    <div :class="['modal-overlay', { open: isCustomerModalOpen }]" @click.self="closeCustomerModal">
      <div class="om-modal">
        <div class="om-left om-left-customer">
          <div class="om-icon-wrap"><i class="fa-solid fa-user"></i></div>
          <h3 class="om-side-title">{{ selectedCustomer?.customer_name }}</h3>
          <p class="om-side-desc">Order Reference <strong>#{{ selectedCustomer?.order_number }}</strong></p>
          <div class="om-amount-chip">LKR {{ formatNumber(selectedCustomer?.total || 0) }}</div>
        </div>
        <div class="om-right">
          <div class="pm-header">
            <div>
              <p class="pm-header-label om-label-customer">Customer Details</p>
              <h4 class="pm-title">{{ selectedCustomer?.customer_name }}</h4>
            </div>
            <button @click="closeCustomerModal" class="pm-close-btn"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <div class="om-body">
            <div class="customer-detail-grid" v-if="selectedCustomer">
              <div class="detail-item">
                <span class="detail-label"><i class="fa-solid fa-phone"></i> Phone</span>
                <span class="detail-value">{{ selectedCustomer.customer_phone }}</span>
              </div>
              <div class="detail-item">
                <span class="detail-label"><i class="fa-solid fa-envelope"></i> Email</span>
                <span class="detail-value">{{ selectedCustomer.customer_email || 'Not Provided' }}</span>
              </div>
              <div class="detail-item full-width">
                <span class="detail-label"><i class="fa-solid fa-location-dot"></i> Address</span>
                <span class="detail-value">{{ selectedCustomer.customer_address }}</span>
              </div>
              <div class="detail-item">
                <span class="detail-label"><i class="fa-solid fa-city"></i> City</span>
                <span class="detail-value">{{ selectedCustomer.customer_city }}</span>
              </div>
              <div class="detail-item">
                <span class="detail-label"><i class="fa-solid fa-hashtag"></i> Order Ref</span>
                <span class="detail-value">#{{ selectedCustomer.order_number }}</span>
              </div>
            </div>
          </div>
          <div class="pm-footer">
            <button @click="closeCustomerModal" class="btn-admin btn-admin-secondary">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminPagination from '@/components/AdminPagination.vue'

const API = 'http://localhost:8000/api'
const orders = ref([])
const stats = ref({})
const currentPage = ref(1)
const lastPage = ref(1)
const paymentFilter = ref('all')
const isSlipModalOpen = ref(false)
const selectedOrder = ref(null)

const isItemsModalOpen = ref(false)
const selectedOrderItems = ref([])
const selectedOrderItemsOrder = ref(null)

const openItemsModal = (order) => {
  selectedOrderItems.value = order.order_items || []
  selectedOrderItemsOrder.value = order
  isItemsModalOpen.value = true
}
const closeItemsModal = () => {
  isItemsModalOpen.value = false
  selectedOrderItems.value = []
  selectedOrderItemsOrder.value = null
}

const filteredOrders = computed(() => {
  let list = orders.value;
  if (paymentFilter.value !== 'all') {
    list = list.filter(o => o.payment_method === paymentFilter.value);
  }
  return list.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
})

const isCustomerModalOpen = ref(false)
const selectedCustomer = ref(null)

const confirmDialog = ref({
  isOpen: false,
  message: '',
  onConfirm: null
})

const openConfirm = (message, onConfirm) => {
  confirmDialog.value = { isOpen: true, message, onConfirm }
}

const closeConfirm = () => {
  confirmDialog.value.isOpen = false
}

const executeConfirm = () => {
  if (confirmDialog.value.onConfirm) {
    confirmDialog.value.onConfirm()
  }
  closeConfirm()
}

const authHeaders = () => {
  return {
    'Authorization': `Bearer ${localStorage.getItem('maneesha-admin-token') || ''}`,
    'Accept': 'application/json'
  }
}

const loadDashboardStats = async () => {
  try {
    const res = await fetch(`${API}/admin/orders/stats`, { headers: authHeaders() })
    if (res.ok) stats.value = await res.json()
  } catch (err) { console.error('Failed to load stats', err) }
}

const loadOrders = async (page = 1) => {
  try {
    const res = await fetch(`${API}/admin/orders?page=${page}`, {
      headers: authHeaders()
    })
    if (res.ok) {
      const data = await res.json()
      orders.value = data.data
      currentPage.value = data.current_page
      lastPage.value = data.last_page
    }
  } catch (err) {
    console.error('Failed to load orders', err)
  }
}

const formatPaymentMethod = (method) => {
  if (method === 'payhere') return 'PayHere Gateway'
  if (method === 'cod') return 'Cash on Delivery'
  if (method === 'bank_deposit') return 'Direct Bank Deposit'
  return method
}

const updateStatus = (order, newStatus) => {
  openConfirm(`Are you sure you want to change order #${order.order_number} to '${newStatus}'?`, async () => {
    try {
      const res = await fetch(`${API}/admin/orders/${order.id}/status`, {
        method: 'PUT',
        headers: {
          ...authHeaders(),
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
      });
      if (res.ok) {
        await loadOrders(currentPage.value);
        await loadDashboardStats();
      }
    } catch(err) {
      console.error('Failed to update status', err);
    }
  });
}

const openSlipModal = (order) => {
  selectedOrder.value = order
  isSlipModalOpen.value = true
}

const closeSlipModal = () => {
  selectedOrder.value = null
  isSlipModalOpen.value = false
}

const openCustomerModal = (order) => {
  selectedCustomer.value = order
  isCustomerModalOpen.value = true
}

const closeCustomerModal = () => {
  selectedCustomer.value = null
  isCustomerModalOpen.value = false
}

const approveSlipFromModal = () => {
  if (selectedOrder.value) {
    updateStatus(selectedOrder.value, 'confirmed')
    closeSlipModal()
  }
}

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

onMounted(() => {
  loadDashboardStats()
  loadOrders(1)
})
</script>

<style scoped>
.table-panel {
  margin-top: 0px;
}

.item-spec-bullet {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.8rem;
  margin-bottom: 4px;
}

.text-gold {
  color: var(--admin-gold);
}

.cell-flex {
  display: flex;
  align-items: center;
  gap: 10px;
}

.text-muted {
  color: var(--admin-text-muted);
}

.cursor-pointer {
  cursor: pointer;
}

.pay-method-badge {
  display: block;
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--admin-text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Modern Button System */
.btn-modern {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px 16px;
  font-size: 0.8rem;
  font-weight: 600;
  border-radius: var(--radius-sm);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
}

.btn-modern-primary {
  background: var(--primary-blue);
  color: white;
}
.btn-modern-primary:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.btn-modern-purple {
  background: #8b5cf6;
  color: white;
}
.btn-modern-purple:hover {
  background: #7c3aed;
  transform: translateY(-1px);
}

.btn-modern-green {
  background: #10b981;
  color: white;
}
.btn-modern-green:hover {
  background: #059669;
  transform: translateY(-1px);
}

.btn-modern-secondary {
  background: var(--bg-light-surface);
  border: 1px solid var(--admin-border);
  color: var(--text-dark-primary);
}
.btn-modern-secondary:hover {
  background: var(--admin-border);
}

.btn-modern-outline {
  background: transparent;
  border: 1px solid var(--primary-blue);
  color: var(--primary-blue);
  padding: 6px 12px;
  font-size: 0.75rem;
}
.btn-modern-outline:hover {
  background: var(--primary-blue);
  color: white;
}

/* Modern Badges */
.modern-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.modern-badge.pending { background: #fef3c7; color: #d97706; }
.modern-badge.confirmed { background: #e0e7ff; color: #4f46e5; }
.modern-badge.processing { background: #f3e8ff; color: #7e22ce; }
.modern-badge.dispatched { background: #d1fae5; color: #059669; }
.modern-badge.delivered { background: #dcfce3; color: #166534; }
.modern-badge.cancelled { background: #fee2e2; color: #dc2626; }

body.dark-mode .modern-badge.pending { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
body.dark-mode .modern-badge.confirmed { background: rgba(79, 70, 229, 0.15); color: #818cf8; }
body.dark-mode .modern-badge.processing { background: rgba(147, 51, 234, 0.15); color: #c084fc; }
body.dark-mode .modern-badge.dispatched { background: rgba(16, 185, 129, 0.15); color: #34d399; }
body.dark-mode .modern-badge.delivered { background: rgba(22, 163, 74, 0.15); color: #4ade80; }
body.dark-mode .modern-badge.cancelled { background: rgba(239, 68, 68, 0.15); color: #f87171; }

.actions-wrapper {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.mt-2 { margin-top: 8px; }

.empty-state {
  padding: 60px;
  text-align: center;
  color: var(--admin-text-muted);
}
.empty-state i {
  font-size: 3rem;
  margin-bottom: 15px;
  display: block;
}

.py-5 {
  padding-top: 40px !important;
  padding-bottom: 40px !important;
}

.text-center {
  text-align: center;
}

/* Slip Review Modal */
.modal-order-meta {
  font-size: 0.85rem;
  color: var(--admin-text-secondary);
  margin-bottom: 20px;
}

.receipt-canvas {
  height: 250px;
  background: #f1f5f9;
  border: 1px solid var(--admin-border);
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  margin-bottom: 20px;
}

.receipt-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.receipt-note {
  font-size: 0.8rem;
  color: var(--admin-text-secondary);
}

.mr-2 {
  margin-right: 8px;
}

.flex-center-between {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}
.mr-auto {
  margin-right: auto;
}
.mr-1 {
  margin-right: 4px;
}

.align-center {
  align-items: center;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.admin-select {
  padding: 8px 12px;
  border: 1px solid var(--admin-border);
  border-radius: var(--radius-sm);
  background: var(--bg-light-surface);
  color: var(--text-dark-primary);
  font-size: 0.9rem;
  font-weight: 500;
  outline: none;
}

body.dark-mode .admin-select {
  background: var(--bg-dark-surface);
  border-color: var(--bg-dark-border);
  color: var(--text-light-primary);
}

.time-badge {
  color: var(--admin-text-secondary);
  font-size: 0.75rem;
  background: rgba(0,0,0,0.05);
  padding: 2px 6px;
  border-radius: 4px;
  margin-top: 4px;
  display: inline-block;
}
body.dark-mode .time-badge {
  background: rgba(255,255,255,0.05);
}

/* Customer Modal Styles */
.customer-cell {
  cursor: pointer;
  transition: background 0.3s ease;
  position: relative;
}
.customer-cell:hover {
  background: var(--bg-light);
}
body.dark-mode .customer-cell:hover {
  background: rgba(255,255,255,0.05);
}
.view-hint {
  font-size: 0.7rem;
  color: var(--primary-blue);
  margin-top: 4px;
  opacity: 0.8;
}

/* ── Modern Orders Modals ────────────────────────────────────── */
.om-modal {
  display: flex;
  width: min(760px, 96vw);
  height: min(82vh, 560px);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.25);
  background: #fff;
  animation: modal-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes modal-pop {
  from { opacity: 0; transform: scale(0.92) translateY(20px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
.om-left {
  width: 200px; flex-shrink: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 28px 20px; text-align: center; gap: 12px;
}
.om-left-bank { background: linear-gradient(160deg, #6366f1 0%, #0ea5e9 100%); }
.om-left-customer { background: linear-gradient(160deg, #f59e0b 0%, #ef4444 100%); }

.om-icon-wrap {
  width: 64px; height: 64px;
  background: rgba(255,255,255,0.2); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.8rem; color: #fff;
}
.om-side-title { font-size: 1rem; font-weight: 700; color: #fff; }
.om-side-desc { font-size: 0.75rem; color: rgba(255,255,255,0.8); line-height: 1.5; flex: 1; }
.om-amount-chip {
  background: rgba(255,255,255,0.2);
  color: #fff; font-weight: 700; font-size: 0.9rem;
  padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3);
}
.om-right { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.om-body { flex: 1; overflow-y: auto; padding: 20px 28px; min-height: 0; }
.om-meta { font-size: 0.875rem; color: var(--admin-text-secondary); margin-bottom: 16px; }
.om-label-bank { color: #6366f1; }
.om-label-customer { color: #f59e0b; }

.pm-header {
  padding: 24px 28px 16px;
  border-bottom: 1px solid var(--admin-border);
  display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;
}
.pm-header-label {
  font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.8px; margin-bottom: 2px;
}
.pm-title { font-size: 1.2rem; font-weight: 700; color: var(--admin-text-primary); }
.pm-close-btn {
  width: 32px; height: 32px; border-radius: 8px;
  border: 1px solid var(--admin-border); background: transparent;
  color: var(--admin-text-secondary); cursor: pointer;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s;
}
.pm-close-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }
.pm-footer {
  padding: 16px 28px; border-top: 1px solid var(--admin-border);
  display: flex; gap: 12px; justify-content: flex-end;
  background: var(--admin-bg);
}
.pm-save-btn {
  background: linear-gradient(135deg, #6366f1, #0ea5e9); min-width: 130px;
}
.om-save-bank { background: linear-gradient(135deg, #10b981, #0ea5e9); }

.receipt-canvas {
  border-radius: 12px; overflow: hidden;
  background: var(--admin-bg); border: 1px solid var(--admin-border);
  margin: 12px 0; min-height: 180px;
  display: flex; align-items: center; justify-content: center;
}
.receipt-empty {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 10px; color: var(--admin-text-muted); font-size: 0.85rem; padding: 40px;
}

/* Confirm Dialog */
.om-confirm {
  background: #fff; border-radius: 20px; padding: 40px 36px;
  text-align: center; max-width: 420px; width: 95vw;
  box-shadow: 0 25px 60px rgba(0,0,0,0.25);
  animation: modal-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.om-confirm-icon {
  width: 72px; height: 72px; border-radius: 50%;
  background: linear-gradient(135deg, #fbbf24, #f59e0b);
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; color: #fff; margin: 0 auto 20px;
}
.om-confirm-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; }
.om-confirm-msg { color: var(--admin-text-secondary); font-size: 0.9rem; margin-bottom: 28px; line-height: 1.5; }
.om-confirm-actions { display: flex; gap: 12px; justify-content: center; }

/* Items Modal */
.om-left-items { background: linear-gradient(160deg, #10b981 0%, #059669 100%); }
.items-modal-body { padding: 0 !important; }
.items-table { border: none !important; border-radius: 0 !important; }
.items-table th, .items-table td { padding: 12px 20px; font-size: 0.85rem; }
.items-table th { background: var(--admin-bg); position: sticky; top: 0; z-index: 10; }

/* Customer grid */
.customer-detail-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
}
.detail-item {
  background: var(--admin-bg); border: 1px solid var(--admin-border);
  padding: 14px 16px; border-radius: 10px;
}
.detail-item.full-width { grid-column: span 2; }
.detail-label {
  display: flex; align-items: center; gap: 6px;
  font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;
  color: var(--admin-text-muted); margin-bottom: 6px; font-weight: 600;
}
.detail-value { font-size: 0.95rem; font-weight: 600; color: var(--admin-text-primary); }

/* Metrics Grid */
.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px; margin-bottom: 30px;
}
.metric-card {
  background: var(--admin-surface); border: 1px solid var(--admin-border);
  border-radius: var(--radius-md); padding: 20px;
  display: flex; justify-content: space-between; align-items: center;
  box-shadow: var(--shadow-card); transition: transform 0.2s, box-shadow 0.2s;
}
.metric-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
.metric-info span {
  font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.5px; color: var(--admin-text-muted);
}
.metric-info h3 { font-size: 1.75rem; margin-top: 6px; font-weight: 700; }
.metric-icon {
  width: 44px; height: 44px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
}
.metric-icon.blue   { background: rgba(14,165,233,0.1);  color: #0ea5e9; }
.metric-icon.purple { background: rgba(139,92,246,0.1);  color: #8b5cf6; }
.metric-icon.green  { background: rgba(16,185,129,0.1);  color: #10b981; }
.metric-icon.yellow { background: rgba(245,158,11,0.1);  color: #f59e0b; }

.admin-table td { vertical-align: middle; }
</style>

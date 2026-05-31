<template>
  <div class="admin-dashboard">

    <!-- Welcome Banner -->
    <div class="dash-welcome animate-fade-up">
      <div class="dash-welcome-text">
        <h2>Welcome back, <span class="dash-name">{{ adminName }}</span> 👋</h2>
        <p>Here's what's happening at Maneesha Fashion today.</p>
      </div>
      <div class="dash-date-chip">
        <i class="fa-regular fa-calendar"></i>
        {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid animate-fade-up">
      <div class="kpi-card kpi-revenue">
        <div class="kpi-icon"><i class="fa-solid fa-coins"></i></div>
        <div class="kpi-body">
          <span class="kpi-label">Total Revenue</span>
          <div class="kpi-value">LKR {{ formatNumber(stats.totalRevenue) }}</div>
        </div>
      </div>

      <div class="kpi-card kpi-orders">
        <div class="kpi-icon"><i class="fa-solid fa-bag-shopping"></i></div>
        <div class="kpi-body">
          <span class="kpi-label">Total Orders</span>
          <div class="kpi-value">{{ stats.totalOrders }}</div>
        </div>
      </div>

      <div class="kpi-card kpi-pending">
        <div class="kpi-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="kpi-body">
          <span class="kpi-label">Pending</span>
          <div class="kpi-value">{{ stats.pendingOrders }}</div>
        </div>
        <span class="kpi-badge-warn" v-if="stats.pendingOrders > 0">Action</span>
      </div>

      <div class="kpi-card kpi-delivered">
        <div class="kpi-icon"><i class="fa-solid fa-truck-fast"></i></div>
        <div class="kpi-body">
          <span class="kpi-label">Delivered</span>
          <div class="kpi-value">{{ stats.deliveredOrders ?? 0 }}</div>
        </div>
      </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="pay-breakdown-grid animate-fade-up">
      <div class="pay-card">
        <div class="pay-icon pay-bank"><i class="fa-solid fa-building-columns"></i></div>
        <div class="pay-info">
          <span>Bank Deposit</span>
          <strong>{{ stats.bankDepositOrders ?? 0 }}</strong>
        </div>
      </div>
      <div class="pay-card">
        <div class="pay-icon pay-cod"><i class="fa-solid fa-hand-holding-dollar"></i></div>
        <div class="pay-info">
          <span>Cash on Delivery</span>
          <strong>{{ stats.codOrders ?? 0 }}</strong>
        </div>
      </div>
      <div class="pay-card">
        <div class="pay-icon pay-ph"><i class="fa-regular fa-credit-card"></i></div>
        <div class="pay-info">
          <span>PayHere</span>
          <strong>{{ stats.payhereOrders ?? 0 }}</strong>
        </div>
      </div>
      <div class="pay-card">
        <div class="pay-icon pay-disp"><i class="fa-solid fa-boxes-packing"></i></div>
        <div class="pay-info">
          <span>Dispatched</span>
          <strong>{{ stats.dispatchedOrders ?? 0 }}</strong>
        </div>
      </div>
    </div>

    <!-- Main Content: Table + Sidebar -->
    <div class="dash-main animate-fade-up">
      <!-- Recent Orders Table -->
      <div class="dash-table-panel">
        <div class="panel-header">
          <div>
            <h3 class="panel-title">Recent Orders</h3>
            <p class="panel-subtitle">Latest {{ stats.recentOrders?.length ?? 0 }} orders</p>
          </div>
          <button @click="loadDashboardStats" class="refresh-btn">
            <i class="fa-solid fa-rotate"></i> Refresh
          </button>
        </div>

        <table class="dash-table">
          <thead>
            <tr>
              <th>Order</th>
              <th>Customer</th>
              <th>Items</th>
              <th>Total</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in stats.recentOrders" :key="order.id">
              <td>
                <div class="order-ref">
                  <span class="order-num">#{{ order.order_number }}</span>
                  <span class="order-date">{{ new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}</span>
                </div>
              </td>
              <td>
                <div class="customer-cell-row">
                  <div class="cust-avatar">{{ order.customer_name?.charAt(0)?.toUpperCase() }}</div>
                  <div>
                    <div class="cust-name">{{ order.customer_name }}</div>
                    <div class="cust-phone">{{ order.customer_phone }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div v-for="(item, idx) in order.order_items" :key="idx" class="item-pill">
                  {{ item.product_name }} &times; {{ item.quantity }}
                </div>
              </td>
              <td><span class="amount-text">LKR {{ formatNumber(order.total) }}</span></td>
              <td>
                <div class="status-col">
                  <span :class="['status-dot', order.status]">{{ order.status }}</span>
                  <button
                    v-if="order.payment_method === 'bank_deposit' && order.bank_slip_path"
                    @click="openSlipModal(order)"
                    class="slip-link"
                  ><i class="fa-regular fa-image"></i> Slip</button>
                </div>
              </td>
              <td>
                <div class="action-btns">
                  <button v-if="order.status === 'pending'" @click="updateStatus(order, 'confirmed')" class="act-btn act-blue">Confirm</button>
                  <button v-if="order.status === 'confirmed'" @click="updateStatus(order, 'processing')" class="act-btn act-purple">Process</button>
                  <button v-if="order.status === 'processing'" @click="updateStatus(order, 'dispatched')" class="act-btn act-green">Dispatch</button>
                  <button v-if="order.status === 'dispatched'" @click="updateStatus(order, 'delivered')" class="act-btn act-gray">Close</button>
                </div>
              </td>
            </tr>
            <tr v-if="!stats.recentOrders?.length">
              <td colspan="6" class="empty-row">
                <i class="fa-solid fa-inbox"></i>
                <p>No orders yet</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Side Panel: Top Regions -->
      <div class="dash-side-panel">
        <h3 class="panel-title">Top Regions</h3>
        <p class="panel-subtitle">Orders by city / district</p>
        <div class="region-list" v-if="stats.districtStats?.length">
          <div v-for="(stat, i) in stats.districtStats" :key="stat.customer_city" class="region-row">
            <div class="region-rank">{{ i + 1 }}</div>
            <div class="region-info">
              <span class="region-name"><i class="fa-solid fa-location-dot"></i> {{ stat.customer_city }}</span>
              <div class="region-bar-wrap">
                <div class="region-bar" :style="{ width: Math.min((stat.count / stats.totalOrders) * 100, 100) + '%' }"></div>
              </div>
            </div>
            <div class="region-count">{{ stat.count }}</div>
          </div>
        </div>
        <div v-else class="empty-panel">
          <i class="fa-solid fa-map-location-dot"></i>
          <p>No region data yet</p>
        </div>
      </div>
    </div>

    <!-- Bank Slip Modal (modern) -->
    <div :class="['modal-overlay', { open: isSlipModalOpen }]" @click.self="closeSlipModal">
      <div class="om-modal">
        <div class="om-left om-left-bank">
          <div class="om-icon-wrap"><i class="fa-solid fa-building-columns"></i></div>
          <h3 class="om-side-title">Bank Receipt</h3>
          <p class="om-side-desc">Verify the transfer before approving.</p>
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
            <p class="receipt-note">Verify total <strong>LKR {{ formatNumber(selectedOrder?.total || 0) }}</strong> before approving.</p>
          </div>
          <div class="pm-footer">
            <a v-if="selectedOrder?.bank_slip_path" :href="'http://127.0.0.1:8000/api/orders/' + selectedOrder.id + '/download-slip'" download class="btn-admin btn-admin-secondary mr-auto">
              <i class="fa-solid fa-download"></i> Download
            </a>
            <button @click="closeSlipModal" class="btn-admin btn-admin-secondary">Close</button>
            <button v-if="selectedOrder?.status === 'pending'" @click="approveSlipFromModal" class="btn-admin om-save-bank">Approve & Confirm</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm Dialog (modern) -->
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

  </div>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue'

const API = 'http://localhost:8000/api'
const token = () => localStorage.getItem('maneesha-admin-token') || ''
const adminName = inject('adminName', ref('Admin'))

const stats = ref({
  totalRevenue: 0,
  totalOrders: 0,
  pendingOrders: 0,
  deliveredOrders: 0,
  dispatchedOrders: 0,
  bankDepositOrders: 0,
  codOrders: 0,
  payhereOrders: 0,
  recentOrders: [],
  districtStats: []
})
const isSlipModalOpen = ref(false)
const selectedOrder = ref(null)

const confirmDialog = ref({ isOpen: false, message: '', onConfirm: null })
const openConfirm = (message, onConfirm) => { confirmDialog.value = { isOpen: true, message, onConfirm } }
const closeConfirm = () => { confirmDialog.value.isOpen = false }
const executeConfirm = () => { if (confirmDialog.value.onConfirm) confirmDialog.value.onConfirm(); closeConfirm() }

const authHeaders = () => ({ 'Authorization': `Bearer ${token()}`, 'Accept': 'application/json' })

const loadDashboardStats = async () => {
  try {
    const res = await fetch(`${API}/admin/dashboard/stats`, { headers: authHeaders() })
    if (res.ok) stats.value = await res.json()
  } catch (err) { console.error('Failed to load dashboard stats', err) }
}

const updateStatus = (order, newStatus) => {
  openConfirm(`Change order #${order.order_number} to '${newStatus}'?`, async () => {
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/admin/orders/${order.id}/status`, {
        method: 'PUT',
        headers: { ...authHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: newStatus })
      })
      if (res.ok) await loadDashboardStats()
    } catch (err) { console.error('Failed to update status', err) }
  })
}

const openSlipModal = (order) => { selectedOrder.value = order; isSlipModalOpen.value = true }
const closeSlipModal = () => { selectedOrder.value = null; isSlipModalOpen.value = false }
const approveSlipFromModal = () => { if (selectedOrder.value) { updateStatus(selectedOrder.value, 'confirmed'); closeSlipModal() } }
const formatNumber = (num) => Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })

onMounted(loadDashboardStats)
</script>

<style scoped>
/* Welcome Banner */
.dash-welcome {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
  flex-wrap: wrap;
  gap: 12px;
}
.dash-welcome-text h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--admin-text-primary);
}
.dash-name { color: var(--primary-blue); }
.dash-welcome-text p { font-size: 0.875rem; color: var(--admin-text-secondary); margin-top: 4px; }
.dash-date-chip {
  display: flex; align-items: center; gap: 8px;
  font-size: 0.8rem; font-weight: 500; color: var(--admin-text-secondary);
  background: var(--admin-surface); border: 1px solid var(--admin-border);
  padding: 8px 16px; border-radius: 20px;
}

/* KPI Cards */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 16px;
}
@media (max-width: 900px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.kpi-card {
  background: var(--admin-surface);
  border: 1px solid var(--admin-border);
  border-radius: 12px;
  padding: 16px 18px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: var(--shadow-card);
  transition: transform 0.2s, box-shadow 0.2s;
  position: relative;
  overflow: hidden;
}
.kpi-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  border-radius: 12px 12px 0 0;
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }

.kpi-revenue::before { background: linear-gradient(90deg, #0ea5e9, #7c3aed); }
.kpi-orders::before  { background: linear-gradient(90deg, #7c3aed, #ec4899); }
.kpi-pending::before { background: linear-gradient(90deg, #f59e0b, #ef4444); }
.kpi-delivered::before { background: linear-gradient(90deg, #10b981, #0ea5e9); }

.kpi-icon {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; flex-shrink: 0;
}
.kpi-revenue .kpi-icon { background: #eff6ff; color: #0ea5e9; }
.kpi-orders  .kpi-icon { background: #f5f3ff; color: #7c3aed; }
.kpi-pending .kpi-icon { background: #fffbeb; color: #f59e0b; }
.kpi-delivered .kpi-icon { background: #ecfdf5; color: #10b981; }

.kpi-body { flex: 1; min-width: 0; }
.kpi-label {
  font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.5px; color: var(--admin-text-muted); display: block;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.kpi-value {
  font-size: 1.25rem; font-weight: 700; margin-top: 3px;
  color: var(--admin-text-primary); line-height: 1.2;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.kpi-badge-warn {
  flex-shrink: 0;
  background: #fef3c7; color: #d97706;
  font-size: 0.62rem; font-weight: 700; padding: 2px 7px; border-radius: 20px;
  white-space: nowrap;
}

/* Payment Breakdown */
.pay-breakdown-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 28px;
}
@media (max-width: 900px) { .pay-breakdown-grid { grid-template-columns: repeat(2, 1fr); } }

.pay-card {
  background: var(--admin-surface);
  border: 1px solid var(--admin-border);
  border-radius: 12px;
  padding: 16px 18px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: var(--shadow-card);
}
.pay-icon {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.pay-bank { background: #f0f4ff; color: #6366f1; }
.pay-cod  { background: #ecfdf5; color: #10b981; }
.pay-ph   { background: #eff6ff; color: #0ea5e9; }
.pay-disp { background: #f5f3ff; color: #7c3aed; }

.pay-info span { display: block; font-size: 0.72rem; font-weight: 600; color: var(--admin-text-muted); text-transform: uppercase; }
.pay-info strong { font-size: 1.4rem; font-weight: 800; color: var(--admin-text-primary); }

/* Main Layout */
.dash-main {
  display: grid;
  grid-template-columns: 1fr 280px;
  gap: 20px;
}
@media (max-width: 1100px) { .dash-main { grid-template-columns: 1fr; } }

/* Table Panel */
.dash-table-panel {
  background: var(--admin-surface);
  border: 1px solid var(--admin-border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
}

.panel-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--admin-border);
  display: flex; align-items: center; justify-content: space-between;
}
.panel-title { font-size: 1rem; font-weight: 700; }
.panel-subtitle { font-size: 0.75rem; color: var(--admin-text-muted); margin-top: 2px; }

.refresh-btn {
  display: flex; align-items: center; gap: 6px;
  font-size: 0.8rem; font-weight: 600; padding: 7px 14px;
  border-radius: 8px; border: 1px solid var(--admin-border);
  background: var(--admin-bg); color: var(--admin-text-secondary);
  cursor: pointer; transition: all 0.2s;
}
.refresh-btn:hover { background: var(--primary-blue); color: #fff; border-color: var(--primary-blue); }

.dash-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
.dash-table th {
  text-align: left; padding: 10px 14px;
  font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.5px; color: var(--admin-text-muted);
  background: var(--admin-bg); border-bottom: 1px solid var(--admin-border);
  white-space: nowrap;
}
.dash-table td {
  padding: 12px 14px;
  border-bottom: 1px solid var(--admin-border);
  vertical-align: middle;
  font-size: 0.78rem;
}
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover { background: var(--admin-bg); }

.order-num { font-weight: 700; color: var(--admin-text-primary); font-size: 0.78rem; white-space: nowrap; display: block; }
.order-date { font-size: 0.68rem; color: var(--admin-text-muted); white-space: nowrap; display: block; }

.item-pill {
  font-size: 0.72rem; background: var(--admin-bg);
  border: 1px solid var(--admin-border); border-radius: 4px;
  padding: 2px 8px; display: inline-block; margin: 1px 2px 1px 0;
  color: var(--admin-text-secondary);
}

.amount-text { font-size: 0.8rem; font-weight: 700; color: var(--admin-text-primary); white-space: nowrap; }

.status-dot {
  display: inline-block; padding: 3px 10px; border-radius: 20px;
  font-size: 0.7rem; font-weight: 700; text-transform: capitalize;
}
.status-dot.pending    { background: #fef3c7; color: #d97706; }
.status-dot.confirmed  { background: #eff6ff; color: #2563eb; }
.status-dot.processing { background: #f5f3ff; color: #7c3aed; }
.status-dot.dispatched { background: #ecfdf5; color: #059669; }
.status-dot.delivered  { background: #f0fdf4; color: #16a34a; }

.slip-link {
  display: flex; align-items: center; gap: 4px;
  font-size: 0.7rem; font-weight: 700; color: var(--primary-blue);
  background: transparent; border: none; cursor: pointer; margin-top: 4px;
}
.slip-link:hover { text-decoration: underline; }

.action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
.act-btn {
  padding: 5px 12px; border-radius: 6px; border: none;
  font-size: 0.72rem; font-weight: 700; cursor: pointer; transition: all 0.2s;
}
.act-blue   { background: #eff6ff; color: #2563eb; } .act-blue:hover   { background: #2563eb; color: #fff; }
.act-purple { background: #f5f3ff; color: #7c3aed; } .act-purple:hover { background: #7c3aed; color: #fff; }
.act-green  { background: #ecfdf5; color: #059669; } .act-green:hover  { background: #059669; color: #fff; }
.act-gray   { background: var(--admin-bg); color: var(--admin-text-secondary); border: 1px solid var(--admin-border); }
.act-gray:hover { background: var(--admin-text-secondary); color: #fff; }

/* Customer avatar in table */
.customer-cell-row {
  display: flex;
  align-items: center;
  gap: 10px;
}
.cust-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: linear-gradient(135deg, #0ea5e9, #7c3aed);
  color: #fff; font-size: 0.8rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.cust-name { font-size: 0.78rem; font-weight: 600; color: var(--admin-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 120px; }
.cust-phone { font-size: 0.68rem; color: var(--admin-text-muted); margin-top: 1px; white-space: nowrap; }

.order-ref { display: flex; flex-direction: column; gap: 2px; }

.status-col { display: flex; flex-direction: column; gap: 4px; }

.empty-row { text-align: center; padding: 60px 20px; color: var(--admin-text-muted); }
.empty-row i { font-size: 2.5rem; display: block; margin-bottom: 12px; }
.empty-row p { font-size: 0.875rem; }

/* Side Panel */
.dash-side-panel {
  background: var(--admin-surface);
  border: 1px solid var(--admin-border);
  border-radius: 14px;
  padding: 20px;
  box-shadow: var(--shadow-card);
  height: fit-content;
}

.region-list { margin-top: 16px; display: flex; flex-direction: column; gap: 12px; }

.region-row {
  display: flex; align-items: center; gap: 10px;
}

.region-rank {
  width: 24px; height: 24px; border-radius: 6px;
  background: var(--admin-bg); border: 1px solid var(--admin-border);
  font-size: 0.72rem; font-weight: 700; color: var(--admin-text-secondary);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.region-info { flex: 1; }
.region-name { font-size: 0.82rem; font-weight: 600; color: var(--admin-text-primary); display: block; margin-bottom: 4px; }
.region-name i { color: #ef4444; margin-right: 4px; font-size: 0.7rem; }

.region-bar-wrap { background: var(--admin-bg); border-radius: 4px; height: 4px; overflow: hidden; }
.region-bar { height: 100%; background: linear-gradient(90deg, #0ea5e9, #7c3aed); border-radius: 4px; transition: width 0.6s ease; }

.region-count { font-size: 0.85rem; font-weight: 800; color: var(--admin-text-primary); flex-shrink: 0; }

.empty-panel { text-align: center; padding: 40px 0; color: var(--admin-text-muted); }
.empty-panel i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

/* Modals (reused om- classes from orders) */
.om-modal {
  display: flex; width: min(760px, 96vw); height: min(82vh, 560px);
  border-radius: 20px; overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.25); background: #fff;
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
.om-icon-wrap { width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #fff; }
.om-side-title { font-size: 1rem; font-weight: 700; color: #fff; }
.om-side-desc { font-size: 0.75rem; color: rgba(255,255,255,0.8); line-height: 1.5; flex: 1; }
.om-amount-chip { background: rgba(255,255,255,0.2); color: #fff; font-weight: 700; font-size: 0.9rem; padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.3); }
.om-right { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.om-body { flex: 1; overflow-y: auto; padding: 20px 28px; min-height: 0; }
.om-meta { font-size: 0.875rem; color: var(--admin-text-secondary); margin-bottom: 16px; }
.om-label-bank { color: #6366f1; }
.receipt-canvas { border-radius: 12px; overflow: hidden; background: var(--admin-bg); border: 1px solid var(--admin-border); margin: 12px 0; min-height: 180px; display: flex; align-items: center; justify-content: center; }
.receipt-image { max-width: 100%; max-height: 320px; object-fit: contain; display: block; }
.receipt-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: var(--admin-text-muted); font-size: 0.85rem; padding: 40px; }
.receipt-empty i { font-size: 3rem; }
.receipt-note { font-size: 0.8rem; color: var(--admin-text-secondary); text-align: center; }
.pm-header { padding: 24px 28px 16px; border-bottom: 1px solid var(--admin-border); display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.pm-header-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px; }
.pm-title { font-size: 1.2rem; font-weight: 700; color: var(--admin-text-primary); }
.pm-close-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--admin-border); background: transparent; color: var(--admin-text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.pm-close-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }
.pm-footer { padding: 16px 28px; border-top: 1px solid var(--admin-border); display: flex; gap: 12px; justify-content: flex-end; background: var(--admin-bg); }
.pm-save-btn { background: linear-gradient(135deg, #6366f1, #0ea5e9); min-width: 130px; }
.om-save-bank { background: linear-gradient(135deg, #10b981, #0ea5e9); color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
.mr-auto { margin-right: auto; }
.om-confirm { background: #fff; border-radius: 20px; padding: 40px 36px; text-align: center; max-width: 420px; width: 95vw; box-shadow: 0 25px 60px rgba(0,0,0,0.25); animation: modal-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.om-confirm-icon { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #fbbf24, #f59e0b); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #fff; margin: 0 auto 20px; }
.om-confirm-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; }
.om-confirm-msg { color: var(--admin-text-secondary); font-size: 0.9rem; margin-bottom: 28px; line-height: 1.5; }
.om-confirm-actions { display: flex; gap: 12px; justify-content: center; }
</style>

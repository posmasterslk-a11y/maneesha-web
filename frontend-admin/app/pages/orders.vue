<template>
  <UDashboardPanel id="orders">
    <template #header>
      <UDashboardNavbar title="Orders">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6">
        <!-- Metrics Grid -->
        <UPageGrid class="lg:grid-cols-6 gap-4 sm:gap-6 lg:gap-px">
          <UPageCard title="Total Orders" icon="i-lucide-boxes" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-blue-500/10 ring ring-inset ring-blue-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none first:rounded-l-lg last:rounded-r-lg">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.totalOrders || 0 }}</span>
          </UPageCard>
          <UPageCard title="Pending" icon="i-lucide-clock" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-amber-500/10 ring ring-inset ring-amber-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.pendingOrders || 0 }}</span>
          </UPageCard>
          <UPageCard title="Done" icon="i-lucide-truck" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-emerald-500/10 ring ring-inset ring-emerald-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.dispatchedOrders || 0 }}</span>
          </UPageCard>
          <UPageCard title="Bank Deposits" icon="i-lucide-building-2" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-purple-500/10 ring ring-inset ring-purple-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.bankDepositOrders || 0 }}</span>
          </UPageCard>
          <UPageCard title="PayHere" icon="i-lucide-credit-card" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-blue-500/10 ring ring-inset ring-blue-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.payhereOrders || 0 }}</span>
          </UPageCard>
          <UPageCard title="COD" icon="i-lucide-banknote" variant="subtle"
            :ui="{ container: 'gap-y-1.5', wrapper: 'items-start', leading: 'p-2.5 rounded-full bg-emerald-500/10 ring ring-inset ring-emerald-500/25 flex-col', title: 'font-normal text-muted text-xs uppercase' }"
            class="lg:rounded-none last:rounded-r-lg">
            <span class="text-2xl font-semibold text-highlighted">{{ stats.codOrders || 0 }}</span>
          </UPageCard>
        </UPageGrid>

        <div class="flex justify-between items-center">
          <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Order Management</h3>
          <div class="flex items-center gap-3">
            <USelect v-model="paymentFilter" :options="[
              { label: 'All Payment Methods', value: 'all' },
              { label: 'Bank Deposit', value: 'bank_deposit' },
              { label: 'Cash on Delivery', value: 'cod' },
              { label: 'PayHere Gateway', value: 'payhere' }
            ]" class="w-48" />
            <UButton icon="i-lucide-refresh-cw" color="primary" variant="soft" @click="loadOrders">Refresh</UButton>
          </div>
        </div>

        <!-- Orders Table -->
        <UCard :ui="{ body: { padding: '' } }">
          
          <UTable :data="filteredOrders" :columns="[
            { id: 'ref', header: 'Ref ID' },
            { id: 'customer', header: 'Customer' },
            { id: 'items', header: 'Items' },
            { id: 'total', header: 'Total' },
            { id: 'payment', header: 'Payment' },
            { id: 'status', header: 'Status' },
            { id: 'actions', header: 'Actions' }
          ]">
            <template #ref-cell="{ row }">
              <div class="flex flex-col">
                <span class="font-bold">#{{ row.original.order_number }}</span>
                <span class="text-xs text-gray-500"><UIcon name="i-lucide-clock" class="inline w-3 h-3" /> {{ new Date(row.original.created_at).toLocaleString() }}</span>
              </div>
            </template>
            
            <template #customer-cell="{ row }">
              <div class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-2 -m-2 rounded transition-colors" @click="openCustomerModal(row.original)">
                <div class="flex items-center gap-2">
                  <UAvatar :alt="row.original.customer_name" size="sm" />
                  <div class="flex flex-col">
                    <span class="font-semibold text-sm">{{ row.original.customer_name }}</span>
                    <span class="text-xs text-gray-500"><UIcon name="i-lucide-phone" class="inline w-3 h-3" /> {{ row.original.customer_phone }}</span>
                  </div>
                </div>
              </div>
            </template>
            
            <template #items-cell="{ row }">
              <UButton size="xs" variant="outline" icon="i-lucide-list" @click="openItemsModal(row.original)">
                View ({{ row.original.order_items?.length || 0 }})
              </UButton>
            </template>
            
            <template #total-cell="{ row }">
              <span class="font-bold">LKR {{ formatNumber(row.original.total) }}</span>
            </template>
            
            <template #payment-cell="{ row }">
              <div class="flex flex-col items-start gap-1">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ formatPaymentMethod(row.original.payment_method) }}</span>
                <UButton v-if="row.original.payment_method === 'bank_deposit' && row.original.bank_slip_path" size="xs" variant="soft" icon="i-lucide-image" @click="openSlipModal(row.original)">
                  Review Slip
                </UButton>
              </div>
            </template>
            
            <template #status-cell="{ row }">
              <UBadge :color="row.original.status === 'delivered' ? 'emerald' : row.original.status === 'pending' ? 'amber' : row.original.status === 'processing' ? 'purple' : row.original.status === 'dispatched' ? 'primary' : 'gray'" variant="soft" class="capitalize">
                {{ row.original.status }}
              </UBadge>
            </template>
            
            <template #actions-cell="{ row }">
              <div class="flex gap-1 flex-wrap max-w-[120px]">
                <UButton v-if="row.original.status === 'pending'" size="xs" color="primary" @click="updateStatus(row.original, 'confirmed')">Confirm</UButton>
                <UButton v-if="row.original.status === 'confirmed'" size="xs" color="purple" @click="updateStatus(row.original, 'processing')">Process</UButton>
                <UButton v-if="row.original.status === 'processing'" size="xs" color="emerald" @click="updateStatus(row.original, 'dispatched')">Dispatch</UButton>
                <UButton v-if="row.original.status === 'dispatched'" size="xs" color="gray" @click="updateStatus(row.original, 'delivered')">Close</UButton>
                <UButton v-if="['pending', 'confirmed', 'processing'].includes(row.original.status)" size="xs" color="red" variant="soft" @click="updateStatus(row.original, 'cancelled')">Cancel</UButton>
              </div>
            </template>
            
            <template #empty>
              <div class="flex flex-col items-center justify-center py-12">
                <UIcon name="i-lucide-inbox" class="w-12 h-12 text-gray-400 mb-4" />
                <p class="text-gray-500">No orders found for the selected criteria.</p>
              </div>
            </template>
          </UTable>
          
          <div class="p-4 border-t border-gray-200 dark:border-gray-800" v-if="orders.length > 0">
            <AdminPagination :current-page="currentPage" :last-page="lastPage" @page-change="loadOrders" />
          </div>
        </UCard>

        <!-- Modals -->
        <!-- Order Details Modal -->
        <UModal v-model:open="isItemsModalOpen" :ui="{ width: 'sm:max-w-3xl' }">
          <template #content>
            <UCard>
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-800">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                      <UIcon name="i-lucide-shopping-bag" class="w-5 h-5" />
                    </div>
                    <div>
                      <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Order Items Review</p>
                      <h3 class="font-bold text-lg leading-none">Order #{{ selectedOrderItemsOrder?.order_number }}</h3>
                    </div>
                  </div>
                  <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeItemsModal" />
                </div>
              <UTable :data="selectedOrderItems || []" :columns="[
                { id: 'product_name', header: 'Product' },
                { id: 'size', header: 'Size' },
                { id: 'quantity', header: 'Qty' },
                { id: 'unit_price', header: 'Price' },
                { id: 'total', header: 'Total' }
              ]">
                <template #unit_price-cell="{ row }">
                  LKR {{ formatNumber(row.original.unit_price) }}
                </template>
                <template #total-cell="{ row }">
                  <span class="font-bold">LKR {{ formatNumber(row.original.subtotal || (row.original.unit_price * row.original.quantity)) }}</span>
                </template>
                <template #product_name-cell="{ row }">
                  {{ row.original.product_name }}
                </template>
                <template #size-cell="{ row }">
                  {{ row.original.size }}
                </template>
                <template #quantity-cell="{ row }">
                  {{ row.original.quantity }}
                </template>
              </UTable>
              <template #footer>
                <div class="flex justify-end">
                  <UButton color="gray" @click="closeItemsModal">Close</UButton>
                </div>
              </template>
            </UCard>
          </template>
        </UModal>

        <UModal v-model:open="isSlipModalOpen">
          <template #content>
          <UCard>
            <template #header>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <UIcon name="i-lucide-receipt" class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Bank Receipt</p>
                    <h3 class="font-bold text-lg leading-none">Order #{{ selectedOrder?.order_number }}</h3>
                  </div>
                </div>
                <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeSlipModal" />
              </div>
            </template>
            
            <div class="space-y-4">
              <p>Customer: <strong>{{ selectedOrder?.customer_name }}</strong></p>
              <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-2 flex justify-center border border-gray-200 dark:border-gray-800 min-h-[200px]">
                <img v-if="selectedOrder?.bank_slip_path && !selectedOrder.bank_slip_path.endsWith('.pdf')" :src="'https://api-maneesha.posmasters.lk/storage/' + selectedOrder.bank_slip_path.replace('public/', '')" alt="Receipt" class="max-w-full max-h-[400px] object-contain" />
                <iframe v-else-if="selectedOrder?.bank_slip_path && selectedOrder.bank_slip_path.endsWith('.pdf')" :src="'https://api-maneesha.posmasters.lk/storage/' + selectedOrder.bank_slip_path.replace('public/', '')" class="w-full h-96 border-0 rounded"></iframe>
                <div v-else class="flex flex-col items-center justify-center text-gray-400">
                  <UIcon name="i-lucide-image" class="w-12 h-12 mb-2" />
                  <span>No slip uploaded</span>
                </div>
              </div>
              <p class="text-sm text-center text-gray-500">Verify the transfer reference and total amount of <strong>LKR {{ formatNumber(selectedOrder?.total || 0) }}</strong> matches bank statements before approving.</p>
            </div>
            
            <template #footer>
              <div class="flex justify-between w-full">
                <UButton v-if="selectedOrder?.bank_slip_path" :to="'https://api-maneesha.posmasters.lk/api/orders/' + selectedOrder.id + '/download-slip'" target="_blank" icon="i-lucide-download" color="gray" variant="solid">Download</UButton>
                <div class="flex gap-2">
                  <UButton color="gray" variant="ghost" @click="closeSlipModal">Close</UButton>
                  <UButton v-if="selectedOrder?.status === 'pending'" color="primary" @click="approveSlipFromModal">Approve & Confirm</UButton>
                </div>
              </div>
            </template>
            </UCard>
          </template>
        </UModal>

        <!-- Confirmation Modal -->
        <UModal v-model:open="confirmDialog.isOpen" :ui="{ width: 'sm:max-w-sm' }">
          <template #content>
            <div class="flex flex-col items-center text-center p-4">
              <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <UIcon name="i-lucide-alert-triangle" class="w-8 h-8" />
              </div>
              <h3 class="text-lg font-bold mb-2">Confirm Action</h3>
              <p class="text-gray-500 mb-6">{{ confirmDialog.message }}</p>
              <div class="flex gap-3 w-full justify-center">
                <UButton color="gray" variant="solid" @click="closeConfirm">Cancel</UButton>
                <UButton color="primary" @click="executeConfirm">Yes, Proceed</UButton>
              </div>
            </div>
          </template>
        </UModal>
        
        <UModal v-model:open="isCustomerModalOpen">
          <template #content>
          <UCard>
            <template #header>
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <UIcon name="i-lucide-user" class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Customer Details</p>
                    <h3 class="font-bold text-lg leading-none">{{ selectedCustomer?.customer_name }}</h3>
                  </div>
                </div>
                <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeCustomerModal" />
              </div>
            </template>
            <div class="space-y-4" v-if="selectedCustomer">
              <div class="flex items-center gap-3">
                <UIcon name="i-lucide-phone" class="w-5 h-5 text-gray-500" />
                <div>
                  <p class="text-xs text-gray-500">Phone</p>
                  <p class="font-semibold">{{ selectedCustomer.customer_phone }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-lucide-mail" class="w-5 h-5 text-gray-500" />
                <div>
                  <p class="text-xs text-gray-500">Email</p>
                  <p class="font-semibold">{{ selectedCustomer.customer_email || 'Not Provided' }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-lucide-map-pin" class="w-5 h-5 text-gray-500" />
                <div>
                  <p class="text-xs text-gray-500">Address</p>
                  <p class="font-semibold">{{ selectedCustomer.customer_address }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-lucide-map" class="w-5 h-5 text-gray-500" />
                <div>
                  <p class="text-xs text-gray-500">City</p>
                  <p class="font-semibold">{{ selectedCustomer.customer_city }}</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <UIcon name="i-lucide-hash" class="w-5 h-5 text-gray-500" />
                <div>
                  <p class="text-xs text-gray-500">Order Ref</p>
                  <p class="font-semibold">#{{ selectedCustomer.order_number }}</p>
                </div>
              </div>
            </div>
            <template #footer>
              <div class="flex justify-end">
                <UButton color="gray" @click="closeCustomerModal">Close</UButton>
              </div>
            </template>
          </UCard>
          </template>
        </UModal>

      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AdminPagination from '@/components/AdminPagination.vue'

const config = useRuntimeConfig()
const API = config.public.apiBase
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

const viewSlip = async (id) => {
  try {
    const r = await fetch(`${API}/admin/orders/${id}/view-slip`, { headers: authHeaders() });
    if (!r.ok) throw new Error('Failed to view slip');
    const blob = await r.blob();
    window.open(URL.createObjectURL(blob), '_blank');
  } catch (e) {
    alert('Failed to view slip');
  }
}

const downloadSlip = async (id) => {
  try {
    const r = await fetch(`${API}/admin/orders/${id}/download-slip`, { headers: authHeaders() });
    if (!r.ok) throw new Error('Failed to download slip');
    const blob = await r.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `slip-${id}.png`;
    a.click();
    URL.revokeObjectURL(url);
  } catch (e) {
    alert('Failed to download slip');
  }
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

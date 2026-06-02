<template>
  <UDashboardPanel id="dashboard">
    <template #header>
      <UDashboardNavbar title="Dashboard">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 animate-fade-in">
        
        <!-- Welcome Banner -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 p-5 rounded-2xl bg-gradient-to-r from-gray-900 to-gray-800 dark:from-gray-800 dark:to-gray-900 shadow-xl relative overflow-hidden group">
          <!-- Decorative Background Elements -->
          <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-700"></div>
          <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-700"></div>
          
          <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
              Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-emerald-400">{{ adminName }}</span> 👋
            </h2>
            <p class="text-gray-300 mt-1 text-sm font-medium max-w-xl">
              Here's your comprehensive overview of Maneesha Fashion today. Keep pushing the boundaries of style!
            </p>
          </div>
          <div class="relative z-10 flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 shadow-inner">
            <UIcon name="i-lucide-calendar-days" class="w-4 h-4 text-emerald-400" />
            <span class="text-white text-xs font-semibold tracking-wide">
              {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
            </span>
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Total Revenue -->
          <div class="modern-card group">
            <div class="card-glow bg-primary-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Revenue</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">LKR {{ formatNumber(stats.totalRevenue) }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900 dark:to-primary-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-trending-up" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-emerald-500">
              <UIcon name="i-lucide-arrow-up-right" class="w-3.5 h-3.5" />
              <span>Overall Growth</span>
            </div>
          </div>

          <!-- Total Orders -->
          <div class="modern-card group">
            <div class="card-glow bg-purple-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Orders</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.totalOrders }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-shopping-bag" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
              <UIcon name="i-lucide-activity" class="w-3.5 h-3.5" />
              <span>Active orders tracked</span>
            </div>
          </div>

          <!-- Pending Orders -->
          <div class="modern-card group border-l-4 border-l-amber-500">
            <div class="card-glow bg-amber-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Pending Actions</p>
                <div class="flex items-center gap-2">
                  <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.pendingOrders }}</h3>
                  <span v-if="stats.pendingOrders > 0" class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                  </span>
                </div>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900 dark:to-amber-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-clock" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-amber-600 dark:text-amber-400">
              <UIcon name="i-lucide-alert-circle" class="w-3.5 h-3.5" />
              <span>Requires attention</span>
            </div>
          </div>

          <!-- Delivered -->
          <div class="modern-card group">
            <div class="card-glow bg-emerald-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Delivered</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.deliveredOrders ?? 0 }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900 dark:to-emerald-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-emerald-500">
              <UIcon name="i-lucide-party-popper" class="w-3.5 h-3.5" />
              <span>Completed</span>
            </div>
          </div>
        </div>

        <!-- Payment Breakdown (Now matches top cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Bank Deposit -->
          <div class="modern-card group">
            <div class="card-glow bg-blue-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Bank Deposit</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.bankDepositOrders ?? 0 }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-building-2" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-blue-600 dark:text-blue-400">
              <UIcon name="i-lucide-pie-chart" class="w-3.5 h-3.5" />
              <span>Payment Type</span>
            </div>
          </div>
          
          <!-- COD -->
          <div class="modern-card group">
            <div class="card-glow bg-emerald-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Cash on Delivery</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.codOrders ?? 0 }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900 dark:to-emerald-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-banknote" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
              <UIcon name="i-lucide-pie-chart" class="w-3.5 h-3.5" />
              <span>Payment Type</span>
            </div>
          </div>

          <!-- PayHere -->
          <div class="modern-card group">
            <div class="card-glow bg-sky-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">PayHere (Card)</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.payhereOrders ?? 0 }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-100 to-sky-200 dark:from-sky-900 dark:to-sky-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-credit-card" class="w-5 h-5 text-sky-600 dark:text-sky-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-sky-600 dark:text-sky-400">
              <UIcon name="i-lucide-pie-chart" class="w-3.5 h-3.5" />
              <span>Payment Type</span>
            </div>
          </div>

          <!-- Dispatched -->
          <div class="modern-card group">
            <div class="card-glow bg-purple-500/20"></div>
            <div class="relative z-10 flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dispatched Orders</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ stats.dispatchedOrders ?? 0 }}</h3>
              </div>
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                <UIcon name="i-lucide-package" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
              </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[10px] font-semibold text-purple-600 dark:text-purple-400">
              <UIcon name="i-lucide-activity" class="w-3.5 h-3.5" />
              <span>In transit</span>
            </div>
          </div>
        </div>

        <!-- Single Column Layout: Main content -->
        <div class="flex flex-col gap-6">
          
          <!-- Recent Orders -->
          <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl border border-gray-200/50 dark:border-gray-800/50 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-white/50 dark:bg-gray-900/50">
              <div>
                <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                  <UIcon name="i-lucide-list-ordered" class="w-4 h-4 text-primary-500" /> Recent Orders
                </h3>
                <p class="text-[10px] font-medium text-gray-500 mt-0.5">Live tracking of the latest {{ stats.recentOrders?.length ?? 0 }} entries</p>
              </div>
              <UButton icon="i-lucide-refresh-cw" color="gray" variant="soft" @click="loadDashboardStats" :class="{'animate-spin': isRefreshing}" class="rounded-lg px-3 py-1 font-semibold text-[10px] tracking-wider uppercase">Refresh</UButton>
            </div>
            
            <UTable :data="stats.recentOrders || []" :columns="[
              { id: 'order', header: 'Order' },
              { id: 'customer', header: 'Customer' },
              { id: 'items', header: 'Items' },
              { id: 'total', header: 'Total' },
              { id: 'status', header: 'Status' },
              { id: 'actions', header: 'Actions' }
            ]" :ui="{ tr: { base: 'transition-colors hover:bg-primary-50/50 dark:hover:bg-primary-900/10 group' }, td: { padding: 'px-3 py-2', font: 'font-medium' }, th: { padding: 'px-3 py-2', font: 'font-bold uppercase tracking-wider text-[9px] text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-800/50' } }">
              
              <template #order-cell="{ row }">
                <div class="flex flex-col gap-0.5">
                  <span class="font-bold text-[13px] text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">#{{ row.original.order_number }}</span>
                  <span class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                    <UIcon name="i-lucide-clock" class="w-2.5 h-2.5" /> {{ new Date(row.original.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) }}
                  </span>
                </div>
              </template>
              
              <template #customer-cell="{ row }">
                <div class="flex items-center gap-2">
                  <UAvatar :alt="row.original.customer_name" size="xs" :ui="{ rounded: 'rounded-lg', fallback: { text: 'font-bold text-[10px]' } }" />
                  <div class="flex flex-col">
                    <span class="font-bold text-[13px] text-gray-900 dark:text-white">{{ row.original.customer_name }}</span>
                    <span class="text-[10px] font-medium text-gray-500">{{ row.original.customer_phone }}</span>
                  </div>
                </div>
              </template>
              
              <template #items-cell="{ row }">
                <div class="flex flex-wrap gap-1 max-w-[200px]">
                  <UBadge v-for="(item, idx) in row.original.order_items" :key="idx" color="gray" variant="soft" size="xs" class="rounded-md font-semibold text-[9px] border border-gray-200 dark:border-gray-700">
                    {{ item.product_name }} <span class="text-primary-500 ml-1">&times; {{ item.quantity }}</span>
                  </UBadge>
                </div>
              </template>
              
              <template #total-cell="{ row }">
                <span class="font-bold text-[13px] text-gray-900 dark:text-white">LKR {{ formatNumber(row.original.total) }}</span>
              </template>
              
              <template #status-cell="{ row }">
                <div class="flex flex-col items-start gap-1">
                  <div :class="[
                    'inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold capitalize border',
                    row.original.status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' : 
                    row.original.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800' : 
                    row.original.status === 'processing' ? 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800' : 
                    row.original.status === 'dispatched' ? 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-900/30 dark:text-sky-400 dark:border-sky-800' : 
                    'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700'
                  ]">
                    <span class="w-1 h-1 rounded-full" :class="[
                      row.original.status === 'delivered' ? 'bg-emerald-500' : 
                      row.original.status === 'pending' ? 'bg-amber-500 animate-pulse' : 
                      row.original.status === 'processing' ? 'bg-purple-500' : 
                      row.original.status === 'dispatched' ? 'bg-sky-500' : 'bg-gray-500'
                    ]"></span>
                    {{ row.original.status }}
                  </div>
                  
                  <UButton v-if="row.original.payment_method === 'bank_deposit' && row.original.bank_slip_path" size="2xs" variant="ghost" color="primary" icon="i-lucide-receipt" @click="openSlipModal(row.original)" class="rounded-md font-bold text-[9px]">
                    Slip
                  </UButton>
                </div>
              </template>
              
              <template #actions-cell="{ row }">
                <div class="flex gap-1 flex-wrap">
                  <UButton v-if="row.original.status === 'pending'" size="2xs" color="primary" variant="solid" class="rounded-md font-bold shadow-sm text-[9px]" @click="updateStatus(row.original, 'confirmed')">Confirm</UButton>
                  <UButton v-if="row.original.status === 'confirmed'" size="2xs" color="purple" variant="solid" class="rounded-md font-bold shadow-sm text-[9px]" @click="updateStatus(row.original, 'processing')">Process</UButton>
                  <UButton v-if="row.original.status === 'processing'" size="2xs" color="emerald" variant="solid" class="rounded-md font-bold shadow-sm text-[9px]" @click="updateStatus(row.original, 'dispatched')">Dispatch</UButton>
                  <UButton v-if="row.original.status === 'dispatched'" size="2xs" color="gray" variant="solid" class="rounded-md font-bold shadow-sm text-[9px]" @click="updateStatus(row.original, 'delivered')">Close</UButton>
                </div>
              </template>
              
              <template #empty>
                <div class="flex flex-col items-center justify-center py-10">
                  <div class="w-12 h-12 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2 border border-gray-100 dark:border-gray-700">
                    <UIcon name="i-lucide-inbox" class="w-5 h-5 text-gray-400" />
                  </div>
                  <p class="text-gray-900 dark:text-white font-bold text-xs">No orders yet</p>
                  <p class="text-gray-500 text-[10px] mt-1">When customers place orders, they will appear here.</p>
                </div>
              </template>
            </UTable>
          </div>
          
          <!-- Top Regions (Moved to bottom) -->
          <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl border border-gray-200/50 dark:border-gray-800/50 rounded-2xl p-5 shadow-sm">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2 mb-0.5">
              <UIcon name="i-lucide-map" class="w-4 h-4 text-primary-500" /> Regional Hotspots
            </h3>
            <p class="text-[10px] font-medium text-gray-500 mb-4">Top performing districts by order volume</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" v-if="stats.districtStats?.length">
              <div v-for="(stat, i) in stats.districtStats" :key="stat.customer_city" class="group bg-gray-50/50 dark:bg-gray-800/30 p-3 rounded-xl border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-md bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-[9px] font-black text-gray-500 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
                      {{ i + 1 }}
                    </div>
                    <span class="text-[11px] font-bold text-gray-900 dark:text-white">{{ stat.customer_city }}</span>
                  </div>
                  <span class="font-black text-[10px] text-primary-600 bg-primary-50 dark:bg-primary-900/30 px-1.5 py-0.5 rounded">{{ stat.count }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1 overflow-hidden">
                  <div class="bg-gradient-to-r from-primary-500 to-emerald-400 h-1 rounded-full transition-all duration-1000 ease-out" :style="{ width: `${(stat.count / (stats.totalOrders || 1)) * 100}%` }"></div>
                </div>
              </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center py-6">
              <UIcon name="i-lucide-map-pin-off" class="w-8 h-8 text-gray-300 mb-2" />
              <p class="text-[10px] font-semibold text-gray-500">No regional data available</p>
            </div>
          </div>
          
        </div>

        <!-- Modals -->
        <UModal v-model:open="isSlipModalOpen" :ui="{ content: 'sm:max-w-md w-full' }">
          <template #content>
            <div class="bg-white dark:bg-gray-900 overflow-hidden rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800">
              <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600">
                    <UIcon name="i-lucide-receipt" class="w-4 h-4" />
                  </div>
                  <div>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Bank Receipt</p>
                    <h3 class="font-black text-sm">Order #{{ selectedOrder?.order_number }}</h3>
                  </div>
                </div>
                <UButton color="gray" variant="ghost" icon="i-lucide-x" @click="closeSlipModal" class="rounded-lg" size="sm" />
              </div>
              
              <div class="p-5 space-y-4">
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-3 rounded-xl">
                  <div>
                    <p class="text-[9px] uppercase font-bold text-gray-500 mb-0.5">Customer</p>
                    <p class="font-bold text-xs">{{ selectedOrder?.customer_name }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-[9px] uppercase font-bold text-gray-500 mb-0.5">Amount Due</p>
                    <p class="font-black text-xs text-primary-600">LKR {{ formatNumber(selectedOrder?.total || 0) }}</p>
                  </div>
                </div>
                
                <div class="bg-gray-100/50 dark:bg-gray-800/50 rounded-xl p-2 flex justify-center border border-gray-200/50 dark:border-gray-700/50 min-h-[200px] relative group">
                  <img v-if="selectedOrder?.bank_slip_path" :src="'http://127.0.0.1:8000/api/orders/' + selectedOrder.id + '/view-slip'" alt="Receipt" class="max-w-full max-h-[300px] object-contain rounded-lg shadow-sm" />
                  <div v-else class="flex flex-col items-center justify-center text-gray-400">
                    <UIcon name="i-lucide-image-off" class="w-10 h-10 mb-2 opacity-50" />
                    <span class="font-semibold text-xs">No slip uploaded</span>
                  </div>
                </div>
              </div>
              
              <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-between bg-gray-50/50 dark:bg-gray-800/50">
                <UButton v-if="selectedOrder?.bank_slip_path" :to="'http://127.0.0.1:8000/api/orders/' + selectedOrder.id + '/download-slip'" target="_blank" icon="i-lucide-download" color="gray" variant="solid" class="rounded-lg font-bold text-[10px]" size="sm">Download</UButton>
                <div v-else></div>
                <div class="flex gap-2">
                  <UButton color="gray" variant="ghost" @click="closeSlipModal" class="rounded-lg font-bold text-[10px]" size="sm">Close</UButton>
                  <UButton v-if="selectedOrder?.status === 'pending'" color="primary" @click="approveSlipFromModal" class="rounded-lg font-bold shadow-md text-[10px]" size="sm">Approve Payment</UButton>
                </div>
              </div>
            </div>
          </template>
        </UModal>

        <UModal v-model:open="confirmDialog.isOpen" :ui="{ content: 'sm:max-w-xs w-full' }">
          <template #content>
            <div class="p-5 text-center">
              <div class="w-16 h-16 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-500 border-4 border-amber-100 dark:border-amber-900 flex items-center justify-center mx-auto mb-4 shadow-inner">
                <UIcon name="i-lucide-triangle-alert" class="w-8 h-8" />
              </div>
              <h3 class="text-base font-black mb-1.5 text-gray-900 dark:text-white">Are you sure?</h3>
              <p class="text-gray-500 dark:text-gray-400 text-xs mb-6 font-medium">{{ confirmDialog.message }}</p>
              <div class="flex gap-2 justify-center w-full">
                <UButton color="gray" variant="solid" @click="closeConfirm" class="flex-1 justify-center rounded-lg font-bold text-[11px]" size="sm">Cancel</UButton>
                <UButton color="primary" @click="executeConfirm" class="flex-1 justify-center rounded-lg font-bold shadow-md text-[11px]" size="sm">Confirm</UButton>
              </div>
            </div>
          </template>
        </UModal>

      </div>
    </template>
  </UDashboardPanel>
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
const isRefreshing = ref(false)

const confirmDialog = ref({ isOpen: false, message: '', onConfirm: null })
const openConfirm = (message, onConfirm) => { confirmDialog.value = { isOpen: true, message, onConfirm } }
const closeConfirm = () => { confirmDialog.value.isOpen = false }
const executeConfirm = () => { if (confirmDialog.value.onConfirm) confirmDialog.value.onConfirm(); closeConfirm() }

const authHeaders = () => ({ 'Authorization': `Bearer ${token()}`, 'Accept': 'application/json' })

const loadDashboardStats = async () => {
  isRefreshing.value = true
  try {
    const res = await fetch(`${API}/admin/dashboard/stats`, { headers: authHeaders() })
    if (res.ok) stats.value = await res.json()
  } catch (err) { console.error('Failed to load dashboard stats', err) }
  finally {
    setTimeout(() => { isRefreshing.value = false }, 500)
  }
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
/* Modern Entrance Animation */
@keyframes fadeInSlideUp {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeInSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Glassmorphism KPI Cards */
.modern-card {
  position: relative;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.4);
  border-radius: 1.25rem;
  padding: 1.25rem;
  overflow: hidden;
  box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 1;
}

.dark .modern-card {
  background: rgba(17, 24, 39, 0.6);
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.3);
}

.modern-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.1);
}

.dark .modern-card:hover {
  box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.4);
}

.card-glow {
  position: absolute;
  top: -30%;
  right: -30%;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  filter: blur(35px);
  opacity: 0.5;
  transition: opacity 0.3s ease;
  z-index: 0;
  pointer-events: none;
}

.modern-card:hover .card-glow {
  opacity: 0.8;
}

/* Clean Scrollbar for Table */
::-webkit-scrollbar {
  width: 5px;
  height: 5px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.dark ::-webkit-scrollbar-thumb {
  background: #475569;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<template>
  <UDashboardPanel>
    <UDashboardNavbar title="SMS Center & Billing" badge="Admin Only">
      <template #right>
        <UTooltip text="Globally enable or disable SMS gateway">
          <div class="flex items-center gap-2">
            <span class="text-sm font-medium" :class="settings.sms_enabled ? 'text-green-500' : 'text-gray-500'">
              {{ settings.sms_enabled ? 'SMS is ON' : 'SMS is OFF' }}
            </span>
            <USwitch v-model="settings.sms_enabled" @update:model-value="toggleSmsSetting" color="green" />
          </div>
        </UTooltip>
      </template>
    </UDashboardNavbar>

    <UDashboardPanelContent>
      <UTabs :items="tabs" class="w-full" :unmount="false">
        <!-- Dashboard & Billing Tab -->
        <template #billing="{ item }">
          <div class="p-6 space-y-6">
            <h2 class="text-xl font-bold">Billing & Usage ({{ billingData.month }})</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <UCard class="bg-primary-50 dark:bg-primary-950">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-lg">
                    <UIcon name="i-lucide-send" class="w-8 h-8 text-primary-600 dark:text-primary-400" />
                  </div>
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total SMS Sent</p>
                    <p class="text-3xl font-bold">{{ billingData.total_sent }}</p>
                  </div>
                </div>
              </UCard>
              
              <UCard class="bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-800">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                    <UIcon name="i-lucide-receipt" class="w-8 h-8 text-amber-600 dark:text-amber-400" />
                  </div>
                  <div>
                    <p class="text-sm text-amber-600 dark:text-amber-400 font-medium">Estimated Bill</p>
                    <p class="text-3xl font-bold text-amber-700 dark:text-amber-300">LKR {{ Number(billingData.total_cost).toFixed(2) }}</p>
                  </div>
                </div>
              </UCard>
            </div>

            <UAlert title="Monthly Subscription & Billing" icon="i-lucide-info" color="blue" variant="soft">
              <template #description>
                Your SMS cost is LKR 1.30 per message. You will receive a final invoice at the end of the month via your registered PayHere account to settle the outstanding balance.
              </template>
            </UAlert>
          </div>
        </template>

        <!-- Promotional SMS Tab -->
        <template #promo="{ item }">
          <div class="p-6 max-w-3xl">
            <h2 class="text-xl font-bold mb-4">Send Promotional Campaign</h2>
            <p class="text-gray-500 mb-6 text-sm">This will send an SMS to all unique customer phone numbers found in your orders database.</p>
            
            <UForm :state="promoForm" @submit="sendPromo" class="space-y-4">
              <UFormGroup label="Message Content" description="Maximum 160 characters.">
                <UTextarea v-model="promoForm.message" :rows="4" placeholder="e.g. Special weekend sale! Get 20% off all designer dresses at Maneesha Fashion." />
                <div class="text-right text-xs mt-1" :class="promoForm.message.length > 160 ? 'text-red-500' : 'text-gray-500'">
                  {{ promoForm.message.length }} / 160
                </div>
              </UFormGroup>
              
              <UButton type="submit" color="primary" size="lg" icon="i-lucide-megaphone" :loading="sendingPromo" :disabled="!settings.sms_enabled || promoForm.message.length === 0 || promoForm.message.length > 160">
                Send to All Customers
              </UButton>
              <p v-if="!settings.sms_enabled" class="text-red-500 text-sm mt-2">SMS Gateway is currently disabled. Turn it on from the top right to send messages.</p>
            </UForm>
          </div>
        </template>

        <!-- Logs Tab -->
        <template #logs="{ item }">
          <div class="p-4">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-bold">SMS Delivery Logs</h2>
              <UButton icon="i-lucide-refresh-cw" color="gray" variant="ghost" @click="fetchLogs" :loading="loadingLogs" />
            </div>
            
            <UTable :rows="logs" :columns="columns" :loading="loadingLogs" class="w-full">
              <template #status-data="{ row }">
                <UBadge :color="row.status === 'sent' ? 'green' : 'red'" variant="subtle">
                  {{ row.status.toUpperCase() }}
                </UBadge>
              </template>
              <template #type-data="{ row }">
                <UBadge :color="row.type === 'promotional' ? 'purple' : 'blue'" variant="soft">
                  {{ row.type }}
                </UBadge>
              </template>
              <template #cost-data="{ row }">
                LKR {{ Number(row.cost).toFixed(2) }}
              </template>
              <template #created_at-data="{ row }">
                {{ new Date(row.created_at).toLocaleString() }}
              </template>
            </UTable>
          </div>
        </template>

        <!-- Templates & Settings Tab -->
        <template #templates="{ item }">
          <div class="p-6 max-w-4xl space-y-8">
            <div>
              <h2 class="text-xl font-bold mb-2">SMS Templates & Settings</h2>
              <p class="text-gray-500 text-sm">Configure automated messages sent to customers and admins. Use {name}, {order_id}, {total}, and {status} as placeholders.</p>
            </div>

            <UForm :state="settings" @submit="saveSettings" class="space-y-6">
              <UCard>
                <template #header>
                  <h3 class="font-bold text-lg">Customer Notifications</h3>
                </template>
                <div class="space-y-4">
                  <UFormGroup label="Order Placed Template" description="Sent to the customer immediately after checkout.">
                    <UTextarea v-model="settings.sms_template_order_customer" :rows="3" />
                    <p class="text-xs text-gray-400 mt-1">Available variables: {name}, {order_id}, {total}</p>
                  </UFormGroup>

                  <UFormGroup label="Order Status Updated Template" description="Sent to the customer when you update the order status.">
                    <UTextarea v-model="settings.sms_template_order_status" :rows="3" />
                    <p class="text-xs text-gray-400 mt-1">Available variables: {name}, {order_id}, {status}</p>
                  </UFormGroup>
                </div>
              </UCard>

              <UCard>
                <template #header>
                  <h3 class="font-bold text-lg">Admin Notifications</h3>
                </template>
                <div class="space-y-4">
                  <UFormGroup label="Admin Phone Numbers" description="Comma-separated list of phone numbers to notify on new orders.">
                    <UInput v-model="settings.sms_admin_numbers" placeholder="e.g. 0771234567, 0719876543" />
                  </UFormGroup>

                  <UFormGroup label="New Order Placed Template" description="Sent to the admin numbers above.">
                    <UTextarea v-model="settings.sms_template_order_admin" :rows="3" />
                    <p class="text-xs text-gray-400 mt-1">Available variables: {name}, {order_id}, {total}</p>
                  </UFormGroup>
                </div>
              </UCard>

              <div class="flex justify-end">
                <UButton type="submit" color="primary" size="lg" icon="i-lucide-save" :loading="savingSettings">
                  Save All Settings
                </UButton>
              </div>
            </UForm>
          </div>
        </template>
      </UTabs>
    </UDashboardPanelContent>
  </UDashboardPanel>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const config = useRuntimeConfig()
const API = config.public.apiBase
const token = () => (localStorage.getItem('maneesha-admin-token') || sessionStorage.getItem('maneesha-admin-token')) || ''

const toast = useToast()

const settings = ref({
  sms_enabled: false,
  sms_template_order_customer: '',
  sms_template_order_status: '',
  sms_template_order_admin: '',
  sms_admin_numbers: ''
})
const savingSettings = ref(false)

const billingData = ref({ total_sent: 0, total_cost: 0, month: '' })
const logs = ref([])
const loadingLogs = ref(false)

const sendingPromo = ref(false)
const promoForm = ref({ message: '' })

const tabs = [
  { label: 'Billing Dashboard', icon: 'i-lucide-pie-chart', slot: 'billing' },
  { label: 'Promotional SMS', icon: 'i-lucide-megaphone', slot: 'promo' },
  { label: 'Message Logs', icon: 'i-lucide-list', slot: 'logs' },
  { label: 'Templates & Settings', icon: 'i-lucide-settings', slot: 'templates' }
]

const columns = [
  { key: 'phone', label: 'Recipient' },
  { key: 'message', label: 'Message' },
  { key: 'type', label: 'Type' },
  { key: 'status', label: 'Status' },
  { key: 'cost', label: 'Cost' },
  { key: 'created_at', label: 'Date / Time' }
]

const getHeaders = () => ({
  'Authorization': `Bearer ${token()}`,
  'Accept': 'application/json'
})

const fetchSettings = async () => {
  try {
    const res = await $fetch(`${API}/admin/sms/settings`, { headers: getHeaders() })
    settings.value = res
  } catch (err) {
    console.error('Failed to fetch SMS settings', err)
  }
}

const toggleSmsSetting = async (val) => {
  try {
    await $fetch(`${API}/admin/sms/settings`, {
      method: 'POST',
      headers: { ...getHeaders(), 'Content-Type': 'application/json' },
      body: { sms_enabled: val }
    })
    toast.add({ title: 'Gateway Updated', description: `SMS Gateway is now ${val ? 'ON' : 'OFF'}`, color: 'green' })
  } catch (err) {
    toast.add({ title: 'Error', description: 'Could not update gateway', color: 'red' })
    settings.value.sms_enabled = !val // revert
  }
}

const saveSettings = async () => {
  savingSettings.value = true
  try {
    await $fetch(`${API}/admin/sms/settings`, {
      method: 'POST',
      headers: { ...getHeaders(), 'Content-Type': 'application/json' },
      body: settings.value
    })
    toast.add({ title: 'Success', description: 'SMS settings and templates saved successfully.', color: 'green' })
  } catch (err) {
    toast.add({ title: 'Error', description: 'Failed to save settings', color: 'red' })
  } finally {
    savingSettings.value = false
  }
}

const fetchBilling = async () => {
  try {
    billingData.value = await $fetch(`${API}/admin/sms/billing`, { headers: getHeaders() })
  } catch (err) {
    console.error('Failed to fetch billing', err)
  }
}

const fetchLogs = async () => {
  loadingLogs.value = true
  try {
    logs.value = await $fetch(`${API}/admin/sms/logs`, { headers: getHeaders() })
  } catch (err) {
    console.error('Failed to load logs', err)
  } finally {
    loadingLogs.value = false
  }
}

const sendPromo = async () => {
  if (!promoForm.value.message || promoForm.value.message.length > 160) return
  
  if (!confirm('Are you sure you want to send this promotional SMS to ALL customers? This action cannot be undone and will incur charges.')) return

  sendingPromo.value = true
  try {
    const res = await $fetch(`${API}/admin/sms/promotional`, {
      method: 'POST',
      headers: { ...getHeaders(), 'Content-Type': 'application/json' },
      body: { message: promoForm.value.message }
    })
    
    toast.add({ 
      title: 'Campaign Sent', 
      description: `Successfully sent to ${res.total_sent} customers out of ${res.total_recipients}.`, 
      color: 'green' 
    })
    
    promoForm.value.message = ''
    fetchLogs()
    fetchBilling()
  } catch (err) {
    toast.add({ title: 'Campaign Failed', description: err.response?._data?.message || err.message, color: 'red' })
  } finally {
    sendingPromo.value = false
  }
}

onMounted(() => {
  fetchSettings()
  fetchBilling()
  fetchLogs()
})
</script>

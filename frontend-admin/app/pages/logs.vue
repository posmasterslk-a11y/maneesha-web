<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar title="Activity Logs" :badge="logs.length">
      </UDashboardNavbar>

      <UDashboardToolbar>
        <template #left>
          <UInput
            v-model="search"
            icon="i-heroicons-magnifying-glass"
            placeholder="Search action..."
            @keyup.enter="fetchLogs"
          />
        </template>
        <template #right>
          <UButton icon="i-heroicons-arrow-path" color="gray" variant="ghost" @click="fetchLogs" />
        </template>
      </UDashboardToolbar>

      <UDashboardPanelContent>
        <UTable
          :rows="logs"
          :columns="columns"
          :loading="pending"
        >
          <template #user_id-data="{ row }">
            <span v-if="row.user">{{ row.user.name }} ({{ row.user.email }})</span>
            <span v-else class="text-gray-400">System / Unknown</span>
          </template>
          
          <template #created_at-data="{ row }">
            {{ new Date(row.created_at).toLocaleString() }}
          </template>
        </UTable>

        <div class="mt-4 flex justify-between items-center" v-if="totalPages > 1">
          <span class="text-sm text-gray-500">Page {{ currentPage }} of {{ totalPages }}</span>
          <UPagination
            v-model="currentPage"
            :page-count="perPage"
            :total="total"
            @update:model-value="fetchLogs"
          />
        </div>
      </UDashboardPanelContent>
    </UDashboardPanel>
  </UDashboardPage>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const config = useRuntimeConfig()
const toast = useToast()

const logs = ref([])
const pending = ref(false)
const search = ref('')
const currentPage = ref(1)
const total = ref(0)
const perPage = ref(20)
const totalPages = ref(1)

const columns = [
  { key: 'user_id', label: 'User' },
  { key: 'action', label: 'Action' },
  { key: 'description', label: 'Description' },
  { key: 'ip_address', label: 'IP Address' },
  { key: 'created_at', label: 'Date & Time' }
]

const fetchLogs = async () => {
  pending.value = true
  try {
    const token = localStorage.getItem('token')
    const query = new URLSearchParams({
      page: currentPage.value.toString(),
      ...(search.value && { action_type: search.value })
    }).toString()

    const response = await $fetch(`${config.public.apiBase}/admin/activity-logs?${query}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })

    logs.value = response.data
    total.value = response.total
    perPage.value = response.per_page
    totalPages.value = response.last_page
  } catch (error: any) {
    console.error('Error fetching logs:', error)
    toast.add({ title: 'Error fetching logs', color: 'red' })
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  fetchLogs()
})
</script>

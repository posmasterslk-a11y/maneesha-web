<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'

const route = useRoute()
const toast = useToast()

const open = ref(false)

const links = [[{
  label: 'Dashboard',
  icon: 'i-lucide-layout-dashboard',
  to: '/',
  onSelect: () => { open.value = false }
}, {
  label: 'Products',
  icon: 'i-lucide-shirt',
  to: '/products',
  onSelect: () => { open.value = false }
}, {
  label: 'Categories',
  icon: 'i-lucide-tags',
  to: '/categories',
  onSelect: () => { open.value = false }
}, {
  label: 'Orders',
  icon: 'i-lucide-shopping-cart',
  to: '/orders',
  onSelect: () => { open.value = false }
}], [{
  label: 'Sign Out',
  icon: 'i-lucide-log-out',
  onSelect: () => { 
    localStorage.removeItem('maneesha-admin-auth');
    localStorage.removeItem('maneesha-admin-token');
    window.location.href = '/login'; 
  }
}]] satisfies NavigationMenuItem[][]

const groups = computed(() => [{
  id: 'links',
  label: 'Go to',
  items: links.flat()
}])

onMounted(async () => {
  const cookie = useCookie('cookie-consent')
  if (cookie.value === 'accepted') {
    return
  }

  toast.add({
    title: 'We use first-party cookies to enhance your experience on our website.',
    duration: 0,
    close: false,
    actions: [{
      label: 'Accept',
      color: 'neutral',
      variant: 'outline',
      onClick: () => {
        cookie.value = 'accepted'
      }
    }, {
      label: 'Opt out',
      color: 'neutral',
      variant: 'ghost'
    }]
  })
})
</script>

<template>
  <UDashboardGroup unit="rem">
    <UDashboardSidebar
      id="default"
      v-model:open="open"
      collapsible
      resizable
      class="bg-elevated/25"
      :ui="{ footer: 'lg:border-t lg:border-default' }"
    >
      <template #header="{ collapsed }">
        <div class="px-2 py-1 font-black text-xl text-primary-500 flex items-center justify-center overflow-hidden whitespace-nowrap">
          <UIcon name="i-lucide-store" class="w-6 h-6 shrink-0" :class="{ 'mr-2': !collapsed }" />
          <span v-if="!collapsed">Maneesha</span>
        </div>
      </template>

      <template #default="{ collapsed }">
        <UDashboardSearchButton :collapsed="collapsed" class="bg-transparent ring-default" />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="links[0]"
          orientation="vertical"
          tooltip
          popover
        />

        <UNavigationMenu
          :collapsed="collapsed"
          :items="links[1]"
          orientation="vertical"
          tooltip
          class="mt-auto"
        />
      </template>

      <template #footer="{ collapsed }">
        <UserMenu :collapsed="collapsed" />
      </template>
    </UDashboardSidebar>

    <UDashboardSearch :groups="groups" />

    <slot />

    <NotificationsSlideover />
  </UDashboardGroup>
</template>

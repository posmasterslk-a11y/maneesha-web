<template>
  <div class="admin-pagination" v-if="lastPage > 1">
    <button class="btn-page" :disabled="currentPage === 1" @click="$emit('page-change', currentPage - 1)">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    
    <div class="page-numbers">
      <button 
        v-for="page in visiblePages" 
        :key="page"
        :class="['btn-page', { active: page === currentPage }]"
        @click="$emit('page-change', page)"
      >
        {{ page }}
      </button>
    </div>

    <button class="btn-page" :disabled="currentPage === lastPage" @click="$emit('page-change', currentPage + 1)">
      <i class="fa-solid fa-chevron-right"></i>
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: { type: Number, required: true },
  lastPage: { type: Number, required: true }
})
defineEmits(['page-change'])

const visiblePages = computed(() => {
  const current = props.currentPage
  const last = props.lastPage
  const delta = 2 // How many pages to show around current page
  
  let left = current - delta
  let right = current + delta
  
  if (left < 1) {
    right = right + (1 - left)
    left = 1
  }
  
  if (right > last) {
    left = left - (right - last)
    right = last
  }
  
  if (left < 1) left = 1
  
  const pages = []
  for (let i = left; i <= right; i++) {
    pages.push(i)
  }
  
  return pages
})
</script>

<style scoped>
.admin-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  margin-top: 24px;
}
.btn-page {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: 1px solid var(--admin-border);
  background: var(--admin-bg);
  color: var(--admin-text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}
.btn-page:not(:disabled):hover {
  background: var(--primary-blue);
  color: #fff;
  border-color: var(--primary-blue);
}
.btn-page:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.page-numbers {
  display: flex;
  gap: 8px;
}
.btn-page.active {
  background: var(--admin-gold, #d4af37);
  color: #fff;
  border-color: var(--admin-gold, #d4af37);
}
</style>

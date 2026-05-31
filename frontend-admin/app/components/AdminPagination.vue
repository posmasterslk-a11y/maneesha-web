<template>
  <div class="admin-pagination" v-if="lastPage > 1">
    <button class="btn-page" :disabled="currentPage === 1" @click="$emit('page-change', currentPage - 1)">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <span class="page-info">Page {{ currentPage }} of {{ lastPage }}</span>
    <button class="btn-page" :disabled="currentPage === lastPage" @click="$emit('page-change', currentPage + 1)">
      <i class="fa-solid fa-chevron-right"></i>
    </button>
  </div>
</template>

<script setup>
defineProps({
  currentPage: { type: Number, required: true },
  lastPage: { type: Number, required: true }
})
defineEmits(['page-change'])
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
.page-info {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--admin-text-primary);
}
</style>

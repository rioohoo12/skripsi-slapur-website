<template>
  <div class="placeholder-view">
    <div class="glass-panel text-center py-5">
      <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-4" style="color: var(--primary); opacity: 0.5; margin: 0 auto;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
      <h2>Halaman Sedang Dibangun</h2>
      <p class="text-muted mt-2">Halaman ini belum diimplementasikan pada tahap ini.</p>
      <router-link to="/dashboard/murid" class="btn btn-primary mt-4" style="text-decoration: none;">Kembali ke Dashboard</router-link>
    </div>
  </div>
</template>

<style scoped>
.placeholder-view {
  max-width: 800px;
  margin: 2rem auto;
}
.py-5 { padding-top: 3rem; padding-bottom: 3rem; }
.text-center { text-align: center; }
.mb-4 { margin-bottom: 1.5rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 1.5rem; }
.text-muted { color: var(--text-secondary); }
</style>

<template>
  <div>
    <h1 class="page-title">Dashboard {{ roleName }}</h1>
    
    <div class="glass-panel stats-card">
      <div v-if="roleName === 'Murid'">
        <h3>Ringkasan Akademik</h3>
        <p>Lihat nilai dan jadwal kelas Anda di sini.</p>
      </div>
      
      <div v-else-if="roleName === 'Guru'">
        <h3>Jadwal Mengajar</h3>
        <p>Anda memiliki 2 kelas hari ini.</p>
      </div>
      
      <div v-else-if="roleName === 'Staff Administrasi'">
        <h3>Laporan Operasional</h3>
        <p>Cek absensi asrama dan log makan hari ini.</p>
      </div>
      
      <div v-else-if="roleName === 'Admin'">
        <h3>Sistem Control Panel</h3>
        <p>Kelola semua pengguna dan hak akses sistem.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const roleName = computed(() => route.meta.role);
</script>

<style scoped>
.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 2rem;
}

.stats-card {
  padding: 2rem;
}

.stats-card h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--primary);
}

.stats-card p {
  color: var(--text-secondary);
}
</style>

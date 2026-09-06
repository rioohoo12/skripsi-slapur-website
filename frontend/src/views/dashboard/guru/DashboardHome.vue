<template>
  <div class="guru-home">
    <div class="glass-panel welcome-card">
      <h2>Selamat Datang, {{ authStore.user?.name }}!</h2>
      <p>Ini adalah Pusat Kendali Guru. Pilih menu di bawah untuk mengelola kelas Anda hari ini.</p>
    </div>

    <div class="features-grid">
      <router-link to="/dashboard/guru/attendance" class="glass-panel feature-card">
        <div class="icon">📋</div>
        <h3>Absensi Murid</h3>
        <p>Catat kehadiran, sakit, atau izin murid setiap harinya.</p>
      </router-link>

      <router-link to="/dashboard/guru/grades" class="glass-panel feature-card">
        <div class="icon">📝</div>
        <h3>Input Nilai</h3>
        <p>Kelola nilai ujian dan tugas murid untuk rapor semester.</p>
      </router-link>

      <router-link to="/dashboard/guru/materials" class="glass-panel feature-card">
        <div class="icon">📚</div>
        <h3>Materi Pembelajaran</h3>
        <p>Unggah dan bagikan dokumen materi pelajaran ke kelas.</p>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../../../stores/auth';
const authStore = useAuthStore();
</script>

<style scoped>
.guru-home {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.welcome-card {
  padding: 2rem;
}

.welcome-card h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: white;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
}

.feature-card {
  padding: 2rem;
  text-decoration: none;
  color: var(--text-primary);
  transition: transform 0.2s, background 0.3s;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.feature-card:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.15);
}

.feature-card .icon {
  font-size: 3rem;
}

.feature-card h3 {
  color: white;
  font-size: 1.2rem;
}
</style>

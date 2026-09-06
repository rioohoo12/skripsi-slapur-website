<template>
  <div>
    <h1 class="page-title">Selamat Datang, {{ authStore.user?.name }}</h1>
    <div class="grid">
      <div class="glass-panel card">
        <h3>Menu Utama</h3>
        <ul class="nav-list">
          <li><router-link to="/dashboard/murid/payment">Pembayaran Pendaftaran</router-link></li>
          <li><router-link to="/dashboard/murid/rooms">Pemilihan Kamar Asrama</router-link></li>
          <li><router-link to="/dashboard/murid/academic">Jadwal & Nilai</router-link></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../../../stores/auth';
const authStore = useAuthStore();
</script>

<style scoped>
.page-title { margin-bottom: 2rem; font-size: 2rem; font-weight: 700; }
.card { padding: 2rem; }
.card h3 { color: var(--primary); margin-bottom: 1rem; }
.nav-list { list-style: none; padding: 0; }
.nav-list li { margin-bottom: 0.5rem; }
.nav-list a { color: var(--text-primary); text-decoration: none; font-weight: 500; transition: color 0.2s; }
.nav-list a:hover { color: var(--primary-hover); }
</style>

<template>
  <div class="dashboard-layout">
    <aside class="sidebar glass-panel">
      <div class="brand">Slapur</div>
      <nav class="nav-menu">
        <!-- Murid Links -->
        <template v-if="authStore.userRole === 'Murid'">
          <router-link to="/dashboard/murid" class="nav-item" exact-active-class="active">Beranda</router-link>
          <router-link to="/dashboard/murid/rooms" class="nav-item" exact-active-class="active">Pilih Kamar</router-link>
          <router-link to="/dashboard/murid/payment" class="nav-item" exact-active-class="active">Pembayaran</router-link>
          <router-link to="/dashboard/murid/academic" class="nav-item" exact-active-class="active">Akademik</router-link>
        </template>
        
        <!-- Guru Links -->
        <template v-else-if="authStore.userRole === 'Guru'">
          <router-link to="/dashboard/guru" class="nav-item" exact-active-class="active">Beranda Guru</router-link>
          <router-link to="/dashboard/guru/attendance" class="nav-item" exact-active-class="active">Absensi</router-link>
          <router-link to="/dashboard/guru/grades" class="nav-item" exact-active-class="active">Input Nilai</router-link>
          <router-link to="/dashboard/guru/materials" class="nav-item" exact-active-class="active">Materi Pelajaran</router-link>
        </template>

        <!-- Staff Links -->
        <template v-else-if="authStore.userRole === 'Staff Administrasi'">
          <router-link to="/dashboard/staff" class="nav-item" exact-active-class="active">Beranda Staff</router-link>
          <router-link to="/dashboard/staff/students" class="nav-item" exact-active-class="active">Master Siswa</router-link>
          <router-link to="/dashboard/staff/payments" class="nav-item" exact-active-class="active">Verifikasi Pembayaran</router-link>
          <router-link to="/dashboard/staff/rooms" class="nav-item" exact-active-class="active">Logistik Kamar</router-link>
          <router-link to="/dashboard/staff/dining" class="nav-item" exact-active-class="active">Layanan Dining</router-link>
        </template>

        <!-- Admin Links -->
        <template v-else-if="authStore.userRole === 'Admin'">
          <router-link to="/dashboard/admin" class="nav-item" exact-active-class="active">Pusat Kendali</router-link>
          <router-link to="/dashboard/admin/users" class="nav-item" exact-active-class="active">Manajemen User</router-link>
          <router-link to="/dashboard/staff" class="nav-item" exact-active-class="active">Laporan Operasional</router-link>
        </template>

        <!-- Default -->
        <template v-else>
          <router-link :to="'/dashboard/' + (authStore.userRole === 'Staff Administrasi' ? 'staff' : authStore.userRole.toLowerCase())" class="nav-item" exact-active-class="active">Dashboard</router-link>
        </template>
      </nav>
      <div class="user-info">
        <p class="user-name">{{ authStore.user?.name }}</p>
        <p class="user-role">{{ authStore.user?.role }}</p>
        <button @click="handleLogout" class="btn btn-primary logout-btn">Logout</button>
      </div>
    </aside>
    
    <main class="main-content">
      <router-view></router-view>
    </main>

    <!-- Chatbot Widget is rendered here so it's globally available across dashboards -->
    <ChatbotWidget />
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import ChatbotWidget from '../components/ChatbotWidget.vue';

const router = useRouter();
const authStore = useAuthStore();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background: var(--bg-gradient-start);
}

.sidebar {
  width: 280px;
  margin: 1rem;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
}

.brand {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  margin-bottom: 2rem;
  text-align: center;
}

.nav-menu {
  flex: 1;
}

.nav-item {
  display: block;
  margin-bottom: 0.5rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  color: var(--text-secondary);
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.nav-item:hover, .nav-item.active {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.user-info {
  margin-top: auto;
  padding-top: 1.5rem;
  border-top: 1px solid var(--surface-border);
}

.user-name {
  font-weight: 600;
  color: white;
  margin-bottom: 0.25rem;
}

.user-role {
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin-bottom: 1rem;
}

.logout-btn {
  width: 100%;
  background: rgba(239, 68, 68, 0.2);
  color: #fca5a5;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.logout-btn:hover {
  background: var(--error);
  color: white;
}

.main-content {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
}
</style>

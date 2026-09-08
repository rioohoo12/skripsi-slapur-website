<template>
  <aside :class="['sidebar', { 'sidebar-collapsed': isCollapsed }]">
    <div class="sidebar-header">
      <div class="brand">
        <template v-if="authStore.userRole === 'Murid'">
          <img src="../logo slapur.jpg" alt="Slapur Logo" class="brand-logo" />
          <div class="brand-text" v-if="!isCollapsed">
            <span class="brand-title">UNAI</span>
            <span class="brand-subtitle">Universitas Advent Indonesia</span>
          </div>
        </template>
        <template v-else>
          <span v-if="!isCollapsed">Slapur</span>
        </template>
      </div>
      <button class="toggle-btn" @click="$emit('toggle')">
        <svg v-if="!isCollapsed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
    </div>
    
    <nav class="nav-menu">
      <!-- Murid Links -->
      <template v-if="authStore.userRole === 'Murid'">
        <router-link to="/dashboard/murid" class="nav-item" exact-active-class="active" title="Dashboard">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
          <span v-if="!isCollapsed">Dashboard</span>
        </router-link>
        <router-link to="/dashboard/murid/krs" class="nav-item" exact-active-class="active" title="KRS / Perkuliahan">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          <span v-if="!isCollapsed">KRS / Perkuliahan</span>
        </router-link>
        <router-link to="/dashboard/murid/schedule" class="nav-item" exact-active-class="active" title="Jadwal Kuliah">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          <span v-if="!isCollapsed">Jadwal Kuliah</span>
        </router-link>
        <router-link to="/dashboard/murid/academic" class="nav-item" exact-active-class="active" title="Nilai">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          <span v-if="!isCollapsed">Nilai</span>
        </router-link>
        <router-link to="/dashboard/murid/transcript" class="nav-item" exact-active-class="active" title="Transkrip">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          <span v-if="!isCollapsed">Transkrip</span>
        </router-link>
        <router-link to="/dashboard/murid/billing" class="nav-item" exact-active-class="active" title="Tagihan & Pembayaran">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          <span v-if="!isCollapsed">Tagihan & Pembayaran</span>
        </router-link>
        <router-link to="/dashboard/murid/profile" class="nav-item" exact-active-class="active" title="Profil Mahasiswa">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span v-if="!isCollapsed">Profil Mahasiswa</span>
        </router-link>
        <router-link to="/dashboard/murid/settings" class="nav-item" exact-active-class="active" title="Pengaturan">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          <span v-if="!isCollapsed">Pengaturan</span>
        </router-link>
      </template>

      <!-- Staff Links (Legacy) -->
      <template v-else-if="authStore.userRole === 'Staff Administrasi'">
        <router-link to="/dashboard/staff" class="nav-item" exact-active-class="active">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
          <span v-if="!isCollapsed">Beranda Staff</span>
        </router-link>
      </template>
      
      <!-- General Fallback -->
      <template v-else>
         <router-link :to="'/dashboard/' + authStore.userRole.toLowerCase()" class="nav-item" exact-active-class="active">
          <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
          <span v-if="!isCollapsed">Dashboard</span>
        </router-link>
      </template>
    </nav>
    
    <div class="sidebar-quote" v-if="!isCollapsed">
      <p>"Pendidikan adalah investasi terbaik untuk masa depan."</p>
      <span class="quote-author">— UNAI</span>
    </div>
  </aside>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
const authStore = useAuthStore();
defineProps({
  isCollapsed: {
    type: Boolean,
    default: false
  }
});
defineEmits(['toggle']);
</script>

<style scoped>
.sidebar {
  width: 280px;
  background-color: var(--primary); /* Solid dark blue */
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  overflow: hidden;
  z-index: 100;
  position: relative;
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-collapsed {
  width: 80px;
}

.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem 1.25rem;
  margin-bottom: 1rem;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: white;
}

.brand-logo {
  width: 36px;
  height: 36px;
  object-fit: contain;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.brand-text {
  display: flex;
  flex-direction: column;
}

.brand-title {
  font-size: 1.25rem;
  font-weight: 700;
  line-height: 1.2;
}

.brand-subtitle {
  font-size: 0.65rem;
  color: rgba(255, 255, 255, 0.7);
  white-space: nowrap;
}

.toggle-btn {
  background: none;
  border: none;
  color: rgba(255, 255, 255, 0.6);
  cursor: pointer;
  padding: 0.25rem;
}

.toggle-btn:hover {
  color: white;
}

.nav-menu {
  flex: 1;
  padding: 0 1rem;
}

.nav-item {
  display: flex;
  align-items: center;
  margin-bottom: 0.25rem;
  padding: 0.85rem 1rem;
  border-radius: 8px;
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
  font-size: 0.9rem;
  white-space: nowrap;
}

.nav-icon {
  margin-right: 1rem;
  flex-shrink: 0;
  opacity: 0.8;
}

.sidebar-collapsed .nav-item {
  justify-content: center;
  padding: 0.85rem 0;
}

.sidebar-collapsed .nav-icon {
  margin-right: 0;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.05);
  color: white;
}

.nav-item.active {
  background: var(--secondary); /* The blue highlight color */
  color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nav-item.active .nav-icon {
  opacity: 1;
}

.sidebar-quote {
  margin: 1rem;
  padding: 1.5rem;
  background: rgba(0, 0, 0, 0.15);
  border-radius: 12px;
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.8rem;
  line-height: 1.5;
  font-style: italic;
  margin-bottom: 2rem;
}

.quote-author {
  display: block;
  margin-top: 0.75rem;
  font-size: 0.7rem;
  font-style: normal;
  color: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: -280px;
    height: 100vh;
    transition: left 0.3s ease;
  }
  
  .sidebar.mobile-open {
    left: 0;
  }
}
</style>

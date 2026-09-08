<template>
  <header class="navbar glass-panel">
    <div class="navbar-left w-full max-w-md">
      <button class="mobile-toggle-btn d-md-none" @click="$emit('toggle-mobile-sidebar')">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
      
      <!-- Search Bar (now on the left like the image) -->
      <div class="search-bar w-full">
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" placeholder="Cari mata kuliah, dosen, atau informasi lainnya..." class="search-input w-full" />
      </div>
    </div>

    <div class="navbar-right">
      <!-- Notifications -->
      <div class="notification-dropdown">
        <button class="notification-btn" @click="toggleNotifications">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          <span class="badge" v-if="unreadNotifications > 0">{{ unreadNotifications }}</span>
        </button>
        <div v-if="showNotifications" class="dropdown-menu notifications-menu glass-panel">
          <div class="dropdown-header">Notifikasi</div>
          <div class="dropdown-content">
            <div class="notification-item">
              <p class="notification-text">Jadwal UTS telah diperbarui.</p>
              <span class="notification-time">1 jam yang lalu</span>
            </div>
            <div class="notification-item">
              <p class="notification-text">Tagihan bulan ini sudah keluar.</p>
              <span class="notification-time">2 hari yang lalu</span>
            </div>
          </div>
          <div class="dropdown-footer cursor-pointer">Lihat Semua</div>
        </div>
      </div>

      <!-- User Profile Header -->
      <div class="user-dropdown">
        <button class="user-btn" @click="toggleUserMenu">
          <div class="user-info d-none d-md-block text-right">
            <span class="user-name">{{ authStore.user?.name || 'User' }}</span>
            <span class="user-role">{{ authStore.userRole }}</span>
          </div>
          <img src="https://ui-avatars.com/api/?name=User&background=1e3a8a&color=fff" alt="User Avatar" class="avatar" />
          <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div v-if="showUserMenu" class="dropdown-menu user-menu glass-panel">
          <router-link to="/dashboard/murid/profile" class="dropdown-item">Profil Saya</router-link>
          <div class="dropdown-divider"></div>
          <button @click="handleLogout" class="dropdown-item text-error">Logout</button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const showNotifications = ref(false);
const showUserMenu = ref(false);
const unreadNotifications = ref(3);

const currentPageName = computed(() => {
  return route.name ? String(route.name).replace(/-/g, ' ') : 'Beranda';
});

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  showUserMenu.value = false;
};

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
  showNotifications.value = false;
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  border-radius: 12px;
  margin-bottom: 2rem;
  position: sticky;
  top: 1rem;
  z-index: 90;
  background: white;
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex: 1;
}

.w-full {
  width: 100%;
}

.max-w-md {
  max-width: 500px;
}

.mobile-toggle-btn {
  background: none;
  border: none;
  color: var(--text-primary);
  cursor: pointer;
  padding: 0.25rem;
  display: none;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

/* Search Bar */
.search-bar {
  display: flex;
  align-items: center;
  background: #f1f5f9; /* Slate 100 */
  border-radius: 8px;
  padding: 0.5rem 1rem;
  gap: 0.75rem;
  border: 1px solid transparent;
  transition: all 0.2s;
}

.search-bar:focus-within {
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.search-input {
  background: transparent;
  border: none;
  color: var(--text-primary);
  outline: none;
  font-size: 0.875rem;
}

.search-input::placeholder {
  color: var(--text-secondary);
}

.search-icon {
  color: var(--text-secondary);
}

/* Buttons */
.notification-btn, .user-btn {
  background: transparent;
  border: none;
  color: var(--text-primary);
  cursor: pointer;
  position: relative;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.notification-btn {
  padding: 0.5rem;
  border-radius: 50%;
  background: #f1f5f9;
}

.badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background: var(--error);
  color: white;
  font-size: 0.65rem;
  padding: 0.15rem 0.35rem;
  border-radius: 10px;
  font-weight: bold;
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
}

.text-right {
  text-align: right;
}

.user-name {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-primary);
}

.user-role {
  display: block;
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.chevron {
  color: var(--text-secondary);
}

/* Dropdowns */
.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 1rem;
  min-width: 250px;
  border-radius: 12px;
  padding: 0.5rem 0;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  background: white;
}

.dropdown-header {
  padding: 0.75rem 1rem;
  font-weight: 600;
  border-bottom: 1px solid var(--surface-border);
  color: var(--text-primary);
}

.notification-item {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid var(--surface-border);
}

.notification-text {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
  color: var(--text-primary);
}

.notification-time {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.dropdown-footer {
  padding: 0.75rem 1rem;
  text-align: center;
  font-size: 0.875rem;
  color: var(--primary);
  font-weight: 500;
}

.dropdown-item {
  display: block;
  padding: 0.75rem 1rem;
  color: var(--text-primary);
  text-decoration: none;
  font-size: 0.875rem;
  cursor: pointer;
  background: transparent;
  border: none;
  width: 100%;
  text-align: left;
}

.dropdown-item:hover {
  background: #f8fafc;
}

.dropdown-divider {
  height: 1px;
  background: var(--surface-border);
  margin: 0.5rem 0;
}

.text-error {
  color: var(--error);
}

@media (max-width: 768px) {
  .search-bar { display: none; }
  .d-md-none { display: block !important; }
  .d-none.d-md-block { display: none !important; }
}
</style>

<template>
  <div class="admin-home">
    <div class="glass-panel welcome-card">
      <h2>Pusat Kendali Admin</h2>
      <p>Selamat datang di kontrol utama sistem Slapur. Anda memiliki akses penuh lintas modul.</p>
    </div>

    <div class="features-grid">
      <router-link to="/dashboard/admin/users" class="glass-panel feature-card">
        <div class="icon">👑</div>
        <h3>Manajemen Pengguna</h3>
        <p>Kontrol akses, ubah peran (role), dan blokir akun.</p>
      </router-link>
      
      <!-- Admins also have access to Staff Dashboard for full reports -->
      <router-link to="/dashboard/staff" class="glass-panel feature-card">
        <div class="icon">📊</div>
        <h3>Laporan Operasional</h3>
        <p>Lihat agregat asrama dan keuangan (Masuk melalui Modul Staff).</p>
      </router-link>
    </div>
  </div>
</template>

<style scoped>
.admin-home {
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

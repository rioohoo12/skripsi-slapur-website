<template>
  <div class="profile-page fade-in">
    <div class="page-header">
      <h1 class="page-title">Profil Siswa</h1>
      <p class="page-subtitle">Kelola informasi pribadi dan pengaturan akun Anda.</p>
    </div>

    <div class="profile-content">
      <div class="profile-card glass-panel">
        <div class="profile-header">
          <div class="avatar-container">
            <img :src="profile.avatar || 'https://ui-avatars.com/api/?name=User&background=10b981&color=fff'" alt="Avatar" class="profile-avatar" />
            <button class="edit-avatar-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            </button>
          </div>
          <div class="profile-summary">
            <h2>{{ profile.name }}</h2>
            <p class="role-badge">Siswa - Kelas {{ profile.class }}</p>
          </div>
        </div>

        <form @submit.prevent="saveProfile" class="profile-form">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" v-model="profile.name" class="form-input" required />
            </div>
            
            <div class="form-group">
              <label class="form-label">Kelas</label>
              <input type="text" v-model="profile.class" class="form-input" disabled />
            </div>

            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" v-model="profile.email" class="form-input" disabled />
            </div>

            <div class="form-group">
              <label class="form-label">Nomor Telepon</label>
              <input type="tel" v-model="profile.phone" class="form-input" placeholder="08xxxxxxxxxx" />
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
const loading = ref(false);

const profile = ref({
  name: '',
  email: '',
  class: '10 MIPA 1', // Dummy data
  phone: '081234567890', // Dummy data
  avatar: ''
});

onMounted(() => {
  if (authStore.user) {
    profile.value.name = authStore.user.name;
    profile.value.email = authStore.user.email;
  }
});

const saveProfile = async () => {
  loading.value = true;
  // Simulate API call
  setTimeout(() => {
    loading.value = false;
    alert('Profil berhasil diperbarui!');
  }, 1000);
};
</script>

<style scoped>
.profile-page {
  max-width: 800px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: white;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: var(--text-secondary);
}

.profile-card {
  padding: 2rem;
}

.profile-header {
  display: flex;
  align-items: center;
  gap: 2rem;
  margin-bottom: 2.5rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid var(--surface-border);
}

.avatar-container {
  position: relative;
}

.profile-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid var(--surface-border);
}

.edit-avatar-btn {
  position: absolute;
  bottom: 0;
  right: 0;
  background: var(--primary);
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0,0,0,0.2);
  transition: transform 0.2s;
}

.edit-avatar-btn:hover {
  transform: scale(1.1);
}

.profile-summary h2 {
  font-size: 1.5rem;
  color: white;
  margin-bottom: 0.5rem;
}

.role-badge {
  display: inline-block;
  background: rgba(16, 185, 129, 0.1);
  color: var(--primary);
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

@media (max-width: 600px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin-bottom: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--surface-border);
  border-radius: 8px;
  color: white;
  outline: none;
  transition: all 0.2s;
}

.form-input:disabled {
  background: rgba(255, 255, 255, 0.02);
  color: var(--text-secondary);
  cursor: not-allowed;
}

.form-input:focus:not(:disabled) {
  border-color: var(--primary);
  box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.form-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
}
</style>

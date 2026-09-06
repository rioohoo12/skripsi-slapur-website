<template>
  <div class="glass-panel login-card">
    <div class="login-header">
      <h2>Daftar Akun {{ roleName }}</h2>
      <p>Buat akun baru portal Slapur</p>
    </div>
    
    <div v-if="error" class="alert alert-error">
      {{ error }}
    </div>

    <form @submit.prevent="handleRegister">
      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" v-model="form.name" class="form-input" placeholder="Nama Lengkap" required />
      </div>

      <div class="form-group">
        <label class="form-label">Jenis Kelamin</label>
        <select v-model="form.gender" class="form-input select-input" required>
          <option value="" disabled>Pilih Jenis Kelamin</option>
          <option value="L">Laki-laki</option>
          <option value="P">Perempuan</option>
        </select>
      </div>
      
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" v-model="form.email" class="form-input" placeholder="contoh@slapur.com" required />
      </div>
      
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" v-model="form.password" class="form-input" placeholder="••••••••" required />
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" v-model="form.password_confirmation" class="form-input" placeholder="••••••••" required />
      </div>
      
      <button type="submit" class="btn btn-primary w-full" :disabled="loading">
        <span v-if="loading">Memproses...</span>
        <span v-else>Daftar Sekarang</span>
      </button>
    </form>
    
    <div class="auth-links">
      <router-link :to="'/login/' + routeRole" class="link">Sudah punya akun? <strong>Masuk di sini</strong></router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const roleName = computed(() => route.meta.role);
const routeRole = computed(() => {
  return roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
});

const form = ref({
  name: '',
  gender: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: roleName.value
});

const loading = ref(false);
const error = ref(null);

const handleRegister = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const res = await axios.post('/register', form.value);
    
    // Auto login
    authStore.setAuth(res.data.access_token, res.data.user);
    
    // Redirect to dashboard
    let dashRole = roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
    router.push(`/dashboard/${dashRole}`);
    
  } catch (err) {
    error.value = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Gagal mendaftar. Silakan periksa isian Anda.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-card { padding: 2.5rem; }
.login-header { text-align: center; margin-bottom: 2rem; }
.login-header h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: white; }
.w-full { width: 100%; }

.select-input {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1em;
}

.auth-links {
  margin-top: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  text-align: center;
}

.link {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 0.875rem;
  transition: color 0.2s;
}

.link:hover { color: white; }
.link strong { color: var(--primary); }
.link strong:hover { color: var(--primary-hover); }
</style>

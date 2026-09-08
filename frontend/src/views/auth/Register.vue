<template>
  <div class="glass-panel login-card">
    <div class="login-header">
      <img src="../../logo slapur.jpg" alt="Slapur Logo" class="auth-logo" />
      <h1 class="academic-title">SLAPUR ACADEMIC</h1>
      <h2>Daftar Akun {{ roleName }}</h2>
      <p>Buat akun baru portal Slapur</p>
    </div>
    
    <div v-if="successMsg" class="alert alert-success">
      {{ successMsg }}
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

      <div v-if="roleName === 'Staff Administrasi'" class="form-group">
        <label class="form-label">Jenis Staff</label>
        <select v-model="form.department" class="form-input select-input" required>
          <option value="" disabled>Pilih Jenis Staff</option>
          <option value="Administrasi">Staff Administrasi</option>
          <option value="Dining">Staff Dining</option>
          <option value="Asrama">Staff Asrama</option>
        </select>
      </div>
      
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" v-model="form.email" class="form-input" placeholder="contoh@gmail.com" required @input="validateEmail" />
        <small v-if="emailError" class="text-error mt-1 block">{{ emailError }}</small>
      </div>
      
      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" v-model="form.password" class="form-input" placeholder="••••••••" required @input="validatePassword" />
        <small v-if="passwordError" class="text-error mt-1 block">{{ passwordError }}</small>
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" v-model="form.password_confirmation" class="form-input" placeholder="••••••••" required />
      </div>
      
      <button type="submit" class="btn btn-primary w-full" :disabled="loading || !!passwordError || !!emailError">
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
  department: ''
});

const loading = ref(false);
const error = ref(null);
const successMsg = ref(null);
const passwordError = ref('');
const emailError = ref('');

const validateEmail = () => {
  if (!form.value.email) {
    emailError.value = '';
    return;
  }
  if (!form.value.email.toLowerCase().endsWith('@gmail.com')) {
    emailError.value = 'Email harus menggunakan domain @gmail.com';
  } else {
    emailError.value = '';
  }
};

const validatePassword = () => {
  const regex = /^[A-Z].*\d+$/;
  if (form.value.password.length < 8) {
    passwordError.value = "Password minimal 8 karakter.";
  } else if (!regex.test(form.value.password)) {
    passwordError.value = "Password harus diawali huruf kapital dan diakhiri angka.";
  } else {
    passwordError.value = "";
  }
};

const handleRegister = async () => {
  validateEmail();
  validatePassword();
  if (passwordError.value || emailError.value) return;
  
  loading.value = true;
  error.value = null;
  successMsg.value = null;
  
  try {
    const payload = {
      ...form.value,
      role: roleName.value
    };
    const res = await axios.post('/register', payload);
    
    // Redirect to login page for this role with success parameter
    router.push(`/login/${routeRole.value}?registered=1`);
    
  } catch (err) {
    if (err.response?.data?.errors) {
      const errs = Object.values(err.response.data.errors).flat();
      error.value = errs.join(' ');
    } else if (err.response?.data?.message) {
      error.value = err.response.data.message;
    } else {
      error.value = 'Gagal mendaftar. Silakan periksa isian Anda.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.login-card { padding: 2.5rem; }
.login-header { text-align: center; margin-bottom: 2rem; }
.auth-logo { width: 80px; height: auto; margin: 0 auto 1rem; display: block; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); }
.academic-title { font-size: 1.25rem; font-weight: 800; color: var(--primary, #10b981); margin-bottom: 0.5rem; letter-spacing: 2px; }
.login-header h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: white; }
.w-full { width: 100%; }

.select-input {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1em;
}

.alert-success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid #10b981;
  color: #34d399;
  margin-bottom: 1rem;
}

.text-error {
  color: #ef4444;
  font-size: 0.75rem;
}
.mt-1 { margin-top: 0.25rem; }
.block { display: block; }

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

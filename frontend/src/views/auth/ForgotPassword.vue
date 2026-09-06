<template>
  <div class="glass-panel login-card">
    <div class="login-header">
      <h2>Lupa Password</h2>
      <p v-if="step === 1">Masukkan email Anda untuk menerima OTP</p>
      <p v-else>Masukkan OTP dan password baru Anda</p>
    </div>
    
    <div v-if="error" class="alert alert-error">
      {{ error }}
    </div>
    
    <div v-if="success" class="alert alert-success">
      {{ success }}
    </div>

    <!-- Step 1: Request OTP -->
    <form v-if="step === 1" @submit.prevent="requestOtp">
      <div class="form-group">
        <label class="form-label">Email terdaftar</label>
        <input type="email" v-model="form.email" class="form-input" placeholder="contoh@slapur.com" required />
      </div>
      
      <button type="submit" class="btn btn-primary w-full" :disabled="loading">
        <span v-if="loading">Memproses...</span>
        <span v-else>Kirim Kode OTP</span>
      </button>
    </form>

    <!-- Step 2: Verify OTP and Reset Password -->
    <form v-else @submit.prevent="resetPassword">
      <div class="form-group">
        <label class="form-label">Kode OTP 6-Digit</label>
        <input type="text" v-model="form.otp" class="form-input" placeholder="123456" maxlength="6" required />
      </div>
      
      <div class="form-group">
        <label class="form-label">Password Baru</label>
        <input type="password" v-model="form.password" class="form-input" placeholder="••••••••" required />
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" v-model="form.password_confirmation" class="form-input" placeholder="••••••••" required />
      </div>
      
      <button type="submit" class="btn btn-primary w-full" :disabled="loading">
        <span v-if="loading">Memproses...</span>
        <span v-else>Simpan Password Baru</span>
      </button>
    </form>
    
    <div class="auth-links">
      <router-link :to="'/login/' + routeRole" class="link">Kembali ke halaman <strong>Login</strong></router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const roleName = computed(() => route.meta.role);
const routeRole = computed(() => {
  return roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
});

const step = ref(1);
const loading = ref(false);
const error = ref(null);
const success = ref(null);

const form = ref({
  email: '',
  otp: '',
  password: '',
  password_confirmation: ''
});

const requestOtp = async () => {
  loading.value = true;
  error.value = null;
  success.value = null;
  
  try {
    const res = await axios.post('/password/forgot', { email: form.value.email });
    success.value = res.data.message;
    step.value = 2; // Move to OTP input
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengirim OTP. Pastikan email terdaftar.';
  } finally {
    loading.value = false;
  }
};

const resetPassword = async () => {
  loading.value = true;
  error.value = null;
  success.value = null;
  
  try {
    const res = await axios.post('/password/reset', form.value);
    success.value = res.data.message;
    
    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push(`/login/${routeRole.value}`);
    }, 2000);
    
  } catch (err) {
    error.value = err.response?.data?.message || err.response?.data?.errors?.password?.[0] || 'Gagal mereset password.';
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

.alert-success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid #10b981;
  color: #34d399;
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

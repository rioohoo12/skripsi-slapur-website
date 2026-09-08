<template>
  <div class="glass-panel login-card">
    <div class="login-header">
      <img src="../../logo slapur.jpg" alt="Slapur Logo" class="auth-logo" />
      <h1 class="academic-title">SLAPUR ACADEMIC</h1>
      <h2>Login {{ roleName }}</h2>
      <p>Masuk ke portal Slapur</p>
    </div>
    
    <div v-if="route.query.registered" class="alert alert-success">
      Akun berhasil dibuat! Silakan masuk menggunakan email dan password Anda.
    </div>
    
    <div v-if="authStore.error" class="alert alert-error">
      {{ authStore.error }}
    </div>

    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label class="form-label">Email</label>
        <input 
          type="email" 
          v-model="email" 
          class="form-input" 
          placeholder="contoh@gmail.com"
          required
        />
      </div>
      
      <div class="form-group">
        <label class="form-label">Password</label>
        <input 
          type="password" 
          v-model="password" 
          class="form-input" 
          placeholder="••••••••"
          required
        />
      </div>

      <div class="form-group remember-me">
        <label class="checkbox-label">
          <input type="checkbox" v-model="rememberMe" />
          <span>Ingat Saya</span>
        </label>
      </div>
      
      <button type="submit" class="btn btn-primary w-full" :disabled="authStore.loading">
        <span v-if="authStore.loading">Memproses...</span>
        <span v-else>Masuk</span>
      </button>
    </form>
    
    <div class="auth-links">
      <router-link :to="'/forgot-password/' + routeRole" class="link">Lupa Password?</router-link>
      <router-link :to="'/register/' + routeRole" class="link">Belum punya akun? <strong>Daftar di sini</strong></router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const rememberMe = ref(true);

const roleName = computed(() => route.meta.role);
const routeRole = computed(() => {
  return roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
});

const handleLogin = async () => {
  const success = await authStore.login(email.value, password.value, roleName.value, rememberMe.value);
  if (success) {
    router.push(`/dashboard/${routeRole.value}`);
  }
};
</script>

<style scoped>
.login-card {
  padding: 2.5rem;
}

.login-header {
  text-align: center;
  margin-bottom: 2rem;
}

.auth-logo {
  width: 80px;
  height: auto;
  margin: 0 auto 1rem;
  display: block;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.academic-title {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--primary, #10b981);
  margin-bottom: 0.5rem;
  letter-spacing: 2px;
}

.login-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: white;
}

.alert-success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid #10b981;
  color: #34d399;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  font-size: 0.875rem;
}

.w-full {
  width: 100%;
}

.role-switcher {
  margin-top: 2rem;
  text-align: center;
  font-size: 0.875rem;
  color: var(--text-secondary);
}

.role-links {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 0.5rem;
}

.role-links a {
  color: var(--primary);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.role-links a:hover {
  color: var(--primary-hover);
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

.link:hover {
  color: white;
}

.link strong {
  color: var(--primary);
}

.link strong:hover {
  color: var(--primary-hover);
}

.remember-me {
  margin-top: 1rem;
  margin-bottom: 1.5rem;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-secondary);
  font-size: 0.875rem;
  cursor: pointer;
}

.checkbox-label input {
  accent-color: var(--primary);
  width: 1rem;
  height: 1rem;
  cursor: pointer;
}
</style>

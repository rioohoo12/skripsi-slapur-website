<template>
  <div class="glass-panel login-card">
    <div class="login-header">
      <h2>Login {{ roleName }}</h2>
      <p>Masuk ke portal Slapur</p>
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
          placeholder="contoh@slapur.com"
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
      
      <button type="submit" class="btn btn-primary w-full" :disabled="authStore.loading">
        <span v-if="authStore.loading">Memproses...</span>
        <span v-else>Masuk</span>
      </button>
    </form>
    
    <div class="auth-links">
      <router-link :to="'/forgot-password/' + routeRole" class="link">Lupa Password?</router-link>
      <router-link :to="'/register/' + routeRole" class="link">Belum punya akun? <strong>Daftar di sini</strong></router-link>
    </div>
    
    <div class="role-switcher">
      <p>Bukan {{ roleName }}?</p>
      <div class="role-links">
        <router-link v-if="roleName !== 'Murid'" to="/login/murid">Murid</router-link>
        <router-link v-if="roleName !== 'Guru'" to="/login/guru">Guru</router-link>
        <router-link v-if="roleName !== 'Staff Administrasi'" to="/login/staff">Staff</router-link>
        <router-link v-if="roleName !== 'Admin'" to="/login/admin">Admin</router-link>
      </div>
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

const roleName = computed(() => route.meta.role);
const routeRole = computed(() => {
  return roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
});

const handleLogin = async () => {
  const success = await authStore.login(email.value, password.value, roleName.value);
  if (success) {
    let routeRole = roleName.value === 'Staff Administrasi' ? 'staff' : roleName.value.toLowerCase();
    router.push(`/dashboard/${routeRole}`);
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

.login-header h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: white;
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
</style>

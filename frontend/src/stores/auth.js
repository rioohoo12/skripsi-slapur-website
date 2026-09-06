import { defineStore } from 'pinia';
import axios from 'axios';

// Configure default axios base URL
axios.defaults.baseURL = 'http://localhost:8000/api';
axios.defaults.withCredentials = true; // For sanctum CSRF if needed

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role || null,
  },

  actions: {
    setAuth(token, user) {
      this.token = token;
      this.user = user;
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      delete axios.defaults.headers.common['Authorization'];
    },

    async login(email, password, role) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.post('/login', { email, password, role });
        const { access_token, user } = response.data;
        
        this.setAuth(access_token, user);
        return true;
      } catch (err) {
        this.error = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Terjadi kesalahan saat login.';
        return false;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      try {
        if (this.token) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.post('/logout');
        }
      } catch (err) {
        console.error('Logout error', err);
      } finally {
        this.clearAuth();
      }
    }
  }
});

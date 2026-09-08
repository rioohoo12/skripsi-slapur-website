import { defineStore } from 'pinia';
import axios from 'axios';

// Configure default axios base URL and headers
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Accept'] = 'application/json';

// Setup initial auth header if token exists
const initialToken = localStorage.getItem('token') || sessionStorage.getItem('token');
if (initialToken) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${initialToken}`;
}

export const useAuthStore = defineStore('auth', {
  state: () => {
    let user = null;
    try {
      user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user'));
    } catch(e) {}
    
    return {
      user: user,
      token: initialToken,
      loading: false,
      error: null
    };
  },

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role || null,
  },

  actions: {
    setAuth(token, user, rememberMe = true) {
      this.token = token;
      this.user = user;
      
      if (rememberMe) {
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
      } else {
        sessionStorage.setItem('token', token);
        sessionStorage.setItem('user', JSON.stringify(user));
      }
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },

    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      sessionStorage.removeItem('token');
      sessionStorage.removeItem('user');
      delete axios.defaults.headers.common['Authorization'];
    },

    async login(email, password, role, rememberMe = true) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.post('/login', { email, password, role });
        const { access_token, user } = response.data;
        
        this.setAuth(access_token, user, rememberMe);
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

<template>
  <div class="qr-profile-page">
    <div class="header">
      <h1 class="page-title">QR Presensi</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali ke Dashboard</router-link>
    </div>

    <div class="glass-panel qr-card">
      <div v-if="loading" class="loading-text">Menyiapkan QR Code Anda...</div>
      <div v-else-if="error" class="alert alert-error">{{ error }}</div>
      <div v-else class="qr-container">
        <h2 class="section-title">Akses Cepat (Dining & Kamar)</h2>
        <p class="subtitle">Tunjukkan QR ini kepada petugas untuk pencatatan akses/presensi.</p>
        
        <div class="qr-wrapper">
          <qrcode-vue :value="qrPayload" :size="250" level="H" render-as="svg" background="#ffffff" foreground="#000000" />
        </div>
        
        <p class="student-name">{{ authStore.user?.name }}</p>
        <p class="student-info">NIM/ID: {{ authStore.user?.id }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import QrcodeVue from 'qrcode.vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const qrPayload = ref('');
const loading = ref(true);
const error = ref(null);

const fetchQr = async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/qr`);
    qrPayload.value = res.data.qr_payload;
  } catch (err) {
    error.value = 'Gagal memuat QR Code. Silakan coba lagi.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchQr();
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; color: var(--text-primary); }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; }
.btn-secondary:hover { background: var(--primary); color: white; }

.qr-card { max-width: 500px; margin: 0 auto; padding: 3rem 2rem; text-align: center; border-radius: 16px; }
.section-title { font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; font-weight: 700; }
.subtitle { color: var(--text-secondary); margin-bottom: 2.5rem; font-size: 0.95rem; }

.qr-wrapper { background: white; padding: 1rem; display: inline-block; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }

.student-name { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0; }
.student-info { font-size: 1rem; color: var(--text-secondary); margin: 0; }

.loading-text { color: var(--text-secondary); font-style: italic; }
</style>

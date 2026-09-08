<template>
  <div class="registration-form-page">
    <div class="header">
      <h1 class="page-title">Permohonan Pendaftaran</h1>
      <router-link to="/dashboard/murid/registration" class="btn btn-secondary">Kembali</router-link>
    </div>

    <div class="glass-panel form-card">
      <div v-if="success" class="alert alert-success">Pembayaran berhasil! Silakan kembali ke halaman status pendaftaran.</div>
      <div v-if="error" class="alert alert-error">{{ error }}</div>

      <form @submit.prevent="submitApplication" v-if="!showBreakdown && !success">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" v-model="form.name" required class="form-control" />
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea v-model="form.address" required class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label>Kelas yang akan ditempuh</label>
          <input type="text" v-model="form.class_name" required class="form-control" placeholder="Contoh: 10A" />
        </div>
        
        <div class="form-group">
          <label>Pilihan Jenis Asrama</label>
          <div class="radio-group">
            <label class="radio-label">
              <input type="radio" v-model="form.dormitory_preference" value="sederhana" required />
              Sederhana
              <span class="sub-label">(L: Hawk | P: Jasmina)</span>
            </label>
            <label class="radio-label">
              <input type="radio" v-model="form.dormitory_preference" value="standar" required />
              Standar
              <span class="sub-label">(L: Cendrawasih/Anex | P: Bougenville/Crysant)</span>
            </label>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-full" :disabled="loading">
          {{ loading ? 'Menyimpan...' : 'Lihat Rincian Biaya' }}
        </button>
      </form>

      <!-- Breakdown & Payment Section -->
      <div v-if="showBreakdown && !success" class="breakdown-section">
        <h3 class="section-title">Rincian Biaya Pendaftaran</h3>
        <table class="breakdown-table">
          <tbody>
            <tr v-for="(amount, label) in breakdownData" :key="label">
              <td>{{ label }}</td>
              <td class="amount">Rp {{ amount.toLocaleString('id-ID') }}</td>
            </tr>
            <tr class="total-row">
              <td><strong>Total Biaya</strong></td>
              <td class="amount total-amount">Rp {{ totalBiaya.toLocaleString('id-ID') }}</td>
            </tr>
          </tbody>
        </table>

        <button @click="payNow" class="btn btn-success w-full mt-2" :disabled="paymentLoading">
          {{ paymentLoading ? 'Menyiapkan Pembayaran...' : 'Bayar Sekarang via Midtrans' }}
        </button>
        <button @click="showBreakdown = false" class="btn btn-secondary w-full mt-2 text-center" style="display:block">
          Edit Form
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const form = ref({
  name: authStore.user?.name || '',
  address: '',
  class_name: '',
  dormitory_preference: ''
});

const loading = ref(false);
const paymentLoading = ref(false);
const error = ref(null);
const success = ref(false);

const showBreakdown = ref(false);
const breakdownData = ref({});
const totalBiaya = ref(0);

const submitApplication = async () => {
  if (!userId) return;
  loading.value = true;
  error.value = null;
  
  try {
    const res = await api.post(`/students/${userId}/registration-application`, form.value);
    breakdownData.value = res.data.breakdown;
    totalBiaya.value = res.data.total_biaya;
    showBreakdown.value = true;
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal menyimpan permohonan.';
  } finally {
    loading.value = false;
  }
};

const loadMidtransScript = () => {
  return new Promise((resolve) => {
    if (document.getElementById('midtrans-script')) {
      resolve();
      return;
    }
    const script = document.createElement('script');
    script.id = 'midtrans-script';
    // Use Sandbox Client Key placeholder
    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
    script.setAttribute('data-client-key', 'SB-Mid-client-XXXXX'); 
    script.onload = () => resolve();
    document.head.appendChild(script);
  });
};

const payNow = async () => {
  try {
    paymentLoading.value = true;
    error.value = null;
    
    // Ensure script is loaded
    await loadMidtransScript();

    // Create transaction in backend
    const res = await api.post('/payment/create');
    const snapToken = res.data.snap_token;
    
    // Trigger Snap Popup
    window.snap.pay(snapToken, {
      onSuccess: function(result){
        success.value = true;
      },
      onPending: function(result){
        alert("Menunggu pembayaran Anda!");
      },
      onError: function(result){
        error.value = "Pembayaran gagal atau dibatalkan.";
      },
      onClose: function(){
        error.value = "Anda menutup popup sebelum menyelesaikan pembayaran.";
      }
    });

  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memulai pembayaran.';
  } finally {
    paymentLoading.value = false;
  }
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; border: none; cursor: pointer; }
.btn-secondary:hover { background: var(--primary); color: white; }
.btn-primary { background: var(--primary); color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; }
.btn-success { background: #22c55e; color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; }

.form-card { max-width: 600px; padding: 2rem; }
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-secondary); }
.form-control { width: 100%; padding: 0.75rem 1rem; background: rgba(var(--primary-rgb), 0.02); border: 1px solid var(--surface-border); border-radius: 8px; color: var(--text-primary); }

.radio-group { display: flex; flex-direction: column; gap: 0.75rem; }
.radio-label { display: flex; align-items: center; gap: 0.5rem; font-weight: 600; cursor: pointer; }
.sub-label { font-size: 0.85rem; color: var(--text-secondary); font-weight: normal; margin-left: 0.5rem; }

.breakdown-section { margin-top: 1rem; }
.section-title { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem; }
.breakdown-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
.breakdown-table td { padding: 0.75rem 0; border-bottom: 1px dashed var(--surface-border); }
.breakdown-table .amount { text-align: right; font-family: monospace; font-size: 1.1rem; }
.total-row td { border-bottom: none; border-top: 2px solid var(--surface-border); padding-top: 1rem; font-size: 1.25rem; }
.total-amount { color: var(--primary); font-weight: 800; }

.w-full { width: 100%; }
.mt-2 { margin-top: 1rem; }
.text-center { text-align: center; }
</style>

<template>
  <div>
    <div class="header">
      <h1 class="page-title">Pembayaran Pendaftaran</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali</router-link>
    </div>

    <div class="glass-panel payment-card">
      <div class="payment-info">
        <h3>Biaya Pendaftaran Asrama Slapur</h3>
        <p class="amount">Rp 1.500.000</p>
        <p class="desc">Pembayaran ini wajib dilakukan untuk mendapatkan akses penuh ke fasilitas asrama dan modul akademik.</p>
      </div>

      <div v-if="error" class="alert alert-error">{{ error }}</div>

      <button @click="payNow" class="btn btn-primary w-full payment-btn" :disabled="loading">
        {{ loading ? 'Menyiapkan Pembayaran...' : 'Bayar Sekarang via Midtrans' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(false);
const error = ref(null);

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
    loading.value = true;
    error.value = null;
    
    // Ensure script is loaded
    await loadMidtransScript();

    // Create transaction in backend
    const res = await axios.post('/payment/create');
    const snapToken = res.data.snap_token;
    
    // Trigger Snap Popup
    window.snap.pay(snapToken, {
      onSuccess: function(result){
        alert("Pembayaran berhasil!");
        console.log(result);
      },
      onPending: function(result){
        alert("Menunggu pembayaran Anda!");
        console.log(result);
      },
      onError: function(result){
        alert("Pembayaran gagal!");
        console.log(result);
      },
      onClose: function(){
        alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
      }
    });

  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memulai pembayaran.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; }
.btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; }
.btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }

.payment-card { max-width: 500px; margin: 0 auto; padding: 3rem; text-align: center; }
.payment-info h3 { font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 1rem; }
.amount { font-size: 3rem; font-weight: 700; color: white; margin-bottom: 1rem; }
.desc { color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6; }

.payment-btn { font-size: 1.125rem; padding: 1rem; }
.w-full { width: 100%; }
</style>

<template>
  <div>
    <div class="header">
      <h1 class="page-title">Riwayat Keuangan</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali ke Dashboard</router-link>
    </div>

    <div class="glass-panel section-card">
      <div v-if="loading" class="loading-text">Memuat riwayat transaksi...</div>
      <div v-else-if="error" class="alert alert-error">{{ error }}</div>
      <div v-else>
        <div class="balance-summary mb-2">
          <p>Saldo Berjalan Saat Ini:</p>
          <h2 class="balance-amount">{{ formatCurrency(currentBalance) }}</h2>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Deskripsi</th>
              <th class="text-right">Debit</th>
              <th class="text-right">Kredit</th>
              <th class="text-right">Saldo</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="trx in transactions" :key="trx.id">
              <td>{{ formatDate(trx.date) }}</td>
              <td>{{ trx.description }}</td>
              <td class="text-right debit-text">{{ parseFloat(trx.debit) > 0 ? formatCurrency(trx.debit) : '-' }}</td>
              <td class="text-right credit-text">{{ parseFloat(trx.credit) > 0 ? formatCurrency(trx.credit) : '-' }}</td>
              <td class="text-right font-bold">{{ formatCurrency(trx.balance) }}</td>
            </tr>
            <tr v-if="transactions.length === 0">
              <td colspan="5" class="text-center py-4">Belum ada riwayat transaksi.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const transactions = ref([]);
const loading = ref(true);
const error = ref(null);

const currentBalance = computed(() => {
  if (transactions.value.length > 0) {
    // API returns newest first (descending), so the first item has the current running balance
    return parseFloat(transactions.value[0].balance);
  }
  return 0;
});

const fetchFinance = async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/finance`);
    transactions.value = res.data;
  } catch (err) {
    error.value = 'Gagal memuat riwayat keuangan.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchFinance();
});

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric'
  }).format(date);
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; color: var(--text-primary); }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; }
.btn-secondary:hover { background: var(--primary); color: white; }

.section-card { padding: 2rem; border-radius: 12px; }
.balance-summary { margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--surface-border); }
.balance-summary p { color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 1.1rem; }
.balance-amount { font-size: 2.5rem; font-weight: 800; color: var(--success, #22c55e); margin: 0; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--surface-border); }
.data-table th { color: var(--text-secondary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
.data-table tr:hover td { background: rgba(var(--primary-rgb), 0.02); }

.text-right { text-align: right !important; }
.text-center { text-align: center !important; }
.font-bold { font-weight: 700; }
.debit-text { color: #ef4444; } /* Red for debit/expense */
.credit-text { color: #22c55e; } /* Green for credit/deposit */
.py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
.loading-text { color: var(--text-secondary); font-style: italic; }
</style>

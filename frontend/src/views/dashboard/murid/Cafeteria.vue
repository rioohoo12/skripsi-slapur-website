<template>
  <div>
    <div class="header">
      <h1 class="page-title">Cafeteria & Kartu Makan</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali ke Dashboard</router-link>
    </div>

    <div class="grid layout-grid">
      <!-- Meal Card Section -->
      <div class="glass-panel section-card meal-card-container">
        <h2 class="section-title">Kartu Makan Anda</h2>
        <div v-if="loadingCard" class="loading-text">Memuat kartu makan...</div>
        <div v-else-if="cardError" class="alert alert-error">{{ cardError }}</div>
        <div v-else class="meal-card">
          <div class="card-header">
            <span>SLAPUR DINING PASS</span>
          </div>
          <div class="card-body">
            <p class="label">Nomor Kartu</p>
            <h2 class="card-number">{{ mealCard?.card_number }}</h2>
            <p class="student-name">{{ authStore.user?.name }}</p>
          </div>
          <div class="card-footer">
            <span>Tunjukkan kartu ini saat mengambil makan.</span>
          </div>
        </div>
      </div>

      <!-- Meal Reports Section -->
      <div class="glass-panel section-card reports-container">
        <h2 class="section-title">Riwayat Pengambilan Makan</h2>
        <div v-if="loadingReports" class="loading-text">Memuat riwayat...</div>
        <div v-else-if="reportsError" class="alert alert-error">{{ reportsError }}</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Waktu</th>
              <th>Jenis Makan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(report, index) in mealReports" :key="report.id">
              <td>{{ index + 1 }}</td>
              <td>{{ formatDate(report.date) }}</td>
              <td>{{ report.time }}</td>
              <td><span class="meal-badge">{{ report.meal_type }}</span></td>
            </tr>
            <tr v-if="mealReports.length === 0">
              <td colspan="4" class="text-center py-4">Belum ada riwayat pengambilan makan.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const mealCard = ref(null);
const mealReports = ref([]);

const loadingCard = ref(true);
const loadingReports = ref(true);
const cardError = ref(null);
const reportsError = ref(null);

const fetchMealCard = async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/meal-card`);
    mealCard.value = res.data;
  } catch (err) {
    cardError.value = err.response?.data?.message || 'Gagal memuat kartu makan.';
  } finally {
    loadingCard.value = false;
  }
};

const fetchMealReports = async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/meal-reports`);
    mealReports.value = res.data;
  } catch (err) {
    reportsError.value = err.response?.data?.message || 'Gagal memuat riwayat makan.';
  } finally {
    loadingReports.value = false;
  }
};

onMounted(() => {
  fetchMealCard();
  fetchMealReports();
});

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric'
  }).format(date);
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; color: var(--text-primary); }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; }
.btn-secondary:hover { background: var(--primary); color: white; }

.layout-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: flex-start; }
@media (max-width: 768px) { .layout-grid { grid-template-columns: 1fr; } }

.section-card { padding: 2rem; border-radius: 12px; }
.section-title { font-size: 1.5rem; color: var(--primary); margin-bottom: 1.5rem; font-weight: 700; }

/* Meal Card Styling */
.meal-card { background: linear-gradient(135deg, var(--primary), #3b82f6); border-radius: 16px; color: white; overflow: hidden; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4); }
.card-header { background: rgba(0,0,0,0.2); padding: 1rem 1.5rem; font-weight: 800; letter-spacing: 1px; font-size: 0.9rem; }
.card-body { padding: 2rem 1.5rem; text-align: center; }
.card-body .label { font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px; }
.card-number { font-size: 3rem; font-weight: 900; margin: 0 0 1rem 0; letter-spacing: 2px; }
.student-name { font-size: 1.25rem; font-weight: 600; margin: 0; }
.card-footer { background: rgba(0,0,0,0.1); padding: 1rem 1.5rem; font-size: 0.85rem; text-align: center; opacity: 0.9; }

/* Table Styling */
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--surface-border); }
.data-table th { color: var(--text-secondary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
.data-table tr:hover td { background: rgba(var(--primary-rgb), 0.02); }

.meal-badge { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; }
.text-center { text-align: center !important; }
.py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
.loading-text { color: var(--text-secondary); font-style: italic; }
</style>

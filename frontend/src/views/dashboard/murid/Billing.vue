<template>
  <div class="billing-page fade-in">
    <div class="page-header">
      <h1 class="page-title">Tagihan & Pembayaran</h1>
      <p class="page-subtitle">Kelola tagihan biaya sekolah dan riwayat pembayaran Anda.</p>
    </div>

    <div class="billing-overview glass-panel mb-4">
      <div class="overview-item">
        <span class="label">Total Tagihan Belum Dibayar</span>
        <span class="value text-error">Rp 1.500.000</span>
      </div>
      <div class="overview-divider"></div>
      <div class="overview-item">
        <span class="label">Tagihan Jatuh Tempo</span>
        <span class="value text-warning">Rp 500.000</span>
      </div>
      <div class="overview-divider"></div>
      <div class="overview-item text-center">
        <button class="btn btn-primary w-full">Bayar Sekarang</button>
      </div>
    </div>

    <div class="billing-card glass-panel">
      <div class="tabs">
        <button class="tab-btn" :class="{ active: activeTab === 'tagihan' }" @click="activeTab = 'tagihan'">Daftar Tagihan</button>
        <button class="tab-btn" :class="{ active: activeTab === 'riwayat' }" @click="activeTab = 'riwayat'">Riwayat Pembayaran</button>
      </div>

      <div class="tab-content">
        <!-- Daftar Tagihan -->
        <div v-if="activeTab === 'tagihan'" class="table-responsive">
          <table class="billing-table">
            <thead>
              <tr>
                <th>Deskripsi Tagihan</th>
                <th>Periode</th>
                <th>Jatuh Tempo</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="bill in bills" :key="bill.id">
                <td class="font-bold">{{ bill.description }}</td>
                <td>{{ bill.period }}</td>
                <td :class="{ 'text-error font-bold': isOverdue(bill.dueDate) }">{{ bill.dueDate }}</td>
                <td>Rp {{ bill.amount.toLocaleString('id-ID') }}</td>
                <td>
                  <span class="status-badge" :class="bill.status.toLowerCase()">{{ bill.status }}</span>
                </td>
                <td>
                  <button v-if="bill.status === 'Belum Dibayar'" class="btn btn-sm btn-outline-primary">Bayar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Riwayat Pembayaran -->
        <div v-else class="table-responsive">
          <table class="billing-table">
            <thead>
              <tr>
                <th>Tanggal Bayar</th>
                <th>Deskripsi Tagihan</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah Dibayar</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="history in paymentHistory" :key="history.id">
                <td>{{ history.date }}</td>
                <td class="font-bold">{{ history.description }}</td>
                <td>{{ history.method }}</td>
                <td>Rp {{ history.amount.toLocaleString('id-ID') }}</td>
                <td>
                  <span class="status-badge lunas">Berhasil</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('tagihan');

const bills = ref([
  { id: 1, description: 'SPP Bulan Oktober', period: 'Oktober 2026', dueDate: '2026-10-10', amount: 500000, status: 'Belum Dibayar' },
  { id: 2, description: 'Uang Gedung Semester 1', period: 'Semester Ganjil', dueDate: '2026-11-01', amount: 1000000, status: 'Belum Dibayar' },
  { id: 3, description: 'Seragam Sekolah', period: 'Tahun Ajaran Baru', dueDate: '2026-08-01', amount: 750000, status: 'Lunas' },
]);

const paymentHistory = ref([
  { id: 1, date: '2026-09-05', description: 'SPP Bulan September', method: 'Virtual Account BCA', amount: 500000 },
  { id: 2, date: '2026-08-01', description: 'Seragam Sekolah', method: 'Transfer Bank Mandiri', amount: 750000 },
  { id: 3, date: '2026-08-01', description: 'SPP Bulan Agustus', method: 'Transfer Bank Mandiri', amount: 500000 },
]);

const isOverdue = (dateString) => {
  const dueDate = new Date(dateString);
  const today = new Date('2026-10-15'); // Simulating today as past due date
  return dueDate < today;
};
</script>

<style scoped>
.billing-page {
  max-width: 1000px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: white;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: var(--text-secondary);
}

.billing-overview {
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 1.5rem;
}

.mb-4 { margin-bottom: 2rem; }

.overview-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
  padding: 0 1.5rem;
}

.overview-divider {
  width: 1px;
  height: 50px;
  background: var(--surface-border);
}

.label {
  font-size: 0.875rem;
  color: var(--text-secondary);
}

.value {
  font-size: 1.75rem;
  font-weight: 700;
}

.text-error { color: #ef4444; }
.text-warning { color: #fbbf24; }
.text-center { text-align: center; }
.w-full { width: 100%; }

.billing-card {
  padding: 0;
  overflow: hidden;
}

.tabs {
  display: flex;
  border-bottom: 1px solid var(--surface-border);
  background: rgba(255, 255, 255, 0.02);
}

.tab-btn {
  flex: 1;
  padding: 1rem;
  background: transparent;
  border: none;
  border-bottom: 2px solid transparent;
  color: var(--text-secondary);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.tab-btn:hover {
  background: rgba(255, 255, 255, 0.05);
  color: white;
}

.tab-btn.active {
  color: var(--primary);
  border-bottom-color: var(--primary);
  background: rgba(16, 185, 129, 0.05);
}

.tab-content {
  padding: 1.5rem;
}

.table-responsive {
  overflow-x: auto;
}

.billing-table {
  width: 100%;
  border-collapse: collapse;
}

.billing-table th, .billing-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--surface-border);
}

.billing-table th {
  font-weight: 600;
  color: var(--text-secondary);
  background: rgba(255, 255, 255, 0.02);
}

.billing-table td {
  color: white;
  font-size: 0.95rem;
}

.font-bold { font-weight: bold; }

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
}

.status-badge.belum {
  background: rgba(239, 68, 68, 0.2);
  color: #fca5a5;
}

.status-badge.lunas {
  background: rgba(16, 185, 129, 0.2);
  color: #34d399;
}

.btn-sm {
  padding: 0.4rem 0.75rem;
  font-size: 0.875rem;
}

.btn-outline-primary {
  background: transparent;
  border: 1px solid var(--primary);
  color: var(--primary);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline-primary:hover {
  background: var(--primary);
  color: white;
}

@media (max-width: 768px) {
  .billing-overview {
    flex-direction: column;
    gap: 1.5rem;
  }
  .overview-divider {
    width: 100%;
    height: 1px;
  }
}
</style>

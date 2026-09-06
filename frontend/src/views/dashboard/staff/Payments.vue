<template>
  <div class="payments-page">
    <div class="glass-panel header-card">
      <h2>Verifikasi Pembayaran</h2>
      <p>Lihat semua riwayat transaksi siswa dan lakukan verifikasi manual jika diperlukan.</p>
    </div>

    <div class="glass-panel table-card">
      <div class="table-responsive">
        <table class="glass-table">
          <thead>
            <tr>
              <th>ID TRX</th>
              <th>Nama Siswa</th>
              <th>Tipe</th>
              <th>Jumlah (Rp)</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Aksi Manual</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="payments.length === 0">
              <td colspan="7" class="text-center">Memuat data atau data kosong...</td>
            </tr>
            <tr v-for="pay in payments" :key="pay.id">
              <td>#{{ pay.id }}</td>
              <td>{{ pay.student?.user?.name }}</td>
              <td class="capitalize">{{ pay.type }}</td>
              <td>{{ formatRupiah(pay.amount) }}</td>
              <td>{{ pay.payment_date }}</td>
              <td>
                <span class="badge" :class="pay.status">{{ pay.status }}</span>
              </td>
              <td>
                <div v-if="pay.status === 'pending'" class="actions">
                  <button class="btn btn-sm btn-success" @click="verifyPayment(pay.id, 'success')" title="Terima Manual">✓</button>
                  <button class="btn btn-sm btn-danger" @click="verifyPayment(pay.id, 'failed')" title="Tolak">✗</button>
                </div>
                <span v-else class="text-muted">-</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const payments = ref([]);

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID').format(number);
};

const fetchPayments = async () => {
  try {
    const res = await axios.get('/staff/payments');
    payments.value = res.data;
  } catch (err) {
    console.error('Failed to fetch payments', err);
  }
};

const verifyPayment = async (id, status) => {
  if (!confirm(`Anda yakin ingin mengubah status menjadi ${status}?`)) return;
  
  try {
    const res = await axios.post(`/staff/payments/${id}/verify`, { status });
    // Update local state
    const index = payments.value.findIndex(p => p.id === id);
    if (index !== -1) {
      payments.value[index] = res.data.payment;
    }
  } catch (err) {
    alert('Gagal memverifikasi pembayaran.');
  }
};

onMounted(() => {
  fetchPayments();
});
</script>

<style scoped>
.payments-page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.header-card {
  padding: 1.5rem;
}

.header-card h2 {
  color: white;
  margin-bottom: 0.5rem;
}

.table-card {
  padding: 1.5rem;
}

.table-responsive {
  overflow-x: auto;
}

.glass-table {
  width: 100%;
  border-collapse: collapse;
}

.glass-table th, .glass-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-table th {
  color: var(--primary);
  font-weight: 600;
}

.text-center { text-align: center; }
.capitalize { text-transform: capitalize; }

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
}
.badge.success { background: rgba(16, 185, 129, 0.2); color: #34d399; }
.badge.pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
.badge.failed { background: rgba(239, 68, 68, 0.2); color: #f87171; }

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.9rem;
  border-radius: 6px;
  min-width: 30px;
}

.btn-success { background: #10b981; color: white; border: none; }
.btn-success:hover { background: #059669; }

.btn-danger { background: #ef4444; color: white; border: none; }
.btn-danger:hover { background: #dc2626; }

.text-muted {
  color: var(--text-secondary);
}
</style>

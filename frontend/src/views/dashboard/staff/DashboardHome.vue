<template>
  <div class="staff-home">
    <div class="glass-panel welcome-card">
      <h2>Laporan Agregat Asrama</h2>
      <p>Ringkasan real-time kondisi asrama dan keuangan.</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
      <div class="glass-panel summary-card">
        <h3>Total Siswa</h3>
        <div class="value">{{ summary.total_students }}</div>
      </div>
      <div class="glass-panel summary-card">
        <h3>Okupansi Kamar</h3>
        <div class="value">{{ summary.occupancy_rate }}%</div>
      </div>
      <div class="glass-panel summary-card">
        <h3>Total Pemasukan (Rp)</h3>
        <div class="value">{{ formatRupiah(summary.total_revenue) }}</div>
      </div>
      <div class="glass-panel summary-card">
        <h3>Tagihan Pending</h3>
        <div class="value">{{ summary.pending_payments }}</div>
      </div>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
      <div class="glass-panel chart-card">
        <h3>Statistik Dining (Hari Ini)</h3>
        <div class="chart-container" v-if="loaded">
          <Doughnut :data="diningData" :options="chartOptions" />
        </div>
      </div>
      
      <div class="glass-panel chart-card">
        <h3>Kapasitas Kamar Asrama</h3>
        <div class="chart-container" v-if="loaded">
          <Pie :data="roomData" :options="chartOptions" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut, Pie } from 'vue-chartjs';

ChartJS.register(ArcElement, Tooltip, Legend);

const summary = ref({
  total_students: 0,
  occupancy_rate: 0,
  total_revenue: 0,
  pending_payments: 0
});

const diningData = ref(null);
const roomData = ref(null);
const loaded = ref(false);

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: {
        color: '#ffffff'
      }
    }
  }
};

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID').format(number);
};

const fetchData = async () => {
  try {
    const res = await axios.get('/staff/dashboard');
    const data = res.data;
    
    summary.value = data.summary;
    
    diningData.value = {
      labels: ['Sudah Makan', 'Tidak Makan'],
      datasets: [{
        backgroundColor: ['#10b981', '#ef4444'],
        data: [data.charts.dining.eaten, data.charts.dining.missed]
      }]
    };
    
    roomData.value = {
      labels: ['Terisi', 'Kosong'],
      datasets: [{
        backgroundColor: ['#3b82f6', '#4b5563'],
        data: [data.charts.rooms.occupied, data.charts.rooms.empty]
      }]
    };
    
    loaded.value = true;
  } catch (err) {
    console.error('Failed to load dashboard data', err);
  }
};

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.staff-home {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.welcome-card {
  padding: 1.5rem;
}

.welcome-card h2 {
  color: white;
  margin-bottom: 0.5rem;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.summary-card {
  padding: 1.5rem;
  text-align: center;
}

.summary-card h3 {
  font-size: 1rem;
  color: var(--text-secondary);
  margin-bottom: 1rem;
}

.summary-card .value {
  font-size: 2rem;
  font-weight: 700;
  color: white;
}

.charts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}

.chart-card {
  padding: 1.5rem;
}

.chart-card h3 {
  color: white;
  margin-bottom: 1.5rem;
  text-align: center;
}

.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}
</style>

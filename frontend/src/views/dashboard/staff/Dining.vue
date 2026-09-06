<template>
  <div class="dining-page">
    <div class="glass-panel header-card">
      <h2>Logistik Dining & Makanan</h2>
      <p>Catat kehadiran siswa saat jam makan (Breakfast, Lunch, Dinner).</p>
      
      <div class="controls">
        <div class="form-group">
          <label>Tanggal</label>
          <input type="date" v-model="selectedDate" class="form-input" @change="fetchDining" />
        </div>
        <div class="form-group">
          <label>Waktu Makan</label>
          <select v-model="mealType" class="form-input" @change="fetchDining">
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
          </select>
        </div>
        <button class="btn btn-primary btn-align" @click="saveDining" :disabled="loading || students.length === 0">
          <span v-if="loading">Menyimpan...</span>
          <span v-else>Simpan Perubahan</span>
        </button>
      </div>
      
      <div v-if="message" class="alert alert-success mt-1">{{ message }}</div>
      <div v-if="error" class="alert alert-error mt-1">{{ error }}</div>
    </div>

    <div class="glass-panel table-card">
      <div class="table-responsive">
        <table class="glass-table">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama Siswa</th>
              <th>Status Makan</th>
              <th>Catatan Menu Khusus</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="students.length === 0">
              <td colspan="4" class="text-center">Memuat data atau data kosong...</td>
            </tr>
            <tr v-for="(student, index) in students" :key="student.id">
              <td>{{ student.nisn }}</td>
              <td>{{ student.name }}</td>
              <td>
                <select v-model="students[index].status" class="form-input select-status" :class="students[index].status">
                  <option value="eaten">Makan</option>
                  <option value="missed">Tidak Makan</option>
                </select>
              </td>
              <td>
                <input type="text" v-model="students[index].menu_served" class="form-input" placeholder="Cth: Alergi udang, diganti ayam" />
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

const today = new Date().toISOString().split('T')[0];
const selectedDate = ref(today);
const mealType = ref('breakfast');
const students = ref([]);
const loading = ref(false);
const message = ref('');
const error = ref('');

const fetchDining = async () => {
  error.value = '';
  message.value = '';
  try {
    const res = await axios.get('/staff/dining', { 
      params: { date: selectedDate.value, meal_type: mealType.value } 
    });
    // Normalize status to 'eaten' if null
    students.value = res.data.map(s => ({
      ...s,
      status: s.status || 'eaten',
      menu_served: s.menu_served || ''
    }));
  } catch (err) {
    error.value = 'Gagal memuat data log dining.';
  }
};

const saveDining = async () => {
  loading.value = true;
  error.value = '';
  message.value = '';
  
  try {
    const payload = {
      date: selectedDate.value,
      meal_type: mealType.value,
      dinings: students.value.map(s => ({
        student_id: s.id,
        status: s.status,
        menu_served: s.menu_served
      }))
    };
    const res = await axios.post('/staff/dining', payload);
    message.value = res.data.message;
  } catch (err) {
    error.value = 'Gagal menyimpan log dining.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDining();
});
</script>

<style scoped>
.dining-page {
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

.controls {
  margin-top: 1.5rem;
  display: flex;
  gap: 1rem;
  align-items: flex-end;
  flex-wrap: wrap;
}

.form-group {
  margin-bottom: 0;
  min-width: 150px;
}

.table-card {
  padding: 1rem;
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

.text-center {
  text-align: center;
}

.select-status {
  min-width: 120px;
}

.select-status.eaten { border-color: #10b981; }
.select-status.missed { border-color: #ef4444; }

.mt-1 { margin-top: 1rem; }
</style>

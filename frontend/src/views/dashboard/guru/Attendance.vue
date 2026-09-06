<template>
  <div class="attendance-page">
    <div class="glass-panel header-card">
      <h2>Absensi Murid</h2>
      <p>Pilih tanggal dan kelola kehadiran seluruh murid.</p>
      
      <div class="controls">
        <input type="date" v-model="selectedDate" class="form-input date-picker" @change="fetchStudents" />
        <button class="btn btn-primary" @click="saveAttendance" :disabled="loading || students.length === 0">
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
              <th>Nama Murid</th>
              <th>Status Kehadiran</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="students.length === 0">
              <td colspan="3" class="text-center">Memuat data atau data kosong...</td>
            </tr>
            <tr v-for="(student, index) in students" :key="student.id">
              <td>{{ student.nisn }}</td>
              <td>{{ student.name }}</td>
              <td>
                <select v-model="students[index].status" class="form-input select-status" :class="students[index].status">
                  <option value="present">Hadir</option>
                  <option value="absent">Alfa</option>
                  <option value="sick">Sakit</option>
                  <option value="leave">Izin</option>
                </select>
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
const students = ref([]);
const loading = ref(false);
const message = ref('');
const error = ref('');

const fetchStudents = async () => {
  error.value = '';
  message.value = '';
  try {
    const res = await axios.get('/teacher/attendance', { params: { date: selectedDate.value } });
    // Normalize status to 'present' if null
    students.value = res.data.map(s => ({
      ...s,
      status: s.status || 'present'
    }));
  } catch (err) {
    error.value = 'Gagal memuat data murid.';
  }
};

const saveAttendance = async () => {
  loading.value = true;
  error.value = '';
  message.value = '';
  
  try {
    const payload = {
      date: selectedDate.value,
      attendances: students.value.map(s => ({
        student_id: s.id,
        status: s.status
      }))
    };
    const res = await axios.post('/teacher/attendance', payload);
    message.value = res.data.message;
  } catch (err) {
    error.value = 'Gagal menyimpan absensi.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchStudents();
});
</script>

<style scoped>
.attendance-page {
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
  align-items: center;
}

.date-picker {
  max-width: 200px;
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
  padding: 0.5rem 1rem;
}

.select-status.present { border-color: #10b981; }
.select-status.absent { border-color: #ef4444; }
.select-status.sick { border-color: #f59e0b; }
.select-status.leave { border-color: #3b82f6; }

.mt-1 { margin-top: 1rem; }
</style>

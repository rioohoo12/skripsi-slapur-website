<template>
  <div>
    <div class="header">
      <h1 class="page-title">Jadwal & Nilai Akademik</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali</router-link>
    </div>

    <div v-if="loading" class="alert">Memuat data akademik...</div>
    <div v-else-if="error" class="alert alert-error">{{ error }}</div>
    <div v-else>
      <div class="glass-panel section-card">
        <h2 class="section-title">Jadwal Pelajaran</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>Hari</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Waktu</th>
              <th>Ruang</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="schedule in schedules" :key="schedule.id">
              <td>{{ schedule.day_of_week }}</td>
              <td>{{ schedule.subject_name }}</td>
              <td>{{ schedule.teacher?.user?.name }}</td>
              <td>{{ schedule.start_time }} - {{ schedule.end_time }}</td>
              <td>{{ schedule.location }}</td>
            </tr>
            <tr v-if="schedules.length === 0">
              <td colspan="5" class="text-center">Belum ada jadwal.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="glass-panel section-card">
        <h2 class="section-title">Transkrip Nilai</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>Semester</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Nilai Akhir</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="grade in grades" :key="grade.id">
              <td>{{ grade.semester }}</td>
              <td>{{ grade.subject_name }}</td>
              <td>{{ grade.teacher?.user?.name }}</td>
              <td><strong>{{ grade.score }}</strong></td>
            </tr>
            <tr v-if="grades.length === 0">
              <td colspan="4" class="text-center">Belum ada nilai yang dipublikasikan.</td>
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

const schedules = ref([]);
const grades = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchAcademicData = async () => {
  try {
    loading.value = true;
    const [scheduleRes, gradeRes] = await Promise.all([
      axios.get('/academic/schedules'),
      axios.get('/academic/grades')
    ]);
    schedules.value = scheduleRes.data;
    grades.value = gradeRes.data;
  } catch (err) {
    error.value = 'Gagal memuat data akademik.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchAcademicData();
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; }
.section-card { padding: 2rem; margin-bottom: 2rem; }
.section-title { font-size: 1.5rem; color: var(--primary); margin-bottom: 1.5rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.5rem; }
.btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; }
.btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th, .data-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--surface-border);
}

.data-table th {
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 0.875rem;
  text-transform: uppercase;
}

.data-table tr:hover td {
  background: rgba(255, 255, 255, 0.02);
}

.text-center { text-align: center !important; color: var(--text-secondary); }
</style>

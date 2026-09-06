<template>
  <div class="grades-page">
    <div class="glass-panel header-card">
      <h2>Input Nilai Murid</h2>
      <p>Kelola nilai pelajaran berdasarkan mata pelajaran dan semester.</p>
      
      <div class="controls">
        <div class="form-group">
          <label>Mata Pelajaran</label>
          <input type="text" v-model="subject" class="form-input" placeholder="Matematika" />
        </div>
        <div class="form-group">
          <label>Semester</label>
          <select v-model="semester" class="form-input">
            <option value="Ganjil 2026">Ganjil 2026</option>
            <option value="Genap 2026">Genap 2026</option>
          </select>
        </div>
        <button class="btn btn-primary" @click="fetchStudents" :disabled="!subject">Muat Data</button>
      </div>
      
      <div v-if="message" class="alert alert-success mt-1">{{ message }}</div>
      <div v-if="error" class="alert alert-error mt-1">{{ error }}</div>
    </div>

    <div class="glass-panel table-card" v-if="students.length > 0">
      <div class="table-responsive">
        <table class="glass-table">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama Murid</th>
              <th>Nilai (0-100)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(student, index) in students" :key="student.id">
              <td>{{ student.nisn }}</td>
              <td>{{ student.name }}</td>
              <td>
                <input type="number" v-model.number="students[index].score" class="form-input w-24" min="0" max="100" placeholder="0" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="mt-2 text-right">
        <button class="btn btn-primary" @click="saveGrades" :disabled="loading">
          <span v-if="loading">Menyimpan...</span>
          <span v-else>Simpan Semua Nilai</span>
        </button>
      </div>
    </div>
    
    <div class="glass-panel p-4" v-else-if="searched">
      <p class="text-center">Tidak ada murid atau data kosong.</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const subject = ref('');
const semester = ref('Ganjil 2026');
const students = ref([]);
const searched = ref(false);
const loading = ref(false);
const message = ref('');
const error = ref('');

const fetchStudents = async () => {
  error.value = '';
  message.value = '';
  if (!subject.value) return;
  
  try {
    const res = await axios.get('/teacher/grades', { 
      params: { 
        subject_name: subject.value,
        semester: semester.value
      } 
    });
    students.value = res.data.map(s => ({
      ...s,
      score: s.score || 0
    }));
    searched.value = true;
  } catch (err) {
    error.value = 'Gagal memuat data murid.';
  }
};

const saveGrades = async () => {
  loading.value = true;
  error.value = '';
  message.value = '';
  
  try {
    const payload = {
      subject_name: subject.value,
      semester: semester.value,
      grades: students.value.map(s => ({
        student_id: s.id,
        score: s.score
      }))
    };
    const res = await axios.post('/teacher/grades', payload);
    message.value = res.data.message;
  } catch (err) {
    error.value = 'Gagal menyimpan nilai.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.grades-page {
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
  gap: 1.5rem;
  align-items: flex-end;
  flex-wrap: wrap;
}

.form-group {
  margin-bottom: 0;
  min-width: 200px;
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
.text-right { text-align: right; }
.mt-1 { margin-top: 1rem; }
.mt-2 { margin-top: 2rem; }
.w-24 { width: 100px; }
.p-4 { padding: 1.5rem; }
</style>

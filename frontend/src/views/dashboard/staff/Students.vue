<template>
  <div class="students-page">
    <div class="glass-panel header-card">
      <h2>Master Data Siswa</h2>
      <p>Kelola data seluruh siswa. (Hanya untuk Admin/Staff)</p>
    </div>

    <div class="glass-panel table-card">
      <div class="table-responsive">
        <table class="glass-table">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama Siswa</th>
              <th>Email</th>
              <th>Gender</th>
              <th>Tgl Lahir</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="students.length === 0">
              <td colspan="6" class="text-center">Memuat data...</td>
            </tr>
            <tr v-for="student in students" :key="student.id">
              <td>{{ student.nisn }}</td>
              <td>{{ student.user?.name }}</td>
              <td>{{ student.user?.email }}</td>
              <td>{{ student.gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
              <td>{{ student.date_of_birth }}</td>
              <td>
                <button class="btn-icon delete" @click="deleteStudent(student.id)" title="Hapus Siswa">🗑️</button>
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

const students = ref([]);

const fetchStudents = async () => {
  try {
    const res = await axios.get('/staff/students');
    students.value = res.data;
  } catch (err) {
    console.error('Failed to fetch students', err);
  }
};

const deleteStudent = async (id) => {
  if (!confirm('Anda yakin ingin menghapus data siswa ini secara permanen beserta riwayatnya?')) return;
  
  try {
    await axios.delete(`/staff/students/${id}`);
    students.value = students.value.filter(s => s.id !== id);
  } catch (err) {
    alert('Gagal menghapus siswa.');
  }
};

onMounted(() => {
  fetchStudents();
});
</script>

<style scoped>
.students-page {
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

.text-center {
  text-align: center;
}

.btn-icon {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 8px;
  transition: background 0.2s;
}

.btn-icon:hover {
  background: rgba(239, 68, 68, 0.2);
}
</style>

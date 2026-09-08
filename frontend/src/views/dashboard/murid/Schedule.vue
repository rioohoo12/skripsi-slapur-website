<template>
  <div class="schedule-page fade-in">
    <div class="page-header">
      <h1 class="page-title">Jadwal Pelajaran</h1>
      <p class="page-subtitle">Lihat jadwal pelajaran mingguan dan harian Anda.</p>
    </div>

    <div class="schedule-card glass-panel">
      <div class="filter-controls">
        <select v-model="selectedDay" class="form-input w-auto">
          <option value="Senin">Senin</option>
          <option value="Selasa">Selasa</option>
          <option value="Rabu">Rabu</option>
          <option value="Kamis">Kamis</option>
          <option value="Jumat">Jumat</option>
          <option value="Sabtu">Sabtu</option>
        </select>
      </div>

      <div class="table-responsive">
        <table class="schedule-table">
          <thead>
            <tr>
              <th>Jam</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Ruangan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredSchedule" :key="item.id">
              <td class="time-cell">{{ item.time }}</td>
              <td class="subject-cell">{{ item.subject }}</td>
              <td>{{ item.teacher }}</td>
              <td>{{ item.room }}</td>
              <td>
                <span class="status-badge" :class="item.status.toLowerCase()">{{ item.status }}</span>
              </td>
            </tr>
            <tr v-if="filteredSchedule.length === 0">
              <td colspan="5" class="text-center text-secondary py-4">Tidak ada jadwal pelajaran di hari ini.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const selectedDay = ref('Senin');

const dummySchedule = ref([
  { id: 1, day: 'Senin', time: '07:30 - 09:00', subject: 'Matematika', teacher: 'Budi Santoso, M.Pd', room: 'Ruang 101', status: 'Hadir' },
  { id: 2, day: 'Senin', time: '09:00 - 10:30', subject: 'Bahasa Indonesia', teacher: 'Siti Aminah, S.Pd', room: 'Ruang 101', status: 'Hadir' },
  { id: 3, day: 'Senin', time: '10:45 - 12:15', subject: 'Fisika', teacher: 'Agus Purnomo, M.Si', room: 'Lab Fisika', status: 'Menunggu' },
  { id: 4, day: 'Selasa', time: '07:30 - 09:00', subject: 'Biologi', teacher: 'Rina Wijayanti, S.Pd', room: 'Lab Biologi', status: 'Menunggu' },
  { id: 5, day: 'Selasa', time: '09:00 - 10:30', subject: 'Kimia', teacher: 'Dewi Lestari, M.Si', room: 'Lab Kimia', status: 'Menunggu' },
  { id: 6, day: 'Rabu', time: '07:30 - 09:00', subject: 'Bahasa Inggris', teacher: 'John Doe, B.A', room: 'Ruang 102', status: 'Menunggu' },
]);

const filteredSchedule = computed(() => {
  return dummySchedule.value.filter(s => s.day === selectedDay.value);
});
</script>

<style scoped>
.schedule-page {
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

.schedule-card {
  padding: 1.5rem;
}

.filter-controls {
  margin-bottom: 1.5rem;
  display: flex;
  gap: 1rem;
}

.form-input {
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--surface-border);
  border-radius: 8px;
  color: white;
  outline: none;
}

.form-input option {
  background: #1e293b;
  color: white;
}

.table-responsive {
  overflow-x: auto;
}

.schedule-table {
  width: 100%;
  border-collapse: collapse;
}

.schedule-table th, .schedule-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--surface-border);
}

.schedule-table th {
  font-weight: 600;
  color: var(--text-secondary);
  background: rgba(255, 255, 255, 0.02);
}

.schedule-table td {
  color: white;
  font-size: 0.95rem;
}

.time-cell {
  font-weight: 500;
  color: var(--primary);
}

.subject-cell {
  font-weight: 600;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-badge.hadir {
  background: rgba(16, 185, 129, 0.2);
  color: #34d399;
}

.status-badge.menunggu {
  background: rgba(245, 158, 11, 0.2);
  color: #fbbf24;
}

.status-badge.absen {
  background: rgba(239, 68, 68, 0.2);
  color: #f87171;
}

.text-center { text-align: center; }
.py-4 { padding-top: 2rem; padding-bottom: 2rem; }
</style>

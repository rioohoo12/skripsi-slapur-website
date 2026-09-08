<template>
  <div class="transcript-page fade-in">
    <div class="page-header flex-between">
      <div>
        <h1 class="page-title">Transkrip Nilai</h1>
        <p class="page-subtitle">Rekapitulasi nilai akhir mata pelajaran Anda.</p>
      </div>
      <button class="btn btn-primary btn-icon-text">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Download PDF
      </button>
    </div>

    <div class="transcript-summary glass-panel">
      <div class="summary-item">
        <span class="label">Total SKS/JP</span>
        <span class="value">42</span>
      </div>
      <div class="summary-divider"></div>
      <div class="summary-item">
        <span class="label">Indeks Prestasi Kumulatif (IPK)</span>
        <span class="value highlight">3.85</span>
      </div>
      <div class="summary-divider"></div>
      <div class="summary-item">
        <span class="label">Status Akademik</span>
        <span class="value success">Memuaskan</span>
      </div>
    </div>

    <div class="transcript-card glass-panel mt-4">
      <div class="filter-controls">
        <select v-model="selectedSemester" class="form-input w-auto">
          <option value="Semua">Semua Semester</option>
          <option value="Semester 1">Semester 1</option>
          <option value="Semester 2">Semester 2</option>
        </select>
      </div>

      <div class="table-responsive">
        <table class="transcript-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Mata Pelajaran</th>
              <th class="text-center">SKS/JP</th>
              <th class="text-center">Nilai Huruf</th>
              <th class="text-center">Nilai Angka</th>
              <th>Semester</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredGrades" :key="item.id">
              <td class="code-cell">{{ item.code }}</td>
              <td class="subject-cell">{{ item.subject }}</td>
              <td class="text-center">{{ item.credits }}</td>
              <td class="text-center font-bold" :class="getGradeColor(item.letterGrade)">{{ item.letterGrade }}</td>
              <td class="text-center">{{ item.numericGrade }}</td>
              <td>{{ item.semester }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const selectedSemester = ref('Semua');

const grades = ref([
  { id: 1, code: 'MAT101', subject: 'Matematika Dasar', credits: 4, letterGrade: 'A', numericGrade: 4.0, semester: 'Semester 1' },
  { id: 2, code: 'IND101', subject: 'Bahasa Indonesia', credits: 3, letterGrade: 'A-', numericGrade: 3.7, semester: 'Semester 1' },
  { id: 3, code: 'ENG101', subject: 'Bahasa Inggris', credits: 3, letterGrade: 'B+', numericGrade: 3.3, semester: 'Semester 1' },
  { id: 4, code: 'FIS101', subject: 'Fisika Dasar', credits: 4, letterGrade: 'A', numericGrade: 4.0, semester: 'Semester 1' },
  { id: 5, code: 'MAT102', subject: 'Matematika Lanjut', credits: 4, letterGrade: 'A', numericGrade: 4.0, semester: 'Semester 2' },
  { id: 6, code: 'KIM101', subject: 'Kimia Dasar', credits: 4, letterGrade: 'B', numericGrade: 3.0, semester: 'Semester 2' },
]);

const filteredGrades = computed(() => {
  if (selectedSemester.value === 'Semua') return grades.value;
  return grades.value.filter(g => g.semester === selectedSemester.value);
});

const getGradeColor = (grade) => {
  if (grade.startsWith('A')) return 'text-success';
  if (grade.startsWith('B')) return 'text-primary';
  if (grade.startsWith('C')) return 'text-warning';
  return 'text-error';
};
</script>

<style scoped>
.transcript-page {
  max-width: 1000px;
  margin: 0 auto;
}

.flex-between {
  display: flex;
  justify-content: space-between;
  align-items: center;
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

.btn-icon-text {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.transcript-summary {
  display: flex;
  justify-content: space-around;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.summary-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.summary-divider {
  width: 1px;
  background: var(--surface-border);
}

.label {
  font-size: 0.875rem;
  color: var(--text-secondary);
}

.value {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
}

.value.highlight {
  color: var(--primary);
}

.value.success {
  color: #10b981;
}

.transcript-card {
  padding: 1.5rem;
}

.filter-controls {
  margin-bottom: 1.5rem;
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

.transcript-table {
  width: 100%;
  border-collapse: collapse;
}

.transcript-table th, .transcript-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--surface-border);
}

.transcript-table th {
  font-weight: 600;
  color: var(--text-secondary);
  background: rgba(255, 255, 255, 0.02);
}

.transcript-table td {
  color: white;
  font-size: 0.95rem;
}

.code-cell {
  font-family: monospace;
  color: var(--text-secondary);
}

.subject-cell {
  font-weight: 600;
}

.text-center { text-align: center !important; }
.font-bold { font-weight: bold; }
.text-success { color: #10b981 !important; }
.text-primary { color: var(--primary) !important; }
.text-warning { color: #fbbf24 !important; }
.text-error { color: #ef4444 !important; }
.mt-4 { margin-top: 2rem; }

@media (max-width: 768px) {
  .flex-between {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  .transcript-summary {
    flex-direction: column;
    gap: 1rem;
  }
  .summary-divider {
    width: 100%;
    height: 1px;
  }
}
</style>

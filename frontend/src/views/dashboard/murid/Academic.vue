<template>
  <div>
    <div class="header">
      <h1 class="page-title">Menu Akademik</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali ke Dashboard</router-link>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="tabs-container glass-panel">
      <button v-for="tab in mainTabs" :key="tab.id" 
              :class="['tab-btn', { active: activeMainTab === tab.id }]"
              @click="activeMainTab = tab.id">
        {{ tab.label }}
      </button>
    </div>

    <!-- Content Sections -->
    <div class="tab-content">
      
      <!-- KELAS SAYA -->
      <div v-if="activeMainTab === 'kelas_saya'" class="glass-panel section-card">
        <h2 class="section-title">Kelas Saya</h2>
        <div v-if="loading.myClass" class="loading-text">Memuat jadwal kelas...</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>Hari</th><th>Mata Pelajaran</th><th>Guru</th><th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="schedule in myClassData" :key="schedule.id">
              <td>{{ schedule.day }}</td>
              <td><strong>{{ schedule.subject }}</strong></td>
              <td>{{ schedule.teacher?.user?.name || '-' }}</td>
              <td>{{ formatTime(schedule.time_start) }} - {{ formatTime(schedule.time_end) }}</td>
            </tr>
            <tr v-if="myClassData.length === 0"><td colspan="4" class="text-center">Belum ada jadwal.</td></tr>
          </tbody>
        </table>
      </div>

      <!-- JADWAL KELAS -->
      <div v-if="activeMainTab === 'jadwal_kelas'" class="glass-panel section-card">
        <h2 class="section-title">Jadwal Seluruh Kelas</h2>
        <div v-if="loading.classSchedule" class="loading-text">Memuat jadwal...</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>Kelas</th><th>Hari</th><th>Mata Pelajaran</th><th>Guru</th><th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="schedule in classScheduleData" :key="schedule.id">
              <td><span class="class-badge">{{ schedule.class_name }}</span></td>
              <td>{{ schedule.day }}</td>
              <td><strong>{{ schedule.subject }}</strong></td>
              <td>{{ schedule.teacher?.user?.name || '-' }}</td>
              <td>{{ formatTime(schedule.time_start) }} - {{ formatTime(schedule.time_end) }}</td>
            </tr>
            <tr v-if="classScheduleData.length === 0"><td colspan="5" class="text-center">Belum ada jadwal.</td></tr>
          </tbody>
        </table>
      </div>

      <!-- ABSENSI -->
      <div v-if="activeMainTab === 'absensi'" class="glass-panel section-card">
        <h2 class="section-title">Riwayat Absensi</h2>
        <div v-if="loading.attendance" class="loading-text">Memuat absensi...</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>Tanggal</th><th>Mata Pelajaran</th><th>Guru</th><th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="att in attendanceData" :key="att.id">
              <td>{{ att.date }}</td>
              <td><strong>{{ att.class_schedule?.subject || '-' }}</strong></td>
              <td>{{ att.class_schedule?.teacher?.user?.name || '-' }}</td>
              <td>
                <span :class="['status-badge', att.status.toLowerCase()]">{{ att.status }}</span>
              </td>
            </tr>
            <tr v-if="attendanceData.length === 0"><td colspan="4" class="text-center">Belum ada riwayat absensi.</td></tr>
          </tbody>
        </table>
      </div>

      <!-- NILAI AKADEMIK -->
      <div v-if="activeMainTab === 'nilai'" class="glass-panel section-card">
        <h2 class="section-title">Nilai Akademik</h2>
        
        <!-- Sub Tabs for Grades -->
        <div class="sub-tabs">
          <button v-for="tab in gradeTabs" :key="tab.id"
                  :class="['sub-tab-btn', { active: activeGradeTab === tab.id }]"
                  @click="activeGradeTab = tab.id; fetchGrades()">
            {{ tab.label }}
          </button>
        </div>

        <div v-if="loading.grades" class="loading-text" style="margin-top: 1rem;">Memuat nilai...</div>
        <table v-else class="data-table" style="margin-top: 1rem;">
          <thead>
            <tr>
              <th>Mata Pelajaran</th><th>Guru</th><th>Tipe Penilaian</th><th>Nilai</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="grade in gradesData" :key="grade.id">
              <td><strong>{{ grade.subject_name }}</strong></td>
              <td>{{ grade.teacher?.user?.name || '-' }}</td>
              <td style="text-transform: capitalize;">{{ grade.grade_type.replace('_', ' ') }}</td>
              <td><span class="score-badge">{{ grade.score }}</span></td>
            </tr>
            <tr v-if="gradesData.length === 0"><td colspan="4" class="text-center">Belum ada nilai untuk kategori ini.</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const mainTabs = [
  { id: 'kelas_saya', label: 'Kelas Saya' },
  { id: 'jadwal_kelas', label: 'Jadwal Kelas' },
  { id: 'absensi', label: 'Absensi' },
  { id: 'nilai', label: 'Nilai Akademik' }
];

const gradeTabs = [
  { id: 'kuis', label: 'Kuis' },
  { id: 'tugas', label: 'Tugas' },
  { id: 'ujian_harian', label: 'Ujian Harian' },
  { id: 'ujian_mid', label: 'Ujian Mid' },
  { id: 'ujian_final', label: 'Ujian Final' }
];

const activeMainTab = ref('kelas_saya');
const activeGradeTab = ref('kuis');

const loading = ref({
  myClass: false,
  classSchedule: false,
  attendance: false,
  grades: false
});

const myClassData = ref([]);
const classScheduleData = ref([]);
const attendanceData = ref([]);
const gradesData = ref([]);

const formatTime = (timeStr) => {
  if (!timeStr) return '';
  return timeStr.substring(0, 5);
};

const fetchData = async () => {
  if (!userId) return;
  
  if (activeMainTab.value === 'kelas_saya') {
    loading.value.myClass = true;
    try {
      const res = await api.get(`/students/${userId}/academic/my-class`);
      myClassData.value = res.data;
    } catch (e) { console.error(e); }
    loading.value.myClass = false;
  }
  else if (activeMainTab.value === 'jadwal_kelas') {
    loading.value.classSchedule = true;
    try {
      const res = await api.get(`/academic/class-schedule`);
      classScheduleData.value = res.data;
    } catch (e) { console.error(e); }
    loading.value.classSchedule = false;
  }
  else if (activeMainTab.value === 'absensi') {
    loading.value.attendance = true;
    try {
      const res = await api.get(`/students/${userId}/attendance`);
      attendanceData.value = res.data;
    } catch (e) { console.error(e); }
    loading.value.attendance = false;
  }
  else if (activeMainTab.value === 'nilai') {
    fetchGrades();
  }
};

const fetchGrades = async () => {
  if (!userId) return;
  loading.value.grades = true;
  try {
    const res = await api.get(`/students/${userId}/grades?type=${activeGradeTab.value}`);
    gradesData.value = res.data;
  } catch (e) { console.error(e); }
  loading.value.grades = false;
};

watch(activeMainTab, () => {
  fetchData();
});

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; color: var(--text-primary); }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.2s; }
.btn-secondary:hover { background: var(--primary); color: white; }

.tabs-container { display: flex; gap: 0.5rem; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; overflow-x: auto; }
.tab-btn { background: transparent; border: none; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 600; color: var(--text-secondary); cursor: pointer; border-radius: 8px; transition: all 0.2s; white-space: nowrap; }
.tab-btn:hover { background: rgba(var(--primary-rgb), 0.05); color: var(--primary); }
.tab-btn.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3); }

.sub-tabs { display: flex; gap: 0.5rem; border-bottom: 2px solid var(--surface-border); padding-bottom: 0.5rem; margin-bottom: 1rem; overflow-x: auto; }
.sub-tab-btn { background: transparent; border: none; padding: 0.5rem 1rem; font-size: 0.9rem; font-weight: 600; color: var(--text-secondary); cursor: pointer; transition: color 0.2s; position: relative; white-space: nowrap; }
.sub-tab-btn:hover { color: var(--primary); }
.sub-tab-btn.active { color: var(--primary); }
.sub-tab-btn.active::after { content: ''; position: absolute; bottom: -0.65rem; left: 0; right: 0; height: 3px; background: var(--primary); border-radius: 3px 3px 0 0; }

.section-card { padding: 2rem; border-radius: 12px; }
.section-title { font-size: 1.5rem; color: var(--primary); margin-bottom: 1.5rem; font-weight: 700; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--surface-border); }
.data-table th { color: var(--text-secondary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
.data-table tr:hover td { background: rgba(var(--primary-rgb), 0.02); }

.class-badge { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.25rem 0.5rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; }
.score-badge { font-weight: 800; font-size: 1.1rem; color: var(--primary); }
.loading-text { color: var(--text-secondary); font-style: italic; }

.status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
.status-badge.hadir { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
.status-badge.izin { background: rgba(234, 179, 8, 0.1); color: #eab308; }
.status-badge.sakit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
.status-badge.alpa { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

.text-center { text-align: center !important; color: var(--text-secondary); }
</style>

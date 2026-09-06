<template>
  <div>
    <div class="header">
      <h1 class="page-title">Pemilihan Kamar Asrama</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali</router-link>
    </div>
    
    <div v-if="loading" class="alert">Memuat daftar kamar...</div>
    <div v-else-if="error" class="alert alert-error">{{ error }}</div>
    <div v-else>
      <div v-if="myRoom" class="glass-panel room-assigned">
        <h3>✅ Anda telah mendapatkan kamar</h3>
        <p><strong>Nama Kamar:</strong> {{ myRoom.room.name }}</p>
        <p><strong>Tahun Ajaran:</strong> {{ myRoom.academic_year }}</p>
        <p><strong>Status:</strong> Aktif</p>
      </div>
      <div v-else>
        <h2 class="section-title">Asrama {{ genderLabel }}</h2>
        <p class="subtitle">Daftar kamar yang tersedia untuk Anda.</p>
        
        <div class="rooms-grid">
          <div v-for="room in availableRooms" :key="room.id" class="glass-panel room-card">
            <h3>{{ room.name }}</h3>
            <div class="room-stats">
              <span class="capacity">Sisa: <strong>{{ room.remaining }}</strong> / {{ room.capacity }} Kasur</span>
            </div>
            <button @click="assignRoom(room.id)" class="btn btn-primary w-full" :disabled="assigning">
              {{ assigning ? 'Memproses...' : 'Pilih Kamar Ini' }}
            </button>
          </div>
          
          <div v-if="availableRooms.length === 0" class="glass-panel empty-state">
            Mohon maaf, saat ini tidak ada kamar yang tersedia.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../../stores/auth';

const authStore = useAuthStore();
const availableRooms = ref([]);
const myRoom = ref(null);
const loading = ref(true);
const assigning = ref(false);
const error = ref(null);

const genderLabel = computed(() => {
  // Try to determine from available rooms data or default
  if (availableRooms.value.length > 0) {
    return availableRooms.value[0].gender_type === 'L' ? 'Putra' : 'Putri';
  }
  return '';
});

const fetchData = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    // Check if already assigned
    const myRoomRes = await axios.get('/rooms/my');
    if (myRoomRes.data && myRoomRes.data.id) {
      myRoom.value = myRoomRes.data;
      return;
    }

    // Get available rooms
    const res = await axios.get('/rooms/available');
    availableRooms.value = res.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memuat data kamar.';
  } finally {
    loading.value = false;
  }
};

const assignRoom = async (roomId) => {
  if (!confirm('Apakah Anda yakin ingin memilih kamar ini?')) return;
  
  try {
    assigning.value = true;
    error.value = null;
    await axios.post('/rooms/assign', {
      room_id: roomId,
      academic_year: '2026/2027' // Example default
    });
    // Refresh
    await fetchData();
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memilih kamar.';
  } finally {
    assigning.value = false;
  }
};

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; }
.section-title { font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; }
.subtitle { color: var(--text-secondary); margin-bottom: 2rem; }
.btn-secondary { background: rgba(255, 255, 255, 0.1); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; }
.btn-secondary:hover { background: rgba(255, 255, 255, 0.2); }

.rooms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}
.room-card { padding: 1.5rem; display: flex; flex-direction: column; }
.room-card h3 { font-size: 1.25rem; margin-bottom: 1rem; }
.room-stats { margin-bottom: 1.5rem; color: var(--text-secondary); }
.room-stats strong { color: white; font-size: 1.125rem; }
.w-full { width: 100%; margin-top: auto; }

.room-assigned { padding: 2rem; border-left: 4px solid #10b981; }
.room-assigned h3 { color: #10b981; margin-bottom: 1rem; }
.room-assigned p { margin-bottom: 0.5rem; }

.empty-state { padding: 3rem; text-align: center; color: var(--text-secondary); }
</style>

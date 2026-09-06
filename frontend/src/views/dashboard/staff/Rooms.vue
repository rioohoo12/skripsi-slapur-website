<template>
  <div class="rooms-page">
    <div class="glass-panel header-card">
      <h2>Logistik Kamar Asrama</h2>
      <p>Lihat okupansi kamar dan atur penempatan siswa.</p>
    </div>

    <div class="rooms-grid">
      <!-- Room Cards -->
      <div v-for="room in rooms" :key="room.id" class="glass-panel room-card">
        <div class="room-header">
          <div class="room-title">
            <h3>{{ room.room_number }}</h3>
            <span class="badge" :class="room.gender_type">{{ room.gender_type === 'L' ? 'Putra' : 'Putri' }}</span>
          </div>
          <div class="room-capacity">
            {{ room.current_occupants }} / {{ room.capacity }}
          </div>
        </div>
        
        <!-- Progress bar -->
        <div class="progress-bar-container">
          <div class="progress-bar" :style="{ width: (room.current_occupants / room.capacity * 100) + '%' }" :class="{ 'full': room.current_occupants >= room.capacity }"></div>
        </div>
        
        <div class="room-actions">
          <button class="btn btn-outline" @click="viewRoomDetails(room.id)">Kelola Penghuni</button>
        </div>
      </div>
    </div>

    <!-- Modal for Room Details -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content glass-panel">
        <div class="modal-header">
          <h3>Kelola Kamar {{ selectedRoom?.room?.room_number }} ({{ selectedRoom?.room?.gender_type === 'L' ? 'Putra' : 'Putri' }})</h3>
          <button class="btn-close" @click="closeModal">✕</button>
        </div>
        
        <div class="modal-body">
          <h4>Daftar Penghuni Saat Ini ({{ selectedRoom?.occupants?.length }} / {{ selectedRoom?.room?.capacity }})</h4>
          <ul class="occupant-list">
            <li v-if="!selectedRoom?.occupants?.length" class="text-muted">Kamar masih kosong.</li>
            <li v-for="occ in selectedRoom?.occupants" :key="occ.id" class="occupant-item">
              <span>{{ occ.student?.user?.name }} (NISN: {{ occ.student?.nisn }})</span>
              <button class="btn-sm btn-danger" @click="removeOccupant(occ.id)">Keluarkan</button>
            </li>
          </ul>

          <div class="assign-form" v-if="selectedRoom?.occupants?.length < selectedRoom?.room?.capacity">
            <h4>Tambahkan Penghuni Manual</h4>
            <div class="form-group flex-row">
              <input type="number" v-model="assignStudentId" class="form-input" placeholder="ID Siswa (Cth: 1)" />
              <button class="btn btn-primary" @click="assignStudent" :disabled="!assignStudentId">Tambahkan</button>
            </div>
            <div v-if="assignError" class="text-danger mt-1">{{ assignError }}</div>
          </div>
          <div v-else class="text-danger mt-1">Kamar ini sudah penuh.</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const rooms = ref([]);
const showModal = ref(false);
const selectedRoom = ref(null);
const assignStudentId = ref('');
const assignError = ref('');

const fetchRooms = async () => {
  try {
    const res = await axios.get('/staff/rooms');
    rooms.value = res.data;
  } catch (err) {
    console.error('Failed to fetch rooms', err);
  }
};

const viewRoomDetails = async (id) => {
  try {
    const res = await axios.get(`/staff/rooms/${id}`);
    selectedRoom.value = res.data;
    assignStudentId.value = '';
    assignError.value = '';
    showModal.value = true;
  } catch (err) {
    alert('Gagal memuat detail kamar.');
  }
};

const closeModal = () => {
  showModal.value = false;
  selectedRoom.value = null;
  fetchRooms(); // Refresh stats
};

const removeOccupant = async (assignmentId) => {
  if (!confirm('Keluarkan siswa ini dari kamar?')) return;
  
  try {
    await axios.delete(`/staff/room-assignments/${assignmentId}`);
    // Refresh details
    viewRoomDetails(selectedRoom.value.room.id);
  } catch (err) {
    alert('Gagal mengeluarkan siswa.');
  }
};

const assignStudent = async () => {
  assignError.value = '';
  try {
    await axios.post(`/staff/rooms/${selectedRoom.value.room.id}/assign`, {
      student_id: assignStudentId.value
    });
    // Refresh details
    viewRoomDetails(selectedRoom.value.room.id);
  } catch (err) {
    assignError.value = err.response?.data?.message || 'Gagal menambahkan siswa (Pastikan ID benar dan gender cocok).';
  }
};

onMounted(() => {
  fetchRooms();
});
</script>

<style scoped>
.rooms-page {
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

.rooms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.room-card {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.room-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.room-title h3 {
  color: white;
  font-size: 1.5rem;
  margin-bottom: 0.25rem;
}

.room-capacity {
  font-size: 1.25rem;
  font-weight: bold;
  color: var(--text-secondary);
}

.badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: 600;
}
.badge.L { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
.badge.P { background: rgba(236, 72, 153, 0.2); color: #f472b6; }

.progress-bar-container {
  height: 8px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 4px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background: var(--primary);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.progress-bar.full {
  background: #ef4444;
}

.btn-outline {
  width: 100%;
  padding: 0.5rem;
  background: transparent;
  border: 1px solid var(--primary);
  color: var(--primary);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline:hover {
  background: var(--primary);
  color: white;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  backdrop-filter: blur(4px);
}

.modal-content {
  width: 100%;
  max-width: 500px;
  padding: 1.5rem;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding-bottom: 0.5rem;
}

.modal-header h3 {
  color: white;
}

.btn-close {
  background: none;
  border: none;
  color: white;
  font-size: 1.25rem;
  cursor: pointer;
}

.occupant-list {
  list-style: none;
  padding: 0;
  margin: 1rem 0 2rem 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.occupant-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.flex-row {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  border: none;
  cursor: pointer;
}

.btn-danger {
  background: #ef4444;
  color: white;
}

.text-danger { color: #f87171; font-size: 0.875rem; }
.mt-1 { margin-top: 0.5rem; }
</style>

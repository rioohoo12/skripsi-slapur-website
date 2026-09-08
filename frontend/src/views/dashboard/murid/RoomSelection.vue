<template>
  <div>
    <div class="header">
      <h1 class="page-title">Pemilihan Kamar Asrama</h1>
      <router-link to="/dashboard/murid/registration" class="btn btn-secondary">Kembali</router-link>
    </div>
    
    <div v-if="loading && availableRooms.length === 0" class="alert">Memuat daftar kamar...</div>
    <div v-else-if="error" class="alert alert-error">{{ error }}</div>
    <div v-else>
      <div v-if="isCompleted" class="glass-panel room-assigned">
        <h3>✅ Anda telah berhasil memilih kamar asrama.</h3>
        <p>Silakan hubungi pengurus asrama untuk informasi pembagian kunci.</p>
      </div>
      <div v-else>
        <div class="info-bar glass-panel mb-2">
          <p>Memuat data kamar secara real-time. Kamar yang sudah penuh tidak dapat dipilih.</p>
        </div>

        <div class="rooms-grid">
          <div v-for="room in availableRooms" :key="room.id" class="glass-panel room-card">
            <h3>{{ room.name }}</h3>
            <div class="room-stats">
              <span class="capacity">
                Terisi: <strong>{{ room.occupied_count }}</strong> / {{ room.capacity }}
              </span>
            </div>
            <button @click="assignRoom(room.id)" 
                    class="btn w-full" 
                    :class="room.occupied_count >= room.capacity ? 'btn-disabled' : 'btn-primary'"
                    :disabled="assigning || room.occupied_count >= room.capacity">
              {{ room.occupied_count >= room.capacity ? 'Kamar Penuh' : (assigning ? 'Memproses...' : 'Pilih Kamar Ini') }}
            </button>
          </div>
          
          <div v-if="availableRooms.length === 0" class="glass-panel empty-state">
            Mohon maaf, saat ini tidak ada kamar yang tersedia sesuai kategori Anda.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;

const availableRooms = ref([]);
const loading = ref(true);
const assigning = ref(false);
const error = ref(null);
const isCompleted = ref(false);

let pollingInterval = null;

const fetchRooms = async () => {
  if (!userId || isCompleted.value) return;
  try {
    const res = await api.get(`/students/${userId}/dormitory-options`);
    availableRooms.value = res.data;
  } catch (err) {
    if (err.response?.status !== 400 && err.response?.status !== 403) {
      error.value = err.response?.data?.message || 'Gagal memuat data kamar.';
    } else {
      error.value = err.response?.data?.message;
    }
  } finally {
    loading.value = false;
  }
};

const checkStatus = async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/registration-progress`);
    if (res.data.step2_status === 'completed') {
      isCompleted.value = true;
      if (pollingInterval) clearInterval(pollingInterval);
    } else if (res.data.step0_status !== 'paid' || res.data.step1_status !== 'paid') {
       error.value = "Anda belum menyelesaikan pembayaran. Halaman ini terkunci.";
       if (pollingInterval) clearInterval(pollingInterval);
    }
  } catch (e) {
    console.error(e);
  }
};

const assignRoom = async (roomId) => {
  if (!confirm('Apakah Anda yakin ingin memilih kamar ini? Pilihan tidak dapat diubah.')) return;
  
  try {
    assigning.value = true;
    error.value = null;
    await api.post(`/students/${userId}/dormitory-selection`, {
      room_id: roomId
    });
    isCompleted.value = true;
    if (pollingInterval) clearInterval(pollingInterval);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memilih kamar. Mungkin kamar baru saja penuh.');
    fetchRooms(); // refresh immediately
  } finally {
    assigning.value = false;
  }
};

onMounted(async () => {
  await checkStatus();
  if (!isCompleted.value && !error.value) {
    await fetchRooms();
    pollingInterval = setInterval(fetchRooms, 5000); // Poll every 5s
  } else {
    loading.value = false;
  }
});

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval);
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; margin: 0; color: var(--text-primary); }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
.btn-primary { background: var(--primary); color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; }
.btn-primary:hover { filter: brightness(1.1); }
.btn-disabled { background: rgba(255,255,255,0.1); color: var(--text-secondary); padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: not-allowed; }

.mb-2 { margin-bottom: 1.5rem; }
.info-bar { padding: 1rem 1.5rem; color: var(--text-secondary); font-size: 0.95rem; border-left: 4px solid var(--primary); }

.rooms-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
.room-card { padding: 1.5rem; display: flex; flex-direction: column; }
.room-card h3 { font-size: 1.5rem; margin-top: 0; margin-bottom: 1rem; color: var(--primary); }
.room-stats { margin-bottom: 1.5rem; color: var(--text-secondary); font-size: 1.1rem; }
.room-stats strong { color: var(--text-primary); font-size: 1.25rem; font-weight: 800; }
.w-full { width: 100%; margin-top: auto; }

.room-assigned { padding: 2rem; border-left: 4px solid #10b981; }
.room-assigned h3 { color: #10b981; margin-bottom: 1rem; }
.room-assigned p { margin-bottom: 0.5rem; color: var(--text-primary); }

.empty-state { padding: 3rem; text-align: center; color: var(--text-secondary); grid-column: 1 / -1; }
</style>

<template>
  <div>
    <div class="header">
      <h1 class="page-title">Status Pendaftaran</h1>
      <router-link to="/dashboard/murid" class="btn btn-secondary">Kembali</router-link>
    </div>

    <div v-if="loading" class="alert">Memuat status pendaftaran...</div>
    <div v-else class="stepper-container glass-panel">
      <!-- Step 0: Permohonan -->
      <div class="step" :class="[progress.step0_status === 'paid' ? 'completed' : 'pending']">
        <div class="step-indicator">
          <span v-if="progress.step0_status === 'paid'">✓</span>
          <span v-else>0</span>
        </div>
        <div class="step-content">
          <h3>Permohonan Pendaftaran & Pembayaran</h3>
          <p v-if="progress.step0_status === 'paid'">Pembayaran telah lunas. Permohonan pendaftaran selesai.</p>
          <p v-else>Anda belum menyelesaikan pembayaran form pendaftaran.</p>
          
          <router-link v-if="progress.step0_status !== 'paid'" to="/dashboard/murid/registration/application" class="btn btn-primary mt-2">
            Isi Form & Bayar
          </router-link>
        </div>
      </div>

      <div class="step-connector" :class="[progress.step0_status === 'paid' ? 'completed' : '']"></div>

      <!-- Step 1: Clearance Slip -->
      <div class="step" :class="[progress.step1_status === 'paid' ? 'completed' : 'pending']">
        <div class="step-indicator">
          <span v-if="progress.step1_status === 'paid'">✓</span>
          <span v-else>1</span>
        </div>
        <div class="step-content">
          <h3>Clearance Slip</h3>
          <p v-if="progress.step1_status === 'paid'">Clearance slip otomatis diterbitkan karena pembayaran lunas.</p>
          <p v-else>Menunggu konfirmasi pembayaran di Langkah 0.</p>
        </div>
      </div>

      <div class="step-connector" :class="[progress.step1_status === 'paid' ? 'completed' : '']"></div>

      <!-- Step 2: Asrama -->
      <div class="step" :class="[progress.step2_status === 'completed' ? 'completed' : (isStep2Unlocked ? 'active' : 'pending')]">
        <div class="step-indicator">
          <span v-if="progress.step2_status === 'completed'">✓</span>
          <span v-else>2</span>
        </div>
        <div class="step-content">
          <h3>Pemilihan Kamar Asrama</h3>
          <p v-if="progress.step2_status === 'completed'">Anda telah berhasil memilih kamar asrama.</p>
          <p v-else-if="isStep2Unlocked">Anda sekarang dapat memilih kamar asrama.</p>
          <p v-else>Selesaikan langkah sebelumnya terlebih dahulu.</p>

          <router-link v-if="isStep2Unlocked && progress.step2_status !== 'completed'" to="/dashboard/murid/rooms" class="btn btn-primary mt-2">
            Pilih Kamar
          </router-link>
          <router-link v-if="progress.step2_status === 'completed'" to="/dashboard/murid/rooms" class="btn btn-secondary mt-2">
            Lihat Kamar
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../../../stores/auth';
import api from '../../../services/api';

const authStore = useAuthStore();
const userId = authStore.user?.id;
const loading = ref(true);
const progress = ref({
  step0_status: 'pending',
  step1_status: 'pending',
  step2_status: 'pending'
});

const isStep2Unlocked = computed(() => {
  return progress.value.step0_status === 'paid' && progress.value.step1_status === 'paid';
});

onMounted(async () => {
  if (!userId) return;
  try {
    const res = await api.get(`/students/${userId}/registration-progress`);
    progress.value = res.data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.page-title { font-size: 2rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.btn-secondary { background: rgba(var(--primary-rgb), 0.1); color: var(--primary); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
.btn-primary { background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; display: inline-block; }
.mt-2 { margin-top: 1rem; }

.stepper-container { padding: 3rem 2rem; display: flex; flex-direction: column; gap: 0; border-radius: 12px; }

.step { display: flex; gap: 1.5rem; align-items: flex-start; }
.step-indicator { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; flex-shrink: 0; transition: all 0.3s; }
.step-content { padding-top: 0.25rem; }
.step-content h3 { margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; }
.step-content p { color: var(--text-secondary); margin: 0; }

.step-connector { height: 40px; width: 4px; background: var(--surface-border); margin: 0.5rem 0 0.5rem 18px; transition: all 0.3s; }

/* Status Colors */
.step.pending .step-indicator { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 2px solid #ef4444; }
.step.pending .step-content h3 { color: #ef4444; }

.step.active .step-indicator { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 2px solid #3b82f6; }
.step.active .step-content h3 { color: #3b82f6; }

.step.completed .step-indicator { background: var(--success, #22c55e); color: white; border: 2px solid var(--success, #22c55e); }
.step.completed .step-content h3 { color: var(--success, #22c55e); }

.step-connector.completed { background: var(--success, #22c55e); }
</style>

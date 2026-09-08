<template>
  <div class="announcements-page fade-in">
    <div class="page-header">
      <h1 class="page-title">Pengumuman Sekolah</h1>
      <p class="page-subtitle">Informasi terbaru dari pihak sekolah dan administrasi.</p>
    </div>

    <div class="announcements-container">
      <div v-for="item in announcements" :key="item.id" class="announcement-card glass-panel" :class="{ 'unread': !item.isRead }">
        <div class="card-header">
          <div class="header-left">
            <span class="category-badge" :class="item.category.toLowerCase()">{{ item.category }}</span>
            <span class="date">{{ item.date }}</span>
          </div>
          <span v-if="!item.isRead" class="status-badge">Baru</span>
        </div>
        <h2 class="title">{{ item.title }}</h2>
        <p class="content">{{ item.content }}</p>
        <button v-if="!item.isRead" @click="markAsRead(item.id)" class="btn btn-outline-primary btn-sm mt-3">Tandai sudah dibaca</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const announcements = ref([
  {
    id: 1,
    title: 'Pemberitahuan Ujian Tengah Semester (UTS) Ganjil 2026/2027',
    content: 'Diberitahukan kepada seluruh siswa bahwa UTS Ganjil akan dilaksanakan mulai tanggal 15 Oktober 2026. Jadwal lengkap dapat dilihat pada menu Jadwal Pelajaran atau Kalender Akademik. Harap mempersiapkan diri dengan baik.',
    category: 'Akademik',
    date: '10 Oktober 2026',
    isRead: false
  },
  {
    id: 2,
    title: 'Pembayaran SPP Bulan Oktober',
    content: 'Mengingatkan kembali bahwa batas akhir pembayaran SPP untuk bulan Oktober 2026 adalah tanggal 10 Oktober 2026. Silakan cek menu Pembayaran untuk detail tagihan Anda.',
    category: 'Keuangan',
    date: '1 Oktober 2026',
    isRead: false
  },
  {
    id: 3,
    title: 'Libur Nasional Hari Pahlawan',
    content: 'Dalam rangka memperingati Hari Pahlawan, kegiatan belajar mengajar pada tanggal 10 November 2026 diliburkan. Siswa asrama diperbolehkan pulang mulai tanggal 9 November sore.',
    category: 'Umum',
    date: '28 September 2026',
    isRead: true
  }
]);

const markAsRead = (id) => {
  const item = announcements.value.find(a => a.id === id);
  if (item) {
    item.isRead = true;
  }
};
</script>

<style scoped>
.announcements-page {
  max-width: 900px;
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

.announcements-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.announcement-card {
  padding: 1.5rem;
  border-left: 4px solid transparent;
  transition: all 0.3s;
}

.announcement-card.unread {
  border-left-color: var(--primary);
  background: rgba(255, 255, 255, 0.08);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.category-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.category-badge.akademik {
  background: rgba(59, 130, 246, 0.2);
  color: #60a5fa;
}

.category-badge.keuangan {
  background: rgba(16, 185, 129, 0.2);
  color: #34d399;
}

.category-badge.umum {
  background: rgba(245, 158, 11, 0.2);
  color: #fbbf24;
}

.date {
  font-size: 0.875rem;
  color: var(--text-secondary);
}

.status-badge {
  background: var(--primary);
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: bold;
}

.title {
  font-size: 1.25rem;
  font-weight: 600;
  color: white;
  margin-bottom: 0.75rem;
}

.content {
  color: var(--text-secondary);
  line-height: 1.6;
}

.btn-outline-primary {
  background: transparent;
  border: 1px solid var(--primary);
  color: var(--primary);
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-outline-primary:hover {
  background: var(--primary);
  color: white;
}

.mt-3 {
  margin-top: 1rem;
}
</style>

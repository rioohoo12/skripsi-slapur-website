<template>
  <div class="dashboard-wrapper fade-in">
    <!-- ROW 1: Greeting & Banner -->
    <div class="grid-row-1 mb-4">
      <div class="greeting-section">
        <h1 class="greeting-title">Selamat datang, {{ profile.name }}!</h1>
        <p class="greeting-subtitle">Semoga hari ini menjadi langkah baik untuk masa depanmu.</p>
        
        <div class="stats-grid mt-4">
          <div class="stat-card glass-panel">
            <div class="stat-icon bg-blue-light"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg></div>
            <div class="stat-info">
              <span class="stat-label">Semester</span>
              <span class="stat-value">5</span>
            </div>
          </div>
          <div class="stat-card glass-panel">
            <div class="stat-icon bg-green-light"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
            <div class="stat-info">
              <span class="stat-label">IPK</span>
              <span class="stat-value">3.70</span>
            </div>
          </div>
          <div class="stat-card glass-panel">
            <div class="stat-icon bg-orange-light"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></div>
            <div class="stat-info">
              <span class="stat-label">SKS Tempuh</span>
              <span class="stat-value">86</span>
            </div>
          </div>
          <div class="stat-card glass-panel">
            <div class="stat-icon bg-purple-light"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
            <div class="stat-info">
              <span class="stat-label">SKS Semester Ini</span>
              <span class="stat-value">20</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="banner-section">
        <div class="university-banner">
          <div class="banner-content">
            <h2>Universitas Advent Indonesia</h2>
            <p>Berkarakter - Unggul - Berdaya Saing Global</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ROW 2: Jadwal & Kalender -->
    <div class="grid-row-2 mb-4">
      <!-- Jadwal Kuliah -->
      <div class="glass-panel card widget-jadwal">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-secondary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <h3 class="font-bold text-lg m-0">Jadwal Kuliah Hari Ini</h3>
          </div>
          <span class="text-primary text-sm font-medium">Selasa, 9 September 2025</span>
        </div>
        <div class="card-body">
          <div class="timeline-list">
            <div class="timeline-item">
              <div class="time">08:00 - 09:40</div>
              <div class="dot bg-blue"></div>
              <div class="details">
                <h4 class="font-bold">Web Programming 1</h4>
                <p class="text-muted text-sm">Sir Andrew Pakpahan</p>
              </div>
              <div class="room text-sm bg-gray-light px-2 py-1 rounded">Lab 3</div>
            </div>
            <div class="timeline-item">
              <div class="time">10:00 - 11:40</div>
              <div class="dot bg-green"></div>
              <div class="details">
                <h4 class="font-bold">Database Systems</h4>
                <p class="text-muted text-sm">Dr. Jefri Siahaan</p>
              </div>
              <div class="room text-sm bg-gray-light px-2 py-1 rounded">R. 204</div>
            </div>
            <div class="timeline-item">
              <div class="time">13:00 - 14:40</div>
              <div class="dot bg-orange"></div>
              <div class="details">
                <h4 class="font-bold">Discrete Mathematics</h4>
                <p class="text-muted text-sm">Dr. Lidia Manalu</p>
              </div>
              <div class="room text-sm bg-gray-light px-2 py-1 rounded">R. 201</div>
            </div>
            <div class="timeline-item">
              <div class="time">15:00 - 16:40</div>
              <div class="dot bg-purple"></div>
              <div class="details">
                <h4 class="font-bold">Cyberprenuership</h4>
                <p class="text-muted text-sm">Mr. Obaja Lumbanraja</p>
              </div>
              <div class="room text-sm bg-gray-light px-2 py-1 rounded">R. 302</div>
            </div>
          </div>
          <router-link to="/dashboard/murid/schedule" class="link-primary text-sm font-medium mt-4 inline-block">Lihat semua jadwal &rarr;</router-link>
        </div>
      </div>

      <!-- Kalender Akademik -->
      <div class="glass-panel card widget-kalender">
        <div class="card-header">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-secondary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <h3 class="font-bold text-lg m-0">Kalender Akademik</h3>
          </div>
        </div>
        <div class="card-body">
          <div class="calendar-mini text-center">
            <div class="flex justify-between items-center mb-4">
              <button class="btn-icon">&lt;</button>
              <h4 class="font-bold m-0">September 2025</h4>
              <button class="btn-icon">&gt;</button>
            </div>
            <div class="cal-grid">
              <span class="cal-day-header">Sen</span><span class="cal-day-header">Sel</span><span class="cal-day-header">Rab</span><span class="cal-day-header">Kam</span><span class="cal-day-header">Jum</span><span class="cal-day-header">Sab</span><span class="cal-day-header">Min</span>
              <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span>
              <span>8</span><span class="cal-active">9</span><span>10</span><span>11</span><span>12</span><span>13</span><span>14</span>
              <span>15</span><span>16</span><span>17</span><span>18</span><span>19</span><span>20</span><span>21</span>
              <span>22</span><span>23</span><span>24</span><span>25</span><span>26</span><span>27</span><span>28</span>
              <span>29</span><span>30</span>
            </div>
          </div>
          <div class="event-card mt-4 flex items-center gap-3">
            <div class="event-icon bg-blue-light text-blue"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
            <div class="flex-1">
              <h5 class="font-bold text-sm text-primary m-0">KRS Semester Ganjil 2025/2026</h5>
              <p class="text-xs text-muted m-0">Periode: 1 - 15 September 2025</p>
            </div>
            <span class="text-primary">&gt;</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ROW 3: Mata Kuliah Aktif, Nilai, Tagihan -->
    <div class="grid-row-3 mb-4">
      <div class="glass-panel card">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            <h3 class="font-bold text-lg m-0">Mata Kuliah Aktif</h3>
          </div>
          <router-link to="/dashboard/murid/academic" class="link-primary text-sm font-medium">Lihat semua &rarr;</router-link>
        </div>
        <div class="card-body p-0">
          <table class="table-clean w-full text-sm">
            <thead>
              <tr><th class="text-left text-muted font-medium pb-2 pt-4 px-4">Kode</th><th class="text-left text-muted font-medium pb-2 pt-4 px-4">Mata Kuliah</th><th class="text-center text-muted font-medium pb-2 pt-4 px-4">SKS</th><th class="text-center text-muted font-medium pb-2 pt-4 px-4">Nilai</th></tr>
            </thead>
            <tbody>
              <tr><td class="px-4 py-3 text-muted">TI222316</td><td class="px-4 py-3 font-medium">Web Programming 1</td><td class="px-4 py-3 text-center">3</td><td class="px-4 py-3 text-center font-bold text-green">A-</td></tr>
              <tr><td class="px-4 py-3 text-muted">TI164740</td><td class="px-4 py-3 font-medium">Cyberprenuership</td><td class="px-4 py-3 text-center">3</td><td class="px-4 py-3 text-center font-bold text-green">A</td></tr>
              <tr><td class="px-4 py-3 text-muted">TI101203</td><td class="px-4 py-3 font-medium">Database Systems</td><td class="px-4 py-3 text-center">3</td><td class="px-4 py-3 text-center font-bold text-orange">B+</td></tr>
              <tr><td class="px-4 py-3 text-muted">TI121205</td><td class="px-4 py-3 font-medium">Discrete Mathematics</td><td class="px-4 py-3 text-center">3</td><td class="px-4 py-3 text-center font-bold text-green">A-</td></tr>
              <tr><td class="px-4 py-3 text-muted border-none">TI131207</td><td class="px-4 py-3 font-medium border-none">Computer Networks</td><td class="px-4 py-3 text-center border-none">3</td><td class="px-4 py-3 text-center border-none">-</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="glass-panel card">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            <h3 class="font-bold text-lg m-0">Nilai Terbaru</h3>
          </div>
          <router-link to="/dashboard/murid/academic" class="link-primary text-sm font-medium">Lihat semua &rarr;</router-link>
        </div>
        <div class="card-body p-0">
          <table class="table-clean w-full text-sm">
            <thead>
              <tr><th class="text-left text-muted font-medium pb-2 pt-4 px-4">Kode</th><th class="text-left text-muted font-medium pb-2 pt-4 px-4">Mata Kuliah</th><th class="text-center text-muted font-medium pb-2 pt-4 px-4">Nilai</th></tr>
            </thead>
            <tbody>
              <tr><td class="px-4 py-3 text-muted">TI222315</td><td class="px-4 py-3 font-medium">Web Programming 1</td><td class="px-4 py-3 text-center font-bold text-green">A-</td></tr>
              <tr><td class="px-4 py-3 text-muted">TI164739</td><td class="px-4 py-3 font-medium">Cyberprenuership</td><td class="px-4 py-3 text-center font-bold text-green">A</td></tr>
              <tr><td class="px-4 py-3 text-muted">TI101202</td><td class="px-4 py-3 font-medium">Database Systems</td><td class="px-4 py-3 text-center font-bold text-orange">B+</td></tr>
              <tr><td class="px-4 py-3 text-muted border-none">TI121204</td><td class="px-4 py-3 font-medium border-none">Discrete Mathematics</td><td class="px-4 py-3 text-center font-bold text-green border-none">A-</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="glass-panel card flex flex-col">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            <h3 class="font-bold text-lg m-0">Tagihan & Pembayaran</h3>
          </div>
        </div>
        <div class="card-body flex-1 flex flex-col">
          <div class="bg-gray-light rounded-lg p-4 mb-4 flex justify-between items-center mt-2">
            <div>
              <p class="text-xs text-muted mb-1">Total Tagihan</p>
              <h2 class="text-2xl font-bold m-0">Rp 3.750.000</h2>
            </div>
            <span class="badge-success px-3 py-1 rounded-full text-sm font-bold">Lunas</span>
          </div>
          
          <div class="flex-1">
            <p class="text-sm text-muted mb-3">Rincian</p>
            <div class="flex justify-between items-center text-sm mb-3">
              <span class="font-medium">SPP Semester Ganjil 2025/2026</span>
              <span>Rp 3.500.000</span>
            </div>
            <div class="flex justify-between items-center text-sm mb-4">
              <span class="font-medium">Biaya Lainnya</span>
              <span>Rp 250.000</span>
            </div>
          </div>
          
          <router-link to="/dashboard/murid/billing" class="btn btn-primary w-full flex justify-between items-center">
            <span>Lihat detail pembayaran</span>
            <span>&rarr;</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- ROW 4: Pengumuman, Akses Cepat, Profil -->
    <div class="grid-row-3">
      <div class="glass-panel card">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <h3 class="font-bold text-lg m-0">Pengumuman</h3>
          </div>
          <router-link to="/dashboard/murid/announcements" class="link-primary text-sm font-medium">Lihat semua &rarr;</router-link>
        </div>
        <div class="card-body">
          <div class="announcement-list">
            <div class="announcement-item">
              <div class="date-box text-center">
                <span class="font-bold text-lg">05</span>
                <span class="text-xs text-muted">Sep</span>
              </div>
              <div class="announcement-details">
                <h5 class="font-bold text-sm m-0">Jadwal Ujian Tengah Semester (UTS)</h5>
                <p class="text-xs text-muted m-0 mt-1 truncate-2">Pelaksanaan UTS akan dimulai pada 20 Oktober 2025...</p>
              </div>
            </div>
            <div class="announcement-item">
              <div class="date-box text-center">
                <span class="font-bold text-lg">02</span>
                <span class="text-xs text-muted">Sep</span>
              </div>
              <div class="announcement-details">
                <div class="flex justify-between items-center">
                  <h5 class="font-bold text-sm m-0">Pengisian KRS Semester Ganjil 2025/2026</h5>
                  <span class="text-muted text-xs">&gt;</span>
                </div>
                <p class="text-xs text-muted m-0 mt-1 truncate-2">Pastikan melakukan pengisian KRS sesuai jadwal yang telah ditentukan.</p>
              </div>
            </div>
            <div class="announcement-item border-none pb-0">
              <div class="date-box text-center">
                <span class="font-bold text-lg text-muted">28</span>
                <span class="text-xs text-muted">Agu</span>
              </div>
              <div class="announcement-details">
                <h5 class="font-bold text-sm m-0">Beasiswa Prestasi UNAI</h5>
                <p class="text-xs text-muted m-0 mt-1 truncate-2">Pendaftaran beasiswa prestasi tahun 2025 telah dibuka.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="glass-panel card">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <h3 class="font-bold text-lg m-0">Akses Cepat</h3>
          </div>
        </div>
        <div class="card-body p-4">
          <div class="quick-access-grid">
            <router-link to="/dashboard/murid/krs" class="quick-btn">
              <div class="icon-box bg-blue-light text-blue"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">KRS</span>
                <span class="text-xs text-muted">Pengisian KRS online</span>
              </div>
            </router-link>
            <router-link to="/dashboard/murid/schedule" class="quick-btn">
              <div class="icon-box bg-green-light text-green"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">Jadwal Kuliah</span>
                <span class="text-xs text-muted">Lihat jadwal dan ruangan</span>
              </div>
            </router-link>
            <router-link to="/dashboard/murid/academic" class="quick-btn">
              <div class="icon-box bg-orange-light text-orange"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">Nilai</span>
                <span class="text-xs text-muted">Lihat nilai perkuliahan</span>
              </div>
            </router-link>
            <router-link to="/dashboard/murid/transcript" class="quick-btn">
              <div class="icon-box bg-purple-light text-purple"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">Transkrip</span>
                <span class="text-xs text-muted">Unduh transkrip nilai</span>
              </div>
            </router-link>
            <router-link to="/dashboard/murid/billing" class="quick-btn">
              <div class="icon-box bg-red-light text-red"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">Tagihan</span>
                <span class="text-xs text-muted">Cek tagihan & pembayaran</span>
              </div>
            </router-link>
            <router-link to="/dashboard/murid/profile" class="quick-btn">
              <div class="icon-box bg-teal-light text-teal"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
              <div class="quick-info">
                <span class="font-bold text-sm text-dark block">Bimbingan Akademik</span>
                <span class="text-xs text-muted">Jadwal & konsultasi</span>
              </div>
            </router-link>
          </div>
        </div>
      </div>

      <div class="glass-panel card flex flex-col">
        <div class="card-header border-b">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <h3 class="font-bold text-lg m-0">Informasi Profil</h3>
          </div>
          <router-link to="/dashboard/murid/profile" class="link-primary text-sm font-medium">Lihat detail &rarr;</router-link>
        </div>
        <div class="card-body flex-1 flex flex-col justify-between">
          <div class="flex items-center gap-4 mb-4 mt-2">
            <img src="https://ui-avatars.com/api/?name=Rio+Saputra&background=1e293b&color=fff" alt="User Avatar" class="avatar-large" />
            <div>
              <h4 class="font-bold m-0 text-lg">Rio Saputra Situmorang</h4>
              <p class="text-sm text-muted m-0">NIM 2023101234</p>
            </div>
          </div>
          
          <div class="profile-details text-sm">
            <div class="flex justify-between mb-2 pb-2 border-b border-dashed">
              <span class="text-muted">Program Studi</span>
              <span class="font-medium text-right">Teknik Informatika</span>
            </div>
            <div class="flex justify-between mb-2 pb-2 border-b border-dashed">
              <span class="text-muted">Fakultas</span>
              <span class="font-medium text-right">Teknologi Informasi</span>
            </div>
            <div class="flex justify-between mb-2 pb-2 border-b border-dashed">
              <span class="text-muted">Angkatan</span>
              <span class="font-medium text-right">2023</span>
            </div>
            <div class="flex justify-between mb-4">
              <span class="text-muted">Status</span>
              <span class="font-medium text-right text-primary">Aktif</span>
            </div>
          </div>
          
          <router-link to="/dashboard/murid/profile" class="btn btn-outline-primary w-full text-center py-2 mt-auto">Lihat Profil Lengkap &rarr;</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../../../stores/auth';

const authStore = useAuthStore();
const profile = ref({
  name: 'Rio'
});

onMounted(() => {
  if (authStore.user) {
    const firstName = authStore.user.name.split(' ')[0];
    profile.value.name = firstName;
  }
});
</script>

<style scoped>
.dashboard-wrapper { max-width: 1300px; margin: 0 auto; padding-bottom: 2rem; }

/* Grid Layouts */
.grid-row-1 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
.grid-row-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
.grid-row-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }

@media (max-width: 1024px) {
  .grid-row-1, .grid-row-2, .grid-row-3 { grid-template-columns: 1fr; }
}

/* Greeting Section */
.greeting-title { font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.5rem 0; }
.greeting-subtitle { color: var(--text-secondary); font-size: 0.95rem; margin: 0; }

.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.stat-card { padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-radius: 12px; }
.stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.stat-info { display: flex; flex-direction: column; }
.stat-label { font-size: 0.75rem; color: var(--text-secondary); font-weight: 500; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1; }

@media (max-width: 640px) {
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}

/* Banner Section */
.university-banner { 
  height: 100%; min-height: 160px; border-radius: 12px; overflow: hidden; position: relative;
  background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');
  background-size: cover; background-position: center;
}
.banner-content {
  position: absolute; bottom: 0; left: 0; right: 0; padding: 1.5rem;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
  color: white;
}
.banner-content h2 { margin: 0 0 0.25rem 0; font-size: 1.25rem; font-weight: 700; }
.banner-content p { margin: 0; font-size: 0.8rem; opacity: 0.9; }

/* Cards Genereal */
.card { padding: 1.5rem; border-radius: 16px; background: white; }
.card-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; }
.border-b { border-bottom: 1px solid var(--surface-border); }
.border-none { border: none !important; }
.card-body { padding-top: 1rem; }

/* Timeline List */
.timeline-list { display: flex; flex-direction: column; gap: 1rem; }
.timeline-item { display: grid; grid-template-columns: 100px 12px 1fr auto; align-items: center; gap: 1rem; }
.timeline-item .time { font-size: 0.875rem; color: var(--text-secondary); text-align: right; }
.timeline-item .dot { width: 10px; height: 10px; border-radius: 50%; justify-self: center; }
.timeline-item h4 { margin: 0 0 0.2rem 0; font-size: 0.95rem; color: var(--text-primary); }

/* Calendar Mini */
.btn-icon { background: none; border: none; cursor: pointer; color: var(--text-secondary); font-weight: bold; font-size: 1.2rem; }
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; text-align: center; font-size: 0.85rem; }
.cal-day-header { font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; font-size: 0.75rem; }
.cal-grid span:not(.cal-day-header) { padding: 0.4rem; border-radius: 50%; color: var(--text-primary); }
.cal-active { background: var(--primary); color: white !important; font-weight: bold; }
.event-card { border: 1px solid var(--primary); border-radius: 8px; padding: 0.75rem; background: rgba(30, 58, 138, 0.02); }
.event-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }

/* Tables */
.table-clean th { border-bottom: 1px solid var(--surface-border); }
.table-clean td { border-bottom: 1px solid #f1f5f9; }
.table-clean tbody tr:hover { background-color: #f8fafc; }

/* Announcement */
.announcement-list { display: flex; flex-direction: column; gap: 1rem; }
.announcement-item { display: flex; gap: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 1rem; }
.date-box { min-width: 40px; display: flex; flex-direction: column; align-items: center; }
.truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }

/* Quick Access */
.quick-access-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.quick-btn { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--surface-border); border-radius: 8px; text-decoration: none; transition: all 0.2s; }
.quick-btn:hover { border-color: var(--primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.icon-box { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.quick-info span { line-height: 1.2; }

/* Profile */
.avatar-large { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; }
.border-dashed { border-bottom-style: dashed; }
.btn-outline-primary { display: block; border: 1px solid var(--primary); background: transparent; color: var(--primary); border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 0.875rem; transition: background 0.2s; }
.btn-outline-primary:hover { background: #eff6ff; }

/* Utilities */
.flex { display: flex; }
.flex-col { flex-direction: column; }
.items-center { align-items: center; }
.justify-between { justify-content: space-between; }
.flex-1 { flex: 1; }
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 0.75rem; }
.gap-4 { gap: 1rem; }
.mb-1 { margin-bottom: 0.25rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-3 { margin-bottom: 0.75rem; }
.mb-4 { margin-bottom: 1rem; }
.mt-1 { margin-top: 0.25rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 1rem; }
.p-0 { padding: 0; }
.p-4 { padding: 1rem; }
.px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
.py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.px-4 { padding-left: 1rem; padding-right: 1rem; }
.py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
.pb-2 { padding-bottom: 0.5rem; }
.pt-4 { padding-top: 1rem; }
.pb-0 { padding-bottom: 0 !important; }
.m-0 { margin: 0; }
.mt-auto { margin-top: auto; }
.w-full { width: 100%; }
.text-left { text-align: left; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.font-medium { font-weight: 500; }
.font-bold { font-weight: 700; }
.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }
.text-lg { font-size: 1.125rem; }
.text-2xl { font-size: 1.5rem; }
.block { display: block; }
.inline-block { display: inline-block; }
.rounded { border-radius: 4px; }
.rounded-lg { border-radius: 8px; }
.rounded-full { border-radius: 9999px; }

/* Colors */
.text-primary { color: var(--primary); }
.text-secondary { color: var(--text-secondary); }
.text-muted { color: #94a3b8; }
.text-dark { color: var(--text-primary); }
.text-blue { color: #2563eb; }
.text-green { color: #16a34a; }
.text-orange { color: #ea580c; }
.text-purple { color: #9333ea; }
.text-red { color: #dc2626; }
.text-teal { color: #0d9488; }

.bg-blue { background-color: #2563eb; }
.bg-green { background-color: #16a34a; }
.bg-orange { background-color: #ea580c; }
.bg-purple { background-color: #9333ea; }
.bg-gray-light { background-color: #f1f5f9; }

.bg-blue-light { background-color: #eff6ff; }
.bg-green-light { background-color: #f0fdf4; }
.bg-orange-light { background-color: #fff7ed; }
.bg-purple-light { background-color: #faf5ff; }
.bg-red-light { background-color: #fef2f2; }
.bg-teal-light { background-color: #f0fdfa; }
.badge-success { background-color: var(--success-bg); color: var(--success); }

.link-primary { color: var(--primary); text-decoration: none; }
.link-primary:hover { text-decoration: underline; }
</style>

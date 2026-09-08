<template>
  <div class="calendar-page fade-in">
    <div class="page-header">
      <h1 class="page-title">Kalender Akademik</h1>
      <p class="page-subtitle">Jadwal kegiatan akademik, ujian, dan hari libur.</p>
    </div>

    <div class="calendar-container glass-panel">
      <!-- Simple Dummy Calendar Implementation -->
      <div class="calendar-header">
        <button class="btn btn-icon">&lt;</button>
        <h2>Oktober 2026</h2>
        <button class="btn btn-icon">&gt;</button>
      </div>

      <div class="calendar-grid">
        <!-- Days of week -->
        <div class="weekday">Sen</div>
        <div class="weekday">Sel</div>
        <div class="weekday">Rab</div>
        <div class="weekday">Kam</div>
        <div class="weekday">Jum</div>
        <div class="weekday">Sab</div>
        <div class="weekday">Min</div>

        <!-- Empty slots for alignment -->
        <div class="day empty"></div>
        <div class="day empty"></div>
        <div class="day empty"></div>
        
        <!-- Days -->
        <div v-for="day in 31" :key="day" class="day" :class="{ 'has-event': hasEvent(day), 'today': day === 8 }">
          <span class="date-num">{{ day }}</span>
          <div v-if="hasEvent(day)" class="event-indicator" :class="getEventType(day)"></div>
        </div>
      </div>

      <div class="events-list mt-4">
        <h3>Kegiatan Bulan Ini</h3>
        <ul class="event-items">
          <li v-for="event in events" :key="event.id" class="event-item">
            <div class="event-date">
              <span class="day">{{ event.date.split(' ')[0] }}</span>
              <span class="month">{{ event.date.split(' ')[1] }}</span>
            </div>
            <div class="event-details">
              <h4>{{ event.title }}</h4>
              <span class="event-type" :class="event.type.toLowerCase()">{{ event.type }}</span>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const events = ref([
  { id: 1, date: '15 Okt', day: 15, title: 'Mulai Ujian Tengah Semester (UTS)', type: 'Ujian' },
  { id: 2, date: '22 Okt', day: 22, title: 'Selesai Ujian Tengah Semester (UTS)', type: 'Ujian' },
  { id: 3, date: '28 Okt', day: 28, title: 'Hari Sumpah Pemuda', type: 'Peringatan' },
]);

const hasEvent = (day) => {
  return events.value.some(e => e.day === day);
};

const getEventType = (day) => {
  const event = events.value.find(e => e.day === day);
  return event ? event.type.toLowerCase() : '';
};
</script>

<style scoped>
.calendar-page {
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

.calendar-container {
  padding: 2rem;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.calendar-header h2 {
  font-size: 1.5rem;
  color: white;
  margin: 0;
}

.btn-icon {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: white;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 1.25rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-icon:hover {
  background: rgba(255, 255, 255, 0.2);
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.weekday {
  text-align: center;
  font-weight: 600;
  color: var(--text-secondary);
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--surface-border);
}

.day {
  aspect-ratio: 1;
  background: rgba(255, 255, 255, 0.03);
  border-radius: 8px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  transition: all 0.2s;
}

.day:not(.empty):hover {
  background: rgba(255, 255, 255, 0.08);
  cursor: pointer;
}

.day.today {
  border: 2px solid var(--primary);
  background: rgba(16, 185, 129, 0.1);
}

.date-num {
  font-size: 1rem;
  color: white;
}

.event-indicator {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  position: absolute;
  bottom: 8px;
}

.event-indicator.ujian { background: #ef4444; }
.event-indicator.libur { background: #10b981; }
.event-indicator.peringatan { background: #3b82f6; }

.events-list {
  border-top: 1px solid var(--surface-border);
  padding-top: 2rem;
}

.events-list h3 {
  font-size: 1.25rem;
  color: white;
  margin-bottom: 1.5rem;
}

.event-items {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.event-item {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.03);
  border-radius: 8px;
}

.event-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  border-left: 3px solid var(--primary);
}

.event-date .day {
  font-size: 1.25rem;
  font-weight: 700;
  color: white;
  background: transparent;
  aspect-ratio: auto;
}

.event-date .month {
  font-size: 0.75rem;
  color: var(--text-secondary);
}

.event-details h4 {
  font-size: 1rem;
  color: white;
  margin: 0 0 0.5rem 0;
}

.event-type {
  font-size: 0.75rem;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
}

.event-type.ujian { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }
.event-type.libur { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }
.event-type.peringatan { background: rgba(59, 130, 246, 0.2); color: #93c5fd; }
</style>

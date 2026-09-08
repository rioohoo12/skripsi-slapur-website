<template>
  <div class="dashboard-layout">
    <Sidebar :is-collapsed="isSidebarCollapsed" @toggle="toggleSidebar" :class="{ 'mobile-open': isMobileSidebarOpen }" />
    
    <div class="main-wrapper">
      <Navbar @toggle-mobile-sidebar="toggleMobileSidebar" />
      <main class="main-content">
        <router-view></router-view>
      </main>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div v-if="isMobileSidebarOpen" class="sidebar-overlay" @click="toggleMobileSidebar"></div>

    <!-- Chatbot Widget is rendered here so it's globally available across dashboards -->
    <ChatbotWidget />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '../components/Sidebar.vue';
import Navbar from '../components/Navbar.vue';
import ChatbotWidget from '../components/ChatbotWidget.vue';

const isSidebarCollapsed = ref(false);
const isMobileSidebarOpen = ref(false);

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const toggleMobileSidebar = () => {
  isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background-color: var(--bg-main);
  position: relative;
}

.main-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 1rem 2rem 1rem 1rem;
  overflow: hidden;
  height: 100vh;
}

.main-content {
  flex: 1;
  overflow-y: auto;
  padding-top: 1rem;
}

.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 90;
  display: none;
}

@media (max-width: 768px) {
  .main-wrapper {
    padding: 1rem;
  }
  .sidebar-overlay {
    display: block;
  }
}
</style>

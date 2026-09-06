<template>
  <div class="auth-layout">
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    
    <div class="auth-container">
      <router-view></router-view>
    </div>
  </div>
</template>

<style scoped>
.auth-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.auth-container {
  width: 100%;
  max-width: 420px;
  padding: 2rem;
  z-index: 10;
}
</style>

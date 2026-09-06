<template>
  <div class="chatbot-container">
    <!-- Chat Button -->
    <button 
      class="chatbot-toggle" 
      @click="toggleChat"
      :class="{ 'is-open': isOpen }"
      aria-label="Toggle Chatbot"
    >
      <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>

    <!-- Chat Window -->
    <transition name="slide-fade">
      <div v-if="isOpen" class="chat-window">
        <!-- Header -->
        <div class="chat-header">
          <div class="header-info">
            <div class="avatar">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
            </div>
            <div>
              <h3>Asisten Virtual</h3>
              <p class="status">Online 24/7</p>
            </div>
          </div>
        </div>

        <!-- Messages Area -->
        <div class="chat-messages" ref="messagesContainer">
          <div v-if="messages.length === 0" class="empty-state">
            <p>👋 Halo! Ada yang bisa saya bantu hari ini?</p>
          </div>
          
          <div 
            v-for="(msg, index) in messages" 
            :key="index"
            class="message-wrapper"
            :class="msg.role === 'user' ? 'is-user' : 'is-bot'"
          >
            <div class="message-bubble" v-html="formatMessage(msg.content)"></div>
            <span class="message-time">{{ formatTime(msg.timestamp) }}</span>
          </div>

          <!-- Typing Indicator -->
          <div v-if="isLoading" class="message-wrapper is-bot">
            <div class="message-bubble typing-indicator">
              <span></span><span></span><span></span>
            </div>
          </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
          <form @submit.prevent="sendMessage" class="input-form">
            <input 
              type="text" 
              v-model="newMessage" 
              placeholder="Tulis pesan..." 
              :disabled="isLoading"
              autocomplete="off"
            />
            <button type="submit" :disabled="!newMessage.trim() || isLoading" class="send-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import api from '../services/api';

const isOpen = ref(false);
const newMessage = ref('');
const messages = ref([]);
const isLoading = ref(false);
const messagesContainer = ref(null);

const toggleChat = () => {
  isOpen.value = !isOpen.value;
  if (isOpen.value && messages.value.length === 0) {
    // Add initial greeting if empty
    messages.value.push({
      role: 'bot',
      content: 'Halo! Saya adalah Asisten Virtual. Ada yang bisa saya bantu terkait pendaftaran, pembayaran, atau jadwal?',
      timestamp: new Date()
    });
  }
  scrollToBottom();
};

const formatTime = (date) => {
  if (!date) return '';
  const d = new Date(date);
  return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

// Very basic markdown formatting for bold and line breaks
const formatMessage = (text) => {
  let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
  formatted = formatted.replace(/\n/g, '<br>');
  return formatted;
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};

const sendMessage = async () => {
  const text = newMessage.value.trim();
  if (!text) return;

  // Add user message
  messages.value.push({
    role: 'user',
    content: text,
    timestamp: new Date()
  });

  newMessage.value = '';
  isLoading.value = true;
  scrollToBottom();

  try {
    const response = await api.post('/chatbot/message', { message: text });
    
    messages.value.push({
      role: 'bot',
      content: response.data.response || "Maaf, saya tidak mengerti maksud Anda.",
      timestamp: new Date()
    });
  } catch (error) {
    console.error('Chatbot error:', error);
    messages.value.push({
      role: 'bot',
      content: "Maaf, terjadi kesalahan pada server. Silakan coba beberapa saat lagi.",
      timestamp: new Date()
    });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.chatbot-container {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  z-index: 9999;
  font-family: 'Inter', sans-serif;
}

.chatbot-toggle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4F46E5, #3B82F6);
  color: white;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.chatbot-toggle:hover {
  transform: scale(1.1);
  box-shadow: 0 15px 35px rgba(79, 70, 229, 0.5);
}

.chatbot-toggle.is-open {
  background: #1F2937;
  transform: scale(0.9);
}

.chat-window {
  position: absolute;
  bottom: 80px;
  right: 0;
  width: 380px;
  height: 600px;
  max-height: calc(100vh - 120px);
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 24px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0,0,0,0.05);
  border: 1px solid rgba(255, 255, 255, 0.2);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.chat-header {
  background: linear-gradient(135deg, #4F46E5, #3B82F6);
  padding: 1.5rem;
  color: white;
}

.header-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.header-info h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.header-info .status {
  margin: 0;
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.header-info .status::before {
  content: '';
  display: block;
  width: 8px;
  height: 8px;
  background-color: #10B981;
  border-radius: 50%;
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  background-color: #F9FAFB;
}

.empty-state {
  text-align: center;
  color: #6B7280;
  margin: auto;
  font-size: 0.95rem;
}

.message-wrapper {
  display: flex;
  flex-direction: column;
  max-width: 85%;
}

.message-wrapper.is-user {
  align-self: flex-end;
  align-items: flex-end;
}

.message-wrapper.is-bot {
  align-self: flex-start;
  align-items: flex-start;
}

.message-bubble {
  padding: 0.8rem 1.2rem;
  border-radius: 18px;
  font-size: 0.95rem;
  line-height: 1.5;
  box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}

.is-user .message-bubble {
  background: linear-gradient(135deg, #4F46E5, #6366F1);
  color: white;
  border-bottom-right-radius: 4px;
}

.is-bot .message-bubble {
  background: white;
  color: #1F2937;
  border-bottom-left-radius: 4px;
  border: 1px solid #F3F4F6;
}

.message-time {
  font-size: 0.7rem;
  color: #9CA3AF;
  margin-top: 0.4rem;
  padding: 0 0.5rem;
}

.typing-indicator {
  display: flex;
  gap: 4px;
  padding: 1rem 1.2rem;
  align-items: center;
}

.typing-indicator span {
  width: 6px;
  height: 6px;
  background-color: #9CA3AF;
  border-radius: 50%;
  animation: bounce 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

.chat-input-area {
  padding: 1.2rem;
  background: white;
  border-top: 1px solid #F3F4F6;
}

.input-form {
  display: flex;
  gap: 0.8rem;
  align-items: center;
  background: #F9FAFB;
  padding: 0.4rem;
  border-radius: 24px;
  border: 1px solid #E5E7EB;
}

.input-form input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.8rem 1rem;
  font-size: 0.95rem;
  color: #1F2937;
  outline: none;
}

.input-form input::placeholder {
  color: #9CA3AF;
}

.send-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #4F46E5;
  color: white;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.send-btn:hover:not(:disabled) {
  background: #4338CA;
}

.send-btn:disabled {
  background: #D1D5DB;
  cursor: not-allowed;
}

/* Transitions */
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}

/* Scrollbar styling */
.chat-messages::-webkit-scrollbar {
  width: 6px;
}
.chat-messages::-webkit-scrollbar-track {
  background: transparent;
}
.chat-messages::-webkit-scrollbar-thumb {
  background-color: #E5E7EB;
  border-radius: 20px;
}
</style>

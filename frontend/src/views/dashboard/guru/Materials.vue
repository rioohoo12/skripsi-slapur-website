<template>
  <div class="materials-page">
    <div class="glass-panel header-card">
      <h2>Distribusi Materi Pembelajaran</h2>
      <p>Unggah dan bagikan dokumen (PDF/Word/PPT) ke seluruh murid.</p>
    </div>

    <div class="glass-panel upload-card">
      <h3>Unggah Materi Baru</h3>
      
      <form @submit.prevent="uploadMaterial" class="upload-form">
        <div class="form-group">
          <label>Judul Materi</label>
          <input type="text" v-model="form.title" class="form-input" placeholder="Bab 1: Logika Matematika" required />
        </div>
        
        <div class="form-group">
          <label>File Dokumen (Max 10MB)</label>
          <div class="file-drop-area">
            <input type="file" @change="handleFileChange" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip" class="file-input" required />
            <div class="file-msg">
              <span v-if="form.file">{{ form.file.name }}</span>
              <span v-else>Pilih file atau tarik ke sini</span>
            </div>
          </div>
        </div>
        
        <div v-if="error" class="alert alert-error mb-1">{{ error }}</div>
        
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading">Mengunggah...</span>
          <span v-else>Unggah Sekarang</span>
        </button>
      </form>
    </div>

    <div class="glass-panel list-card">
      <h3>Daftar Materi yang Dibagikan</h3>
      
      <div v-if="fetching" class="text-center p-4">Memuat data...</div>
      
      <div v-else-if="materials.length === 0" class="text-center p-4">
        Belum ada materi yang diunggah.
      </div>
      
      <div v-else class="materials-grid">
        <div v-for="mat in materials" :key="mat.id" class="material-item">
          <div class="mat-icon">📄</div>
          <div class="mat-info">
            <h4>{{ mat.title }}</h4>
            <span class="mat-date">{{ mat.created_at }}</span>
          </div>
          <div class="mat-actions">
            <a :href="mat.file_url" target="_blank" class="btn-icon view" title="Buka/Unduh">👁️</a>
            <button @click="deleteMaterial(mat.id)" class="btn-icon delete" title="Hapus">🗑️</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const materials = ref([]);
const fetching = ref(false);
const loading = ref(false);
const error = ref('');
const form = ref({
  title: '',
  file: null
});

const fetchMaterials = async () => {
  fetching.value = true;
  try {
    const res = await axios.get('/teacher/materials');
    materials.value = res.data;
  } catch (err) {
    console.error(err);
  } finally {
    fetching.value = false;
  }
};

const handleFileChange = (e) => {
  if (e.target.files.length > 0) {
    form.value.file = e.target.files[0];
  }
};

const uploadMaterial = async () => {
  if (!form.value.file) {
    error.value = 'Harap pilih file terlebih dahulu.';
    return;
  }
  
  loading.value = true;
  error.value = '';
  
  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('file', form.value.file);
  
  try {
    const res = await axios.post('/teacher/materials', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    
    // Reset form
    form.value.title = '';
    form.value.file = null;
    document.querySelector('.file-input').value = '';
    
    // Add to list
    materials.value.unshift(res.data.material);
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengunggah materi.';
  } finally {
    loading.value = false;
  }
};

const deleteMaterial = async (id) => {
  if (!confirm('Anda yakin ingin menghapus materi ini?')) return;
  
  try {
    await axios.delete(`/teacher/materials/${id}`);
    materials.value = materials.value.filter(m => m.id !== id);
  } catch (err) {
    alert('Gagal menghapus materi.');
  }
};

onMounted(() => {
  fetchMaterials();
});
</script>

<style scoped>
.materials-page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.header-card, .upload-card, .list-card {
  padding: 1.5rem;
}

h2, h3 {
  color: white;
  margin-bottom: 0.5rem;
}

.upload-card h3, .list-card h3 {
  font-size: 1.2rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.upload-form {
  max-width: 600px;
}

.file-drop-area {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  border: 2px dashed rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.05);
  transition: all 0.3s ease;
  cursor: pointer;
}

.file-drop-area:hover {
  border-color: var(--primary);
  background: rgba(var(--primary-rgb), 0.1);
}

.file-input {
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  cursor: pointer;
}

.file-msg {
  color: var(--text-secondary);
  font-weight: 500;
}

.materials-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
}

.material-item {
  display: flex;
  align-items: center;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.mat-icon {
  font-size: 2rem;
  margin-right: 1rem;
}

.mat-info {
  flex-grow: 1;
}

.mat-info h4 {
  color: white;
  margin: 0 0 0.25rem 0;
  font-size: 1rem;
}

.mat-date {
  font-size: 0.8rem;
  color: var(--text-secondary);
}

.mat-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 8px;
  transition: background 0.2s;
}

.btn-icon:hover {
  background: rgba(255, 255, 255, 0.1);
}

.text-center { text-align: center; }
.p-4 { padding: 1.5rem; }
.mb-1 { margin-bottom: 1rem; }
</style>

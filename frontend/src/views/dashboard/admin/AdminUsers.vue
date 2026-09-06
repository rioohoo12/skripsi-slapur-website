<template>
  <div class="admin-users-page">
    <div class="glass-panel header-card">
      <h2>Manajemen Pengguna (User)</h2>
      <p>Kendalikan seluruh akses akun, perbarui hak akses (role), atau nonaktifkan akun bermasalah.</p>
    </div>

    <div class="glass-panel table-card">
      <div class="table-responsive">
        <table class="glass-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Pengguna</th>
              <th>Email</th>
              <th>Role Saat Ini</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="users.length === 0">
              <td colspan="6" class="text-center">Memuat data...</td>
            </tr>
            <tr v-for="(user, index) in users" :key="user.id">
              <td>#{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>
                <select v-model="users[index].selectedRole" class="form-input select-role" @change="changeRole(user, index)">
                  <option value="Murid">Murid</option>
                  <option value="Guru">Guru</option>
                  <option value="Staff Administrasi">Staff Administrasi</option>
                  <option value="Admin">Admin</option>
                </select>
              </td>
              <td>
                <span class="badge" :class="user.is_active ? 'success' : 'failed'">
                  {{ user.is_active ? 'Aktif' : 'Dinonaktifkan' }}
                </span>
              </td>
              <td>
                <button 
                  class="btn-sm" 
                  :class="user.is_active ? 'btn-danger' : 'btn-success'"
                  @click="toggleStatus(user, index)"
                >
                  {{ user.is_active ? 'Banned' : 'Unbanned' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const users = ref([]);

const fetchUsers = async () => {
  try {
    const res = await axios.get('/admin/users');
    users.value = res.data.map(u => ({
      ...u,
      selectedRole: u.roles && u.roles.length > 0 ? u.roles[0].name : ''
    }));
  } catch (err) {
    console.error('Failed to fetch users', err);
  }
};

const changeRole = async (user, index) => {
  if (!confirm(`Ubah role ${user.name} menjadi ${user.selectedRole}? (Catatan: Ini tidak membuat profil otomatis seperti di register)`)) {
    // Revert visually
    users.value[index].selectedRole = user.roles[0].name;
    return;
  }
  
  try {
    const res = await axios.put(`/admin/users/${user.id}/role`, { role: user.selectedRole });
    // Update local roles array
    users.value[index].roles = res.data.user.roles;
    alert(res.data.message);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengubah role.');
    // Revert visually
    users.value[index].selectedRole = user.roles[0].name;
  }
};

const toggleStatus = async (user, index) => {
  const action = user.is_active ? 'menonaktifkan' : 'mengaktifkan kembali';
  if (!confirm(`Anda yakin ingin ${action} akun ${user.name}?`)) return;
  
  try {
    const res = await axios.put(`/admin/users/${user.id}/toggle-status`);
    users.value[index].is_active = res.data.user.is_active;
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengubah status akun.');
  }
};

onMounted(() => {
  fetchUsers();
});
</script>

<style scoped>
.admin-users-page {
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

.table-card {
  padding: 1.5rem;
}

.table-responsive {
  overflow-x: auto;
}

.glass-table {
  width: 100%;
  border-collapse: collapse;
}

.glass-table th, .glass-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-table th {
  color: var(--primary);
  font-weight: 600;
}

.text-center { text-align: center; }

.select-role {
  min-width: 150px;
  padding: 0.25rem 0.5rem;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
}
.badge.success { background: rgba(16, 185, 129, 0.2); color: #34d399; }
.badge.failed { background: rgba(239, 68, 68, 0.2); color: #f87171; }

.btn-sm {
  padding: 0.4rem 0.75rem;
  font-size: 0.85rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  transition: opacity 0.2s;
}
.btn-sm:hover { opacity: 0.8; }

.btn-danger { background: #ef4444; color: white; }
.btn-success { background: #10b981; color: white; }
</style>

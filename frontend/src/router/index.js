import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

import AuthLayout from '../layouts/AuthLayout.vue';
import Login from '../views/auth/Login.vue';
import Register from '../views/auth/Register.vue';
import ForgotPassword from '../views/auth/ForgotPassword.vue';
import DashboardLayout from '../layouts/DashboardLayout.vue';
import Dashboard from '../views/dashboard/Dashboard.vue';

import GuruHome from '../views/dashboard/guru/DashboardHome.vue';
import GuruAttendance from '../views/dashboard/guru/Attendance.vue';
import GuruGrades from '../views/dashboard/guru/Grades.vue';
import GuruMaterials from '../views/dashboard/guru/Materials.vue';

const routes = [
  {
    path: '/',
    redirect: '/login/murid'
  },
  {
    path: '/login',
    component: AuthLayout,
    redirect: '/login/murid',
    children: [
      { path: 'murid', component: Login, meta: { role: 'Murid' } },
      { path: 'guru', component: Login, meta: { role: 'Guru' } },
      { path: 'staff', component: Login, meta: { role: 'Staff Administrasi' } },
      { path: 'admin', component: Login, meta: { role: 'Admin' } },
    ]
  },
  {
    path: '/register',
    component: AuthLayout,
    children: [
      { path: 'murid', component: Register, meta: { role: 'Murid' } },
      { path: 'guru', component: Register, meta: { role: 'Guru' } },
      { path: 'staff', component: Register, meta: { role: 'Staff Administrasi' } },
      { path: 'admin', component: Register, meta: { role: 'Admin' } },
    ]
  },
  {
    path: '/forgot-password',
    component: AuthLayout,
    children: [
      { path: 'murid', component: ForgotPassword, meta: { role: 'Murid' } },
      { path: 'guru', component: ForgotPassword, meta: { role: 'Guru' } },
      { path: 'staff', component: ForgotPassword, meta: { role: 'Staff Administrasi' } },
      { path: 'admin', component: ForgotPassword, meta: { role: 'Admin' } },
    ]
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', redirect: to => {
          let user = null;
          try {
            user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user'));
          } catch(e) {}
          const role = user?.role?.toLowerCase() || 'murid';
          return `/dashboard/${role === 'staff administrasi' ? 'staff' : role}`;
      }},
      {
        path: 'murid',
        component: Dashboard,
        meta: { role: 'Murid' },
        children: [
          { path: '', component: () => import('../views/dashboard/murid/DashboardHome.vue') },
          { path: 'krs', name: 'KRS', component: () => import('../views/dashboard/Placeholder.vue') },
          { path: 'profile', name: 'Profil Saya', component: () => import('../views/dashboard/murid/Profile.vue') },
          { path: 'schedule', name: 'Jadwal Kuliah', component: () => import('../views/dashboard/murid/Schedule.vue') },
          { path: 'calendar', name: 'Kalender Akademik', component: () => import('../views/dashboard/murid/AcademicCalendar.vue') },
          { path: 'transcript', name: 'Transkrip Nilai', component: () => import('../views/dashboard/murid/Transcript.vue') },
          { path: 'billing', name: 'Tagihan & Pembayaran', component: () => import('../views/dashboard/murid/Billing.vue') },
          { path: 'announcements', name: 'Pengumuman', component: () => import('../views/dashboard/murid/Announcements.vue') },
          { path: 'settings', name: 'Pengaturan', component: () => import('../views/dashboard/Placeholder.vue') },
          { path: 'registration', component: () => import('../views/dashboard/murid/RegistrationStatus.vue') },
          { path: 'registration/application', component: () => import('../views/dashboard/murid/RegistrationApplication.vue') },
          { path: 'finance', component: () => import('../views/dashboard/murid/Finance.vue') },
          { path: 'cafeteria', component: () => import('../views/dashboard/murid/Cafeteria.vue') },
          { path: 'rooms', component: () => import('../views/dashboard/murid/RoomSelection.vue') },
          { path: 'academic', name: 'Nilai', component: () => import('../views/dashboard/murid/Academic.vue') },
          { path: 'qr', component: () => import('../views/dashboard/murid/QrProfile.vue') }
        ]
      },
      {
        path: 'guru',
        meta: { role: 'Guru' },
        children: [
          { path: '', component: GuruHome },
          { path: 'attendance', component: GuruAttendance },
          { path: 'grades', component: GuruGrades },
          { path: 'materials', component: GuruMaterials },
        ]
      },
      {
        path: 'staff',
        meta: { role: 'Staff Administrasi' },
        children: [
          { path: '', component: () => import('../views/dashboard/staff/DashboardHome.vue') },
          { path: 'students', component: () => import('../views/dashboard/staff/Students.vue') },
          { path: 'payments', component: () => import('../views/dashboard/staff/Payments.vue') },
          { path: 'rooms', component: () => import('../views/dashboard/staff/Rooms.vue') },
          { path: 'dining', component: () => import('../views/dashboard/staff/Dining.vue') },
        ]
      },
      {
        path: 'admin',
        meta: { role: 'Admin' },
        children: [
          { path: '', component: () => import('../views/dashboard/admin/DashboardHome.vue') },
          { path: 'users', component: () => import('../views/dashboard/admin/AdminUsers.vue') },
        ]
      },
    ]
  },
  // Catch all 404
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Route Guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      // Not logged in -> redirect to login (default to murid)
      return next({ path: '/login/murid' });
    }
    
    // Check if the user is accessing the correct role dashboard
    if (to.meta.role && authStore.userRole !== to.meta.role) {
      // Wrong role -> redirect to their own dashboard
      let routeRole = authStore.userRole === 'Staff Administrasi' ? 'staff' : authStore.userRole.toLowerCase();
      return next({ path: `/dashboard/${routeRole}` });
    }
  } else {
    // If accessing login page while already logged in
    if (authStore.isAuthenticated && to.path.startsWith('/login')) {
      let routeRole = authStore.userRole === 'Staff Administrasi' ? 'staff' : authStore.userRole.toLowerCase();
      return next({ path: `/dashboard/${routeRole}` });
    }
  }
  
  next();
});

export default router;

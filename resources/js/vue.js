import { createApp } from 'vue';
import DashboardStats from './components/DashboardStats.vue';

const app = createApp({
    components: {
        DashboardStats,
    },
    data() {
        return {
            stats: [
                { title: 'Total Karyawan', value: '124', icon: 'fa-users', colorClass: 'bg-blue-100', textClass: 'text-blue-600' },
                { title: 'Absensi Hari Ini', value: '118', icon: 'fa-calendar-check', colorClass: 'bg-green-100', textClass: 'text-green-600' },
                { title: 'Izin Pending', value: '5', icon: 'fa-file-medical', colorClass: 'bg-yellow-100', textClass: 'text-yellow-600' },
                { title: 'Tugas Pending', value: '23', icon: 'fa-tasks', colorClass: 'bg-purple-100', textClass: 'text-purple-600' },
            ],
        };
    },
});

if (document.getElementById('vue-root')) {
    app.mount('#vue-root');
}

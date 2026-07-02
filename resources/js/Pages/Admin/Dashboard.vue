<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import { Bar, Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale, LinearScale, PointElement,
  LineElement, BarElement, Title, Tooltip, Legend
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend);

const props = defineProps<{
  stats: Record<string, any>;
  consultas_por_estado: Array<any>;
  ingresos_mensuales: Array<any>;
  mascotas_por_especie: Array<any>;
  consultas_recientes: Array<any>;
  eventos_recientes: Array<any>;
}>();

const isDarkMode = ref(document.documentElement.classList.contains('dark'));

const updateTheme = () => {
  isDarkMode.value = document.documentElement.classList.contains('dark');
};

onMounted(() => {
  const observer = new MutationObserver(updateTheme);
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
  onUnmounted(() => observer.disconnect());
});

const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: true, labels: { color: isDarkMode.value ? '#e5e7eb' : '#1f2937' } }
  },
  scales: {
    y: { ticks: { color: isDarkMode.value ? '#e5e7eb' : '#1f2937' }, grid: { color: isDarkMode.value ? '#374151' : '#e5e7eb' } },
    x: { ticks: { color: isDarkMode.value ? '#e5e7eb' : '#1f2937' }, grid: { color: isDarkMode.value ? '#374151' : '#e5e7eb' } }
  }
}));

const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

const estadoChartData = computed(() => {
  const data = props.consultas_por_estado.reduce((acc, item) => {
    acc.labels.push(item.estado);
    acc.data.push(item.total);
    return acc;
  }, { labels: [] as string[], data: [] as number[] });
  return {
    labels: data.labels,
    datasets: [{
      label: 'Consultas por Estado',
      data: data.data,
      backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(234, 179, 8, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(239, 68, 68, 0.8)'],
    }],
  };
});

const ingresosChartData = computed(() => {
  const labels = props.ingresos_mensuales.map(i => `${mesesNombres[i.mes - 1]} ${i.anio}`);
  const data = props.ingresos_mensuales.map(i => parseFloat(i.total));
  return {
    labels,
    datasets: [{
      label: 'Ingresos (Bs.)',
      data,
      borderColor: 'rgb(34, 197, 94)',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      tension: 0.4,
    }],
  };
});

const especieChartData = computed(() => ({
  labels: props.mascotas_por_especie.map(e => e.especie || 'Sin especificar'),
  datasets: [{
    label: 'Mascotas por Especie',
    data: props.mascotas_por_especie.map(e => e.total),
    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(168, 85, 247, 0.8)', 'rgba(34, 197, 94, 0.8)'],
  }],
}));

const formatDate = (date: string) => new Date(date).toLocaleDateString('es-ES');
</script>

<template>
  <AuthenticatedLayout>
    <template #header>Dashboard Clínica Veterinaria</template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <StatCard title="Mascotas Registradas" :value="stats.total_mascotas" color="blue">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 12.3a1 1 0 0 1-1.4 0l-1.3-1.3-1.3 1.3a1 1 0 0 1-1.4-1.4l1.3-1.3-1.3-1.3a1 1 0 1 1 1.4-1.4l1.3 1.3 1.3-1.3a1 1 0 0 1 1.4 1.4l-1.3 1.3 1.3 1.3a1 1 0 0 1 0 1.4z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Consultas Realizadas" :value="stats.total_consultas" color="green">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Clientes Registrados" :value="stats.total_clientes" color="purple">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Veterinarios" :value="stats.total_veterinarios" color="indigo">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Ingresos (Bs.)" :value="stats.total_ingresos" color="green">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Pendiente Cobro" :value="stats.ingresos_pendientes" color="orange">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </template>
        </StatCard>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Consultas por Estado</h3>
          <div class="h-64"><Bar :data="estadoChartData" :options="chartOptions" /></div>
        </Card>

        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Ingresos Mensuales</h3>
          <div class="h-64"><Line :data="ingresosChartData" :options="chartOptions" /></div>
        </Card>
      </div>

      <Card>
        <h3 class="text-lg font-semibold text-theme-primary mb-4">Mascotas por Especie</h3>
        <div class="h-64"><Bar :data="especieChartData" :options="chartOptions" /></div>
      </Card>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-theme-primary">Consultas Recientes</h3>
            <Link :href="route('admin.consultas.index')">
              <Button variant="ghost" size="sm">Ver todas</Button>
            </Link>
          </div>
          <div class="divide-y divide-theme">
            <div v-for="c in consultas_recientes" :key="c.id" class="py-3 flex justify-between items-center">
              <div>
                <p class="font-medium text-theme-primary">{{ c.mascota?.nombre }}</p>
                <p class="text-xs text-theme-secondary">{{ c.motivo_consulta?.slice(0, 50) }} - {{ formatDate(c.fecha_consulta) }}</p>
              </div>
              <Badge :variant="c.estado === 'activo' ? 'success' : 'warning'">{{ c.estado }}</Badge>
            </div>
          </div>
        </Card>

        <Card>
          <h3 class="text-lg font-semibold text-theme-primary mb-4">Accesos Directos</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Link :href="route('admin.consultas.index')" class="bg-blue-500 rounded-xl p-4 text-white hover:shadow-lg transition-all">
              <h4 class="font-bold">Consultas</h4>
              <p class="text-blue-100 text-sm">Gestionar consultas</p>
            </Link>
            <Link :href="route('admin.mascotas.index')" class="bg-green-500 rounded-xl p-4 text-white hover:shadow-lg transition-all">
              <h4 class="font-bold">Mascotas</h4>
              <p class="text-green-100 text-sm">Ver mascotas</p>
            </Link>
            <Link :href="route('admin.historial.index')" class="bg-purple-500 rounded-xl p-4 text-white hover:shadow-lg transition-all">
              <h4 class="font-bold">Historial</h4>
              <p class="text-purple-100 text-sm">Historial clínico</p>
            </Link>
            <Link :href="route('admin.reportes.index')" class="bg-orange-500 rounded-xl p-4 text-white hover:shadow-lg transition-all">
              <h4 class="font-bold">Reportes</h4>
              <p class="text-orange-100 text-sm">Estadísticas</p>
            </Link>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

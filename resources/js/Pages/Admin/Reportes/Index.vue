<script setup lang="ts">
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps<{
  stats: {
    total_mascotas: number;
    total_consultas: number;
    total_clientes: number;
    total_veterinarios: number;
    total_ingresos: number;
    ingresos_pendientes: number;
  };
  consultas_por_mes: Array<{ mes: number; anio: number; total: number }>;
  servicios_populares: Array<{ id: number; nombre: string; total_usos: number }>;
  productos_vendidos: Array<{ id: number; nombre: string; total_vendido: number }>;
  consultas_por_vet: Array<{ id: number; nombre: string; apellido: string; total_consultas: number }>;
}>();

const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

const maxConsultas = computed(() => {
  if (props.consultas_por_mes.length === 0) return 1;
  return Math.max(...props.consultas_por_mes.map(c => c.total));
});

const formatMoney = (value: number): string => {
  return Number(value).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="page-header">
          <div>
            <h1 class="page-title">Reportes y Estadísticas</h1>
            <p class="page-subtitle">Resumen general del sistema</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <StatCard title="Total Mascotas" :value="stats.total_mascotas" color="blue">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
            </template>
          </StatCard>

          <StatCard title="Total Consultas" :value="stats.total_consultas" color="indigo">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </template>
          </StatCard>

          <StatCard title="Clientes Registrados" :value="stats.total_clientes" color="green">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </template>
          </StatCard>

          <StatCard title="Veterinarios" :value="stats.total_veterinarios" color="purple">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </template>
          </StatCard>

          <StatCard title="Ingresos (Bs.)" :value="formatMoney(stats.total_ingresos)" color="green">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </template>
          </StatCard>

          <StatCard title="Pendientes (Bs.)" :value="formatMoney(stats.ingresos_pendientes)" color="orange">
            <template #icon>
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </template>
          </StatCard>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-4">Consultas por Mes</h3>
            <div v-if="consultas_por_mes.length > 0" class="space-y-3">
              <div v-for="item in consultas_por_mes" :key="`${item.anio}-${item.mes}`" class="flex items-center gap-3">
                <span class="text-sm text-theme-secondary w-20 flex-shrink-0">
                  {{ mesesNombres[item.mes - 1] }} {{ item.anio }}
                </span>
                <div class="flex-1 bg-theme-secondary rounded-full h-5 overflow-hidden">
                  <div
                    class="h-full bg-blue-500 rounded-full transition-all duration-500"
                    :style="{ width: (item.total / maxConsultas) * 100 + '%' }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-theme-primary w-8 text-right">{{ item.total }}</span>
              </div>
            </div>
            <p v-else class="text-center text-theme-tertiary py-8">No hay datos disponibles</p>
          </Card>

          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-4">Servicios Populares</h3>
            <div v-if="servicios_populares.length > 0" class="space-y-3">
              <div
                v-for="(servicio, index) in servicios_populares"
                :key="servicio.id"
                class="flex items-center justify-between p-3 bg-theme-secondary rounded-lg"
              >
                <div class="flex items-center gap-3">
                  <span class="w-7 h-7 rounded-full bg-blue-500 text-white text-xs font-bold flex items-center justify-center">
                    {{ index + 1 }}
                  </span>
                  <span class="font-medium text-theme-primary">{{ servicio.nombre }}</span>
                </div>
                <Badge variant="info">{{ servicio.total_usos }} usos</Badge>
              </div>
            </div>
            <p v-else class="text-center text-theme-tertiary py-8">No hay datos disponibles</p>
          </Card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-4">Productos más Vendidos</h3>
            <div v-if="productos_vendidos.length > 0" class="space-y-3">
              <div
                v-for="(producto, index) in productos_vendidos"
                :key="producto.id"
                class="flex items-center justify-between p-3 bg-theme-secondary rounded-lg"
              >
                <div class="flex items-center gap-3">
                  <span class="w-7 h-7 rounded-full bg-green-500 text-white text-xs font-bold flex items-center justify-center">
                    {{ index + 1 }}
                  </span>
                  <span class="font-medium text-theme-primary">{{ producto.nombre }}</span>
                </div>
                <Badge variant="success">{{ producto.total_vendido }} vendidos</Badge>
              </div>
            </div>
            <p v-else class="text-center text-theme-tertiary py-8">No hay datos disponibles</p>
          </Card>

          <Card>
            <h3 class="text-lg font-semibold text-theme-primary mb-4">Consultas por Veterinario</h3>
            <div v-if="consultas_por_vet.length > 0" class="overflow-x-auto">
              <table class="table">
                <thead class="table-header">
                  <tr>
                    <th class="table-header-cell">Veterinario</th>
                    <th class="table-header-cell text-right">Consultas</th>
                  </tr>
                </thead>
                <tbody class="table-body">
                  <tr v-for="vet in consultas_por_vet" :key="vet.id" class="table-row">
                    <td class="table-cell font-medium">{{ vet.nombre }} {{ vet.apellido }}</td>
                    <td class="table-cell text-right">
                      <Badge variant="info">{{ vet.total_consultas }}</Badge>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p v-else class="text-center text-theme-tertiary py-8">No hay datos disponibles</p>
          </Card>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

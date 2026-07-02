<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Badge from '@/Components/UI/Badge.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps<{
  stats: {
    total_consultas: number;
    total_mascotas: number;
    consultas_hoy: number;
  };
  consultas_recientes: Array<{
    id: number;
    fecha_consulta: string;
    motivo_consulta: string;
    estado: string;
    mascota: { id: number; nombre: string };
  }>;
}>();

const getEstadoVariant = (estado: string): 'success' | 'warning' | 'error' | 'info' | 'neutral' => {
  if (estado === 'completada') return 'success';
  if (estado === 'pendiente') return 'warning';
  if (estado === 'cancelada') return 'error';
  return 'neutral';
};

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const formatDate = (dateStr: string): string => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>Dashboard del Veterinario</template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <StatCard title="Total Consultas" :value="stats.total_consultas" color="blue">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Mascotas Tratadas" :value="stats.total_mascotas" color="green">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Consultas Hoy" :value="stats.consultas_hoy" color="purple">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </template>
        </StatCard>
      </div>

      <Card :padding="false">
        <div class="px-6 py-4 border-b border-theme">
          <h3 class="text-lg font-semibold text-theme-primary">Consultas Recientes</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">ID</th>
                <th class="table-header-cell">Mascota</th>
                <th class="table-header-cell">Fecha</th>
                <th class="table-header-cell">Motivo</th>
                <th class="table-header-cell">Estado</th>
                <th class="table-header-cell text-right">Acción</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="c in consultas_recientes" :key="c.id" class="table-row">
                <td class="table-cell">{{ c.id }}</td>
                <td class="table-cell font-medium">{{ c.mascota?.nombre }}</td>
                <td class="table-cell-secondary">{{ formatDate(c.fecha_consulta) }}</td>
                <td class="table-cell-secondary">{{ c.motivo_consulta }}</td>
                <td class="table-cell">
                  <Badge :variant="getEstadoVariant(c.estado)">
                    {{ capitalizeFirst(c.estado) }}
                  </Badge>
                </td>
                <td class="table-cell text-right">
                  <Link :href="route('veterinario.consultas.show', c.id)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver detalle
                  </Link>
                </td>
              </tr>
              <tr v-if="consultas_recientes.length === 0">
                <td colspan="6" class="table-cell text-center text-theme-tertiary py-8">
                  No hay consultas recientes
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>

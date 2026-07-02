<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Mis Consultas</h1>
            <p class="page-subtitle">Consulta el historial de atenciones de tus mascotas</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Fecha</th>
                  <th class="table-header-cell">Mascota</th>
                  <th class="table-header-cell">Veterinario</th>
                  <th class="table-header-cell">Motivo</th>
                  <th class="table-header-cell">Estado</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="consulta in consultas.data" :key="consulta.id" class="table-row">
                  <td class="table-cell-secondary">{{ formatDate(consulta.fecha_consulta) }}</td>
                  <td class="table-cell font-medium">{{ consulta.mascota?.nombre }}</td>
                  <td class="table-cell">{{ consulta.veterinario?.nombre }} {{ consulta.veterinario?.apellido }}</td>
                  <td class="table-cell-secondary">{{ consulta.motivo_consulta }}</td>
                  <td class="table-cell">
                    <Badge :variant="getEstadoVariant(consulta.estado)">
                      {{ capitalizeFirst(consulta.estado) }}
                    </Badge>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="p-4 border-t border-theme">
            <Pagination :data="consultas" />
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  consultas: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
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
  return new Date(dateStr).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

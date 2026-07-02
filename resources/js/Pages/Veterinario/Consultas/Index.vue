<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  consultas: {
    data: Array<{
      id: number;
      fecha_consulta: string;
      motivo_consulta: string;
      estado: string;
      mascota: { id: number; nombre: string };
    }>;
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
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Mis Consultas</h1>
            <p class="page-subtitle">Consultas atendidas por usted</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">ID</th>
                  <th class="table-header-cell">Fecha</th>
                  <th class="table-header-cell">Mascota</th>
                  <th class="table-header-cell">Motivo</th>
                  <th class="table-header-cell">Estado</th>
                  <th class="table-header-cell text-right">Acción</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="consulta in consultas.data" :key="consulta.id" class="table-row">
                  <td class="table-cell">{{ consulta.id }}</td>
                  <td class="table-cell-secondary">{{ formatDate(consulta.fecha_consulta) }}</td>
                  <td class="table-cell font-medium">{{ consulta.mascota?.nombre }}</td>
                  <td class="table-cell-secondary">{{ consulta.motivo_consulta }}</td>
                  <td class="table-cell">
                    <Badge :variant="getEstadoVariant(consulta.estado)">
                      {{ capitalizeFirst(consulta.estado) }}
                    </Badge>
                  </td>
                  <td class="table-cell text-right">
                    <Link :href="route('veterinario.consultas.show', consulta.id)">
                      <Button variant="ghost" size="sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </Button>
                    </Link>
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

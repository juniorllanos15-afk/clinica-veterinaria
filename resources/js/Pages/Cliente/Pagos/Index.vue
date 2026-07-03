<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Mis Pagos</h1>
            <p class="page-subtitle">Historial de pagos de consultas</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">ID</th>
                  <th class="table-header-cell">Consulta #</th>
                  <th class="table-header-cell">Mascota</th>
                  <th class="table-header-cell">Total (Bs.)</th>
                  <th class="table-header-cell">Tipo Pago</th>
                  <th class="table-header-cell">Estado</th>
                  <th class="table-header-cell">Cuotas</th>
                  <th class="table-header-cell">Acción</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="pago in pagos.data" :key="pago.id" class="table-row">
                  <td class="table-cell">{{ pago.id }}</td>
                  <td class="table-cell font-medium">{{ pago.consulta_id }}</td>
                  <td class="table-cell">{{ pago.consulta?.mascota?.nombre }}</td>
                  <td class="table-cell-secondary">Bs. {{ Number(pago.total).toFixed(2) }}</td>
                  <td class="table-cell">
                    <Badge :variant="pago.tipo_pago === 'contado' ? 'success' : 'info'">
                      {{ capitalizeFirst(pago.tipo_pago) }}
                    </Badge>
                  </td>
                  <td class="table-cell">
                    <Badge :variant="pago.estado === 'completado' ? 'success' : pago.estado === 'pendiente' ? 'warning' : 'error'">
                      {{ capitalizeFirst(pago.estado) }}
                    </Badge>
                  </td>
                  <td class="table-cell-secondary">
                    <template v-if="pago.cuotas && pago.cuotas.length > 0">
                      {{ pagadas(pago.cuotas) }}/{{ pago.cuotas.length }} pagadas
                    </template>
                    <span v-else>-</span>
                  </td>
                  <td class="table-cell">
                    <Link :href="route('cliente.pagos.show', pago.id)">
                      <Button variant="primary" size="sm">Ver</Button>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="p-4 border-t border-theme">
            <Pagination :data="pagos" />
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
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  pagos: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const pagadas = (cuotas: any[]): number => {
  return cuotas.filter(c => c.estado === 'Pagado').length;
};
</script>

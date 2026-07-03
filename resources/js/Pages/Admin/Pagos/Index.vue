<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Gestión de Pagos</h1>
            <p class="page-subtitle">Administración de pagos del sistema</p>
          </div>
          <Link :href="route('admin.pagos.create')">
            <Button variant="primary" size="lg">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </Button>
          </Link>
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
                  <th class="table-header-cell">Cuotas</th>
                  <th class="table-header-cell">Estado</th>
                  <th class="table-header-cell text-right">Acciones</th>
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
                  <td class="table-cell-secondary">{{ pago.cantidad_cuotas || '-' }}</td>
                  <td class="table-cell">
                    <Badge :variant="pago.estado === 'completado' ? 'success' : pago.estado === 'pendiente' ? 'warning' : 'error'">
                      {{ capitalizeFirst(pago.estado) }}
                    </Badge>
                  </td>
                  <td class="table-cell">
                    <div class="flex items-center justify-end gap-2">
                      <Link :href="route('admin.pagos.show', pago.id)">
                        <Button variant="ghost" size="sm" title="Ver cuotas">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                        </Button>
                      </Link>
                      <Link :href="route('admin.pagos.edit', pago.id)">
                        <Button variant="ghost" size="sm">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                          </svg>
                        </Button>
                      </Link>
                      <Button variant="ghost" size="sm" @click="eliminar(pago.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                      </Button>
                    </div>
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
import { Link, router } from '@inertiajs/vue3';
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

const eliminar = (id: number) => {
  if (confirm('¿Está seguro de eliminar este pago?')) {
    router.delete(route('admin.pagos.destroy', id));
  }
};
</script>

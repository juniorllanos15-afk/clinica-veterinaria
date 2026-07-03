<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Consulta #{{ consulta.id }}</h1>
            <p class="page-subtitle">Detalle de la atención veterinaria</p>
          </div>
          <Link :href="route('cliente.consultas')">
            <Button variant="secondary">Volver</Button>
          </Link>
        </div>

        <Card class="mb-6">
          <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <p class="text-sm text-gray-500">Fecha</p>
                <p class="font-semibold">{{ consulta.fecha_consulta }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Mascota</p>
                <p class="font-semibold">{{ consulta.mascota?.nombre }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Veterinario</p>
                <p class="font-semibold">{{ consulta.veterinario?.nombre }} {{ consulta.veterinario?.apellido }}</p>
              </div>
            </div>
            <div>
              <p class="text-sm text-gray-500">Motivo</p>
              <p class="font-semibold">{{ consulta.motivo_consulta || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Diagnóstico</p>
              <p class="font-semibold">{{ consulta.diagnostico || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Tratamiento</p>
              <p class="font-semibold">{{ consulta.tratamiento || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Observaciones</p>
              <p class="font-semibold">{{ consulta.observaciones || '-' }}</p>
            </div>
          </div>
        </Card>

        <div v-if="consulta.servicios?.length" class="mb-6">
          <Card>
            <div class="p-6">
              <h2 class="text-lg font-semibold mb-4">Servicios</h2>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead class="table-header">
                    <tr>
                      <th class="table-header-cell">Servicio</th>
                      <th class="table-header-cell">Cantidad</th>
                      <th class="table-header-cell">Precio</th>
                      <th class="table-header-cell">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="table-body">
                    <tr v-for="s in consulta.servicios" :key="s.id" class="table-row">
                      <td class="table-cell font-medium">{{ s.nombre }}</td>
                      <td class="table-cell">{{ s.pivot.cantidad }}</td>
                      <td class="table-cell-secondary">Bs. {{ Number(s.pivot.precio).toFixed(2) }}</td>
                      <td class="table-cell-secondary">Bs. {{ Number(s.pivot.subtotal).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Card>
        </div>

        <div v-if="consulta.productos?.length" class="mb-6">
          <Card>
            <div class="p-6">
              <h2 class="text-lg font-semibold mb-4">Productos</h2>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead class="table-header">
                    <tr>
                      <th class="table-header-cell">Producto</th>
                      <th class="table-header-cell">Cantidad</th>
                      <th class="table-header-cell">Precio</th>
                      <th class="table-header-cell">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="table-body">
                    <tr v-for="p in consulta.productos" :key="p.id" class="table-row">
                      <td class="table-cell font-medium">{{ p.nombre }}</td>
                      <td class="table-cell">{{ p.pivot.cantidad }}</td>
                      <td class="table-cell-secondary">Bs. {{ Number(p.pivot.precio).toFixed(2) }}</td>
                      <td class="table-cell-secondary">Bs. {{ Number(p.pivot.subtotal).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Card>
        </div>

        <Card v-if="consulta.pago">
          <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Pago</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="font-semibold text-blue-700">Bs. {{ Number(consulta.pago.total).toFixed(2) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Tipo</p>
                <p class="font-semibold">{{ capitalizeFirst(consulta.pago.tipo_pago) }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Estado</p>
                <Badge :variant="consulta.pago.estado === 'Pagado' ? 'success' : 'warning'">
                  {{ consulta.pago.estado }}
                </Badge>
              </div>
            </div>
            <div v-if="consulta.pago.cuotas?.length" class="mt-4">
              <h3 class="font-semibold mb-2">Cuotas</h3>
              <div v-for="cuota in consulta.pago.cuotas" :key="cuota.id" class="border rounded p-3 mb-2">
                <div class="flex justify-between items-center">
                  <span class="font-medium">Cuota #{{ cuota.numero_cuota }}</span>
                  <span>Bs. {{ Number(cuota.monto).toFixed(2) }}</span>
                  <Badge :variant="cuota.estado === 'Pagado' ? 'success' : 'warning'">{{ cuota.estado }}</Badge>
                </div>
              </div>
            </div>
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

defineProps<{
  consulta: any;
}>();

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Historial Clínico</h1>
            <p class="page-subtitle">Consultas registradas para {{ mascota.nombre }}</p>
          </div>
          <Link :href="route('admin.historial.index')">
            <Button variant="secondary" size="lg">
              Volver al listado
            </Button>
          </Link>
        </div>

        <Card class="mb-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Nombre</p>
              <p class="font-semibold text-lg">{{ mascota.nombre }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Especie / Raza</p>
              <p class="font-semibold">{{ mascota.especie }} — {{ mascota.raza }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Edad</p>
              <p class="font-semibold">{{ mascota.edad ?? '—' }} años</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Dueño</p>
              <p class="font-semibold">{{ mascota.dueno?.nombre }} {{ mascota.dueno?.apellido }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Total Consultas</p>
              <p class="font-semibold">{{ mascota.consultas.length }}</p>
            </div>
          </div>
        </Card>

        <div v-if="mascota.consultas.length === 0" class="text-center py-12 text-gray-500">
          Esta mascota no tiene consultas registradas.
        </div>

        <div v-for="consulta in mascota.consultas" :key="consulta.id" class="mb-6">
          <Card>
            <div class="flex items-start justify-between mb-4">
              <div>
                <h2 class="text-lg font-bold">Consulta #{{ consulta.id }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(consulta.fecha_consulta) }} — {{ consulta.veterinario?.nombre }} {{ consulta.veterinario?.apellido }}
                </p>
              </div>
              <Badge :variant="getEstadoVariant(consulta.estado)">
                {{ capitalizeFirst(consulta.estado) }}
              </Badge>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Motivo</p>
                <p>{{ consulta.motivo_consulta || '—' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Diagnóstico</p>
                <p>{{ consulta.diagnostico || '—' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tratamiento</p>
                <p>{{ consulta.tratamiento || '—' }}</p>
              </div>
            </div>

            <div v-if="consulta.servicios.length > 0" class="mb-4">
              <h3 class="font-semibold mb-2">Servicios Realizados</h3>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead class="table-header">
                    <tr>
                      <th class="table-header-cell">Servicio</th>
                      <th class="table-header-cell text-right">Cantidad</th>
                      <th class="table-header-cell text-right">Precio</th>
                      <th class="table-header-cell text-right">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="table-body">
                    <tr v-for="servicio in consulta.servicios" :key="servicio.id" class="table-row">
                      <td class="table-cell">{{ servicio.nombre }}</td>
                      <td class="table-cell text-right">{{ servicio.pivot.cantidad }}</td>
                      <td class="table-cell text-right">${{ formatNumber(servicio.pivot.precio) }}</td>
                      <td class="table-cell text-right font-medium">${{ formatNumber(servicio.pivot.subtotal) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div v-if="consulta.productos.length > 0" class="mb-4">
              <h3 class="font-semibold mb-2">Productos Usados</h3>
              <div class="overflow-x-auto">
                <table class="table">
                  <thead class="table-header">
                    <tr>
                      <th class="table-header-cell">Producto</th>
                      <th class="table-header-cell text-right">Cantidad</th>
                      <th class="table-header-cell text-right">Precio</th>
                      <th class="table-header-cell text-right">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="table-body">
                    <tr v-for="producto in consulta.productos" :key="producto.id" class="table-row">
                      <td class="table-cell">{{ producto.nombre }}</td>
                      <td class="table-cell text-right">{{ producto.pivot.cantidad }}</td>
                      <td class="table-cell text-right">${{ formatNumber(producto.pivot.precio) }}</td>
                      <td class="table-cell text-right font-medium">${{ formatNumber(producto.pivot.subtotal) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-theme">
              <div>
                <Badge v-if="consulta.pago" :variant="getPagoEstadoVariant(consulta.pago.estado)">
                  Pago: {{ capitalizeFirst(consulta.pago.estado) }}
                </Badge>
                <Badge v-else variant="neutral">
                  Sin pago registrado
                </Badge>
              </div>
              <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total de la consulta</p>
                <p class="text-xl font-bold">${{ formatNumber(calcularTotal(consulta)) }}</p>
              </div>
            </div>
          </Card>
        </div>
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
  mascota: any;
}>();

const formatDate = (dateStr: string): string => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
};

const formatNumber = (value: number | string): string => {
  const num = typeof value === 'string' ? parseFloat(value) : value;
  return num.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const getEstadoVariant = (estado: string): 'success' | 'warning' | 'error' | 'info' | 'neutral' => {
  if (estado === 'completada') return 'success';
  if (estado === 'pendiente') return 'warning';
  if (estado === 'cancelada') return 'error';
  return 'neutral';
};

const getPagoEstadoVariant = (estado: string): 'success' | 'warning' | 'error' | 'info' | 'neutral' => {
  if (estado === 'Pagado' || estado === 'Completado') return 'success';
  if (estado === 'Pendiente') return 'warning';
  if (estado === 'Cancelado' || estado === 'Vencido') return 'error';
  return 'neutral';
};

const calcularTotal = (consulta: any): number => {
  const totalServicios = consulta.servicios?.reduce((sum: number, s: any) => sum + Number(s.pivot.subtotal), 0) || 0;
  const totalProductos = consulta.productos?.reduce((sum: number, p: any) => sum + Number(p.pivot.subtotal), 0) || 0;
  return totalServicios + totalProductos;
};
</script>

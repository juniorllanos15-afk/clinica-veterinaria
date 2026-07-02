<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps<{
  consulta: {
    id: number;
    fecha_consulta: string;
    motivo_consulta: string;
    diagnostico: string;
    tratamiento: string;
    observaciones: string;
    estado: string;
    mascota: {
      id: number;
      nombre: string;
      especie: string;
      raza: string;
      sexo: string;
      color: string;
      fecha_nacimiento: string;
      dueno: { id: number; nombre: string; apellido: string; telefono: string };
    };
    servicios: Array<{
      id: number;
      nombre: string;
      pivot: { cantidad: number; precio: number; subtotal: number };
    }>;
    productos: Array<{
      id: number;
      nombre: string;
      pivot: { cantidad: number; precio: number; subtotal: number };
    }>;
    pago: {
      id: number;
      tipo_pago: string;
      cantidad_cuotas: number;
      total: number;
      fecha_pago: string;
      estado: string;
      cuotas: Array<{
        id: number;
        numero_cuota: number;
        monto: number;
        fecha_vencimiento: string;
        fecha_pago: string;
        estado: string;
      }>;
    } | null;
  };
}>();

const getEstadoVariant = (estado: string): 'success' | 'warning' | 'error' | 'info' | 'neutral' => {
  if (estado === 'completada' || estado === 'Pagado') return 'success';
  if (estado === 'pendiente' || estado === 'Pendiente') return 'warning';
  if (estado === 'cancelada' || estado === 'Vencido') return 'error';
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

const formatCurrency = (value: number): string => {
  return 'Bs. ' + Number(value).toFixed(2);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="page-header">
          <div>
            <h1 class="page-title">Consulta #{{ consulta.id }}</h1>
            <p class="page-subtitle">{{ formatDate(consulta.fecha_consulta) }}</p>
          </div>
          <Link :href="route('veterinario.consultas')">
            <Button variant="secondary" size="lg">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
              </svg>
              Volver
            </Button>
          </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="lg:col-span-2 space-y-6">
            <Card>
              <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                  <h2 class="text-lg font-semibold text-theme-primary">Información de la Consulta</h2>
                  <Badge :variant="getEstadoVariant(consulta.estado)">
                    {{ capitalizeFirst(consulta.estado) }}
                  </Badge>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm text-theme-tertiary">Motivo</p>
                    <p class="text-theme-primary">{{ consulta.motivo_consulta || 'No especificado' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Diagnóstico</p>
                    <p class="text-theme-primary">{{ consulta.diagnostico || 'No especificado' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Tratamiento</p>
                    <p class="text-theme-primary">{{ consulta.tratamiento || 'No especificado' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Observaciones</p>
                    <p class="text-theme-primary">{{ consulta.observaciones || 'Ninguna' }}</p>
                  </div>
                </div>
              </div>
            </Card>

            <Card v-if="consulta.servicios && consulta.servicios.length > 0">
              <div class="p-6">
                <h2 class="text-lg font-semibold text-theme-primary mb-4">Servicios Realizados</h2>
                <div class="overflow-x-auto">
                  <table class="table">
                    <thead class="table-header">
                      <tr>
                        <th class="table-header-cell">Servicio</th>
                        <th class="table-header-cell text-right">Cantidad</th>
                        <th class="table-header-cell text-right">Precio Unit.</th>
                        <th class="table-header-cell text-right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="table-body">
                      <tr v-for="s in consulta.servicios" :key="s.id" class="table-row">
                        <td class="table-cell">{{ s.nombre }}</td>
                        <td class="table-cell text-right">{{ s.pivot.cantidad }}</td>
                        <td class="table-cell text-right">{{ formatCurrency(s.pivot.precio) }}</td>
                        <td class="table-cell text-right font-medium">{{ formatCurrency(s.pivot.subtotal) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </Card>

            <Card v-if="consulta.productos && consulta.productos.length > 0">
              <div class="p-6">
                <h2 class="text-lg font-semibold text-theme-primary mb-4">Productos Recetados</h2>
                <div class="overflow-x-auto">
                  <table class="table">
                    <thead class="table-header">
                      <tr>
                        <th class="table-header-cell">Producto</th>
                        <th class="table-header-cell text-right">Cantidad</th>
                        <th class="table-header-cell text-right">Precio Unit.</th>
                        <th class="table-header-cell text-right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="table-body">
                      <tr v-for="p in consulta.productos" :key="p.id" class="table-row">
                        <td class="table-cell">{{ p.nombre }}</td>
                        <td class="table-cell text-right">{{ p.pivot.cantidad }}</td>
                        <td class="table-cell text-right">{{ formatCurrency(p.pivot.precio) }}</td>
                        <td class="table-cell text-right font-medium">{{ formatCurrency(p.pivot.subtotal) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </Card>
          </div>

          <div class="space-y-6">
            <Card>
              <div class="p-6">
                <h2 class="text-lg font-semibold text-theme-primary mb-4">Mascota</h2>
                <div class="space-y-3">
                  <div>
                    <p class="text-sm text-theme-tertiary">Nombre</p>
                    <p class="text-theme-primary font-medium">{{ consulta.mascota?.nombre }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Especie</p>
                    <p class="text-theme-primary">{{ consulta.mascota?.especie || 'No especificada' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Raza</p>
                    <p class="text-theme-primary">{{ consulta.mascota?.raza || 'No especificada' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Sexo</p>
                    <p class="text-theme-primary">{{ consulta.mascota?.sexo || 'No especificado' }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Color</p>
                    <p class="text-theme-primary">{{ consulta.mascota?.color || 'No especificado' }}</p>
                  </div>
                </div>
              </div>
            </Card>

            <Card>
              <div class="p-6">
                <h2 class="text-lg font-semibold text-theme-primary mb-4">Dueño</h2>
                <div class="space-y-3">
                  <div>
                    <p class="text-sm text-theme-tertiary">Nombre</p>
                    <p class="text-theme-primary font-medium">{{ consulta.mascota?.dueno?.nombre }} {{ consulta.mascota?.dueno?.apellido }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Teléfono</p>
                    <p class="text-theme-primary">{{ consulta.mascota?.dueno?.telefono || 'No registrado' }}</p>
                  </div>
                </div>
              </div>
            </Card>

            <Card v-if="consulta.pago">
              <div class="p-6">
                <h2 class="text-lg font-semibold text-theme-primary mb-4">Pago</h2>
                <div class="space-y-3">
                  <div>
                    <p class="text-sm text-theme-tertiary">Tipo</p>
                    <p class="text-theme-primary">{{ consulta.pago.tipo_pago }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Total</p>
                    <p class="text-theme-primary font-medium">{{ formatCurrency(consulta.pago.total) }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-theme-tertiary">Estado</p>
                    <Badge :variant="getEstadoVariant(consulta.pago.estado)">
                      {{ capitalizeFirst(consulta.pago.estado) }}
                    </Badge>
                  </div>

                  <div v-if="consulta.pago.cuotas && consulta.pago.cuotas.length > 0">
                    <p class="text-sm text-theme-tertiary mb-2">Cuotas</p>
                    <div v-for="cuota in consulta.pago.cuotas" :key="cuota.id" class="flex items-center justify-between py-1 text-sm">
                      <span class="text-theme-secondary">Cuota #{{ cuota.numero_cuota }}</span>
                      <div class="flex items-center gap-2">
                        <span class="text-theme-primary">{{ formatCurrency(cuota.monto) }}</span>
                        <Badge :variant="getEstadoVariant(cuota.estado)" class="text-xs">{{ capitalizeFirst(cuota.estado) }}</Badge>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </Card>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

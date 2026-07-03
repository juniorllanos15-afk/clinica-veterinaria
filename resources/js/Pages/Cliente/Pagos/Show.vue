<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Pago #{{ pago.id }}</h1>
            <p class="page-subtitle">Detalle del pago y cuotas</p>
          </div>
          <Link :href="route('cliente.pagos')">
            <Button variant="secondary">Volver</Button>
          </Link>
        </div>

        <Card class="mb-6">
          <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <p class="text-sm text-gray-500">Consulta</p>
              <p class="font-semibold">#{{ pago.consulta?.id }} - {{ pago.consulta?.mascota?.nombre }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Total</p>
              <p class="font-semibold text-blue-700">Bs. {{ Number(pago.total).toFixed(2) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Tipo / Estado</p>
              <p class="font-semibold">{{ capitalizeFirst(pago.tipo_pago) }} - {{ pago.estado }}</p>
            </div>
          </div>
        </Card>

        <Card>
          <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Cuotas</h2>
            <div v-if="pago.cuotas?.length">
              <div v-for="cuota in pago.cuotas" :key="cuota.id" class="border rounded-lg p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                  <div>
                    <p class="text-sm text-gray-500">Cuota #{{ cuota.numero_cuota }}</p>
                    <p class="font-semibold">Bs. {{ Number(cuota.monto).toFixed(2) }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Vencimiento</p>
                    <p class="font-semibold">{{ cuota.fecha_vencimiento }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Estado</p>
                    <Badge :variant="cuota.estado === 'Pagado' ? 'success' : 'warning'">
                      {{ cuota.estado }}
                    </Badge>
                  </div>
                  <div v-if="cuota.qr_base64" class="text-center">
                    <div class="bg-white inline-block p-2 rounded-xl shadow-lg">
                      <img :src="'data:image/png;base64,' + cuota.qr_base64" alt="QR Cuota" class="w-36 h-36" />
                    </div>
                    <p v-if="cuota.payment_number" class="text-xs text-gray-500 mt-1">Nro: {{ cuota.payment_number }}</p>
                  </div>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-500">Sin cuotas registradas</p>
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
  pago: any;
}>();

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};
</script>

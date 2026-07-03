<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Pago #{{ pago.id }}</h1>
            <p class="page-subtitle">Detalle del pago y cuotas</p>
          </div>
          <Link :href="route('admin.pagos.index')">
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
              <p class="font-semibold">{{ pago.tipo_pago }} - {{ pago.estado }}</p>
            </div>
          </div>
        </Card>

        <Card>
          <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Cuotas</h2>
            <div v-for="cuota in pago.cuotas" :key="cuota.id" class="border rounded-lg p-4 mb-4">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
                  <p class="font-semibold" :class="cuota.estado === 'pagado' ? 'text-green-600' : 'text-yellow-600'">
                    {{ cuota.estado }}
                  </p>
                </div>
                <div class="flex gap-2 items-end">
                  <Input
                    :model-value="montosPago[cuota.id] ?? cuota.monto"
                    type="number"
                    step="0.01"
                    :label="'Monto a pagar'"
                    :disabled="cuota.estado === 'pagado'"
                    @update:model-value="(val: any) => { montosPago[cuota.id] = Number(val) || 0; }"
                  />
                  <Button variant="primary" size="sm" @click="generarQR(cuota)" :disabled="generando === cuota.id || cuota.estado === 'pagado'">
                    {{ cuota.estado === 'pagado' ? 'Pagada' : (generando === cuota.id ? '...' : 'QR') }}
                  </Button>
                </div>
              </div>
              <div v-if="cuotasQR[cuota.id] || cuota.qr_base64" class="mt-4 pt-4 border-t text-center">
                <div class="bg-white inline-block p-4 rounded-xl shadow-lg mb-3">
                  <img :src="'data:image/png;base64,' + (cuotasQR[cuota.id]?.qr ?? cuota.qr_base64)" alt="QR Cuota" class="w-48 h-48" />
                </div>
                <p class="text-sm font-semibold">Bs. {{ Number(cuotasQR[cuota.id]?.amount ?? cuota.monto).toFixed(2) }}</p>
                <p class="text-xs text-gray-500">Nro: {{ cuotasQR[cuota.id]?.payment_number ?? cuota.payment_number }}</p>
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';

const props = defineProps<{
  pago: any;
}>();

const montosPago = reactive<Record<number, number>>({});
const cuotasQR = reactive<Record<number, { qr: string; amount: number; payment_number: string }>>({});
const generando = ref<number | null>(null);

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const generarQR = async (cuota: any) => {
  generando.value = cuota.id;
  try {
    const amount = montosPago[cuota.id] ?? cuota.monto;
    const res = await fetch(route('admin.pagos.cuotas.qr', { pago: props.pago.id, cuota: cuota.id }), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      credentials: 'same-origin',
      body: JSON.stringify({ amount }),
    });
    if (!res.ok) {
      const text = await res.text();
      throw new Error(text.slice(0, 100));
    }
    const data = await res.json();
    if (data.success) {
      cuotasQR[cuota.id] = { qr: data.qr_base64, amount: data.amount, payment_number: data.payment_number };
    } else {
      alert(data.error || 'Error al generar QR');
    }
  } catch (e: any) {
    alert(e.message || 'Error de conexión');
  } finally {
    generando.value = null;
  }
};
</script>

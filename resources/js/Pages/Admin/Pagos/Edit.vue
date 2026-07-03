<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Editar Pago</h1>
            <p class="page-subtitle">Modifique los datos del pago</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Select
                  v-model="form.consulta_id"
                  label="Consulta"
                  :options="consultas.map(c => ({ value: c.id, label: '#' + c.id + ' - ' + (c.mascota?.nombre || 'Sin mascota') + ' (' + c.fecha_consulta + ')' }))"
                  :error="validationErrors.consulta_id || form.errors.consulta_id"
                />

                <Select
                  v-model="form.tipo_pago"
                  label="Tipo de Pago"
                  :options="[
                    { value: 'contado', label: 'Contado' },
                    { value: 'credito', label: 'Crédito' }
                  ]"
                  :error="validationErrors.tipo_pago || form.errors.tipo_pago"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input
                  v-model="form.total"
                  type="number"
                  step="0.01"
                  label="Total (Bs.)"
                  :error="validationErrors.total || form.errors.total"
                />

                <Input
                  v-model="form.cantidad_cuotas"
                  type="number"
                  label="Cantidad de Cuotas"
                  :error="validationErrors.cantidad_cuotas || form.errors.cantidad_cuotas"
                />

                <Input
                  v-model="form.fecha_pago"
                  type="date"
                  label="Fecha de Pago"
                  :error="validationErrors.fecha_pago || form.errors.fecha_pago"
                />
              </div>

              <div v-if="form.cuotas.length > 0" class="border-t pt-4">
                <h3 class="text-lg font-semibold mb-3">Cuotas</h3>
                <div v-for="(cuota, index) in form.cuotas" :key="index" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3 p-3 border rounded-lg">
                  <Input
                    :model-value="'Cuota #' + cuota.numero_cuota"
                    label="Número"
                    disabled
                  />
                  <Input
                    :model-value="cuota.monto.toFixed(2)"
                    type="number"
                    step="0.01"
                    label="Monto (Bs.)"
                    disabled
                  />
                  <Input
                    :model-value="cuota.fecha_vencimiento"
                    type="date"
                    :label="'Fecha Venc. Cuota #' + cuota.numero_cuota"
                    :error="validationErrors['cuotas.' + index + '.fecha_vencimiento'] || form.errors['cuotas.' + index + '.fecha_vencimiento']"
                    @update:model-value="(val: any) => { form.cuotas[index].fecha_vencimiento = val; }"
                  />
                </div>
              </div>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.pagos.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Pago' }}
              </Button>
            </div>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import { useValidation } from '@/composables/useValidation';

const props = defineProps<{
  pago: any;
  consultas: any[];
}>();

const form = useForm({
  consulta_id: props.pago.consulta_id,
  tipo_pago: props.pago.tipo_pago || 'contado',
  cantidad_cuotas: props.pago.cantidad_cuotas || '',
  total: props.pago.total,
  fecha_pago: props.pago.fecha_pago,
  cuotas: props.pago.cuotas?.map((c: any) => ({
    id: c.id,
    numero_cuota: c.numero_cuota,
    monto: Number(c.monto),
    fecha_vencimiento: c.fecha_vencimiento,
  })) || [],
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

watch(() => form.consulta_id, (val) => {
  if (!val) {
    form.total = '';
    return;
  }
  const consulta = props.consultas.find(c => c.id === Number(val));
  if (consulta) {
    const totalServicios = consulta.servicios?.reduce((sum: number, s: any) => sum + Number(s.pivot?.subtotal || 0), 0) || 0;
    const totalProductos = consulta.productos?.reduce((sum: number, p: any) => sum + Number(p.pivot?.subtotal || 0), 0) || 0;
    form.total = totalServicios + totalProductos;
  }
});

watch(() => form.cantidad_cuotas, (val) => {
  const count = Number(val) || 0;
  const montoPorCuota = count > 0 ? (Number(form.total) || 0) / count : 0;
  const cuotas = [];
  for (let i = 0; i < count; i++) {
    cuotas.push({
      id: form.cuotas[i]?.id || null,
      numero_cuota: i + 1,
      monto: montoPorCuota,
      fecha_vencimiento: form.cuotas[i]?.fecha_vencimiento || '',
    });
  }
  form.cuotas = cuotas;
});

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    consulta_id: { required: true },
    tipo_pago: { required: true },
    cantidad_cuotas: { numeric: true },
    total: { required: true, numeric: true },
    fecha_pago: { required: true, date: true },
  });

  if (!isValid) return;

  form.put(route('admin.pagos.update', props.pago.id), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

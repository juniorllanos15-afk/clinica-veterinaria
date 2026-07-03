<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Editar Consulta</h1>
            <p class="page-subtitle">Modifique los datos de la consulta</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input
                  v-model="form.fecha_consulta"
                  type="date"
                  label="Fecha de Consulta"
                  :error="validationErrors.fecha_consulta || form.errors.fecha_consulta"
                />

                <Select
                  v-model="form.mascota_id"
                  label="Mascota"
                  :options="mascotas.map(m => ({ value: m.id, label: m.nombre }))"
                  :error="validationErrors.mascota_id || form.errors.mascota_id"
                />

                <Select
                  v-model="form.veterinario_id"
                  label="Veterinario"
                  :options="veterinarios.map(v => ({ value: v.id, label: v.nombre + ' ' + v.apellido }))"
                  :error="validationErrors.veterinario_id || form.errors.veterinario_id"
                />
              </div>

              <Input
                v-model="form.motivo_consulta"
                label="Motivo de Consulta"
                :error="validationErrors.motivo_consulta || form.errors.motivo_consulta"
              />

              <Input
                v-model="form.diagnostico"
                label="Diagnóstico"
                :error="validationErrors.diagnostico || form.errors.diagnostico"
              />

              <Input
                v-model="form.tratamiento"
                label="Tratamiento"
                :error="validationErrors.tratamiento || form.errors.tratamiento"
              />

              <Input
                v-model="form.observaciones"
                label="Observaciones"
                :error="validationErrors.observaciones || form.errors.observaciones"
              />

              <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="text-lg font-semibold">Servicios</h3>
                  <Button type="button" variant="secondary" size="sm" @click="agregarServicio">
                    + Agregar Servicio
                  </Button>
                </div>
                <div v-for="(servicio, index) in form.servicios" :key="index" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-3 p-3 border rounded-lg">
                  <Select
                    :model-value="servicio.id"
                    :label="'Servicio ' + (index + 1)"
                    :options="servicios.map(s => ({ value: s.id, label: s.nombre + ' - $' + s.precio }))"
                    :error="validationErrors['servicios.' + index + '.id'] || form.errors['servicios.' + index + '.id']"
                    @update:model-value="(val: any) => { servicio.id = val; actualizarPrecioSubtotal(servicio, 'servicio'); }"
                  />
                  <Input
                    :model-value="servicio.cantidad"
                    type="number"
                    min="1"
                    label="Cantidad"
                    :error="validationErrors['servicios.' + index + '.cantidad'] || form.errors['servicios.' + index + '.cantidad']"
                    @update:model-value="(val: any) => { servicio.cantidad = Number(val) || 1; actualizarPrecioSubtotal(servicio, 'servicio'); }"
                  />
                  <Input
                    :model-value="servicio.precio"
                    type="number"
                    step="0.01"
                    label="Precio Unit."
                    readonly
                  />
                  <Input
                    :model-value="servicio.subtotal"
                    type="number"
                    step="0.01"
                    label="Subtotal"
                    readonly
                  />
                  <Button type="button" variant="danger" size="sm" @click="eliminarServicio(index)" class="mb-1">
                    Eliminar
                  </Button>
                </div>
                <div v-if="servicioTotal > 0" class="text-right font-semibold text-green-700">
                  Total Servicios: ${{ servicioTotal.toFixed(2) }}
                </div>
              </div>

              <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-3">
                  <h3 class="text-lg font-semibold">Productos</h3>
                  <Button type="button" variant="secondary" size="sm" @click="agregarProducto">
                    + Agregar Producto
                  </Button>
                </div>
                <div v-for="(producto, index) in form.productos" :key="index" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-3 p-3 border rounded-lg">
                  <Select
                    :model-value="producto.id"
                    :label="'Producto ' + (index + 1)"
                    :options="productos.map(p => ({ value: p.id, label: p.nombre + ' - $' + p.precio }))"
                    :error="validationErrors['productos.' + index + '.id'] || form.errors['productos.' + index + '.id']"
                    @update:model-value="(val: any) => { producto.id = val; actualizarPrecioSubtotal(producto, 'producto'); }"
                  />
                  <Input
                    :model-value="producto.cantidad"
                    type="number"
                    min="1"
                    label="Cantidad"
                    :error="validationErrors['productos.' + index + '.cantidad'] || form.errors['productos.' + index + '.cantidad']"
                    @update:model-value="(val: any) => { producto.cantidad = Number(val) || 1; actualizarPrecioSubtotal(producto, 'producto'); }"
                  />
                  <Input
                    :model-value="producto.precio"
                    type="number"
                    step="0.01"
                    label="Precio Unit."
                    readonly
                  />
                  <Input
                    :model-value="producto.subtotal"
                    type="number"
                    step="0.01"
                    label="Subtotal"
                    readonly
                  />
                  <Button type="button" variant="danger" size="sm" @click="eliminarProducto(index)" class="mb-1">
                    Eliminar
                  </Button>
                </div>
                <div v-if="productoTotal > 0" class="text-right font-semibold text-green-700">
                  Total Productos: ${{ productoTotal.toFixed(2) }}
                </div>
              </div>

              <div v-if="totalGeneral > 0" class="border-t pt-4 text-right text-xl font-bold text-blue-700">
                Total General: ${{ totalGeneral.toFixed(2) }}
              </div>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.consultas.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Consulta' }}
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
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import { useValidation } from '@/composables/useValidation';

const props = defineProps<{
  consulta: any;
  mascotas: any[];
  veterinarios: any[];
  productos: any[];
  servicios: any[];
}>();

const crearItem = (tipo: 'servicio' | 'producto') => ({
  id: '',
  cantidad: 1,
  precio: 0,
  subtotal: 0,
});

const form = useForm({
  fecha_consulta: props.consulta.fecha_consulta,
  mascota_id: props.consulta.mascota_id,
  veterinario_id: props.consulta.veterinario_id,
  motivo_consulta: props.consulta.motivo_consulta,
  diagnostico: props.consulta.diagnostico,
  tratamiento: props.consulta.tratamiento || '',
  observaciones: props.consulta.observaciones || '',
  servicios: props.consulta.servicios?.map((s: any) => ({
    id: s.id,
    cantidad: s.pivot.cantidad,
    precio: s.pivot.precio,
    subtotal: s.pivot.subtotal,
  })) || [],
  productos: props.consulta.productos?.map((p: any) => ({
    id: p.id,
    cantidad: p.pivot.cantidad,
    precio: p.pivot.precio,
    subtotal: p.pivot.subtotal,
  })) || [],
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const actualizarPrecioSubtotal = (item: any, tipo: 'servicio' | 'producto') => {
  const lista = tipo === 'servicio' ? props.servicios : props.productos;
  const encontrado = lista.find((x: any) => x.id === Number(item.id));
  item.precio = encontrado ? encontrado.precio : 0;
  item.subtotal = (item.cantidad || 0) * item.precio;
};

const agregarServicio = () => {
  form.servicios.push(crearItem('servicio'));
};

const eliminarServicio = (index: number) => {
  form.servicios.splice(index, 1);
};

const agregarProducto = () => {
  form.productos.push(crearItem('producto'));
};

const eliminarProducto = (index: number) => {
  form.productos.splice(index, 1);
};

const servicioTotal = computed(() => {
  return form.servicios.reduce((sum, s) => sum + (s.subtotal || 0), 0);
});

const productoTotal = computed(() => {
  return form.productos.reduce((sum, p) => sum + (p.subtotal || 0), 0);
});

const totalGeneral = computed(() => {
  return servicioTotal.value + productoTotal.value;
});

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    fecha_consulta: { required: true, date: true },
    mascota_id: { required: true },
    veterinario_id: { required: true },
    motivo_consulta: { required: true, maxLength: 500 },
    diagnostico: { required: true, maxLength: 500 },
    tratamiento: { maxLength: 500 },
    observaciones: { maxLength: 500 },
  });

  if (!isValid) return;

  form.servicios.forEach((s: any) => { s.subtotal = s.cantidad * s.precio; });
  form.productos.forEach((p: any) => { p.subtotal = p.cantidad * p.precio; });

  form.put(route('admin.consultas.update', props.consulta.id), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

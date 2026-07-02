<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Editar Servicio</h1>
            <p class="page-subtitle">Modifique los datos del servicio</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.codigo_servicio"
                  label="Código de Servicio"
                  :error="validationErrors.codigo_servicio || form.errors.codigo_servicio"
                />

                <Input
                  v-model="form.nombre"
                  label="Nombre"
                  :error="validationErrors.nombre || form.errors.nombre"
                />
              </div>

              <Input
                v-model="form.descripcion"
                label="Descripción"
                :error="validationErrors.descripcion || form.errors.descripcion"
              />

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.duracion_estimada"
                  type="number"
                  label="Duración Estimada (minutos)"
                  :error="validationErrors.duracion_estimada || form.errors.duracion_estimada"
                />

                <Input
                  v-model="form.precio"
                  type="number"
                  step="0.01"
                  label="Precio (Bs.)"
                  :error="validationErrors.precio || form.errors.precio"
                />
              </div>

              <Select
                v-model="form.estado"
                label="Estado"
                :options="[
                  { value: 'activo', label: 'Activo' },
                  { value: 'inactivo', label: 'Inactivo' }
                ]"
                :error="validationErrors.estado || form.errors.estado"
              />
            </div>

            <div class="form-actions">
              <Link :href="route('admin.servicios.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Servicio' }}
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import { useValidation } from '@/composables/useValidation';

const props = defineProps<{
  servicio: any;
}>();

const form = useForm({
  codigo_servicio: props.servicio.codigo_servicio,
  nombre: props.servicio.nombre,
  descripcion: props.servicio.descripcion,
  duracion_estimada: props.servicio.duracion_estimada,
  precio: props.servicio.precio,
  estado: props.servicio.estado || 'activo',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    codigo_servicio: { required: true, maxLength: 20 },
    nombre: { required: true, maxLength: 100 },
    descripcion: { required: true, maxLength: 255 },
    duracion_estimada: { required: true, numeric: true },
    precio: { required: true, numeric: true },
    estado: { required: true },
  });

  if (!isValid) return;

  form.put(route('admin.servicios.update', props.servicio.id), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

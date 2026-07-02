<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Editar Producto</h1>
            <p class="page-subtitle">Modifique los datos del producto</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.codigo_producto"
                  label="Código de Producto"
                  :error="validationErrors.codigo_producto || form.errors.codigo_producto"
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

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input
                  v-model="form.stock"
                  type="number"
                  label="Stock"
                  :error="validationErrors.stock || form.errors.stock"
                />

                <Input
                  v-model="form.precio"
                  type="number"
                  step="0.01"
                  label="Precio (Bs.)"
                  :error="validationErrors.precio || form.errors.precio"
                />

                <Select
                  v-model="form.categoria_id"
                  label="Categoría"
                  :options="categorias.map(c => ({ value: c.id, label: c.nombre }))"
                  :error="validationErrors.categoria_id || form.errors.categoria_id"
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
              <Link :href="route('admin.productos.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Actualizando...' : 'Actualizar Producto' }}
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
  producto: any;
  categorias: any[];
}>();

const form = useForm({
  codigo_producto: props.producto.codigo_producto,
  nombre: props.producto.nombre,
  descripcion: props.producto.descripcion,
  stock: props.producto.stock,
  precio: props.producto.precio,
  categoria_id: props.producto.categoria_id,
  estado: props.producto.estado || 'activo',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    codigo_producto: { required: true, maxLength: 20 },
    nombre: { required: true, maxLength: 100 },
    descripcion: { required: true, maxLength: 255 },
    stock: { required: true, numeric: true },
    precio: { required: true, numeric: true },
    categoria_id: { required: true },
    estado: { required: true },
  });

  if (!isValid) return;

  form.put(route('admin.productos.update', props.producto.id), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

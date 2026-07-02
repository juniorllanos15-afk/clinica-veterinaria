<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nueva Categoría</h1>
            <p class="page-subtitle">Complete el formulario para registrar una nueva categoría</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <Input
                v-model="form.nombre"
                label="Nombre"
                placeholder="Nombre de la categoría"
                :error="validationErrors.nombre || form.errors.nombre"
              />

              <Input
                v-model="form.descripcion"
                label="Descripción"
                placeholder="Descripción de la categoría"
                :error="validationErrors.descripcion || form.errors.descripcion"
              />
            </div>

            <div class="form-actions">
              <Link :href="route('admin.categorias.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Categoría' }}
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
import { useValidation } from '@/composables/useValidation';

const form = useForm({
  nombre: '',
  descripcion: '',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    nombre: { required: true, maxLength: 100 },
    descripcion: { required: true, maxLength: 255 },
  });

  if (!isValid) return;

  form.post(route('admin.categorias.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

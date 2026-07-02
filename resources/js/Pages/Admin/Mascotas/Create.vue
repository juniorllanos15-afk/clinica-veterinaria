<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nueva Mascota</h1>
            <p class="page-subtitle">Complete el formulario para registrar una nueva mascota</p>
          </div>
        </div>

        <Card>
          <form @submit.prevent="submit" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.nombre"
                  label="Nombre"
                  placeholder="Nombre de la mascota"
                  :error="validationErrors.nombre || form.errors.nombre"
                />

                <Input
                  v-model="form.especie"
                  label="Especie"
                  placeholder="Perro, Gato, etc."
                  :error="validationErrors.especie || form.errors.especie"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.raza"
                  label="Raza"
                  placeholder="Raza de la mascota"
                  :error="validationErrors.raza || form.errors.raza"
                />

                <Input
                  v-model="form.fecha_nacimiento"
                  type="date"
                  label="Fecha de Nacimiento"
                  :error="validationErrors.fecha_nacimiento || form.errors.fecha_nacimiento"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Select
                  v-model="form.sexo"
                  label="Sexo"
                  :options="[
                    { value: 'Macho', label: 'Macho' },
                    { value: 'Hembra', label: 'Hembra' }
                  ]"
                  :error="validationErrors.sexo || form.errors.sexo"
                />

                <Input
                  v-model="form.color"
                  label="Color"
                  placeholder="Color del pelaje"
                  :error="validationErrors.color || form.errors.color"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  v-model="form.peso"
                  type="number"
                  step="0.1"
                  label="Peso (kg)"
                  placeholder="0.0"
                  :error="validationErrors.peso || form.errors.peso"
                />

                <Select
                  v-model="form.dueno_id"
                  label="Dueño"
                  :options="clientes.map(c => ({ value: c.id, label: c.nombre + ' ' + c.apellido }))"
                  :error="validationErrors.dueno_id || form.errors.dueno_id"
                />
              </div>
            </div>

            <div class="form-actions">
              <Link :href="route('admin.mascotas.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Mascota' }}
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
  clientes: any[];
}>();

const form = useForm({
  nombre: '',
  especie: '',
  raza: '',
  fecha_nacimiento: '',
  sexo: '',
  color: '',
  peso: '',
  dueno_id: '',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

const submit = () => {
  clearErrors();

  const isValid = validate(form, {
    nombre: { required: true, maxLength: 100 },
    especie: { required: true, maxLength: 50 },
    raza: { required: true, maxLength: 50 },
    fecha_nacimiento: { required: true, date: true },
    sexo: { required: true },
    color: { required: true, maxLength: 50 },
    peso: { required: true, numeric: true },
    dueno_id: { required: true },
  });

  if (!isValid) return;

  form.post(route('admin.mascotas.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

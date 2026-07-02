<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Crear Nueva Consulta</h1>
            <p class="page-subtitle">Complete el formulario para registrar una nueva consulta</p>
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
                placeholder="Describa el motivo de la consulta"
                :error="validationErrors.motivo_consulta || form.errors.motivo_consulta"
              />

              <Input
                v-model="form.diagnostico"
                label="Diagnóstico"
                placeholder="Diagnóstico realizado"
                :error="validationErrors.diagnostico || form.errors.diagnostico"
              />

              <Input
                v-model="form.tratamiento"
                label="Tratamiento"
                placeholder="Tratamiento recetado"
                :error="validationErrors.tratamiento || form.errors.tratamiento"
              />

              <Input
                v-model="form.observaciones"
                label="Observaciones"
                placeholder="Observaciones adicionales"
                :error="validationErrors.observaciones || form.errors.observaciones"
              />
            </div>

            <div class="form-actions">
              <Link :href="route('admin.consultas.index')">
                <Button type="button" variant="secondary">Cancelar</Button>
              </Link>
              <Button type="submit" variant="primary" :disabled="form.processing">
                {{ form.processing ? 'Creando...' : 'Crear Consulta' }}
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
  mascotas: any[];
  veterinarios: any[];
}>();

const form = useForm({
  fecha_consulta: '',
  mascota_id: '',
  veterinario_id: '',
  motivo_consulta: '',
  diagnostico: '',
  tratamiento: '',
  observaciones: '',
});

const { errors: validationErrors, validate, clearErrors, setErrors } = useValidation();

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

  form.post(route('admin.consultas.store'), {
    onError: (errors) => {
      setErrors(errors);
    }
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">{{ mascota.nombre }}</h1>
            <p class="page-subtitle">Detalle de la mascota</p>
          </div>
          <Link :href="route('cliente.mascotas')">
            <Button variant="secondary">Volver</Button>
          </Link>
        </div>

        <Card>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <p class="text-sm text-gray-500">Especie</p>
                <p class="font-semibold text-lg">{{ mascota.especie }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Raza</p>
                <p class="font-semibold text-lg">{{ mascota.raza }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Sexo</p>
                <p class="font-semibold text-lg">{{ mascota.sexo }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Color</p>
                <p class="font-semibold text-lg">{{ mascota.color }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Fecha de Nacimiento</p>
                <p class="font-semibold text-lg">{{ mascota.fecha_nacimiento }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Peso</p>
                <p class="font-semibold text-lg">{{ mascota.peso ? mascota.peso + ' kg' : '-' }}</p>
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

defineProps<{
  mascota: any;
}>();
</script>

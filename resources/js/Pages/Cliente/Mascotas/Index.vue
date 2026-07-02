<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Mis Mascotas</h1>
            <p class="page-subtitle">Mascotas registradas a tu nombre</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Nombre</th>
                  <th class="table-header-cell">Especie</th>
                  <th class="table-header-cell">Raza</th>
                  <th class="table-header-cell">Sexo</th>
                  <th class="table-header-cell">Color</th>
                  <th class="table-header-cell">Peso</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="mascota in mascotas.data" :key="mascota.id" class="table-row">
                  <td class="table-cell font-medium">{{ mascota.nombre }}</td>
                  <td class="table-cell-secondary">{{ mascota.especie }}</td>
                  <td class="table-cell-secondary">{{ mascota.raza }}</td>
                  <td class="table-cell">{{ mascota.sexo }}</td>
                  <td class="table-cell-secondary">{{ mascota.color }}</td>
                  <td class="table-cell-secondary">{{ mascota.peso ? mascota.peso + ' kg' : '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="p-4 border-t border-theme">
            <Pagination :data="mascotas" />
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  mascotas: {
    data: any[];
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();
</script>

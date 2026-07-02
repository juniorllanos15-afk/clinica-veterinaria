<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Card from '@/Components/UI/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps<{
  mascotas: {
    data: Array<{
      id: number;
      nombre: string;
      especie: string;
      raza: string;
      estado: string;
      dueno: { id: number; nombre: string; apellido: string };
    }>;
    links: any[];
    from: number;
    to: number;
    total: number;
  };
}>();

const capitalizeFirst = (str: string): string => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Mis Mascotas</h1>
            <p class="page-subtitle">Mascotas que ha atendido</p>
          </div>
        </div>

        <Card :padding="false">
          <div class="overflow-x-auto">
            <table class="table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">ID</th>
                  <th class="table-header-cell">Nombre</th>
                  <th class="table-header-cell">Especie</th>
                  <th class="table-header-cell">Raza</th>
                  <th class="table-header-cell">Dueño</th>
                  <th class="table-header-cell">Estado</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="mascota in mascotas.data" :key="mascota.id" class="table-row">
                  <td class="table-cell">{{ mascota.id }}</td>
                  <td class="table-cell font-medium">{{ mascota.nombre }}</td>
                  <td class="table-cell-secondary">{{ mascota.especie }}</td>
                  <td class="table-cell-secondary">{{ mascota.raza }}</td>
                  <td class="table-cell">{{ mascota.dueno?.nombre }} {{ mascota.dueno?.apellido }}</td>
                  <td class="table-cell">
                    <Badge :variant="mascota.estado === 'activo' ? 'success' : 'warning'">
                      {{ capitalizeFirst(mascota.estado) }}
                    </Badge>
                  </td>
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

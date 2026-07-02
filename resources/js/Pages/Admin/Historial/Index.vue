<template>
  <AuthenticatedLayout>
    <div class="page-container py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-header">
          <div>
            <h1 class="page-title">Historial Clínico</h1>
            <p class="page-subtitle">Consulta el historial de consultas de cada mascota</p>
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
                  <th class="table-header-cell">Cant. Consultas</th>
                  <th class="table-header-cell text-right">Acciones</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="mascota in mascotas.data" :key="mascota.id" class="table-row">
                  <td class="table-cell">{{ mascota.id }}</td>
                  <td class="table-cell font-medium">{{ mascota.nombre }}</td>
                  <td class="table-cell-secondary">{{ mascota.especie }}</td>
                  <td class="table-cell-secondary">{{ mascota.raza }}</td>
                  <td class="table-cell">{{ mascota.dueno?.nombre }} {{ mascota.dueno?.apellido }}</td>
                  <td class="table-cell">—</td>
                  <td class="table-cell">
                    <div class="flex items-center justify-end gap-2">
                      <Link :href="route('admin.historial.show', mascota.id)">
                        <Button variant="primary" size="sm">
                          Ver Historial
                        </Button>
                      </Link>
                    </div>
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

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/UI/Button.vue';
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

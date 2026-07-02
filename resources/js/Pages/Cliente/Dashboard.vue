<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Badge from '@/Components/UI/Badge.vue';

defineProps<{
  stats: { total_mascotas: number; total_consultas: number };
  mascotas: any[];
  consultas_recientes: any[];
}>();

const formatDate = (dateStr: string): string => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
  <AuthenticatedLayout>
    <template #header>Mi Panel</template>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <StatCard title="Mis Mascotas" :value="stats.total_mascotas" color="blue">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
          </template>
        </StatCard>

        <StatCard title="Consultas Realizadas" :value="stats.total_consultas" color="green">
          <template #icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </template>
        </StatCard>
      </div>

      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-theme flex items-center justify-between">
          <h3 class="text-lg font-semibold text-theme-primary">Mis Mascotas</h3>
          <Link :href="route('cliente.mascotas')" class="text-sm text-blue-600 hover:underline">Ver todas</Link>
        </div>
        <div v-if="mascotas.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
          <div v-for="mascota in mascotas" :key="mascota.id" class="card-hover p-4 rounded-lg">
            <div class="flex items-center gap-3 mb-2">
              <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold text-theme-primary">{{ mascota.nombre }}</h4>
                <p class="text-xs text-theme-secondary">{{ mascota.especie }} · {{ mascota.raza }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-theme-tertiary">
              <span v-if="mascota.sexo">{{ mascota.sexo }}</span>
              <span v-if="mascota.color">{{ mascota.color }}</span>
              <span v-if="mascota.peso">{{ mascota.peso }} kg</span>
            </div>
          </div>
        </div>
        <p v-else class="text-center text-theme-tertiary py-8">No tienes mascotas registradas</p>
      </div>

      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-theme flex items-center justify-between">
          <h3 class="text-lg font-semibold text-theme-primary">Consultas Recientes</h3>
          <Link :href="route('cliente.consultas')" class="text-sm text-blue-600 hover:underline">Ver todas</Link>
        </div>
        <div v-if="consultas_recientes.length > 0" class="overflow-x-auto">
          <table class="table">
            <thead class="table-header">
              <tr>
                <th class="table-header-cell">Fecha</th>
                <th class="table-header-cell">Mascota</th>
                <th class="table-header-cell">Veterinario</th>
                <th class="table-header-cell">Motivo</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="consulta in consultas_recientes" :key="consulta.id" class="table-row">
                <td class="table-cell-secondary">{{ formatDate(consulta.fecha_consulta) }}</td>
                <td class="table-cell font-medium">{{ consulta.mascota?.nombre }}</td>
                <td class="table-cell">{{ consulta.veterinario?.nombre }} {{ consulta.veterinario?.apellido }}</td>
                <td class="table-cell-secondary">{{ consulta.motivo_consulta }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-center text-theme-tertiary py-8">No hay consultas registradas</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

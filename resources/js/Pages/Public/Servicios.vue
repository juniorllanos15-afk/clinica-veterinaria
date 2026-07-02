<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps<{
  servicios: Array<{
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    categoria: { nombre: string };
  }>;
  categorias: Array<{
    id: number;
    nombre: string;
    descripcion: string;
  }>;
}>();
</script>

<template>
  <GuestLayout>
    <div class="bg-gradient-to-r from-green-600 to-teal-600 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Nuestros Servicios</h1>
        <p class="text-xl text-white/90">Atención integral para la salud de tu mascota</p>
      </div>
    </div>

    <div class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Categorías</h2>
        <div class="grid md:grid-cols-3 gap-6">
          <div
            v-for="cat in categorias"
            :key="cat.id"
            class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow"
          >
            <h3 class="text-lg font-bold text-green-600 mb-2">{{ cat.nombre }}</h3>
            <p class="text-gray-600 text-sm">{{ cat.descripcion }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Todos los Servicios</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="servicio in servicios"
            :key="servicio.id"
            class="bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-green-500 transition-all hover:shadow-xl"
          >
            <div class="bg-gradient-to-br from-green-500 to-teal-500 p-6">
              <span class="inline-block px-3 py-1 bg-white text-green-600 text-xs font-bold rounded-full mb-2">
                {{ servicio.categoria?.nombre || 'General' }}
              </span>
              <h3 class="text-2xl font-bold text-white">{{ servicio.nombre }}</h3>
            </div>

            <div class="p-6">
              <p class="text-gray-600 mb-4">{{ servicio.descripcion }}</p>

              <div class="space-y-3 mb-6">
                <div class="flex items-center text-sm text-gray-700">
                  <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Precio: Bs. {{ servicio.precio }}
                </div>
              </div>

              <div class="border-t pt-4">
                <Link
                  :href="route('register')"
                  class="block w-full text-center px-4 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg transition-all"
                >
                  Solicitar Ahora
                </Link>
              </div>
            </div>
          </div>
        </div>

        <div v-if="!servicios || servicios.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
          <p class="text-gray-600 text-lg">No hay servicios disponibles en este momento</p>
        </div>
      </div>
    </div>

    <div class="bg-gray-900 py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿Tienes Dudas?</h2>
        <p class="text-xl text-gray-300 mb-8">
          Contáctanos y uno de nuestros veterinarios te ayudará
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link
            :href="route('nosotros')"
            class="inline-flex items-center px-6 py-3 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Contactar
          </Link>

          <Link
            :href="route('home')"
            class="inline-flex items-center px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-gray-900 transition-colors"
          >
            Volver al Inicio
          </Link>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

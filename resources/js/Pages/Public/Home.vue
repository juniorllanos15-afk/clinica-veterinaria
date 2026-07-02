<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps<{
  servicios?: Array<{
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    categoria: { nombre: string };
  }>;
}>();
</script>

<template>
  <GuestLayout>
    <div class="relative bg-gradient-to-br from-green-600 via-teal-600 to-blue-600 overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')]"></div>
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <div class="flex justify-center mb-8">
            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-2xl">
              <svg class="w-20 h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
          </div>

          <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
            Clínica Veterinaria
          </h1>
          <p class="text-2xl md:text-3xl text-green-100 font-semibold mb-4">
            Cuidado y Amor por tus Mascotas
          </p>
          <p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-8">
            Brindamos atención médica integral para tus mascotas con profesionales capacitados y equipos modernos.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('register')"
              class="inline-flex items-center px-8 py-4 bg-white text-green-600 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
              </svg>
              Registrarse Ahora
            </Link>

            <Link
              :href="route('servicios.publicos')"
              class="inline-flex items-center px-8 py-4 bg-teal-400 text-gray-900 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
              Ver Servicios
            </Link>
          </div>
        </div>
      </div>
    </div>

    <div class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">¿Por qué elegirnos?</h2>

        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Veterinarios Certificados</h3>
            <p class="text-gray-600">Profesionales con años de experiencia y especialización</p>
          </div>

          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Instalaciones Modernas</h3>
            <p class="text-gray-600">Equipos de última generación y consultorios equipados</p>
          </div>

          <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Atención 24/7</h3>
            <p class="text-gray-600">Emergencias las 24 horas, porque tu mascota nos importa</p>
          </div>
        </div>
      </div>
    </div>

    <div class="py-16 bg-white" v-if="servicios && servicios.length > 0">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">Nuestros Servicios</h2>
        <p class="text-center text-gray-600 mb-12">Atención integral para la salud de tu mascota</p>

        <div class="grid md:grid-cols-3 gap-8">
          <div
            v-for="servicio in servicios.slice(0, 3)"
            :key="servicio.id"
            class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1"
          >
            <div class="flex items-center justify-between mb-4">
              <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                {{ servicio.categoria?.nombre || 'General' }}
              </span>
              <span class="text-2xl font-bold text-gray-900">Bs. {{ servicio.precio }}</span>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ servicio.nombre }}</h3>
            <p class="text-gray-600 mb-4 line-clamp-2">{{ servicio.descripcion }}</p>

            <Link
              :href="route('servicios.publicos')"
              class="block w-full text-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors"
            >
              Más Información
            </Link>
          </div>
        </div>

        <div class="text-center mt-8">
          <Link
            :href="route('servicios.publicos')"
            class="inline-flex items-center text-green-600 font-semibold hover:text-green-700"
          >
            Ver todos los servicios
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>

    <div class="bg-gradient-to-r from-green-600 to-teal-600 py-16">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
          ¿Tu Mascota Necesita Atención?
        </h2>
        <p class="text-xl text-white/90 mb-8">
          Agenda una consulta y dale el cuidado que se merece
        </p>
        <Link
          :href="route('register')"
          class="inline-flex items-center px-8 py-4 bg-white text-green-600 text-lg font-bold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all"
        >
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          Agendar Ahora
        </Link>
      </div>
    </div>

    <div class="bg-gray-900 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8 text-white">
          <div>
            <h3 class="text-lg font-bold mb-4">Contacto</h3>
            <p class="text-gray-400">Santa Cruz de la Sierra, Bolivia</p>
            <p class="text-gray-400">Tel: (3) 333-3333</p>
            <p class="text-gray-400">info@veterinaria.com.bo</p>
          </div>

          <div>
            <h3 class="text-lg font-bold mb-4">Horario de Atención</h3>
            <p class="text-gray-400">Lunes a Viernes: 8:00 - 20:00</p>
            <p class="text-gray-400">Sábados: 8:00 - 17:00</p>
            <p class="text-gray-400">Domingos: 9:00 - 13:00 (Emergencias)</p>
          </div>

          <div>
            <h3 class="text-lg font-bold mb-4">Enlaces Rápidos</h3>
            <div class="space-y-2">
              <Link :href="route('servicios.publicos')" class="block text-gray-400 hover:text-white transition-colors">Servicios</Link>
              <Link :href="route('nosotros')" class="block text-gray-400 hover:text-white transition-colors">Nosotros</Link>
              <Link :href="route('login')" class="block text-gray-400 hover:text-white transition-colors">Iniciar Sesión</Link>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2024 Clínica Veterinaria Santa Cruz. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

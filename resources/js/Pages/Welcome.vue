<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-green-600 via-teal-600 to-blue-600 flex items-center justify-center px-4">
    <div class="max-w-4xl w-full">
      <div class="text-center">
        <div class="mb-8">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-2xl mb-6">
            <svg class="w-14 h-14 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </div>
          <h1 class="text-6xl font-bold text-white mb-4">
            Clínica Veterinaria
          </h1>
          <p class="text-xl text-white/90 mb-8">
            Sistema de Gestión Integral
          </p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-2xl border border-white/20">
          <h2 class="text-2xl font-semibold text-white mb-6">
            Bienvenido al Sistema
          </h2>
          
          <div class="space-y-4">
            <Link 
              :href="route('login')" 
              class="block w-full py-4 px-6 bg-white text-green-600 rounded-xl font-semibold text-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200"
            >
              Iniciar Sesión
            </Link>
            
            <div class="text-white/80 text-sm">
              <p>Sistema de gestión para clínica veterinaria</p>
              <p class="mt-2">Acceso para propietarios, veterinarios y clientes</p>
            </div>
          </div>
        </div>

        <div class="mt-8 text-white/60 text-sm">
          <p>&copy; 2024 Clínica Veterinaria. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </div>
</template>

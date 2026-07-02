<template>
  <div class="relative" v-click-outside="closeResults">
    <!-- Search Input -->
    <div class="relative">
      <input
        v-model="query"
        @input="handleSearch"
        @focus="showResults = true"
        @keydown.down.prevent="highlightNext"
        @keydown.up.prevent="highlightPrevious"
        @keydown.enter.prevent="selectHighlighted"
        @keydown.esc="closeResults"
        type="text"
        placeholder="Buscar en todo el sistema..."
        class="w-64 pl-10 pr-4 py-2 border border-theme rounded-lg bg-theme-surface text-theme-primary placeholder-text-theme-tertiary focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      />
      <svg
        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-theme-tertiary"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
        />
      </svg>

      <!-- Loading Spinner -->
      <div v-if="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Results Dropdown -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="showResults && (hasResults || query.length > 0)"
        class="absolute top-full mt-2 w-[600px] max-h-[70vh] overflow-auto bg-theme-surface border border-theme rounded-lg shadow-2xl z-50"
      >
        <!-- No Query -->
        <div v-if="query.length === 0" class="p-4 text-center text-theme-tertiary text-sm">
          Escribe para buscar en TODO el sistema (11 tablas)...
        </div>

        <!-- Loading -->
        <div v-else-if="loading" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <p class="mt-2 text-theme-secondary text-sm">Buscando...</p>
        </div>

        <!-- No Results -->
        <div v-else-if="!hasResults" class="p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-theme-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="mt-2 text-theme-primary font-medium">No se encontraron resultados</p>
          <p class="text-sm text-theme-tertiary">Intenta con otros términos de búsqueda</p>
        </div>

        <!-- Results -->
        <div v-else class="py-2">
          <!-- Usuarios -->
          <div v-if="results.usuarios?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Usuarios ({{ results.usuarios.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.usuarios"
              :key="`user-${item.id}`"
              :href="`/admin/usuarios/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('usuarios', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-blue-600 font-semibold text-sm">{{ item.nombre?.[0]?.toUpperCase() }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }} {{ item.apellido }}</p>
                  <p class="text-theme-tertiary text-sm truncate">{{ item.email }} • {{ item.rol?.nombre }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Mascotas -->
          <div v-if="results.mascotas?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Mascotas ({{ results.mascotas.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.mascotas"
              :key="`mascota-${item.id}`"
              :href="`/admin/mascotas/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('mascotas', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }}</p>
                  <p class="text-theme-tertiary text-sm truncate">{{ item.especie }} • Dueño: {{ item.dueno?.nombre }} {{ item.dueno?.apellido }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Consultas -->
          <div v-if="results.consultas?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Consultas ({{ results.consultas.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.consultas"
              :key="`consulta-${item.id}`"
              :href="`/admin/consultas/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('consultas', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.motivo }}</p>
                  <p class="text-theme-tertiary text-sm truncate">{{ item.mascota?.nombre }} • {{ formatDate(item.fecha) }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Servicios -->
          <div v-if="results.servicios?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Servicios ({{ results.servicios.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.servicios"
              :key="`servicio-${item.id}`"
              :href="`/admin/servicios/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('servicios', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }}</p>
                  <p class="text-theme-tertiary text-sm truncate">{{ item.categoria?.nombre || 'General' }} • Bs. {{ item.precio }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Productos -->
          <div v-if="results.productos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Productos ({{ results.productos.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.productos"
              :key="`producto-${item.id}`"
              :href="`/admin/productos/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('productos', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }}</p>
                  <p class="text-theme-tertiary text-sm">${{ item.precio }} • Stock: {{ item.stock }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Categorías -->
          <div v-if="results.categorias?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Categorías ({{ results.categorias.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.categorias"
              :key="`categoria-${item.id}`"
              :href="`/admin/categorias/${item.id}/edit`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('categorias', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">{{ item.nombre }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Pagos -->
          <div v-if="results.pagos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pagos ({{ results.pagos.length }})
              </h3>
            </div>
            <Link
              v-for="(item, index) in results.pagos"
              :key="`pago-${item.id}`"
              :href="`/admin/pagos/${item.id}`"
              @click="closeResults"
              :class="[
                'block px-4 py-3 hover:bg-theme-hover transition-colors cursor-pointer border-l-4',
                highlightedIndex === getGlobalIndex('pagos', index) ? 'bg-theme-hover border-blue-500' : 'border-transparent'
              ]"
            >
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-emerald-600 font-bold text-sm">$</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-theme-primary font-medium truncate">
                    Consulta #{{ item.consulta?.id || item.consulta_id }} - Total: ${{ item.total }}
                  </p>
                  <p class="text-theme-tertiary text-sm">Estado: {{ item.estado }}</p>
                </div>
              </div>
            </Link>
          </div>

          <!-- Roles -->
          <div v-if="results.roles?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Roles ({{ results.roles.length }})</h3>
            </div>
            <Link v-for="item in results.roles" :key="`rol-${item.id}`" :href="`/admin/roles/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }}</p>
            </Link>
          </div>

          <!-- Menús -->
          <div v-if="results.menus?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Menús ({{ results.menus.length }})</h3>
            </div>
            <Link v-for="item in results.menus" :key="`menu-${item.id}`" :href="`/admin/menus/${item.id}/edit`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.nombre }} - {{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">Rol: {{ item.rol?.nombre || 'Todos' }}</p>
            </Link>
          </div>

          <!-- Eventos -->
          <div v-if="results.eventos?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Eventos del Sistema ({{ results.eventos.length }})</h3>
            </div>
            <Link v-for="item in results.eventos" :key="`evento-${item.id}`" :href="`/admin/eventos`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">{{ item.descripcion || 'Sin descripción' }} • {{ formatDate(item.created_at) }}</p>
            </Link>
          </div>

          <!-- Visitas -->
          <div v-if="results.visitas?.length" class="mb-2">
            <div class="px-4 py-2 bg-theme-secondary">
              <h3 class="text-xs font-semibold text-theme-secondary uppercase tracking-wider">Visitas ({{ results.visitas.length }})</h3>
            </div>
            <Link v-for="item in results.visitas" :key="`visita-${item.id}`" :href="`/admin/visitas`" @click="closeResults" class="block px-4 py-3 hover:bg-theme-hover">
              <p class="text-theme-primary font-medium">{{ item.ruta }}</p>
              <p class="text-theme-tertiary text-sm">{{ item.contador }} visitas</p>
            </Link>
          </div>

          <!-- Total Count -->
          <div class="px-4 py-3 bg-theme-secondary border-t border-theme text-center">
            <p class="text-xs text-theme-tertiary">
              Total: {{ totalResults }} resultado{{ totalResults !== 1 ? 's' : '' }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const query = ref('');
const results = ref({});
const loading = ref(false);
const showResults = ref(false);
const highlightedIndex = ref(-1);

let searchTimeout = null;

const hasResults = computed(() => {
  return Object.values(results.value).some(arr => arr && arr.length > 0);
});

const totalResults = computed(() => {
  return Object.values(results.value).reduce((sum, arr) => sum + (arr?.length || 0), 0);
});

const handleSearch = () => {
  clearTimeout(searchTimeout);

  if (query.value.length < 2) {
    results.value = {};
    return;
  }

  loading.value = true;
  highlightedIndex.value = -1;

  searchTimeout = setTimeout(async () => {
    try {
      const response = await axios.get('/api/search', {
        params: { q: query.value }
      });
      results.value = response.data;
    } catch (error) {
      console.error('Search error:', error);
      results.value = {};
    } finally {
      loading.value = false;
    }
  }, 300);
};

const closeResults = () => {
  showResults.value = false;
  highlightedIndex.value = -1;
};

const getGlobalIndex = (category, localIndex) => {
  const categories = ['usuarios', 'mascotas', 'consultas', 'servicios', 'productos', 'categorias', 'pagos'];
  let globalIndex = 0;

  for (const cat of categories) {
    if (cat === category) {
      return globalIndex + localIndex;
    }
    globalIndex += results.value[cat]?.length || 0;
  }
  return globalIndex;
};

const highlightNext = () => {
  const max = totalResults.value - 1;
  if (highlightedIndex.value < max) {
    highlightedIndex.value++;
  }
};

const highlightPrevious = () => {
  if (highlightedIndex.value > 0) {
    highlightedIndex.value--;
  }
};

const selectHighlighted = () => {
  if (highlightedIndex.value < 0) return;

  let currentIndex = 0;
  const categories = ['usuarios', 'mascotas', 'consultas', 'servicios', 'productos', 'categorias', 'pagos'];

  for (const category of categories) {
    const items = results.value[category] || [];
    if (currentIndex + items.length > highlightedIndex.value) {
      const localIndex = highlightedIndex.value - currentIndex;
      const item = items[localIndex];
      let url = '';

      switch(category) {
        case 'usuarios':
          url = `/admin/usuarios/${item.id}/edit`;
          break;
        case 'mascotas':
          url = `/admin/mascotas/${item.id}/edit`;
          break;
        case 'consultas':
          url = `/admin/consultas/${item.id}/edit`;
          break;
        case 'servicios':
          url = `/admin/servicios/${item.id}/edit`;
          break;
        case 'productos':
          url = `/admin/productos/${item.id}/edit`;
          break;
        case 'categorias':
          url = `/admin/categorias/${item.id}/edit`;
          break;
        case 'pagos':
          url = `/admin/pagos/${item.id}`;
          break;
      }

      if (url) {
        window.location.href = url;
      }
      return;
    }
    currentIndex += items.length;
  }
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  }
};
</script>

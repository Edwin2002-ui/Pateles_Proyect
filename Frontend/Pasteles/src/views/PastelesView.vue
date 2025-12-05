<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-gray-800 p-4 rounded-xl border border-gray-700">
       <div class="relative w-full max-w-md">
           <input 
             v-model="searchQuery" 
             type="text" 
             placeholder="Buscar pastel..." 
             class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 placeholder-gray-500"
           >
           <MagnifyingGlassIcon class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" />
       </div>
       <button 
          @click="openModal()"
          class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-indigo-500/20 transition-all"
        >
          <PlusIcon class="w-5 h-5" />
          <span>Nuevo</span>
        </button>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-sm">
       <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pastel</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Repostero</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Vencimiento</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
            
            <tr v-for="pastel in filteredPasteles" :key="pastel.ID_pastel" class="hover:bg-gray-700/50 transition-colors" @click="viewDetails(pastel)">
                
                <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-gray-700 flex items-center justify-center text-xl">
                    🎂 </div>
                    <div class="ml-4">
                    <div class="text-sm font-medium text-white">{{ pastel.Nombre }}</div>
                    <div class="text-sm text-gray-400">{{ pastel.Descripcion }}</div>
                    </div>
                </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-200">{{ pastel.Preparado_por }}</div>
                <div class="text-xs text-gray-500">Creado: {{ pastel.Fecha_creacion }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                <span 
                    :class="[
                    new Date(pastel.Fecha_Vencimiento) > new Date() 
                        ? 'bg-green-500/10 text-green-400 border-green-500/20' 
                        : 'bg-red-500/10 text-red-400 border-red-500/20',
                    'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border'
                    ]"
                >
                    {{ new Date(pastel.Fecha_Vencimiento) > new Date() ? 'Disponible' : 'Vencido' }}
                </span>
                <div class="text-xs text-gray-500 mt-1">Vence: {{ pastel.Fecha_Vencimiento }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editPastel(pastel)" class="text-indigo-400 hover:text-indigo-300 mr-4">Editar</button>
                <button @click="deletePastel(pastel.ID_pastel)" class="text-red-400 hover:text-red-300">Eliminar</button>
                </td>
            </tr>
            <BaseDrawer :open="showDrawer" @close="showDrawer = false">
        
                <div v-if="selectedPastel">
                    
                    <div class="px-4 py-6 sm:px-6 bg-gray-900/50 border-b border-gray-700">
                        <div class="flex items-start gap-4">
                            <div class="h-16 w-16 rounded-xl bg-gray-700 flex items-center justify-center text-3xl shadow-lg">
                            🎂
                            </div>
                            <div>
                            <h2 class="text-xl font-bold leading-6 text-white">{{ selectedPastel.Nombre }}</h2>
                            <p class="mt-1 text-sm text-gray-400">{{ selectedPastel.Descripcion }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-6 flex-1 px-4 sm:px-6 space-y-8 pb-10">
                    
                        <div>
                            <h3 class="text-sm font-medium text-indigo-400 uppercase tracking-wider mb-3">Información General</h3>
                            <div class="bg-gray-700/30 rounded-lg p-4 space-y-3 border border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Preparado por</span>
                                    <span class="text-white">{{ selectedPastel.Preparado_por }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Fecha de creacion</span>
                                    <span class="text-white">{{ selectedPastel.Fecha_creacion }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Vence</span>
                                    <span class="text-white">{{ selectedPastel.Fecha_Vencimiento }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-indigo-400 uppercase tracking-wider mb-3">Ingredientes</h3>
                            
                            <ul v-if="selectedPastel.ingredientes && selectedPastel.ingredientes.length > 0" class="space-y-2">
                            <li v-for="ing in selectedPastel.ingredientes" :key="ing.ID_ingrediente" class="flex items-center justify-between bg-gray-900 p-3 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                    <span class="text-gray-200 text-sm">{{ ing.Nombre }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ ing.Descripcion }}</span>
                            </li>
                            </ul>

                            <div v-else class="text-center py-6 bg-gray-700/20 rounded-lg border border-dashed border-gray-600">
                            <p class="text-gray-500 text-sm">No hay ingredientes registrados.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </BaseDrawer>
            </tbody>
        </table>
    </div>

    <PastelModal 
      :show="showModal"
      :is-editing="!!editingPastel"
      v-model="formData"
      @close="closeModal"
      @save="savePastel"
    />
  </div>
</template>

<!-- <script setup>
import { ref, computed } from 'vue';
import PastelModal from '@/components/PastelModal.vue';
import { PlusIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';

// --- TODA LA LÓGICA DE PASTELES SE MUEVE AQUÍ ---
const searchQuery = ref('');
const showModal = ref(false);
const editingPastel = ref(null);


const pasteles = ref([
  { id: 1, nombre: 'Pastel de Tres Leches', sabor: 'Vainilla', precio: '150.00', emoji: '🍰', disponible: true },
  { id: 2, nombre: 'Chocolate Supremo', sabor: 'Chocolate', precio: '175.00', emoji: '🎂', disponible: true },

]);


const formData = ref({ nombre: '', sabor: '', precio: '', emoji: '🎂', disponible: true });

const filteredPasteles = computed(() => {
  if (!searchQuery.value) return pasteles.value;
  return pasteles.value.filter(p => p.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

// Funciones CRUD
const openModal = () => {
  editingPastel.value = null;
  formData.value = { nombre: '', sabor: '', precio: '', emoji: '🎂', disponible: true };
  showModal.value = true;
};

const editPastel = (pastel) => {
  editingPastel.value = pastel;
  formData.value = { ...pastel };
  showModal.value = true;
};

const savePastel = () => { /* ... tu lógica de guardar ... */ closeModal(); };
const deletePastel = (id) => { /* ... tu lógica de borrar ... */ };
const closeModal = () => showModal.value = false;



</script> -->

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import PastelModal from '@/components/PastelModal.vue';
import BaseDrawer from '@/components/BaseDrawer.vue'; // <--- IMPORTAMOS EL NUEVO COMPONENTE GENÉRICO
import { PlusIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';

// Variables de estado
const pasteles = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const showDrawer = ref(false); // Controla si se ve el panel lateral
const selectedPastel = ref(null); // Guarda los datos del pastel seleccionado
const editingPastel = ref(null);
const formData = ref({});

// 1. CARGA DE DATOS
const obtenerPasteles = async () => {
  try {
    const response = await api.get('/pasteles');
    if (response.data.success) {
      pasteles.value = response.data.data;
    }
  } catch (error) {
    console.error("Error API:", error);
  }
};

// 2. FILTRADO
const filteredPasteles = computed(() => {
  if (!searchQuery.value) return pasteles.value;
  return pasteles.value.filter(p => 
    p.Nombre.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// 3. LOGICA DEL DRAWER (DETALLES)
const viewDetails = (pastel) => {
  selectedPastel.value = pastel; // Guardamos el pastel clickeado
  showDrawer.value = true;       // Abrimos el marco genérico
};

// 4. LÓGICA DEL MODAL (EDICIÓN)
const openModal = () => {
  editingPastel.value = null;
  formData.value = { Nombre: '', Descripcion: '' }; // Ajusta según tu form real
  showModal.value = true;
};

const editPastel = (pastel) => {
  editingPastel.value = pastel;
  formData.value = { ...pastel };
  showModal.value = true;
};

// ... Resto de funciones (save, delete, close) ...
const closeModal = () => showModal.value = false;
const savePastel = () => { closeModal(); }; // Aquí iría tu lógica POST/PUT
const deletePastel = (id) => { console.log("Borrar", id); };

onMounted(() => {
  obtenerPasteles();
});
</script>
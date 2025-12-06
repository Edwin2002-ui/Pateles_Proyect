<template>
  <AppLayout 
    :current-view="'estadisticas'" 
    :page-title="'Reporte General'"       
    :page-subtitle="'Detalle de pasteles e ingredientes'"
  >
    <div class="space-y-6">
       
       <div class="flex justify-end">
          <button 
            @click="imprimirReporte"
            class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir PDF
          </button>
       </div>

       <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-sm overflow-x-auto">
         <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900/50">
              <tr>
                 <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pastel</th>
                 <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Responsable</th>
                 <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Ingredientes (Receta)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <tr v-for="(fila, index) in reporte" :key="index" class="hover:bg-gray-700/50">
                 
                 <td class="px-6 py-4">
                    <div class="text-sm font-bold text-white">{{ fila.Pastel }}</div>
                    <div class="text-xs text-gray-400">{{ fila.Descripcion }}</div>
                 </td>

                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ fila.Repostero }}
                 </td>

                 <td class="px-6 py-4 text-sm text-gray-300">
                    <div v-if="fila.Lista_Ingredientes">
                        <span 
                          v-for="ing in fila.Lista_Ingredientes.split(', ')" 
                          :key="ing"
                          class="inline-block bg-indigo-500/20 text-indigo-300 text-xs px-2 py-1 rounded-md mr-1 mb-1 border border-indigo-500/30"
                        >
                          {{ ing }}
                        </span>
                    </div>
                    <span v-else class="text-gray-500 italic">Sin ingredientes registrados</span>
                 </td>

              </tr>
            </tbody>
         </table>
       </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue'; 
import api from '@/services/api';
import { mostrarError } from '@/utils/alertas';

const reporte = ref([]);

const cargarReporte = async () => {
    try {
        // Asegúrate de crear esta ruta en tu backend
        const response = await api.get('/pasteles/reporte'); 
        if (response.data.success) {
            reporte.value = response.data.data;
        }
    } catch (error) {
        mostrarError('No se pudo cargar el reporte');
    }
};

const imprimirReporte = () => {
    window.print(); // Abre el diálogo nativo de imprimir del navegador
};

onMounted(() => {
    cargarReporte();
});
</script>
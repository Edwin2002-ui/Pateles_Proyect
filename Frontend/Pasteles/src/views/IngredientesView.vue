<template>
  <AppLayout 
    :current-view="'ingredientes'" 
    :page-title="'Gestión de Ingredientes'"       
    :page-subtitle="'Control de inventario y stock'"
  >
    <div class="space-y-6">
      
      <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-800 p-4 rounded-xl border border-gray-700 gap-4">
         <div class="relative w-full max-w-md">
             <input 
               v-model="searchQuery" 
               type="text" 
               placeholder="Buscar ingrediente..." 
               class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 placeholder-gray-500"
             >
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
             </svg>
         </div>
         <button 
           @click="openModal()"
           class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow-lg shadow-indigo-500/20 transition-all w-full sm:w-auto justify-center"
         >
           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
           </svg>
           <span>Nuevo Ingrediente</span>
         </button>
      </div>
  
      <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-sm overflow-x-auto">
         <table class="min-w-full divide-y divide-gray-700">
              <thead class="bg-gray-900/50">
              <tr>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nombre</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fecha Ingreso</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Vencimiento</th>
                  <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
              
              <tr v-for="item in filteredIngredientes" :key="item.ID_ingrediente" class="hover:bg-gray-700/50 transition-colors">
                  
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-white">{{ item.Nombre }}</div>
                    <div class="text-xs text-gray-400 max-w-[200px] truncate">{{ item.Descripcion }}</div>
                  </td>
  
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ item.Fecha_ingreso }}
                  </td>
  
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                        :class="[
                        new Date(item.Fecha_Vencimiento) > new Date() 
                            ? 'bg-green-500/10 text-green-400 border-green-500/20' 
                            : 'bg-red-500/10 text-red-400 border-red-500/20',
                        'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border'
                        ]"
                    >
                        {{ item.Fecha_Vencimiento }}
                    </span>
                  </td>
  
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button @click="editItem(item)" class="text-indigo-400 hover:text-indigo-300 mr-4 font-semibold">Editar</button>
                    <button @click="deleteItem(item.ID_ingrediente)" class="text-red-400 hover:text-red-300 font-semibold">Eliminar</button>
                  </td>
              </tr>
              
              <tr v-if="filteredIngredientes.length === 0">
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                  No hay ingredientes registrados.
                </td>
              </tr>

              </tbody>
         </table>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-white mb-4">{{ editingItem ? 'Editar Ingrediente' : 'Nuevo Ingrediente' }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nombre</label>
                    <input v-model="formData.nombre" type="text" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Descripción</label>
                    <textarea v-model="formData.descripcion" rows="2" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">F. Ingreso</label>
                        <input v-model="formData.fecha_ingreso" type="date" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">F. Vencimiento</label>
                        <input v-model="formData.fecha_vencimiento" type="date" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button @click="closeModal" class="flex-1 px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700">Cancelar</button>
                <button @click="saveIngrediente" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-lg">Guardar</button>
            </div>
        </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import api from '@/services/api';
import { confirmarAccion, mostrarExito, mostrarError } from '@/utils/alertas';

const ingredientes = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const editingItem = ref(null);

const formData = ref({
    nombre: '',
    descripcion: '',
    fecha_ingreso: '',
    fecha_vencimiento: ''
});


const obtenerDatos = async () => {
    try {
        const response = await api.get('/ingredientes');
        if (response.data.success) {
            ingredientes.value = response.data.data;
        }
    } catch (error) {
        console.error(error);
        mostrarError('Error al cargar ingredientes');
    }
};


const filteredIngredientes = computed(() => {
    if (!searchQuery.value) return ingredientes.value;
    return ingredientes.value.filter(i => 
        i.Nombre.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});


const openModal = () => {
    editingItem.value = null;

    const today = new Date().toISOString().split('T')[0];
    
    formData.value = { 
        nombre: '', 
        descripcion: '', 
        fecha_ingreso: today, 
        fecha_vencimiento: '' 
    };
    showModal.value = true;
};

const editItem = (item) => {
    editingItem.value = item;
    
    formData.value = {
        nombre: item.Nombre,
        descripcion: item.Descripcion,
        fecha_ingreso: item.Fecha_ingreso ? item.Fecha_ingreso.split(' ')[0] : '',
        fecha_vencimiento: item.Fecha_Vencimiento ? item.Fecha_Vencimiento.split(' ')[0] : ''
    };
    showModal.value = true;
};

const closeModal = () => showModal.value = false;


const saveIngrediente = async () => {
   
    const userStr = localStorage.getItem('user');
    const user = userStr ? JSON.parse(userStr) : null;
    const userId = user ? user.id : 1; 

    const payload = {
        nombre: formData.value.nombre,
        descripcion: formData.value.descripcion,
        fecha_ingreso: formData.value.fecha_ingreso,
        fecha_vencimiento: formData.value.fecha_vencimiento,
        created_by: userId 
    };

    if (!payload.nombre || !payload.fecha_vencimiento) {
        mostrarError("Nombre y Vencimiento son obligatorios");
        return;
    }

    try {
        let response;

        if (editingItem.value) {
            const id = editingItem.value.ID_ingrediente;
            response = await api.put(`/ingredientes/${id}`, payload);
        } else {
            response = await api.post('/ingredientes', payload);
        }

        if (response.data.success) {
            await obtenerDatos();
            closeModal();
            mostrarExito(editingItem.value ? 'Ingrediente actualizado' : 'Ingrediente creado');
        } else {
            mostrarError(response.data.message);
        }
    } catch (error) {
        console.error(error);
        mostrarError('Error al guardar en el servidor');
    }
};


const deleteItem = async (id) => {
    const confirmado = await confirmarAccion('¿Eliminar ingrediente?', 'Se marcará como eliminado.');
    
    if (confirmado) {
        try {
            const response = await api.delete(`/ingredientes/${id}`);
            if (response.data.success) {

                ingredientes.value = ingredientes.value.filter(i => i.ID_ingrediente !== id);
                mostrarExito('Ingrediente eliminado');
            } else {
                mostrarError('No se pudo eliminar');
            }
        } catch (error) {
            mostrarError('Error de conexión');
        }
    }
};

onMounted(() => {
    obtenerDatos();
});
</script>
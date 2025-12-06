<template>
  <AppLayout 
    :current-view="'reposteros'" 
    :page-title="'Gestión de Reposteros'"       
    :page-subtitle="'Control de reposteros'"
   
  >
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-800 p-4 rounded-xl border border-gray-700 gap-4">
         <div class="relative w-full max-w-md">
             <input 
               v-model="searchQuery" 
               type="text" 
               placeholder="Buscar Repostero..." 
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

      <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shodow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-gray-900/50">
            <tr>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nombre</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Apellido</th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fecha creación</th>
                  <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
              </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">

            <tr v-for="item in filteredRep" :key="item.id" class="hover:bg-gray-700/50 transition-colors">

              <td class="px-6 py-4">
                <div class="text-sm font-medium text-white">{{ item.nombres }}</div>
              </td>
              
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-white">{{ item.apellidos }}</div>
              </td>

              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                {{ item.created_at }}
              </td>

              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="deleteItem(item.id)" class="text-red-400 hover:text-red-300 font-semibold">Eliminar</button>
              </td>

            </tr>

          </tbody>

        </table>

      </div>

      <div v-if="showModal" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-white mb-4">{{ editingItem ? 'Editar Ingrediente' : 'Nuevo Ingrediente' }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nombres</label>
                    <input v-model="formData.nombres" type="text" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Apellidos</label>
                    <input v-model="formData.apellidos" type="text" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">F. Ingreso</label>
                        <input v-model="formData.created_at" type="date" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                   
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button @click="closeModal" class="flex-1 px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700">Cancelar</button>
                <button @click="saveRep" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-lg">Guardar</button>
            </div>
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


const reposteros  = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const editingItem = ref(null);

const formData = ref({
     nombres: '',
      apellidos:'',
      created_at: ''
})


const obtenerDatos = async () => {
  try {
     const response = await api.get('/reposteros');
     if (response.data.success) {
      reposteros.value = response.data.data;
      
     }
  } catch (error) {
    console.log(error);
    mostrarError('Error al caragar Datos');
    
  }
}

const filteredRep = computed(() => {
    if (!searchQuery.value) return reposteros.value;
    
    return reposteros.value.filter(i => 
        i.Nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        i.Apellido.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});


const openModal = () => {
  editingItem.value = null;

    const today = new Date().toISOString().split('T')[0];

    formData.value = {
      nombres: '',
      apellidos:'',
      created_at: today
    }


  showModal.value = true;
} 

const editItem = (item) => {
    editingItem.value = item;

    formData.value = {
      nombres: item.nombres,
      apellidos: item.apellidos,
      created_at: item.created_at
    }

  showModal.value = true;

}

const closeModal = () => showModal.value = false;


const saveRep = async () => {
  const payload = {
    nombres: formData.value.nombres,
    apellidos: formData.value.apellidos,
    created_at: formData.value.created_at,

  }

  if (!payload.nombres || !payload.apellidos) {
        mostrarError("Es obligatorio por lo menos un nombre y un apelllido");
        return;
    }

  try {
    let response;

    if (editingItem.value) {
      const id = editingItem.value.id;
      response = await api.put(`/reposteros/${id}`, payload);
    }else{
      response = await api.post('/reposteros',payload);
    }

    if (response.data.success) {

      await obtenerDatos();
      closeModal();

      mostrarExito(editingItem.value ? 'Repostero actualizado' : 'Repostero creado');
    }else{
      mostrarError(response.dat.message);
    }
  
    
  } catch (error) {
    console.log(error);
    mostrarError('Error al guardar en el servidor');
  
  }
};


const deleteItem = async (id) => {
  const confirmado = await confirmarAccion('¿Eliminar Repostero?', 'Se eliminará permanentemente.');
  
  if (confirmado) {
      try {
          const response = await api.delete(`/reposteros/${id}`);
          if (response.data.success) {
           
              reposteros.value = reposteros.value.filter(r => r.id !== id);
              mostrarExito('Repostero eliminado');
          } else {
              mostrarError(response.data.message);
          }
      } catch (error) {
          console.log(error);
          mostrarError('Error de conexión');
      }
  }
}



onMounted(() => {
    obtenerDatos();
});

</script>
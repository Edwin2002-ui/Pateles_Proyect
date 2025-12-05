<template>
  <div v-if="show" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
    <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all overflow-y-auto max-h-[90vh]">
      
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-white">{{ isEditing ? 'Editar Pastel' : 'Nuevo Pastel' }}</h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-white transition-colors">
          <span class="text-2xl">&times;</span>
        </button>
      </div>

      <div class="space-y-4">
        
        <div>
          <label class="block text-sm font-medium text-gray-400 mb-2">Nombre del Pastel</label>
          <input 
            :value="modelValue.Nombre"
            @input="updateField('Nombre', $event.target.value)"
            type="text" 
            class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500 placeholder-gray-500"
            placeholder="Ej: Tres Leches"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-400 mb-2">Descripción</label>
          <textarea 
            :value="modelValue.Descripcion"
            @input="updateField('Descripcion', $event.target.value)"
            rows="3"
            class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500 placeholder-gray-500 resize-none"
            placeholder="Ej: Pastel húmedo decorado con merengue..."
          ></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
           <div>
             <label class="block text-sm font-medium text-gray-400 mb-2">Creación</label>
             <input 
               :value="modelValue.Fecha_creacion"
               @input="updateField('Fecha_creacion', $event.target.value)"
               type="date" 
               class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500"
             >
           </div>
           <div>
             <label class="block text-sm font-medium text-gray-400 mb-2">Vencimiento</label>
             <input 
               :value="modelValue.Fecha_Vencimiento"
               @input="updateField('Fecha_Vencimiento', $event.target.value)"
               type="date" 
               class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500"
             >
           </div>
        </div>

        <div>
           <label class="block text-sm font-medium text-gray-400 mb-2">Preparado por (Repostero)</label>
           <select 
              :value="modelValue.Preparado_por"
              @change="updateField('Preparado_por', $event.target.value)"
              class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-indigo-500 appearance-none"
           >
              <option value="" disabled selected>Selecciona...</option>
              <option v-for="rep in listaReposteros" :key="rep.id" :value="rep.id">
                 {{ rep.nombres }} {{ rep.apellidos }}
              </option>
           </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Ingredientes Utilizados</label>
            <div class="grid grid-cols-2 gap-2 bg-gray-900 p-3 rounded-lg border border-gray-600 max-h-32 overflow-y-auto">
                <div v-for="ing in listaIngredientes" :key="ing.ID_ingrediente" class="flex items-center">
                    <input 
                        type="checkbox" 
                        :id="'ing-' + ing.ID_ingrediente" 
                        :value="ing.ID_ingrediente"
                        :checked="modelValue.ingredientesIds && modelValue.ingredientesIds.includes(ing.ID_ingrediente)"
                        @change="toggleIngrediente(ing.ID_ingrediente)"
                        class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-500 rounded focus:ring-indigo-500"
                    >
                    <label :for="'ing-' + ing.ID_ingrediente" class="ml-2 text-sm text-gray-300 cursor-pointer select-none">
                        {{ ing.Nombre }}
                    </label>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">Selecciona los ingredientes para la receta.</p>
        </div>

      </div>

      <div class="flex gap-3 mt-8">
        <button @click="$emit('close')" class="flex-1 px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
          Cancelar
        </button>
        <button @click="$emit('save')" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-lg shadow-indigo-500/20 transition-all">
          {{ isEditing ? 'Actualizar' : 'Guardar' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps([
    'show', 
    'modelValue', 
    'isEditing', 
    'listaReposteros',   // Array de objetos { id, nombres, apellidos }
    'listaIngredientes'  // Array de objetos { ID_ingrediente, Nombre }
]);

const emit = defineEmits(['close', 'save', 'update:modelValue']);

// Función auxiliar para actualizar campos simples
const updateField = (field, value) => {
    emit('update:modelValue', { ...props.modelValue, [field]: value });
};

// Lógica para el array de IDs de ingredientes (Para la tabla pivote)
const toggleIngrediente = (id) => {
    // Usamos 'ingredientesIds' para guardar solo los números (IDs)
    let currentIds = props.modelValue.ingredientesIds ? [...props.modelValue.ingredientesIds] : [];
    
    if (currentIds.includes(id)) {
        currentIds = currentIds.filter(item => item !== id);
    } else {
        currentIds.push(id);
    }

    updateField('ingredientesIds', currentIds);
};
</script>
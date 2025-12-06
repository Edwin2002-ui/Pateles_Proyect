<template>
  <AppLayout 
    :current-view="currentView" 
    :page-title="viewTitle"
    :page-subtitle="viewSubtitle"
    @change-view="currentView = $event"
  >
    <PastelesView v-if="currentView === 'pasteles'" />
    
    <IngredientesView v-if="currentView === 'ingredientes'" />
    
    <ReposterosView v-if="currentView === 'reposteros'" />

    <ReporteView v-if="currentView === 'estadisticas'"/>

  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue'; 
// Importamos las vistas
import PastelesView from '@/views/PastelesView.vue';
import IngredientesView from '@/views/IngredientesView.vue';
import ReposterosView from '@/views/ReposterosView.vue';
import ReporteView from '@/views/ReporteView.vue';

const currentView = ref('pasteles');

// Títulos dinámicos
const viewTitle = computed(() => {
  const titles = {
    pasteles: 'Gestión de Pasteles',
    ingredientes: 'Inventario de Ingredientes',
    reposteros: 'Equipo'
  };
  return titles[currentView.value];
});

const viewSubtitle = computed(() => {
   // ... lógica de subtítulos
   return 'Panel de administración';
});
</script>
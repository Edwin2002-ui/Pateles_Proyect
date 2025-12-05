<template>
  <AppLayout 
    :current-view="currentView" 
    :page-title="viewTitle"       
    :page-subtitle="viewSubtitle"
    @change-view="handleNavigation"
  >
    <div class="text-white text-2xl p-10 border border-dashed border-gray-600 rounded-lg">
      ¡Ahora sí me ves! Aquí iría tu tabla de ingredientes. 🥕🥚🥛
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router'; // Si usas router
import AppLayout from '@/layouts/AppLayout.vue'; 

const router = useRouter();

// 1. SOLUCIÓN JS: Definimos las variables que el Layout espera
const viewTitle = ref('Gestión de Ingredientes');
const viewSubtitle = ref('Control de inventario y stock');
const currentView = ref('reposteros'); // Para que el menú se pinte activo

// Función para cuando hacen clic en el menú
const handleNavigation = (viewName) => {
    // Si estás usando rutas, aquí harías el router.push
    console.log("Navegar a:", viewName);
    // router.push({ name: viewName }); 
};
</script>
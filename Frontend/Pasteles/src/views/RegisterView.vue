<template>
  <div class="fixed inset-0 flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-8 overflow-auto">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Crear cuenta</h2>
        <p class="text-gray-400">Únete a nosotros hoy</p>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 p-8">
        
        <div v-if="errorMessage" class="mb-4 p-3 rounded bg-red-500/10 border border-red-500/50 text-red-200 text-sm text-center">
            {{ errorMessage }}
        </div>

        <form @submit.prevent="handleRegister" class="space-y-6">
          
          <div>
            <label for="name" class="block text-sm font-medium text-gray-200 mb-2">
              Nombre completo
            </label>
            <input
              type="text"
              id="name"
              v-model="name"
              required
              placeholder="Juan Pérez"
              autocomplete="name"
              class="block w-full rounded-lg bg-gray-900/50 border border-gray-700 px-4 py-3 text-white placeholder:text-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
            />
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
              Email
            </label>
            <input
              type="email"
              id="email"
              v-model="email"
              required
              placeholder="tu@email.com"
              autocomplete="email"
              class="block w-full rounded-lg bg-gray-900/50 border border-gray-700 px-4 py-3 text-white placeholder:text-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
            />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
              Contraseña
            </label>
            <input
              type="password"
              id="password"
              v-model="password"
              required
              placeholder="••••••••"
              autocomplete="new-password"
              class="block w-full rounded-lg bg-gray-900/50 border border-gray-700 px-4 py-3 text-white placeholder:text-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
            />
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="w-full flex justify-center items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:from-indigo-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isLoading ? 'Registrando...' : 'Registrarse' }}
          </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
          ¿Ya tienes cuenta?
          <router-link :to="{ name: 'login' }" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors ml-1">
            Inicia sesión
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoading = ref(false);

const authStore = useAuthStore();
const router = useRouter();

const handleRegister = async () => {
  errorMessage.value = '';
  isLoading.value = true;

  try {
    
    await authStore.register(name.value, email.value, password.value);
    

    router.push({ name: 'login' }); 
    
  } catch (error) {
    if (error.response && error.response.status === 409) {
      errorMessage.value = 'El correo electrónico ya está registrado.';
    } else {
      errorMessage.value = 'Error al crear la cuenta. Inténtalo de nuevo.';
      console.error(error);
    }
  } finally {
    isLoading.value = false;
  }
};
</script>
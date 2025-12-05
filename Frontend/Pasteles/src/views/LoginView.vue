<template>
  <div class="fixed inset-0 flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-8 overflow-auto">
    <div class="w-full max-w-md">
      <!-- Logo and Title -->
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Inicio de sesión</h2>
        <p class="text-gray-400">Accede a tu cuenta</p>
      </div>

      <!-- Login Card -->
      <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 p-8">
        
        <!-- Mensaje de Error (Nuevo) -->
        <div v-if="errorMessage" class="mb-4 p-3 rounded bg-red-500/10 border border-red-500/50 text-red-200 text-sm text-center">
            {{ errorMessage }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email Field -->
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

          <!-- Password Field -->
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
              autocomplete="current-password"
              class="block w-full rounded-lg bg-gray-900/50 border border-gray-700 px-4 py-3 text-white placeholder:text-gray-500 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
            />
          </div>

          <!-- Sign In Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full flex justify-center items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:from-indigo-500 hover:to-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <!-- Spinner si está cargando -->
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ isLoading ? 'Entrando...' : 'Sign in' }}
          </button>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-700"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-gray-800 text-gray-400">Or continue with</span>
          </div>
        </div>

        <!-- Google Sign In Button -->
        <button
          @click="GoogleSignIn"
          type="button"
          class="w-full flex justify-center items-center gap-3 rounded-lg bg-white px-4 py-3 text-sm font-semibold text-gray-900 shadow-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
        >
          <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path
              fill="#4285F4"
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
            />
            <path
              fill="#34A853"
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
            />
            <path
              fill="#FBBC05"
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
            />
            <path
              fill="#EA4335"
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
            />
          </svg>
          Iniciar sesión con Google
        </button>
      </div>

      <!-- Footer -->
      <p class="mt-6 text-center text-sm text-gray-500">
        ¿No tienes cuenta?
        <a href="#" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors ml-1">
          Regístrate
        </a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth'; 


const email = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoading = ref(false);


const authStore = useAuthStore();

const handleLogin = async () => {
  
  errorMessage.value = '';
  isLoading.value = true;

  try {
   
    await authStore.login(email.value, password.value);
  
  } catch (error) {
   
    if (error.response && error.response.status === 401) {
      errorMessage.value = 'Credenciales incorrectas.';
    } else {
      errorMessage.value = 'Error de conexión con el servidor.';
    }
  } finally {
    isLoading.value = false;
  }
};

const GoogleSignIn = () => {
  console.log('Pendiente de implementar con Firebase o Google API');
};
</script>
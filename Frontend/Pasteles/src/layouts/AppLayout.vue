<template>
  <div class="fixed inset-0  min-h-full bg-gray-900 font-sans flex flex-col">
    
    <Disclosure as="nav" class="bg-gray-800 border-b border-gray-700 sticky top-0 z-40" v-slot="{ open }">
      <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between"> <div class="flex items-center gap-8"> <div class="shrink-0 flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20">
                P
              </div>
              <span class="text-white font-bold text-lg tracking-wide hidden md:block">Pasteles</span>
            </div>

            <div class="hidden md:block">
              <div class="flex items-baseline space-x-2">
                <a 
                  v-for="item in navigation" 
                  :key="item.name" 
                  @click.prevent="$emit('change-view', item.id)"
                  href="#"
                  :class="[
                    currentView === item.id 
                      ? 'bg-gray-900 text-white shadow-md border border-gray-700' 
                      : 'text-gray-400 hover:bg-gray-700 hover:text-white border border-transparent', 
                    'rounded-lg px-4 py-2.5 text-sm font-medium transition-all duration-200'
                  ]"
                >
                  {{ item.name }}
                </a>
              </div>
            </div>
          </div>
          
          <div class="hidden md:block">
            <div class="ml-4 flex items-center gap-6">

              <Menu as="div" class="relative ml-3">
                <MenuButton class="relative flex items-center gap-3 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 group transition-all">
                    
                    <div class="text-right hidden lg:block">
                    <p class="text-sm font-semibold text-gray-200 group-hover:text-white transition-colors">
                        {{ user.name }}
                    </p>
                    <p class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors">
                        {{ user.email }} </p>
                    </div>

                    <div class="h-10 w-10 rounded-full bg-gray-700 ring-2 ring-gray-700 group-hover:ring-indigo-500 transition-all overflow-hidden">
                    <img 
                        :src="`https://ui-avatars.com/api/?name=${user.name}&background=6366f1&color=fff`" 
                        alt="" 
                        class="h-full w-full object-cover" 
                    />
                    </div>
                </MenuButton>

                <transition 
                    enter-active-class="transition ease-out duration-100" 
                    enter-from-class="transform opacity-0 scale-95" 
                    enter-to-class="transform scale-100" 
                    leave-active-class="transition ease-in duration-75" 
                    leave-from-class="transform scale-100" 
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-700">
                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                            <a 
                            :href="item.href"
                            @click="handleItemClick(item)" 
                            :class="[
                                /* LÓGICA DE COLORES: */
                                active 
                                ? (item.logout ? 'bg-red-500/10 text-red-400' : 'bg-gray-700 text-white') 
                                : 'text-gray-300',
                                'block px-4 py-2 text-sm cursor-pointer transition-colors'
                            ]"
                            >
                            {{ item.name }}
                            </a>
                        </MenuItem>
                    </MenuItems>
                </transition>
                </Menu>
            </div>
          </div>

          

          <div class="-mr-2 flex md:hidden">
            <DisclosureButton class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
              <span class="sr-only">Abrir menú</span>
              <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden border-t border-gray-700">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <DisclosureButton v-for="item in navigation" :key="item.name" as="a" @click="$emit('change-view', item.id)" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
            {{ item.name }}
          </DisclosureButton>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <header class="bg-gray-800/50 backdrop-blur-sm shadow-sm border-b border-gray-800">
      <div class="w-full px-4 py-8 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
           <h1 class="text-3xl font-bold tracking-tight text-white">{{ pageTitle }}</h1>
           <p class="text-gray-400 mt-2 flex items-center gap-2">
             <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
             {{ pageSubtitle }}
           </p>
        </div>
        <div class="flex items-center">
            <slot name="actions"></slot>
        </div>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="animate-fade-in-up">
            <slot />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel,Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useAuthStore } from '@/stores/auth';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const userStr = localStorage.getItem('user');
const authStore = useAuthStore();
const router = useRouter();


const user = ref(userStr ? JSON.parse(userStr) : { 
  name: '', 
  email: '', 
  role: '' 
});

const userNavigation = [
  { name: 'Ajustes', href: '#' },
  { name: 'Cerrar Sesión', href: '#', logout: true }, 
];

const props = defineProps({
  currentView: String,
  pageTitle: String,
  pageSubtitle: String
});

const handleItemClick = (item) => {
  if (item.logout) {
    authStore.logout();
    router.push('/'); 
  }
};

const navigation = [
  { name: 'Pasteles', id: 'pasteles' },
  { name: 'Ingredientes', id: 'ingredientes' },
  { name: 'Resposteros', id: 'reposteros' },
  { name: 'Estadisticas', id: 'estadisticas' },
]
</script>

<style scoped>

.animate-fade-in-up {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
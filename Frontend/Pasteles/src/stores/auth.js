import { defineStore } from 'pinia';
import api from '../services/api'; 
import router from '../router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null
    }),
    
    actions: {
        async login(email, password) {
            try {
                // Llama a tu backend
                const response = await api.post('/auth/login', { email, password });
                
         
                this.token = response.data.data.token;
                this.user = response.data.data.user;

            
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));

        
                router.push('/home');
                
            } catch (error) {
                console.error("Error Login:", error);
                throw error; 
            }
        },

        async register(name, email, password) {
           
            const response = await api.post('/auth/register', {
                name: name,
                email: email,
                password: password
            });
            
        },

        logout() {
            this.token = null;
            this.user = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            router.push('/');
        }
    }
});
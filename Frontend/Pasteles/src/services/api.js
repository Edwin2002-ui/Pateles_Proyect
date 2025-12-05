import axios from 'axios';

// Accedemos a la variable de entorno usando import.meta.env
const api = axios.create({
    baseURL: 'http://pateles_proyect.test/Backend/public', 
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

api.interceptors.request.use(config => {
    const token = localStorage.getItem('token');

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    
    return config;
}, error => {
    return Promise.reject(error);
});


export default api;
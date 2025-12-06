
import Swal from 'sweetalert2';

const swalDark = Swal.mixin({
  background: '#1f2937',  
  color: '#fff',          
  confirmButtonColor: '#4f46e5', 
  cancelButtonColor: '#d33',
  iconColor: '#fbbf24',   
});

export const confirmarAccion = async (titulo = '¿Estás seguro?', texto = 'No podrás revertir esto') => {
  const result = await swalDark.fire({
    title: titulo,
    text: texto,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, continuar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true 
  });

  return result.isConfirmed; 
};


export const mostrarExito = (titulo = 'Operación exitosa') => {
  return swalDark.fire({
    title: titulo,
    icon: 'success',
    timer: 2000,
    showConfirmButton: false
  });
};


export const mostrarError = (mensaje = 'Algo salió mal') => {
  return swalDark.fire({
    title: 'Error',
    text: mensaje,
    icon: 'error',
    confirmButtonColor: '#d33' 
  });
};
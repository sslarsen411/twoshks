import './bootstrap';
import Swal from 'sweetalert2'

import.meta.glob([
    '../images/**',
]);
window.addEventListener('doAlert', event => {
    Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.icon
    })
});

import axios from 'axios';
import Alpine from 'alpinejs'
import UI from './components/UI.js';
import Clock from './components/Clock.js';

window.axios = axios;
window.Alpine = Alpine;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Alpine.data('UI', UI);
Alpine.data('Clock', Clock);
Alpine.start();

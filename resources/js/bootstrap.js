import axios from 'axios';
import Alpine from 'alpinejs'
import UI from './components/UI.js';
import Clock from './components/Clock.js';
import Weather from './components/Weather.js';
import store from './store.js';
import { UIMode } from '@lib/constants';

window.axios = axios;
window.Alpine = Alpine;
window.UIMode = UIMode;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Alpine.data('UI', UI);
Alpine.data('Clock', Clock);
Alpine.data('Weather', Weather);

Alpine.store('state', store);

Alpine.start();

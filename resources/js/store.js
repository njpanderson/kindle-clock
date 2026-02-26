import Alpine from 'alpinejs';
import dayjs from 'dayjs';
import axios from 'axios';

import { UIMode } from '@lib/constants';

export default () => ({
    tick: {
        count: 0,
        lastReset: Alpine.$persist(dayjs()).as('store.tick.lastReset')
    },

    ui: {
        brightness: null,
        darkMode: false,
        refresh: false,
        fields: {
            brightness: 0
        },
        mode: Alpine.$persist(UIMode.full).as('store.ui.mode'),
        lux: null
    },

    sun: {
        isNight: false,
    },

    weather: {
        daily: [],
    },

    toolbar: {
        open: false
    }

    ,

    async fetchWeather() {
        try {
            const response = await axios.get('/ui/weather');

            // replace the array atomically to ensure Alpine reactivity
            this.weather.daily = response.data.daily || response.data || [];
        } catch (e) {
            // swallow — debug/logging handled elsewhere
            console.error('fetchWeather error', e);
        }
    }
});

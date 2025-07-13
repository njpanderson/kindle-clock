import Alpine from 'alpinejs';
import dayjs from 'dayjs';

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

    toolbar: {
        open: false
    }
});

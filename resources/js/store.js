import { UIMode } from '@lib/constants';

export default {
    tick: {
        count: 0,
        lastReset: null
    },

    ui: {
        brightness: null,
        darkMode: false,
        refresh: false,
        fields: {
            brightness: 0
        },
        mode: UIMode.full
    },

    sun: {
        isNight: false,
    },

    toolbar: {
        open: false
    }
};

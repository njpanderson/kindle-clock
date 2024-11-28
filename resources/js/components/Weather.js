import axios from 'axios';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import { canRunOnTick } from '../lib/utils';

export default () => ({
    lastRunHour: null,

    state: {
        daily: []
    },

    init() {
        eventBus.bind('ui:tick', (event) => {
            if (canRunOnTick(event.detail.tickCount, 30, 'minute')) {
                this.updateWeather();
            }
        });

        this.updateWeather();
    },

    updateWeather() {
        debug.log('Updating weather');

        axios.get('/ui/weather')
            .then((response) => {
                this.state = response.data;
            })
    }
});

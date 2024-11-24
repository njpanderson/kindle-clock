import axios from 'axios';
import dayjs from 'dayjs';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';

export default () => ({
    lastRunHour: null,

    init() {
        eventBus.bind('ui:tick', (event) => {
            if (dayjs().hour() !== this.lastRunHour) {
                this.updateWeather();
            }
        });

        this.updateWeather();
    },

    updateWeather() {
        debug.log('Updating weather');

        this.lastRunHour = dayjs().hour();

        axios.get('/ui/weather')
            .then((response) => {
                this.$refs.weatherList.innerHTML = response.data;
            })
    },
});

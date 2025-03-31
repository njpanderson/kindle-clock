import Alpine from 'alpinejs'
import axios from 'axios';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import sun from '@lib/sun';
import { canRunOnTick } from '@lib/utils';

export default () => ({
    state: {
        time: {
            hours: '',
            minutes: '',
            meridiem: ''
        },
        sun: {
            rises: '',
            sets: ''
        },
        phasePercentage: 0,
        date: '',
        dateShort: ''
    },

    init() {
        this.store = Alpine.store('state');

        this.updateTime();
        this.setSunRiseAndSet();
        this.setTime();

        eventBus.bind('ui:tick', (event) => {
            this.updateTime();

            if (canRunOnTick(event.detail.tickCount, 30, 'minute')) {
                this.setSunRiseAndSet();
            }

            if (canRunOnTick(event.detail.tickCount, 1, 'hour')) {
                this.setTime();
            }
        });
    },

    setTime() {
        axios.post('/kindle/set-time')
            .then((response) => {
                debug.log('Set time over NTP', response);
            });
    },

    updateTime() {
        debug.log('Updating time');

        this.state.time.hours = this.now.format('h');
        this.state.time.minutes = this.now.format('mm');
        this.state.time.meridiem = this.now.format('A').toLowerCase();
        this.state.date = this.now.format('dddd, MMMM Do');
        this.state.dateShort = this.now.format('ddd Do');

        this.state.phasePercentage = this.getPhaseSpan();
    },

    getPhaseSpan() {
        const sunTimes = sun.getPhase(this.tap);

        return sun.getPhaseSpan(
            this.tap,
            sunTimes.start,
            sunTimes.end
        );
    },

    setSunRiseAndSet() {
        this.state.sun.rises = sun.getSunrise(this.tap).format('h:mm a');
        this.state.sun.sets = sun.getSunset(this.tap).format('h:mm a');
    }
});

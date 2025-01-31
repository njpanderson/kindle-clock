import Alpine from 'alpinejs'

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

        eventBus.bind('ui:tick', (event) => {
            this.updateTime();

            if (canRunOnTick(event.detail.tickCount, 30, 'minute')) {
                this.setSunRiseAndSet();
            }
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

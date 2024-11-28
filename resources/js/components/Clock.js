import dayjs from 'dayjs';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import sun from '@lib/sun';

export default () => ({
    state: {
        time: {
            hours: '',
            minutes: '',
            meridiem: ''
        },
        isNight: false,
        phasePercentage: 0,
        date: ''
    },

    init() {
        this.updateTime(this.now);

        eventBus.bind('ui:tick', this.updateTime.bind(this));
    },

    updateTime() {
        debug.log('Updating time');

        this.state.time.hours = this.now.format('h');
        this.state.time.minutes = this.now.format('mm');
        this.state.time.meridiem = this.now.format('A').toLowerCase();
        this.state.date = this.now.format('dddd, MMMM Do');

        this.state.isNight = sun.isNight(this.tap);
        this.state.phasePercentage = this.getPhaseSpan();
    },

    getPhaseSpan() {
        const sunTimes = sun.getPhase(this.tap);

        return sun.getPhaseSpan(
            this.tap,
            sunTimes.start,
            sunTimes.end
        );
    }
});

import dayjs from 'dayjs';
import advancedFormat from 'dayjs/plugin/advancedFormat';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';

dayjs.extend(advancedFormat);

export default () => ({
    init() {
        this.state.clock = {
            time: {
                hours: '',
                minutes: '',
                meridiem: ''
            },
            date: ''
        };

        this.updateTime();

        eventBus.bind('ui:tick', this.updateTime.bind(this));
    },

    updateTime() {
        debug.log('Updating time');

        const now = dayjs();

        this.state.clock.time.hours = now.format('h');
        this.state.clock.time.minutes = now.format('mm');
        this.state.clock.time.meridiem = now.format('A').toLowerCase();
        this.state.clock.date = now.format('dddd, MMMM Do');
    },
});

import dayjs from 'dayjs';
import advancedFormat from 'dayjs/plugin/advancedFormat';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';

dayjs.extend(advancedFormat);

export default () => ({
    state: {
        time: {
            hours: '',
            minutes: '',
            meridiem: ''
        },
        date: ''
    },

    init() {
        this.updateTime();

        eventBus.bind('ui:tick', this.updateTime.bind(this));
    },

    updateTime() {
        debug.log('Updating time');

        const now = dayjs();

        this.state.time.hours = now.format('h');
        this.state.time.minutes = now.format('mm');
        this.state.time.meridiem = now.format('A').toLowerCase();
        this.state.date = now.format('dddd, MMMM Do');
    },
});

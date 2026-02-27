import axios from 'axios';
import Alpine from 'alpinejs';

import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import { canRunOnTick } from '../lib/utils';

export default () => ({
    lastRunHour: null,

    init() {
        this.store = Alpine.store('state');
    }
});

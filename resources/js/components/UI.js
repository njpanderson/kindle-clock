import axios from 'axios';
import dayjs from 'dayjs';
import advancedFormat from 'dayjs/plugin/advancedFormat';

import sun from '@lib/sun';
import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import { canRunOnTick } from '../lib/utils';

dayjs.extend(advancedFormat);

export default (lat, lng) => ({
    now: null,

    debug: debug.on,

    state: {
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
            }
        },
        sun: {
            isNight: false,
            rises: '',
            sets: ''
        },
        toolbar: {
            open: false
        }
    },

    /**
     * Get the Time And Place
     */
    get tap() {
        return {
            now: this.now,
            lat: lat,
            lng: lng
        };
    },

    get canDisableLight() {
        return (
            !this.state.toolbar.open
        );
    },

    init() {
        debug.log('UI init');

        this.$watch('state.sun.isNight', (state, oldState) => {
            if (state !== oldState)
                this.setDarkMode(state);
        });

        this.$watch('state.ui.brightness', (state) => {
            // Set slider field to match screen brightness
            this.state.ui.fields.brightness = state;
        })

        eventBus.bind('ui:tick', this.onTick.bind(this));

        if (!this.debug) {
            this.setupKindle();
        }

        // this.getFrontLightBrightness();

        this.tick();
    },

    onClockClick() {
        // Boost front light
        this.frontLightBoost();
    },

    onTick(event) {
        debug.log('Tock', event.detail.tickCount);

        // Update sunset data
        this.state.sun.isNight = sun.isNight(this.tap);

        this.setSunRiseAndSet();

        if (canRunOnTick(event.detail.tickCount, 15, 'minute')) {
            this.refreshDisplay();
        }
    },

    /**
     * Force an e-ink redraw by momentarily placing a single colour overlay of
     * the currently opposing colour on top of the content.
     */
    refreshDisplay() {
        this.state.ui.refresh = true;

        setTimeout(() => {
            this.state.ui.refresh = false
        }, 500);
    },

    setupKindle() {
        axios.post('/kindle/setup')
            .then((response) => {
                this.state.ui.brightness = response.data.brightness;
            })
            .catch(() => {
                alert('There was an error setting up the kindle. Is it connected and acessible?');
            });
    },

    /**
     * Update the front light brightness based on the real Kindle data
     */
    getFrontLightBrightness() {
        axios.get('/kindle/frontlight')
            .then((response) => {
                this.state.ui.brightness = response.data;
            });
    },

    /**
     * Tick tock! This function runs every x seconds and runs whatever is needed.
     */
    tick() {
        this.state.tick.count += 1;

        debug.log(`Tick`);

        // Set now
        this.now = dayjs().add(0, 'hour');

        eventBus.fire('ui:tick', {
            tickCount: this.state.tick.count
        });

        const now = dayjs();

        // Check if 24 hours have passed since the last reset
        if (now.diff(this.state.tick.lastReset, 'hour') >= 24) {
            // Reloading will reset the tick and help prevent memory leaks
            this.reload();
        }

        // Set up next tick
        setTimeout(this.tick.bind(this), window.config.tick);
    },

    frontLightBoost() {
        debug.log(`Boosting front light`);

        console.log('this.state.ui.brightness', this.state.ui.brightness, Math.round(this.state.ui.brightness * 0.05))

        let boost = this.state.ui.brightness + Math.round(this.state.ui.brightness * 0.05);

        if (this.state.ui.brightness >= boost)
            boost = this.state.ui.brightness + 3;

        if (this.state.ui.brightness > window.config.brightness.max)
            this.state.ui.brightness = window.config.brightness.max;

        this.brightness(boost, false, false)
            .then(() => {
                window.setTimeout(() => {
                    this.resetBrightness();
                }, 5000);
            });
    },

    frontLightOff() {
        this.brightness(0, true);
    },

    resetBrightness() {
        if (!this.canDisableLight) {
            // If couldn't set back to initial
            debug.log(`Setting 2s timeout to change brightness level to ${this.state.ui.brightness}`);

            window.setTimeout(() => {
                this.resetBrightness();
            }, 2000);

            return;
        }

        this.brightness(this.state.ui.brightness, true);
    },

    async brightness(brightness, force = false, save = true) {
        if (this.state.ui.brightness === brightness && !force) {
            return Promise.resolve();
        }

        debug.log(`Setting ${(save ? '' : 'temporary ')}brightness level to ${brightness}`);

        return axios.patch(`/kindle/brightness/${brightness}`)
            .then(() => {
                if (save) {
                    this.state.ui.brightness = brightness;
                }
            })
            .catch(console.error);
    },

    openToolbar() {
        this.state.toolbar.open = true;
    },

    closeToolbar() {
        this.state.toolbar.open = false;
    },

    setDarkMode(dark) {
        this.state.ui.darkMode = dark;
    },

    setSunRiseAndSet() {
        // if (this.state.sun.isNight) {
        //     this.state.sun.rises = sun.getSunrise({
        //         ...this.tap,
        //         now: this.tap.now.add(1, 'day')
        //     }).format('h:mm a');
        // } else {
            this.state.sun.rises = sun.getSunrise(this.tap).format('h:mm a');
            this.state.sun.sets = sun.getSunset(this.tap).format('h:mm a');
        // }
    },

    toggleDarkMode() {
        this.setDarkMode(!this.state.ui.darkMode);
    },

    requestPointerLock() {
        window.parent.document.body.requestPointerLock();
    },

    reload() {
        location.reload();
    },

    /**
     * Toggle full screen on the parent frame
     */
    toggleFullScreen() {
        if (!window.parent.document.fullscreenElement) {
            window.parent.document.documentElement.requestFullscreen();
        } else if (window.parent.document.exitFullscreen) {
            window.parent.document.exitFullscreen();
        }
    }
});

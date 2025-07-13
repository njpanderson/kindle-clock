import Alpine from 'alpinejs'
import axios from 'axios';
import dayjs from 'dayjs';
import advancedFormat from 'dayjs/plugin/advancedFormat';

import sun from '@lib/sun';
import eventBus from '@lib/event-bus';
import debug from '@lib/debug';
import { canRunOnTick } from '@lib/utils';

dayjs.extend(advancedFormat);

export default (lat, lng) => ({
    now: null,

    debug: debug.on,

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
            !this.store.toolbar.open
        );
    },

    timers: [],

    init() {
        debug.log('UI init');

        this.store = Alpine.store('state');

        this.initModes();

        // Set up the kindle...
        // !this.debug
        this.initKindle().then(() => {
            // ... Then bind stuff
            this.bindEventsAndWatchers();

            // ... Then start ticking
            this.tick();
        });
    },

    async initKindle(xhr = true) {
        if (!xhr) {
            return Promise.resolve();
        }

        axios.post('/kindle/setup')
            .then((response) => {
                this.store.ui.brightness = response.data.brightness;

                this.setAutoBrightness();
            })
            .catch(() => {
                alert('There was an error setting up the kindle. Is it connected and acessible?');
            });
    },

    initModes() {
        this.now = dayjs();

        this.store.sun.isNight = sun.isNight(this.tap);
        this.store.ui.darkMode = this.store.sun.isNight;
    },

    toggleUIMode() {
        this.setUIMode(
            (this.store.ui.mode === UIMode.clock ?
                UIMode.full :
                UIMode.clock),
            false
        );
    },

    bindEventsAndWatchers() {
        this.$watch('store.sun.isNight', (state, oldState) => {
            // Based on the night value...
            if (state !== oldState) {
                // ... Set dark mode
                this.setDarkMode(state);

                // ... And set the UI mode (between full and clock)
                this.setUIMode(state ? UIMode.full : UIMode.clock, false);
            }
        });

        this.$watch('store.ui.brightness', (state) => {
            // Set slider field to match screen brightness
            this.store.ui.fields.brightness = state;
        });

        eventBus.bind('ui:tick', this.onTick.bind(this));
    },

    onUIClick() {
        // Boost front light
        this.frontLightBoost();
    },

    onTick(event) {
        debug.log('Tock', event.detail.tickCount);

        // Update sunset data
        this.store.sun.isNight = sun.isNight(this.tap);

        if (canRunOnTick(event.detail.tickCount, 1, 'minute')) {
            this.setAutoBrightness();
        }

        if (canRunOnTick(event.detail.tickCount, 15, 'minute')) {
            this.refreshDisplay();
        }
    },

    /**
     * Tick tock! This function runs every x seconds and runs whatever is needed.
     */
    tick() {
        this.store.tick.count += 1;

        debug.log(`Tick`);

        // Set now
        this.now = dayjs();

        eventBus.fire('ui:tick', {
            tickCount: this.store.tick.count
        });

        const now = dayjs();

        // Reloading will reset the tick and help prevent memory leaks
        // Check if a new day has dawned since the last reset
        if (now.diff(this.store.tick.lastReset, 'day') >= 1) {
            this.store.tick.lastReset = now;

            // Reload after a short delay just to ensure localstorage is set
            // I probably don't need to delay this but whatever
            window.setTimeout(() => {
                this.reload();
            }, 500);

            // Early return to prevent the timer restarting
            return;
        }

        // Set up next tick
        setTimeout(this.tick.bind(this), window.config.tick);
    },

    /**
     * Force an e-ink redraw by momentarily placing a single colour overlay of
     * the currently opposing colour on top of the content.
     */
    refreshDisplay() {
        this.store.ui.refresh = true;

        setTimeout(() => {
            this.store.ui.refresh = false
        }, 500);
    },

    /**
     * Update the front light brightness based on the real Kindle data
     */
    getFrontLightBrightness() {
        axios.get('/kindle/frontlight')
            .then((response) => {
                this.store.ui.brightness = response.data;
            });
    },

    frontLightBoost() {
        debug.log(`Boosting front light`);

        console.log('this.store.ui.brightness', this.store.ui.brightness, Math.round(this.store.ui.brightness * 0.05))

        let boost = this.store.ui.brightness + Math.round(this.store.ui.brightness * 0.05);

        if (this.store.ui.brightness >= boost)
            boost = this.store.ui.brightness + 3;

        if (this.store.ui.brightness > window.config.brightness.max)
            this.store.ui.brightness = window.config.brightness.max;

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

    async setAutoBrightness() {
        console.log('setAutoBrightness', window.config.brightness.auto.enabled, window.config.brightness.auto.mode);
        if (!window.config.brightness.auto.enabled)
            return;

        if (window.config.brightness.auto.mode === 'sun') {
            this.setAutoBrightnessForSun();
        } else if (window.config.brightness.auto.mode === 'lux') {
            this.setAutoBrightnessForLux();
        }
    },

    async setAutoBrightnessForSun() {
        console.log('setAutoBrightnessForSun');
        // Night time
        if (
            this.store.sun.isNight &&
            this.store.ui.brightness !== window.config.brightness.auto.sun.night
        ) {
            return await this.brightness(window.config.brightness.auto.sun.night, true);
        }

        // Day time
        if (
            !this.store.sun.isNight &&
            this.store.ui.brightness !== window.config.brightness.auto.sun.day
        ) {
            return await this.brightness(window.config.brightness.auto.sun.day, true);
        }
    },

    async setAutoBrightnessForLux() {
        console.log('setAutoBrightnessForLux');

        // Get brightness level from lux
        const response = await axios.get('/kindle/brightness-for-lux');

        if (response.data) {
            this.store.ui.lux = response.data.lux
            return await this.brightness(response.data.brightness, true);
        }
    },

    resetBrightness() {
        if (!this.canDisableLight) {
            // If couldn't set back to initial
            debug.log(`Setting 2s timeout to change brightness level to ${this.store.ui.brightness}`);

            window.setTimeout(() => {
                this.resetBrightness();
            }, 1000);

            return;
        }

        this.brightness(this.store.ui.brightness, true);
    },

    async brightness(brightness, force = false, save = true) {
        if (this.store.ui.brightness === brightness && !force) {
            return Promise.resolve();
        }

        debug.log(`Setting ${(save ? '' : 'temporary ')}brightness level to ${brightness}`);

        return axios.patch(`/kindle/brightness/${brightness}`)
            .then(() => {
                if (save) {
                    this.store.ui.brightness = brightness;
                }
            })
            .catch(console.error);
    },

    getBgImageStyle() {
        if (this.store.ui.mode !== 'clock')
            return '';

        return 'background-image: url(\'images/potd/night/32305g1.jpg\')';
    },

    setUIMode(mode, delay = true) {
        if (this.timers.setUIMode)
            clearTimeout(this.timers.setUIMode);

        if (delay) {
            this.timers.setUIMode = setTimeout(() => {
                this.setUIMode(mode, false);
            }, 10000);
            return;
        }

        this.store.ui.mode = mode;
    },

    openToolbar() {
        this.store.toolbar.open = true;
    },

    closeToolbar() {
        this.store.toolbar.open = false;
    },

    setDarkMode(dark) {
        this.store.ui.darkMode = dark;
    },

    toggleDarkMode() {
        this.setDarkMode(!this.store.ui.darkMode);
    },

    requestPointerLock() {
        window.parent.document.body.requestPointerLock();
    },

    reload() {
        console.log('reloading!');
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


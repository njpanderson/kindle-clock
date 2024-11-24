import axios from 'axios';

import sun from '@lib/sun';
import eventBus from '@lib/event-bus';
import debug from '@lib/debug';

export default (lat, lng) => ({
    state: {
        ui: {
            brightness: 0,
            darkMode: false
        },
        sun: {
            hasSet: null,
            rises: '',
            sets: ''
        },
        clock: {},
        toolbar: {
            open: false
        }
    },

    config: {
        tick: 20000 // ms
    },

    get canDisableLight() {
        return (
            !this.state.toolbar.open
        );
    },

    init() {
        debug.log('UI init');

        this.$watch('state.sun.hasSet', (state, oldState) => {
            if (state !== oldState)
                this.setDarkMode(state);
        });

        this.getFrontLightBrightness();

        this.tick();
    },

    onClockClick() {
        // Boost front light
        this.frontLightBoost();
    },

    /**
     * Update the front light brightness based on the real Kindle data
     */
    getFrontLightBrightness() {
        axios.get(`/kindle/frontlight`)
            .then((response) => {
                this.state.ui.brightness = response.data;
            });
    },

    /**
     * Tick tock! This function runs every x seconds and runs whatever is needed.
     */
    tick() {
        debug.log('Tick');

        eventBus.fire('ui:tick');

        // Update sunset data
        this.state.sun.hasSet = sun.getHasSunSet(lat, lng);
        this.state.sun.rises = sun.getSunrise(lat, lng).format('h:mm a');
        this.state.sun.sets = sun.getSunset(lat, lng).format('h:mm a');

        // Set up next tick
        setTimeout(this.tick.bind(this), this.config.tick);
    },

    frontLightBoost() {
        debug.log(`Boosting front light`);

        axios.patch(`/kindle/frontlight/boost`)
            .then((response) => {
                const result = response.data

                this.state.ui.brightness = result.new_level;

                if (result.status === 'increased') {
                    debug.log(`Setting timeout to change brightness level to ${result.previous_level}`);

                    window.setTimeout(() => {
                        this.brightness(result.previous_level, false, true);
                    }, 5000);
                } else {
                    debug.log(`Front light already at brightness ${result.previous_level}. Not boosting.`);
                }
            })
            .catch(console.error);
    },

    frontLightOff() {
        this.brightness(0, true);
    },

    brightness(brightness, force = false, tryAgain = false) {
        if (this.state.ui.brightness === brightness) {
            return;
        }

        if (brightness === 0 && !this.canDisableLight && !force) {
            if (tryAgain) {
                // If couldn't lower, try again
                debug.log(`Setting timeout to change brightness level to ${brightness}`);

                window.setTimeout(() => {
                    this.brightness(0, force, true);
                }, 5000);
            }

            return;
        }

        debug.log(`Setting brightness level to ${brightness}`);

        axios.patch(`/kindle/brightness/${brightness}`)
            .then(() => {
                this.state.ui.brightness = brightness;
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

    toggleDarkMode() {
        this.setDarkMode(!this.state.ui.darkMode);
    },

    requestPointerLock() {
        document.body.requestPointerLock()
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

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
        sunset: null,
        clock: {},
        toolbar: {
            open: false
        }
    },

    config: {
        tick: 20000 // ms
    },

    get canLowerBrightness() {
        return (
            !this.state.toolbar.open
        );
    },

    init() {
        debug.log('UI init');

        this.$watch('state.ui.brightness', this.onBrightnessChange.bind(this));
        this.$watch('state.sunset', (state, oldState) => {
            if (state !== oldState)
                this.setDarkMode(state);
        });

        this.tick();
    },

    onClockClick() {
        // TODO: Change this based on sunrise/sunset!
        this.brightness(this.state.sunset ? 3 : 20);
    },

    onBrightnessChange() {
        axios.get(`/kindle/brightness/${this.state.ui.brightness}`);
    },

    tick() {
        debug.log('Tick');

        eventBus.fire('ui:tick');

        this.state.sunset = sun.hasSunSet(lat, lng);

        setTimeout(this.tick.bind(this), this.config.tick);
    },

    brightness(brightness, tryAgain = false) {
        if (this.state.ui.brightness === brightness) {
            return;
        }

        if (brightness !== 0 || (brightness === 0 && this.canLowerBrightness)) {
            debug.log(`Setting brightness level to ${brightness}`);
            this.state.ui.brightness = brightness;
        }

        if (brightness > 0 || (!this.canLowerBrightness && tryAgain)) {
            // If more than zero OR couldn't lower, try again
            debug.log(`Setting timeout to change brightness level to 0`);
            window.setTimeout(() => {
                this.brightness(0, true);
            }, 5000);
        }
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

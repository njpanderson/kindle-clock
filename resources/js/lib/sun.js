import dayjs from 'dayjs';
import SunCalc from 'suncalc';

/**
 * Most of these functions take a tap (Time And Place) object defined in UI.js
 */
export default {
    times(tap) {
        return SunCalc.getTimes(tap.now.toDate(), tap.lat, tap.lng)
    },

    getSunrise(tap) {
        return dayjs(this.times(tap).sunrise);
    },

    getSunset(tap) {
        return dayjs(this.times(tap).sunset);
    },

    isNight(tap) {
        const times = this.times(tap);

        return tap.now.isAfter(times.dusk) || tap.now.isBefore(times.dawn);
    },

    getPhase(tap) {
        // Ensure currentTime is a dayjs instance
        if (!dayjs.isDayjs(tap.now)) {
            throw new Error('tap.now must be a dayjs instance');
        }

        // Get the sun times for the given date and location
        const sunTimes = this.times(tap);
        const dawn = dayjs(sunTimes.dawn);
        const dusk = dayjs(sunTimes.dusk);

        let start, end;

        if (tap.now.isBefore(dawn)) {
            // Before dawn: use previous day's dusk
            const previousDusk = dayjs(sunTimes.dusk).subtract(1, 'day');
            start = previousDusk;
            end = dawn;
        } else if (tap.now.isAfter(dusk)) {
            // After dusk: use next day's dawn
            const nextDawn = dayjs(sunTimes.dawn).add(1, 'day');
            start = dusk;
            end = nextDawn;
        } else {
            // Between dawn and dusk
            start = dawn;
            end = dusk;
        }

        return {
            start,
            end
        };
    },

    getPhaseSpan(tap, start, end) {
        // Calculate the total duration
        const totalDuration = end.diff(start);

        // Calculate the elapsed time
        let elapsedTime = 0;

        elapsedTime = tap.now.isBefore(end) ? tap.now.diff(start) : totalDuration;

        // Calculate the percentage
        const percentage = (elapsedTime / totalDuration) * 100;

        // Ensure percentage is between 0 and 100
        return Math.min(Math.max(percentage, 0), 100);
    }
};

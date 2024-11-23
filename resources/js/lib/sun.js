import dayjs from 'dayjs';
import SunCalc from 'suncalc';

export default {
    hasSunSet(lat, lng) {
        const times = SunCalc.getTimes(new Date(), lat, lng);
        const now = dayjs();

        // Some debug methods for simulating sun rise/set
        // const now = dayjs().add(2, 'hour').add(40, 'minute');
        // const now = dayjs().subtract(8, 'hour').add(22, 'minute');
        // console.log(now.format('H:m:ss'), times.dawn, times.dusk);

        return now.isAfter(times.dusk) || now.isBefore(times.dawn);
    }
};

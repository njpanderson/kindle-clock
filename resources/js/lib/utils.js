export function canRunOnTick(tickCount, number, intervalType = 'minute') {
    const ticksPerMinute = 60 / (window.config.tick / 1000); // 60 seconds / 20 seconds = 3 ticks per minute
    const ticksPerHour = ticksPerMinute * 60; // 180 ticks per hour

    let ticksRequired;

    if (intervalType === 'minute') {
        ticksRequired = ticksPerMinute * number;
    } else if (intervalType === 'hour') {
        ticksRequired = ticksPerHour * number;
    } else {
        throw new Error('Invalid interval type. Use "minute" or "hour".');
    }

    return tickCount % ticksRequired === 0;
}

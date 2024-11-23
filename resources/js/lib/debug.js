/**
 * Debugging class — this has been constructed using prototypes and binding
 * to ensure that when the fuctions are called, the originally executing line
 * is shown in the stack trace of console, instead of the line in this file.
 */
function Debug() {}

const debug = (import.meta.env.VITE_APP_DEBUG == 'true');

Debug.prototype.on = debug;

if (debug) {
    console.log('Debugging ON');
}

/**
 * Create a log group
 * @param {string} $label Log group label
 */
Debug.prototype.group = (() => {
    if (debug) {
        return console.group.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * Create a collapsed log group
 * @param {string} $label Log group label
 */
Debug.prototype.groupCollapsed = (() => {
    if (debug) {
        return console.groupCollapsed.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * End a log group
 */
Debug.prototype.groupEnd = (() => {
    if (debug) {
        return console.groupEnd.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * Log an entry to the console
 * @param {string...} Data to log
 */
Debug.prototype.log = (() => {
    if (debug) {
        return console.debug.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * Log a warning to the console
 * @param {string...} Data to log
 */
Debug.prototype.warn = (() => {
    if (debug) {
        return console.warn.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * Log an error to the console
 * @param {string...} Data to log
 */
Debug.prototype.error = (() => {
    if (debug) {
        return console.error.bind(window.console);
    } else {
        return () => {}
    }
})();

/**
 * Log a tabular data to the console
 * @param {object} Tabular data to log
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Console/table
 */
Debug.prototype.table = (() => {
    if (debug) {
        return console.table.bind(window.console);
    } else {
        return () => {}
    }
})();

Debug.prototype.listItem = ((item, desc) => {
    if (debug) {
        console.log(
            ` - %c${item}%c ${(desc ? `(${desc})` : '')}`,
            'color: green',
            'color: darkgrey'
        );
    } else {
        return () => {}
    }
});

Debug.prototype.mark = ((message, callback) => {
    console.groupCollapsed(`Mark !! ${message}`);
    console.trace();
    console.groupEnd();
    callback();
});

export default (new Debug);

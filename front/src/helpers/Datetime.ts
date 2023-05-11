export const DateTimeUTCFormat = (date: Date): string => {
    return (
        [
            date.getUTCFullYear(),
            DateTimePadTo2Digits(date.getUTCMonth() + 1),
            DateTimePadTo2Digits(date.getUTCDate()),
        ].join('-') +
        'T' +
        [
            DateTimePadTo2Digits(date.getUTCHours()),
            DateTimePadTo2Digits(date.getUTCMinutes()),
            DateTimePadTo2Digits(date.getUTCSeconds()),
        ].join(':') +
        '.000Z'
    );
};

// todo: move to string helper ?
export const DateTimePadTo2Digits = (num: number) => {
    return num.toString().padStart(2, '0');
};

export const DateTimeToday = (): string => {
    const date = new Date();

    return (
        [
            date.getFullYear(),
            DateTimePadTo2Digits(date.getMonth() + 1),
            DateTimePadTo2Digits(date.getDate()),
        ].join('-') + ' 00:00:00'
    );
};

/**
 * return 2023-01-20T12:01:41.216Z format
 */
export const DateTimeIsValid = (datetime: string): string | false => {
    const timestamp = Date.parse(datetime);

    if (!isNaN(timestamp)) {
        return DateTimeUTCFormat(new Date(timestamp));
    }

    return false;
};

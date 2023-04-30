export const DateTimeUTCFormat = (v: Date) => {
    // v is a `Date` object 2023-04-20T22:27:37+00:00

    const pad = '00';
    const yy = v.getUTCFullYear().toString();
    const mm = (v.getUTCMonth() + 1).toString();
    const dd = v.getUTCDate().toString();
    const hh = v.getUTCHours().toString();
    const min = v.getUTCMinutes().toString();
    const sec = v.getUTCSeconds().toString();

    return `${yy}-${(pad + mm).slice(-2)}-${(pad + dd).slice(-2)}T${(
        pad + hh
    ).slice(-2)}:${(pad + min).slice(-2)}:${(pad + sec).slice(-2)}+00:00`;
};

export const DateTimeToday = () => {
    const inputDate = new Date();

    let day, month;

    day = inputDate.getDate();
    month = inputDate.getMonth() + 1;
    const year = inputDate.getFullYear();

    day = day.toString().padStart(2, '0');

    month = month.toString().padStart(2, '0');

    return `${year}-${month}-${day} 00:00:00`;
};

export const isValid = (datetime: string) => {
    const timestamp = Date.parse(datetime);

    if (!isNaN(timestamp)) {
        return DateTimeUTCFormat(new Date(timestamp));
    }

    return false;
};
import {
    DateTimeIsValid,
    DateTimeToday,
    DateTimeUTCFormat,
    DateTimePadTo2Digits,
} from '../../src/helpers/Datetime';

describe('Test Datetime helper cases', () => {
    test('[DateTimeIsValid] Return false when datetime is invalid', () => {
        expect(DateTimeIsValid('invalid-date')).toBeFalsy();
    });

    test('[DateTimeIsValid] Return false when datetime is empty', () => {
        expect(DateTimeIsValid('')).toBeFalsy();
    });

    test('[DateTimeIsValid] Formatting a valid datetime string to datetime format', () => {
        expect(DateTimeIsValid('2024')).toBe('2024-01-01T00:00:00.000Z');
        expect(DateTimeIsValid('2024-04-29')).toBe('2024-04-29T00:00:00.000Z');
        expect(DateTimeIsValid('2024-04-29 10:11')).toBe(
            '2024-04-29T10:11:00.000Z'
        );
    });

    test('[DateTimeToday] Get today datetime', () => {
        expect(DateTimeToday()).toBe('2023-05-01 00:00:00');
    });

    test('[DateTimeUTCFormat] Convert date to string format', () => {
        expect(DateTimeUTCFormat(new Date('2024-04-29'))).toBe(
            '2024-04-29T00:00:00.000Z'
        );
        expect(DateTimeUTCFormat(new Date('2024-04-29 10:11:33'))).toBe(
            '2024-04-29T10:11:33.000Z'
        );
    });

    test('[DateTimePadTo2Digits] Return string with 2 charcter', () => {
        expect(DateTimePadTo2Digits(33)).toBe('33');
        expect(DateTimePadTo2Digits(3)).toBe('03');
    });
});

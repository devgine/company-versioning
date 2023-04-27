import { useRecordContext } from 'react-admin';

export const AddressTitle = () => {
    const record = useRecordContext();

    return `Address "${record.id || ''}"`;
};

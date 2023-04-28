import { useRecordContext } from 'react-admin';

export const AddressTitle = () => {
    const record = useRecordContext();

    return <span>Address "{record.id || ''}"</span>;
};

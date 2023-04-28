import { useRecordContext } from 'react-admin';

export const CompanyTitle = () => {
    const record = useRecordContext();

    return <span>Company "{record ? String(record.name) : ''}"</span>;
};

import {
    useRecordContext
} from "react-admin";

export const CompanyTitle = () => {
    const record = useRecordContext();

    return `Company "${record ? String(record.name) : ''}"`;
};

import {
    Datagrid,
    TextField,
    useRecordContext,
    Show,
    SimpleShowLayout,
    DateField,
    ArrayField
} from 'react-admin';

import CompanyHistory from './CompanyHistoryForm'

const CompanyTitle = () => {
    const record = useRecordContext();
    return <span>Company {record ? `"${record.name}"` : ''}</span>;
};

export default () => (
    <Show title={<CompanyTitle />} aside={<CompanyHistory />}>
        <SimpleShowLayout>
            <TextField source='id' />
            <TextField source='name' />
            <TextField source='sirenNumber' />
            <TextField source='capital' />
            <TextField source='legalStatus' />
            <TextField source='registrationCity' />
            <DateField source='registrationDate' showTime />
            <DateField source='createdDate' showTime />
            <DateField source='lastUpdateDate' showTime />
            <ArrayField source='addresses'>
                <Datagrid>
                    <TextField source='number' />
                    <TextField source='streetType' />
                    <TextField source='streetName' />
                    <TextField source='city' />
                    <TextField source='zipCode' />
                </Datagrid>
            </ArrayField>
        </SimpleShowLayout>
    </Show>
);

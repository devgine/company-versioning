import { TextField, Show, SimpleShowLayout, DateField } from 'react-admin';
import { AddressTitle } from './common';

export default () => (
    <Show title={<AddressTitle />}>
        <SimpleShowLayout>
            <TextField source="id" />
            <TextField source="company.name" />
            <TextField source="number" />
            <TextField source="streetType" />
            <TextField source="streetName" />
            <TextField source="city" />
            <TextField source="zipCode" />
            <DateField source="createdDate" showTime />
            <DateField source="lastUpdateDate" showTime />
        </SimpleShowLayout>
    </Show>
);

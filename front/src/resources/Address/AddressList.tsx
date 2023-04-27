import {
    List,
    Datagrid,
    TextField,
    EditButton,
    TextInput,
    ShowButton,
    DateField,
} from 'react-admin';

const addressFilters = [<TextInput source="search" alwaysOn />];

export default () => (
    <List filters={addressFilters}>
        <Datagrid>
            <TextField source="id" />
            <TextField source="company.name" />
            <TextField source="number" />
            <TextField source="streetType" />
            <TextField source="streetName" />
            <TextField source="city" />
            <TextField source="zipCode" />
            <DateField source="createdDate" showTime />
            <DateField source="lastUpdateDate" showTime />
            <EditButton />
            <ShowButton />
        </Datagrid>
    </List>
);

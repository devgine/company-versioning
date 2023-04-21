import {
    List,
    Datagrid,
    TextField,
    EditButton,
    Edit,
    SimpleForm,
    TextInput,
    Create,
    useRecordContext,
    Show, AutocompleteInput,
    SimpleShowLayout, ShowButton, DateField, ReferenceInput
} from "react-admin";

const addressFilters = [
    <TextInput source='search' alwaysOn />
];

const AddressTitle = () => {
    const record = useRecordContext();
    return <span>Address {record ? `"${record.name}"` : ''}</span>;
};

export const AddressList = () => (
    <List filters={addressFilters}>
        <Datagrid>
            <TextField source='id' />
            <TextField source="company.name" />
            <TextField source="number" />
            <TextField source="streetType" />
            <TextField source="streetName" />
            <TextField source="city" />
            <TextField source="zipCode" />
            <DateField source='createdDate' showTime />
            <DateField source='lastUpdateDate' showTime />
            <EditButton />
            <ShowButton />
        </Datagrid>
    </List>
);

export const AddressShow = () => (
    <Show title={<AddressTitle />}>
        <SimpleShowLayout>
            <TextField source='id' />
            <TextField source="company.name" />
            <TextField source="number" />
            <TextField source="streetType" />
            <TextField source="streetName" />
            <TextField source="city" />
            <TextField source="zipCode" />
            <DateField source='createdDate' showTime />
            <DateField source='lastUpdateDate' showTime />
        </SimpleShowLayout>
    </Show>
);

export const AddressEdit = () => (
    <Edit title={<AddressTitle />}>
        <SimpleForm>
            <TextInput source='company.name' disabled />
            <TextInput source='number' required />
            <TextInput source='streetType' required />
            <TextInput source='streetName' required />
            <TextInput source='city' required />
            <TextInput source='zipCode' required />
        </SimpleForm>
    </Edit>
);

export const AddressCreate = () => (
    <Create>
        <SimpleForm>
            <ReferenceInput source="company_id" reference="companies" label="name" >
                <AutocompleteInput optionText="name"  />
            </ReferenceInput>
            <TextInput source='number' required />
            <TextInput source='streetType' required />
            <TextInput source='streetName' required />
            <TextInput source='city' required />
            <TextInput source='zipCode' required />
        </SimpleForm>
    </Create>
);

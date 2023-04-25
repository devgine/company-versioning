import {
    Edit,
    SimpleForm,
    TextInput,
    useRecordContext
} from "react-admin";

const AddressTitle = () => {
    const record = useRecordContext();
    return <span>Address {record ? `"${record.name}"` : ''}</span>;
};

export default () => (
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

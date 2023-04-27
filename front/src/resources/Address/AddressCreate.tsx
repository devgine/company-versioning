import {
    SimpleForm,
    TextInput,
    Create,
    AutocompleteInput,
    ReferenceInput,
} from 'react-admin';

export default () => (
    <Create>
        <SimpleForm>
            <ReferenceInput
                source="company_id"
                reference="companies"
                label="name"
            >
                <AutocompleteInput optionText="name" />
            </ReferenceInput>
            <TextInput source="number" required />
            <TextInput source="streetType" required />
            <TextInput source="streetName" required />
            <TextInput source="city" required />
            <TextInput source="zipCode" required />
        </SimpleForm>
    </Create>
);

import {
    SimpleForm,
    TextInput,
    DateTimeInput,
    Create,
    NumberInput,
    AutocompleteInput,
    ReferenceInput
} from "react-admin";

export default () => (
    <Create>
        <SimpleForm>
            <TextInput source='name' required />
            <TextInput source='sirenNumber' required />
            <NumberInput source='capital' required />
            <ReferenceInput source='legalStatus' reference='legalStatuses' label='label' required >
                <AutocompleteInput optionValue='label' optionText='label' />
            </ReferenceInput>
            <TextInput source='registrationCity' required />
            <DateTimeInput source='registrationDate' required />
        </SimpleForm>
    </Create>
);

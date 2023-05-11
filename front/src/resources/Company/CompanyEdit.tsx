import {
    Edit,
    SimpleForm,
    TextInput,
    DateTimeInput,
    NumberInput,
    AutocompleteInput,
    ReferenceInput,
} from 'react-admin';

import { CompanyTitle } from './common';

export default () => (
    <Edit title={<CompanyTitle />}>
        <SimpleForm>
            <TextInput source="name" required />
            <TextInput source="sirenNumber" required />
            <NumberInput source="capital" required />
            <ReferenceInput
                source="legalStatus"
                reference="legal-statuses"
                label="label"
                required
            >
                <AutocompleteInput optionValue="label" optionText="label" />
            </ReferenceInput>
            <TextInput source="registrationCity" required />
            <DateTimeInput source="registrationDate" required />
        </SimpleForm>
    </Edit>
);

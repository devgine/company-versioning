import {
    Edit,
    SimpleForm,
    TextInput,
    DateTimeInput,
    useRecordContext,
    NumberInput,
    AutocompleteInput,
    ReferenceInput
} from "react-admin";

const CompanyTitle = () => {
    const record = useRecordContext();
    return <span>Company {record ? `"${record.name}"` : ''}</span>;
};

export default () => (
    <Edit title={<CompanyTitle />}>
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
    </Edit>
);

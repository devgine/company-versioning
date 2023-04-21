import {
    List,
    Datagrid,
    TextField,
    EditButton,
    Edit,
    SimpleForm,
    TextInput,
    DateTimeInput,
    Create,
    useRecordContext,
    Show,
    NumberInput,
    AutocompleteInput,
    SimpleShowLayout, ShowButton, DateField, ArrayField, ReferenceInput
} from "react-admin";

const companyFilters = [
    <TextInput source='search' alwaysOn />
];

const CompanyTitle = () => {
    const record = useRecordContext();
    return <span>Company {record ? `"${record.name}"` : ''}</span>;
};

export const CompanyList = () => (
    <List filters={companyFilters}>
        <Datagrid>
            <TextField source='id' />
            <TextField source='name' />
            <TextField source='sirenNumber' />
            <TextField source='capital' />
            <TextField source='legalStatus' />
            <TextField source='registrationCity' />
            <DateField source='registrationDate' showTime />
            <DateField source='createdDate' showTime />
            <DateField source='lastUpdateDate' showTime />
            <EditButton />
            <ShowButton />
        </Datagrid>
    </List>
);

export const CompanyShow = () => (
    <Show title={<CompanyTitle />}>
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
            <ArrayField source="addresses">
                <Datagrid>
                    <TextField source="number" />
                    <TextField source="streetType" />
                    <TextField source="streetName" />
                    <TextField source="city" />
                    <TextField source="zipCode" />
                    <DateField source='createdDate' showTime />
                    <DateField source='lastUpdateDate' showTime />
                </Datagrid>
            </ArrayField>
        </SimpleShowLayout>
    </Show>
);

export const CompanyEdit = () => (
    <Edit title={<CompanyTitle />}>
        <SimpleForm>
            <TextInput source='name' required />
            <TextInput source='sirenNumber' required />
            <NumberInput source='capital' required />
            <ReferenceInput source="legalStatus" reference="legalStatuses" label="label" required >
                <AutocompleteInput optionValue="label" optionText="label" />
            </ReferenceInput>
            <TextInput source='registrationCity' required />
            <DateTimeInput source='registrationDate' required />
        </SimpleForm>
    </Edit>
);

export const CompanyCreate = () => (
    <Create>
        <SimpleForm>
            <TextInput source='name' required />
            <TextInput source='sirenNumber' required />
            <NumberInput source='capital' required />
            <ReferenceInput source="legalStatus" reference="legalStatuses" label="label" required >
                <AutocompleteInput optionValue="label" optionText="label" />
            </ReferenceInput>
            <TextInput source='registrationCity' required />
            <DateTimeInput source='registrationDate' required />
        </SimpleForm>
    </Create>
);

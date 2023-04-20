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
    SimpleShowLayout, ShowButton, DateField
} from "react-admin";

const companyFilters = [
    <TextInput source='search' alwaysOn />
];

const CompanyTitle = () => {
    const record = useRecordContext();
    return <span>Site {record ? `"${record.name}"` : ''}</span>;
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
        </SimpleShowLayout>
    </Show>
);

export const CompanyEdit = () => (
    <Edit title={<CompanyTitle />}>
        <SimpleForm>
            <TextInput source='name' />
            <TextInput source='sirenNumber' />
            <TextInput source='capital' />
            <TextInput source='legalStatus' />
            <TextInput source='registrationCity' />
            <DateTimeInput source='registrationDate' showTime />
        </SimpleForm>
    </Edit>
);

export const CompanyCreate = () => (
    <Create>
        <SimpleForm>
            <TextInput source='name' />
            <TextInput source='sirenNumber' />
            <TextInput source='capital' />
            <TextInput source='legalStatus' />
            <TextInput source='registrationCity' />
            <DateTimeInput source='registrationDate' showTime />
        </SimpleForm>
    </Create>
);

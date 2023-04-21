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
    AutocompleteInput, Form, Button,
    SimpleShowLayout, ShowButton, DateField, ArrayField, ReferenceInput
} from "react-admin";

import { Box, Typography, Grid } from '@mui/material';

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
    <Show title={<CompanyTitle />} aside={<Aside />}>
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
                </Datagrid>
            </ArrayField>
        </SimpleShowLayout>
    </Show>
);


const Aside = () => {
    return (<Box sx={{width: '50%', margin: '1em'}}>
        <Typography variant="h6">History</Typography>

        <Show>
            <SimpleForm>
                <DateTimeInput source='lastUpdateDate'/>
            </SimpleForm>
            <SimpleShowLayout source='data'>
                <TextField source='id'/>
                <TextField source='name'/>
                <TextField source='sirenNumber'/>
                <TextField source='capital'/>
                <TextField source='legalStatus'/>
                <TextField source='registrationCity'/>
                <DateField source='registrationDate' showTime/>
                <DateField source='createdDate' showTime/>
                <DateField source='lastUpdateDate' showTime/>
                <ArrayField source="addresses">
                    <Datagrid>
                        <TextField source="number"/>
                        <TextField source="streetType"/>
                        <TextField source="streetName"/>
                        <TextField source="city"/>
                        <TextField source="zipCode"/>
                    </Datagrid>
                </ArrayField>
            </SimpleShowLayout>
        </Show>
    </Box>);
};

const handleClick = () => {

    const record = useRecordContext();

    fetch(`/companyHistories/${record.id}/2023-04-21T12:00:00+02:00`);
};

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

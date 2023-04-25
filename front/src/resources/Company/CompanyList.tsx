import {
    List,
    Datagrid,
    TextField,
    EditButton,
    TextInput,
    ShowButton,
    DateField
} from "react-admin";


const companyFilters = [
    <TextInput source='search' alwaysOn />
];

export default () => (
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


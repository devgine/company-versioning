import {
    Datagrid,
    TextField,
    TextInput,
    DateTimeInput,
    Show,
    SimpleForm,
    Toolbar, Button, Loading, useGetOne, RefreshButton,
    SimpleShowLayout, DateField, ArrayField, useGetRecordId, RecordContextProvider, useShowContext
} from 'react-admin';
import { useForm, SubmitHandler, useFormState } from 'react-hook-form';
import { Box, Typography, Card } from '@mui/material';
import { DateTimeFormat } from '../../helpers/Datetime';

const CompanyShowHistory = (props) => {
    //const { isLoading } = useShowContext();
    //if (isLoading) console.log(props);
    const {id, datetime} = props;

    const { data, loading, error } = useGetOne(`companyHistories`, {id: `${id}/${datetime}`});
    if (error) { console.log('useGetOne error');return null;}
    if (loading) { return <Loading />; }

    return (
        <RecordContextProvider value={data}>
            <SimpleShowLayout emptyWhileLoading>
                <TextField source='name' />
                <TextField source='sirenNumber'/>
                <TextField source='capital'/>
                <TextField source='legalStatus'/>
                <TextField source='registrationCity'/>
                <DateField source='registrationDate' showTime/>
                <ArrayField source='addresses'>
                    <Datagrid>
                        <TextField source='number'/>
                        <TextField source='streetType'/>
                        <TextField source='streetName'/>
                        <TextField source='city'/>
                        <TextField source='zipCode'/>
                    </Datagrid>
                </ArrayField>
            </SimpleShowLayout>
        </RecordContextProvider>
    );
};
const HistoryFormToolbar = () => (
    <Toolbar>
        <RefreshButton />
    </Toolbar>
);

export default () => {
    const recordId = useGetRecordId();
    let dateTimeSelected = '2023-04-20T12:00:00+02:00';

    const { register, handleSubmit, control } = useForm({
        defaultValues: {
            historyDateTime: dateTimeSelected
        }
    });

    const { dirtyFields } = useFormState({
        control
    });
    //console.log(register("dateTimeSelected"));

    const onSubmit = (data) => console.log(data);
    //const onSubmit: SubmitHandler = (data, e) => {
    //    console.log(DateTimeFormat(e.lastUpdateDate));
    //    dateTimeSelected = DateTimeFormat(e.lastUpdateDate);
    //}

    return (
        <Box sx={{width: '50%', margin: '1em'}}>
            <Typography variant="h6">History</Typography>
            <Show>
                <form onSubmit={handleSubmit(onSubmit)}>
                    <input id='historyDateTime' {...register("historyDateTime")} type='datetime-local' placeholder="Datetime" />
                    <input type='submit' label='Show history' />
                </form>

                {dirtyFields.historyDateTime && (
                    <Card>
                        <CompanyShowHistory id={recordId} datetime={document.getElementById('historyDateTime').value} />
                    </Card>
                )}
            </Show>
        </Box>
    );
};

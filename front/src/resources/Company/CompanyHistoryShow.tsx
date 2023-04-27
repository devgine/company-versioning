import {
    Datagrid,
    TextField,
    Loading,
    useGetOne,
    SimpleShowLayout,
    DateField,
    ArrayField,
    RecordContextProvider,
    useNotify
} from 'react-admin';
import { isValid } from '../../helpers/Datetime'

export const CompanyHistoryShow = (props) => {
    const {id, datetime} = props;

    const notify = useNotify();

    const datetimeTz = isValid(datetime);

    if (!datetimeTz) {
        return notify(`CompanyHistoryShow: ${datetime} id not a valid datetime to fetch history`, {type: 'error'});
    }

    const { data, loading, error } = useGetOne(`companyHistories`, {id: `${id}/${datetimeTz}`}, {retry: 0});

    if (loading) { return <Loading />; }
    if (error) {
        return notify(`Could not load history: ${error.message}`, {type: 'error'});
    }

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

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
import {CompanyHistoryRequestInterface} from "../../DTO/CompanyHistoryRequestInterface";
import {UseGetOneResponseInterface} from "../../DTO/ClientResponseInterface";

export const CompanyHistoryShow = (props: CompanyHistoryRequestInterface) => {
    const {id, datetime} = props;

    const notify = useNotify();

    const datetimeTz = isValid(datetime);

    if (!datetimeTz) {
        return notify(`CompanyHistoryShow: ${datetime} id not a valid datetime to fetch history`, {type: 'error'});
    }

    const companyResponse: UseGetOneResponseInterface = useGetOne(
        `companyHistories`,
        {id: `${id}/${datetimeTz}`},
        {retry: 0}
    );

    if (companyResponse.loading) { return <Loading />; }
    if (companyResponse.error) {
        return notify(`Could not load history: ${companyResponse.error.message}`, {type: 'error'});
    }

    return (
        <RecordContextProvider value={companyResponse.data}>
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

import {
    Datagrid,
    TextField,
    Loading,
    useGetOne,
    SimpleShowLayout,
    DateField,
    ArrayField,
    RecordContextProvider,
    useNotify,
} from 'react-admin';
import { DateTimeIsValid } from '../../helpers/Datetime';
import { CompanyHistoryRequestInterface } from '../../DTO/CompanyHistoryRequestInterface';
import { UseGetOneResponseInterface } from '../../DTO/ClientResponseInterface';

export const CompanyHistoryShow = (props: CompanyHistoryRequestInterface) => {
    const { id, datetime } = props;

    const notify = useNotify();

    if (null === datetime) {
        return null;
    }

    const datetimeTz = DateTimeIsValid(datetime);

    if (!datetimeTz) {
        notify(
            `CompanyHistoryShow: "${datetime}" is not a valid datetime to fetch history`,
            { type: 'error' }
        );

        return null;
    }

    const companyResponse: UseGetOneResponseInterface = useGetOne(
        `companyHistories`,
        { id: `${id}/${datetimeTz}` },
        { retry: 0 }
    );

    if (true === companyResponse.loading) {
        return <Loading />;
    }

    if (undefined === companyResponse.data || null === companyResponse.data) {
        notify(`Not found history`, { type: 'info' });

        return null;
    }

    if (companyResponse.error !== null && companyResponse.error !== undefined) {
        notify(`Could not load history: ${companyResponse.error.message}`, {
            type: 'error',
        });

        return null;
    }

    return (
        <RecordContextProvider value={companyResponse.data}>
            <SimpleShowLayout>
                <TextField source="name" />
                <TextField source="sirenNumber" />
                <TextField source="capital" />
                <TextField source="legalStatus" />
                <TextField source="registrationCity" />
                <DateField source="registrationDate" showTime />
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
        </RecordContextProvider>
    );
};

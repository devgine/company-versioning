import {
    Datagrid,
    TextField,
    Loading,
    useGetOne,
    SimpleShowLayout,
    DateField,
    ArrayField,
    RecordContextProvider
} from 'react-admin';

export const CompanyHistoryShow = (props) => {
    const {id, datetime} = props;

    // todo validate datetime format
    const { data, loading, error } = useGetOne(`companyHistories`, {id: `${id}/${datetime}`});
    // todo catch error and display error alert
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

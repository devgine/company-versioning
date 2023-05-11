import {
    SimpleForm,
    Toolbar,
    Button,
    useGetRecordId,
    useNotify,
    useStore,
} from 'react-admin';

import { Box, Typography, Card } from '@mui/material';

import { CompanyHistoryShow } from './CompanyHistoryShow';
import { DateTimeIsValid } from '../../helpers/Datetime';

const HistoryFormToolbar = () => {
    const notify = useNotify();
    const [companyHistoryDatetime, setCompanyHistoryDatetime] = useStore(
        'company.history.datetime.search'
    );

    const handleClick = () => {
        const datetime: string | null = (
            document.getElementById(
                'companyHistoryDatetimeSearch'
            ) as HTMLInputElement
        ).value.trim();

        if ('' === datetime || null === datetime) {
            notify('Datetime should not be empty', { type: 'error' });

            return null;
        }

        if (!DateTimeIsValid(datetime)) {
            notify('Invalid datetime passed', { type: 'error' });

            return null;
        }

        setCompanyHistoryDatetime(datetime);
    };

    return (
        <Toolbar>
            <Button label="Show history" onClick={handleClick} />
        </Toolbar>
    );
};

export default () => {
    const recordId = useGetRecordId();
    const [companyHistoryDatetime, setCompanyHistoryDatetime] = useStore(
        'company.history.datetime.search',
        ''
    );

    return (
        <Box sx={{ width: '40%', margin: '1em' }}>
            <Typography variant="h6">History</Typography>
            <Card>
                <SimpleForm toolbar={<HistoryFormToolbar />}>
                    <div>
                        <label>Datetime</label>
                    </div>
                    <div>
                        <input type="text" id="companyHistoryDatetimeSearch" />
                    </div>
                </SimpleForm>
                {companyHistoryDatetime !== '' && (
                    <CompanyHistoryShow
                        id={Number(recordId)}
                        datetime={String(companyHistoryDatetime)}
                    />
                )}
            </Card>
        </Box>
    );
};

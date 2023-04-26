import { useRef } from 'react';
import {
    Show,
    SimpleForm,
    Toolbar, RefreshButton,
    useGetRecordId
} from 'react-admin';

import { Box, Typography } from '@mui/material';

import { TextInputRef } from '../../helpers/TextInputRef'
import { CompanyHistoryShow } from './CompanyHistoryShow'

const HistoryFormToolbar = () => (
    <Toolbar>
        <RefreshButton />
    </Toolbar>
);

export default () => {
    const recordId = useGetRecordId();
    const datetimeRef = useRef(null);

    return (
        <Box sx={{width: '40%', margin: '1em'}}>
            <Typography variant="h6">History</Typography>
            <Show>
                <SimpleForm toolbar={<HistoryFormToolbar />}>
                    {/* todo validate datetime format */}
                    <TextInputRef ref={datetimeRef} />
                </SimpleForm>
                {datetimeRef.current && datetimeRef.current.value &&
                    <CompanyHistoryShow id={recordId} datetime={datetimeRef.current.value} />
                }
            </Show>
        </Box>
    );
};

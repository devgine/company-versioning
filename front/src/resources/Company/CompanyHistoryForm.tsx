import { useRef } from 'react';
import {
    Show,
    SimpleForm,
    Toolbar,
    Button,
    useGetRecordId,
    useRefresh,
    useNotify
} from 'react-admin';

import { Box, Typography } from '@mui/material';

import { TextInputRef } from '../../helpers/TextInputRef'
import { CompanyHistoryShow } from './CompanyHistoryShow'
import {isValid} from "../../helpers/Datetime";

const HistoryFormToolbar = () => {
    const refresh = useRefresh();
    const notify = useNotify();

    const handleClick = () => {
        const datetime = document.getElementById('companyHistoryDatetimeSearch').value.trim()

        if ('' === datetime) {
            notify('Datetime should not be empty', { type: 'error' });
            return null;
        }

        if (!isValid(datetime)) {
            notify('Invalid datetime passed', { type: 'error' });

            return null;
        }

        refresh();
    }

    return (
        <Toolbar>
            <Button label="Show history" onClick={handleClick} />
        </Toolbar>
    );
};

export default () => {
    const recordId = useGetRecordId();
    const datetimeRef = useRef(null);

    return (
        <Box sx={{width: '40%', margin: '1em'}}>
            <Typography variant="h6">History</Typography>
            <Show>
                <SimpleForm toolbar={<HistoryFormToolbar />}>
                    <TextInputRef label='Datetime' id='companyHistoryDatetimeSearch' ref={datetimeRef} />
                </SimpleForm>
                {datetimeRef.current && datetimeRef.current.value &&
                    <CompanyHistoryShow id={recordId} datetime={datetimeRef.current.value} />
                }
            </Show>
        </Box>
    );
};

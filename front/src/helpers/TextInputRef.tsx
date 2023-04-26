import { forwardRef } from 'react';

export const TextInputRef = forwardRef((props, ref) => {
    return (
        <div>
            <div><label>Datetime (format: yyyy-mm-dd hh:ii:ss)</label></div>
            <div><input type='text' ref={ref} {...props} placeholder='yyyy-mm-dd hh:ii:ss' /></div>
        </div>
    )
})

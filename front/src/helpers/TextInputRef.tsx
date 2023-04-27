import { forwardRef } from 'react';

export const TextInputRef = forwardRef((props, ref) => {
    const { label, ...rest } = props;

    return (
        <div>
            <div>
                <label>{label}</label>
            </div>
            <div>
                <input type="text" ref={ref} {...rest} />
            </div>
        </div>
    );
});

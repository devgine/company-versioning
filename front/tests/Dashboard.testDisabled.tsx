import Dashboard from '../src/Dashboard';
import { render, screen } from '@testing-library/react';

describe('Test Dashboard cases', () => {
    test('[Dashboard] Render Dashboard correctly', async () => {
        render(<Dashboard />);

        const title = await screen.findAllByText(
            /Welcome to the administration/
        );
        const content = await screen.findAllByText(
            /Lorem ipsum sic dolor amet/
        );
        expect(title).toHaveLength(1);
        expect(content).toHaveLength(3);
    });
});

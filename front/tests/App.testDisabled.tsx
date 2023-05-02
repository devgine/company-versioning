import App from '../src/App';
import { render, screen } from '@testing-library/react';

describe('Test App cases', () => {
    test('[App] Render App correctly', async () => {
        render(<App />);

        const menuDashboard = await screen.findAllByText(/Dashboard/);
        const menuCompanies = await screen.findAllByText(/Companies/);
        const menuAddresses = await screen.findAllByText(/Addresses/);
        expect(menuDashboard).toHaveLength(1);
        expect(menuCompanies).toHaveLength(1);
        expect(menuAddresses).toHaveLength(1);
    });
});

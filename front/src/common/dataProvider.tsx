import jsonServerProvider from 'ra-data-json-server';

export default jsonServerProvider(import.meta.env.VITE_API_V1_URL);

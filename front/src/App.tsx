import {Admin, Resource, fetchUtils} from 'react-admin';
import jsonServerProvider from 'ra-data-json-server';
import { CompanyList, CompanyShow, CompanyEdit, CompanyCreate } from './resources/Company';
import LinkIcon from '@mui/icons-material/Link';
import { Dashboard } from './Dashboard';
import { authProvider } from './security/authProvider';

const fetchJson = (url: string, options: fetchUtils.Options = {}) => {
    const customHeaders = (options.headers ||
        new Headers({
            Accept: 'application/json',
        })) as Headers;
    // add your own headers here
    customHeaders.set('Authorization', 'Bearer '+localStorage.getItem('token'));
    options.headers = customHeaders;

    return fetchUtils.fetchJson(url, options);
}

const dataProvider = jsonServerProvider(import.meta.env.VITE_API_V1_URL, fetchJson);

const App = () => (
    <Admin authProvider={authProvider} dataProvider={dataProvider} dashboard={Dashboard}>
        <Resource name='companies' list={CompanyList} show={CompanyShow} edit={CompanyEdit} create={CompanyCreate} icon={LinkIcon} />
    </Admin>
);

export default App;

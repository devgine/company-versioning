import {Admin, Resource} from 'react-admin';
import jsonServerProvider from 'ra-data-json-server';
import { CompanyList, CompanyShow, CompanyEdit, CompanyCreate } from './resources/Company';
import LinkIcon from '@mui/icons-material/Link';
import { Dashboard } from './Dashboard';
import {AddressCreate, AddressEdit, AddressList, AddressShow} from "./resources/Address";

const dataProvider = jsonServerProvider(import.meta.env.VITE_API_V1_URL);

const App = () => (
    <Admin dataProvider={dataProvider} dashboard={Dashboard}>
        <Resource name='companies' list={CompanyList} show={CompanyShow} edit={CompanyEdit} create={CompanyCreate} icon={LinkIcon} />
        <Resource name='addresses' list={AddressList} show={AddressShow} edit={AddressEdit} create={AddressCreate} icon={LinkIcon} />
    </Admin>
);

export default App;

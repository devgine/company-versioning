import { Admin, Resource } from 'react-admin';
import { Dashboard } from './Dashboard';
import dataProvider from './common/dataProvider';
import companies from './resources/Company';
import addresses from './resources/Address';

const App = () => (
    <Admin dataProvider={dataProvider} dashboard={Dashboard}>
        <Resource name="companies" {...companies} />
        <Resource name="addresses" {...addresses} />
    </Admin>
);

export default App;

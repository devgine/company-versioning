import { Admin, Resource } from 'react-admin';
import { Dashboard } from './Dashboard';
import ApiDataProvider from './common/dataProvider';
import FakeDataProvider from './common/dataProviderFaker';
import companies from './resources/Company';
import addresses from './resources/Address';

const dataProvider =
    import.meta.env.VITE_FAKER_REST == 0 ? ApiDataProvider : FakeDataProvider;

const App = () => (
    <Admin dataProvider={dataProvider} dashboard={Dashboard}>
        <Resource name="companies" {...companies} />
        <Resource name="addresses" {...addresses} />
    </Admin>
);

export default App;

import {
    Edit,
    SimpleForm,
    TextInput
} from "react-admin";
import { AddressTitle } from "./common";

export default () => (
    <Edit title={<AddressTitle />}>
        <SimpleForm>
            <TextInput source='company.name' disabled />
            <TextInput source='number' required />
            <TextInput source='streetType' required />
            <TextInput source='streetName' required />
            <TextInput source='city' required />
            <TextInput source='zipCode' required />
        </SimpleForm>
    </Edit>
);

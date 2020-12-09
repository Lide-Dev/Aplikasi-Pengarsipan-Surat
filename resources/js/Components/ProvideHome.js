
import {
    provide,
} from "vue";
/**
*  Make props provide for inject to child component of Home.
* - dataTable = Data of table view if provided by server.
* - props = All props available if provided by server.
*
* Reserved variable on server:
* - props
* - props.data
*
* @param props Reactive props at home component.
* @param globalProps Props that defined by server.
*
*/
export const provideConfig = (props, globalProps) => {
    console.log("Checking provide var", props, globalProps)
    if ("props" in globalProps.dyComponent) {
        provide("props", props);
        if (props.data) {
            provide("dataTable", props.data);
        }
        if (props.tableConfig) {
            provide("tableConfig", props.tableConfig);
        }
    }
};

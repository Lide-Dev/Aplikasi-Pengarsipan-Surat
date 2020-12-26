import Axios from "axios";
// import { ref } from "vue";

const CancelToken = Axios.CancelToken;

/**
 * Handle the request if auto complete search box updated.
 *
 * @param {Ref} search Is good if it get a new value. Make sure it `Ref` variable.
 * @param {Ref} tags Tags selection. Make sure it `Ref` variable.
 * @param {Ref} autoComplete Auto complete select option. Make sure it `Ref` variable.
 * @param {Props} props Contain props of autocomplete component.
 * @param {Ref} cancelToken Cancel token of this request if it server data. Make sure it `Ref` variable.
 * @param {Ref} clientData Data of auto complete if it not from server. Make sure it `Ref` variable.
 */
// eslint-disable-next-line no-unused-vars
export function onUpdateSearchBox(search, tags, autoComplete, props, cancelToken = "", clientData = "") {
    // console.log(search);
    if (search.length > 0) {
        if (props.isServerData) {
            if (typeof cancelToken.value === "function") {
                cancelToken.value();
            }
            let data = { search: search };
            if (tags.value.length > 0) {
                let filterNotNew = tags.value.filter((e) => {
                    return !("new" in e);
                  });
                data.restrict = filterNotNew
                    .map(function (item) {
                        return item.id;
                    })
                    .toString();
            }
            Axios.get(props.url, {
                params: data,
                cancelToken: new CancelToken(function executor(c) {
                    cancelToken.value = c;
                }),
            })
                .then(({ data }) => {
                    autoComplete.value = data;
                })
                .catch((e) => {
                    if (Axios.isCancel(e)) console.log('Canceled autocompleted cause a new request autocompleted')
                });
        }
    } else {
        autoComplete.value = [];
    }
}

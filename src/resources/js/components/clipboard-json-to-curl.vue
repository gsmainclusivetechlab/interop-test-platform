<template>
    <clipboard-copy-btn
        :data="jsonToCurl(request)"
        :show-copy-icon="false"
        :copy-button-text="'Copy cURL'"
        style="position: inherit; top: -0.5rem; left: 35rem;"
    ></clipboard-copy-btn>
</template>

<script>
export default {
    props: {
        request: {
            type: Object,
        },
    },
    methods: {
        jsonToCurl: function (request) {
            let headers = '';
            for (let h in request['headers']) {
                headers += `--header '${h}: ${request['headers'][h][0]}' `;
            }

            const info = `${request['method']} '${request['uri']}'`;
            const body = `--data-raw ${JSON.stringify(request['body'])}`;
            const result = `curl --location --request ${info} ${headers} ${body}`;

            return result;
        },
    },
};
</script>

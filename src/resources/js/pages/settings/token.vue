<template>
    <layout>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">API token</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row align-items-center" v-if="token">
                        <h3 class="card-title">
                            Copy the API token and save it in your notes as it will be hidden once you leave this page.
                            No worries, it can be regenerated anytime.
                        </h3>
                        <label class="col-sm-3">
                            <b>Token:</b>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input
                                    id="token"
                                    type="text"
                                    :value="token"
                                    class="form-control"
                                    readonly
                                />
                                <clipboard-copy-btn
                                    target="#token"
                                    title="Copy"
                                ></clipboard-copy-btn>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center" v-else>
                        <h3 class="card-title">
                            Click "Generate" to get a new API token.
                            Please note that the previous one will be removed (if it exists).
                        </h3>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button
                    type="button"
                    class="btn btn-primary btn-space"
                    @click.prevent="generate"
                >
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Generate
                </button>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/settings';

export default {
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            token: '',
        };
    },
    methods: {
        generate() {
            this.sending = true;
            axios
                .post(route('settings.token.generate'))
                .then((data) => {
                    this.token = data.data;
                    this.sending = false;
                });
        },
    },
};
</script>

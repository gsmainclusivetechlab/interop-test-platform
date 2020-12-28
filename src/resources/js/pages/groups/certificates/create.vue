<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Upload new certificates for ${group.name}` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label"> Name </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.props.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    CA certificate
                                </label>
                                <b-form-file
                                    v-model="form.ca_crt"
                                    placeholder="Choose file..."
                                    v-bind:class="{
                                        'is-invalid': $page.props.errors.ca_crt,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.ca_crt"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.ca_crt }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Client certificate
                                </label>
                                <b-form-file
                                    v-model="form.client_crt"
                                    placeholder="Choose file..."
                                    v-bind:class="{
                                        'is-invalid':
                                            $page.props.errors.client_crt,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.client_crt"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.client_crt }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Client key </label>
                                <b-form-file
                                    v-model="form.client_key"
                                    placeholder="Choose file..."
                                    v-bind:class="{
                                        'is-invalid':
                                            $page.props.errors.client_key,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.client_key"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.client_key }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Pass phrase </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.passphrase"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.passphrase,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.passphrase"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.passphrase }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="
                                    route('groups.certificates.index', group.id)
                                "
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: `Upload new certificates for ${this.group.name}`,
        };
    },
    components: {
        Layout,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                name: '',
                ca_crt: '',
                client_crt: '',
                client_key: '',
                passphrase: '',
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;

            let data = new FormData();
            Object.keys(this.form).forEach((key) =>
                data.append(key, this.form[key])
            );

            this.$inertia
                .post(route('groups.certificates.store', this.group), data)
                .then(() => (this.sending = false));
        },
    },
};
</script>

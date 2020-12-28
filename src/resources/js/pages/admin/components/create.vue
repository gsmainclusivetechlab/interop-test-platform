<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Create new component</b>
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
                                <label class="form-label"> Base URL </label>
                                <input
                                    name="base_url"
                                    type="text"
                                    class="form-control"
                                    v-model="form.base_url"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.base_url,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.base_url"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.base_url }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Connections </label>
                                <selectize
                                    v-model="connections"
                                    multiple
                                    class="form-select"
                                    placeholder="Select connections..."
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.connections_id,
                                    }"
                                    label="name"
                                    :keys="['name']"
                                    :options="connectionsList"
                                    :createItem="false"
                                    :searchFn="searchConnections"
                                >
                                    <template
                                        slot="option"
                                        slot-scope="{ option }"
                                    >
                                        <div>{{ option.name }}</div>
                                        <div class="text-muted small">
                                            {{ option.base_url }}
                                        </div>
                                    </template>
                                </selectize>
                                <span
                                    v-if="$page.props.errors.groups_id"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{
                                            collect(
                                                $page.props.errors.groups_id
                                            ).implode(' ')
                                        }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Description </label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.props.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.description }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.sutable"
                                    />
                                    <span class="form-check-label"
                                        >Can be SUT</span
                                    >
                                </label>
                                <span
                                    v-if="$page.props.errors.sutable"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.sutable }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="form.sutable"
                                    />
                                    <span class="form-check-label"
                                        >Can be SUT</span
                                    >
                                </label>
                                <span
                                    v-if="$page.props.errors.sutable"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.sutable }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.components.index')"
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
    metaInfo: {
        title: 'Create new component',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            connections: [],
            connectionsList: [],
            form: {
                name: null,
                sutable: true,
                base_url: null,
                description: null,
                connections_id: null,
            },
        };
    },
    watch: {
        connections: {
            immediate: true,
            handler: function (value) {
                this.form.connections_id = value
                    ? collect(value)
                          .map((item) => item.id)
                          .all()
                    : [];
            },
        },
    },
    mounted() {
        this.loadConnectionsList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(route('admin.components.store'), this.form, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
        loadConnectionsList(query = '') {
            axios
                .get(route('admin.components.connection-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.connectionsList = collect(result.data.data)
                        .whereNotIn('id', this.form.connections_id)
                        .all();
                });
        },
        searchConnections(query, callback) {
            this.loadConnectionsList(query);
            callback();
        },
    },
};
</script>

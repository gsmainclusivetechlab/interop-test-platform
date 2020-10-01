<template>
    <layout :session="session">
        <div class="col-6 m-auto mt-3">
            <form class="card" @submit.prevent="submit">
                <div class="card-header">
                    <h3 class="card-title">
                        Session info
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="col-sm-3">
                            <b>Name</b>
                        </label>
                        <input
                            type="text"
                            v-model="form.name"
                            class="form-control"
                            :class="{
                                    'is-invalid': $page.errors.name,
                                }"
                        />
                        <span
                            v-if="$page.errors.name"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.name }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-3">
                            <b>Description</b>
                        </label>
                        <textarea
                            class="form-control"
                            rows="5"
                            v-model="form.description"
                            :class="{
                                        'is-invalid': $page.errors.description,
                                    }"
                        ></textarea>
                        <span
                            v-if="$page.errors.description"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.description }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-3">
                            <b>SUT</b>
                        </label>
                        <input
                            type="text"
                            :value="component.name"
                            readonly
                            class="form-control"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-3">
                            <b>SUT URL</b>
                        </label>
                        <input
                            type="text"
                            v-model="form.component_base_url"
                            class="form-control"
                            :class="{
                                    'is-invalid': $page.errors.component_base_url,
                                }"
                        />
                        <span
                            v-if="$page.errors.component_base_url"
                            class="invalid-feedback"
                        >
                            {{ $page.errors.component_base_url }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="col-sm-3">
                            <b>Environments</b>
                        </label>
                        <selectize
                            v-model="groupEnvironment"
                            class="form-select mb-3"
                            placeholder="Group environments..."
                            label="name"
                            :keys="['name']"
                            :options="groupEnvironmentsList"
                            :createItem="false"
                            :searchFn="searchGroupEnvironments"
                            v-if="hasGroupEnvironments"
                        />
                        <ul class="list-group">
                            <li class="list-group-item" v-for="(environment, index) in form.environments">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        placeholder="Name"
                                        class="form-control"
                                        v-model="environment.name"
                                        :class="{
                                                        'is-invalid': collect($page.errors).has(`environments.${index}.name`),
                                                    }"
                                    />
                                    <input
                                        type="text"
                                        placeholder="Value"
                                        class="form-control"
                                        v-model="environment.value"
                                        :class="{
                                                        'is-invalid': collect($page.errors).has(`environments.${index}.value`),
                                                    }"
                                    />
                                    <button type="button" class="btn btn-secondary btn-icon" @click="deleteEnvironment(index)">
                                        <icon name="trash" />
                                    </button>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <button type="button" class="btn btn-block btn-secondary" @click="addEnvironment">
                                    <icon name="plus" />
                                    Add New
                                </button>
                            </li>
                        </ul>
                        <div
                            class="text-danger small mt-2"
                            v-if="$page.errors.environments"
                        >
                            <strong>{{ $page.errors.environments }}</strong>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <inertia-link
                        :href="route('sessions.show', session.id)"
                        class="btn btn-link"
                    >
                        Cancel
                    </inertia-link>
                    <button type="submit" class="btn btn-primary btn-space">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/app';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        component: {
            type: Object,
            required: true,
        },
        hasGroupEnvironments: {
            type: Boolean,
            required: true,
        }
    },
    data() {
        return {
            sending: false,
            groupEnvironment: null,
            groupEnvironmentsList: [],
            form: {
                name: this.session.name,
                description: this.session.description,
                environments: this.session.environments ?? [],
                component_id: this.component.id,
                component_base_url: this.component.base_url,
            },
        };
    },
    watch: {
        groupEnvironment: {
            immediate: true,
            handler: function (value) {
                if (value !== null) {
                    let environments = [];
                    environments = environments.concat(value.variables);
                    this.form.environments = environments;
                }
            },
        },
    },
    mounted() {
        this.loadGroupEnvironmentList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .put(route('sessions.update', this.session.id), this.form)
                .then(() => (this.sending = false));
        },
        addEnvironment() {
            this.form.environments.push({name: '', value: ''});
        },
        deleteEnvironment(index) {
            this.form.environments.splice(index, 1);
        },
        loadGroupEnvironmentList(query = '') {
            axios
                .get(route('sessions.register.group-environment-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.groupEnvironmentsList = result.data.data;
                });
        },
        searchGroupEnvironments(query, callback) {
            this.loadGroupEnvironmentList(query);
            callback();
        },
    },
};
</script>

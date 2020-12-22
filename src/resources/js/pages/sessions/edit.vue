<template>
    <layout :session="session">
        <div class="col-10 m-auto mt-3">
            <form class="card" @submit.prevent="submit">
                <div class="card-header">
                    <h3 class="card-title">Update session info</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="col-sm-3">
                                    <b>Name</b>
                                </label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    class="form-control"
                                    :readonly="isCompliance"
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
                                    :readonly="isCompliance"
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
                            <div class="mb-3" v-if="components.data">
                                <label class="col-sm-3">
                                    <b>SUTs</b>
                                </label>
                                <selectize
                                    :value="components.data"
                                    :options="components.data"
                                    keyBy="id"
                                    :keys="['name']"
                                    label="name"
                                    :multiple="true"
                                    class="form-select"
                                    disabled
                                />
                            </div>
                            <div
                                class="mb-3"
                                v-for="component in components.data"
                            >
                                <div class="mb-3">
                                    <label>
                                        <b>{{ component.name }} URL</b>
                                    </label>
                                    <input
                                        type="text"
                                        v-model="
                                            form.components[component.id].base_url
                                        "
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                $page.errors[
                                                    `components.${component.id}.base_url`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.errors[
                                                `components.${component.id}.base_url`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{
                                            $page.errors[
                                                `components.${component.id}.base_url`
                                            ]
                                        }}
                                    </span>
                                </div>
                                <div>
                                    <label class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            v-model="form.components[
                                                component.id
                                            ].use_encryption"
                                        />
                                        <span class="form-check-label">Use Encryption</span>
                                    </label>
                                    <span
                                        v-if="$page.errors[`components.${component.id}.use_encryption`]"
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{ $page.errors[`components.${component.id}.use_encryption`] }}
                                        </strong>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="col-sm-3">
                                    <b>Environments</b>
                                </label>
                                <selectize
                                    v-model="groupEnvironment"
                                    class="form-select mb-3"
                                    placeholder="Group environment..."
                                    label="name"
                                    :keys="['name']"
                                    :options="groupEnvironmentsList"
                                    :createItem="false"
                                    :searchFn="searchGroupEnvironments"
                                    v-if="hasGroupEnvironments"
                                />
                                <environments
                                    v-model="form.environments"
                                    ref="environments"
                                />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.errors.environments"
                                >
                                    <strong>{{
                                        $page.errors.environments
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="col-sm-3">
                                    <b>Test Cases</b>
                                </label>
                                <test-case-checkboxes
                                    style="max-height: 485px"
                                    :session="session"
                                    :useCases="useCases"
                                    :isCompliance="isCompliance"
                                    v-model="form.test_cases"
                                />
                                <div
                                    class="text-danger small mt-3"
                                    v-if="$page.errors.test_cases"
                                >
                                    <strong>{{
                                        $page.errors.test_cases
                                    }}</strong>
                                </div>
                            </div>
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
import Environments from '@/components/environments';
import TestCaseCheckboxes from '@/components/sessions/test-case-checkboxes';

export default {
    components: {
        Layout,
        Environments,
        TestCaseCheckboxes,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
        hasGroupEnvironments: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
            groupEnvironment: this.session.groupEnvironment
                ? this.session.groupEnvironment.data
                : null,
            groupEnvironmentsList: [],
            form: {
                name: this.session.name,
                description: this.session.description,
                group_environment_id: this.session.groupEnvironment
                    ? this.session.groupEnvironment.data.id
                    : null,
                environments: this.session.environments,
                components: collect(this.components.data)
                    .map(function (component) {
                        return collect(component).only(['id', 'base_url', 'use_encryption']).all()
                    })
                    .keyBy('id')
                    .all(),
                test_cases: collect(this.session.testCases.data)
                    .pluck('id')
                    .all(),
            },
        };
    },
    watch: {
        groupEnvironment: {
            immediate: false,
            handler: function (value) {
                this.form.group_environment_id = value ? value.id : null;
                if (value !== null) {
                    this.form.environments = value.variables;
                    this.$refs.environments.syncEnvironments(
                        this.form.environments
                    );
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

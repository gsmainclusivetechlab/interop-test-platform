<template>
    <layout :sut="session.sut" :components="components">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Configure components</h3>
                </div>
                <div class="card-body">
                    <template v-for="connection in sut.connections.data">
                        <div class="mb-3">
                            <label class="form-label">
                                {{ connection.name }}
                            </label>
                            <div class="input-group">
                                <input
                                    :id="`testing-${connection.id}`"
                                    type="text"
                                    :value="
                                        route('testing.sut', [
                                            session.info.uuid,
                                            sut.uuid,
                                            connection.uuid,
                                        ])
                                    "
                                    class="form-control"
                                    readonly
                                />
                                <clipboard-copy-btn
                                    :target="`#testing-${connection.id}`"
                                    title="Copy"
                                ></clipboard-copy-btn>
                            </div>
                        </div>
                    </template>
                    <div class="mb-3">
                        <label class="form-label">
                            Environments
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
                        <environments v-model="form.environments" />
                        <div
                            class="text-danger small mt-2"
                            v-if="$page.errors.environments"
                        >
                            <strong>{{ $page.errors.environments }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.info')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Confirm
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';
import Environments from '@/components/environments';

export default {
    components: {
        Layout,
        Environments,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        sut: {
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
        }
    },
    data() {
        return {
            sending: false,
            groupEnvironment: null,
            groupEnvironmentsList: [],
            form: {
                environments: []
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
                .post(route('sessions.register.config.store'), this.form)
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

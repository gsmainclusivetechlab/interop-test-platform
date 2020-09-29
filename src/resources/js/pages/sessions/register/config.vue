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
                            v-model="template"
                            class="form-select mb-3"
                            placeholder="Select template..."
                            label="name"
                            :keys="['name']"
                            :options="templatesList"
                            :createItem="false"
                            :searchFn="searchTemplates"
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

export default {
    components: {
        Layout,
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
    },
    data() {
        return {
            sending: false,
            template: null,
            templatesList: [],
            form: {
                environments: []
            },
        };
    },
    watch: {
        template: {
            immediate: true,
            handler: function (value) {
                if (value !== null) {
                    this.form.environments = value.variables;
                }
            },
        },
    },
    mounted() {
        this.loadTemplatesList();
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.config.store'), this.form)
                .then(() => (this.sending = false));
        },
        addEnvironment() {
            this.form.environments.push({name: '', value: ''});
        },
        deleteEnvironment(index) {
            this.form.environments.splice(index, 1);
        },
        loadTemplatesList(query = '') {
            axios
                .get(route('sessions.register.environment-candidates'), {
                    params: { q: query },
                })
                .then((result) => {
                    this.templatesList = result.data.data;
                });
        },
        searchTemplates(query, callback) {
            this.loadTemplatesList(query);
            callback();
        },
    },
};
</script>

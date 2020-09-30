<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Update environment for ${group.name}` }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Name
                                </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Variables
                                </label>
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(environment, index) in form.variables">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                placeholder="Name"
                                                class="form-control"
                                                v-model="environment.name"
                                                :class="{
                                                    'is-invalid': collect($page.errors).has(`variables.${index}.name`),
                                                }"
                                            />
                                            <input
                                                type="text"
                                                placeholder="Value"
                                                class="form-control"
                                                v-model="environment.value"
                                                :class="{
                                                    'is-invalid': collect($page.errors).has(`variables.${index}.value`),
                                                }"
                                            />
                                            <button type="button" class="btn btn-secondary btn-icon" @click="deleteVariable(index)">
                                                <icon name="trash" />
                                            </button>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <button type="button" class="btn btn-block btn-secondary" @click="addVariable">
                                            <icon name="plus" />
                                            Add New
                                        </button>
                                    </li>
                                </ul>
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.errors.variables"
                                >
                                    <strong>{{ $page.errors.variables }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.environments.index', group.id)"
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                Update
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
            title: `Update environment for ${this.group.name}`,
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
        environment: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                name: this.environment.name,
                variables: this.environment.variables,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .put(route('groups.environments.update', [this.group, this.environment]), this.form)
                .then(() => (this.sending = false));
        },
        addVariable() {
            this.form.variables.push({name: '', value: ''});
        },
        deleteVariable(index) {
            this.form.variables.splice(index, 1);
        }
    },
};
</script>

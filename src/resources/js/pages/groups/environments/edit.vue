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
                                <label class="form-label"> Variables </label>
                                <environments v-model="form.variables" />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.variables"
                                >
                                    <strong>{{
                                        $page.props.errors.variables
                                    }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Files </label>
                                <file-environments v-model="form.files" />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.files"
                                >
                                    <strong>{{
                                        $page.props.errors.files
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="
                                    route('groups.environments.index', group.id)
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
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/main';
import Environments from '@/components/environments/environments';
import FileEnvironments from '@/components/environments/file-environments';

export default {
    metaInfo() {
        return {
            title: `Update environment for ${this.group.name}`,
        };
    },
    components: {
        Layout,
        Environments,
        FileEnvironments,
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
                _method: 'PUT',
                name: this.environment.name,
                variables: this.environment.variables,
                files: this.environment.files,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('groups.environments.update', [
                    this.group,
                    this.environment,
                ]),
                serialize(this.form, {
                    indices: true,
                }),
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
    },
};
</script>

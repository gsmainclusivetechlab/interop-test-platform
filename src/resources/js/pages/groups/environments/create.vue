<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Create new environment for ${group.name}` }}</b>
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
                                <environments
                                    ref="textEnv"
                                    v-model="form.combinedVars"
                                />
                                <div
                                    class="text-danger small mt-2"
                                    v-if="$page.props.errors.variables"
                                >
                                    <strong>{{
                                        $page.props.errors.variables
                                    }}</strong>
                                </div>
                            </div>
                            <button
                                type="button"
                                class="btn btn-block btn-secondary"
                                @click="$refs.textEnv.addEnvironment('text')"
                            >
                                <icon name="plus" />
                                <span>Add New</span>
                            </button>
                            <button
                                type="button"
                                class="btn btn-block btn-secondary"
                                @click="$refs.textEnv.addEnvironment('file')"
                            >
                                <icon name="plus" />
                                <span>Add File</span>
                            </button>
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
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/main';
import Environments from '@/components/environments';

export default {
    metaInfo() {
        return {
            title: `Create new environment for ${this.group.name}`,
        };
    },
    components: {
        Layout,
        Environments,
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
                name: null,
                variables: [],
                files: [],
            },
        };
    },
    methods: {
        submit() {
            const form = {
                name: this.form.name,
                variables: this.form.combinedVars
                    ?.filter(({ type }) => type === 'text')
                    .reduce(
                        (obj, item) => ((obj[item.key] = item.value), obj),
                        {}
                    ),
                combinedVars: this.form.variables
                    .map((x) => {
                        return { ...x, type: 'text' };
                    })
                    .concat(
                        this.form.files.map((x) => {
                            return { ...x, type: 'file' };
                        })
                    ),
                files: this.form.combinedVars
                    ?.filter(({ type }) => type === 'file')
                    .reduce(
                        (obj, item) => ((obj[item.key] = item.value), obj),
                        {}
                    ),
            };

            this.sending = true;
            this.$inertia.post(
                route('groups.environments.store', this.group),
                serialize(form, {
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

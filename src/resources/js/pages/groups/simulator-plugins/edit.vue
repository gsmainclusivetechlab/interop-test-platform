<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ `Update simulator plugin for ${group.name}` }}</b>
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
                                <label class="form-label"> JS file </label>
                                <b-form-file
                                    v-model="form.file"
                                    placeholder="Choose file..."
                                    :class="{
                                        'is-invalid': $page.props.errors.file,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.file"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.file }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('groups.plugins.index', group.id)"
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

export default {
    metaInfo() {
        return {
            title: `Update simulator plugin for ${this.group.name}`,
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
        plugin: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                _method: 'PUT',
                name: this.plugin.name,
                file: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;

            this.$inertia
                .post(
                    route('plugins.update', this.plugin),
                    serialize(this.form)
                )
                .then(() => (this.sending = false));
        },
    },
};
</script>

<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Create new group</b>
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
                                    Email Filter
                                </label>
                                <selectize
                                    v-model="domains"
                                    multiple
                                    class="form-select"
                                    :class="{
                                        'is-invalid': $page.errors.domain,
                                    }"
                                />
                                <span
                                    v-if="$page.errors.domain"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.domain }}
                                    </strong>
                                </span>
                                <div class="mt-1 text-muted small">
                                    Only users whose email matches these filters
                                    will be eligible to join the group
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Description
                                </label>
                                <textarea
                                    name="description"
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
                                    <strong>
                                        {{ $page.errors.description }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.groups.index')"
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
        title: 'Create new group',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            domains: [],
            form: {
                name: null,
                domain: null,
                description: null,
            },
        };
    },
    watch: {
        domains: {
            immediate: true,
            handler: function (value) {
                this.form.domain = value ? collect(value).implode(', ') : null;
            },
        },
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('admin.groups.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>

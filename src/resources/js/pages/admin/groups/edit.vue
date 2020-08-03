<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Update group</b>
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
                                <selectize-tags
                                    v-model="form.domain"
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
    metaInfo: {
        title: 'Update group',
    },
    components: {
        Layout,
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
                name: this.group.name,
                domain: this.group.domain,
                description: this.group.description,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .put(route('admin.groups.update', this.group.id), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>

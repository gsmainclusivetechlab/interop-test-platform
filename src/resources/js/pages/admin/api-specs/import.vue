<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Import api spec</b>
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
                                            'is-invalid': $page.errors.name
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
                                    File
                                </label>
                                <b-form-file
                                    v-model="form.file"
                                    placeholder="Choose file..."
                                    v-bind:class="{ 'is-invalid': $page.errors.file }"
                                />
                                <span
                                    v-if="$page.errors.file"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.file }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.api-specs.index')"
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span v-if="sending" class="spinner-border spinner-border-sm mr-2"></span>
                                Import
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
            title: 'Import api spec'
        },
        components: {
            Layout,
        },
        data() {
            return {
                sending: false,
                form: {
                    name: null,
                    file: null,
                }
            };
        },
        methods: {
            submit() {
                this.sending = true;
                let data = new FormData();

                if (this.form.name) {
                    data.append('name', this.form.name);
                }

                if (this.form.file) {
                    data.append('file', this.form.file);
                }

                this.$inertia
                    .post(route('admin.api-specs.import.confirm'), data)
                    .then(() => (this.sending = false));
            }
        }
    };
</script>

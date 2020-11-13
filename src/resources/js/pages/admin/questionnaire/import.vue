<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Import questionnaire definition</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label"> File </label>
                                    <b-form-file
                                        v-model="form.file"
                                        placeholder="Choose file..."
                                        v-bind:class="{
                                            'is-invalid': $page.errors.file,
                                        }"
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
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('home')"
                                class="btn btn-link"
                            >
                                Cancel
                            </inertia-link>
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :disabled="!form.file"
                            >
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
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
        title: 'Import questionnaire definition',
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            form: {
                file: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            let data = new FormData();
            data.append('file', this.form.file);
            this.$inertia
                .post(route('admin.questionnaire.import.confirm'), data)
                .then(() => (this.sending = false));
        },
    },
};
</script>

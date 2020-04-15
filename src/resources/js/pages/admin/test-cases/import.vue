<template>
    <layout :scenario="scenario">
        <div class="row">
            <div class="col-8 m-auto">
                <form class="card" @submit.prevent="submit">
                    <div class="card-header">
                        <h3 class="card-title">
                            <b>
                                {{ `Import test case for scenario for ${scenario.name}` }}
                            </b>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <b-form-file
                                v-model="form.file"
                                placeholder="Choose file..."
                                v-bind:class="{ 'is-invalid': $page.errors.file }"
                            />
                            <span v-if="$page.errors.file" class="invalid-feedback">
                                {{ $page.errors.file }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <inertia-link
                            :href="route('admin.scenarios.test-cases.index', scenario.id)"
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
    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/scenario.vue';

    export default {
        components: {
            Layout,
        },
        props: {
            scenario: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                sending: false,
                form: {
                    file: null,
                }
            };
        },
        methods: {
            submit() {
                this.sending = true;

                let data = new FormData();
                data.append('file', this.form.file);

                this.$inertia
                    .post(route('admin.scenarios.test-cases.import.confirm', this.scenario.id), data)
                    .then(() => (this.sending = false));
            }
        }
    };
</script>

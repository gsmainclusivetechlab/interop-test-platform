<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ $t('page.title') }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <div class="page-header">
                        <h1 class="page-title text-center">
                            {{ testCase.name }}
                        </h1>
                    </div>
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">{{
                                        $t('inputs.file.label')
                                    }}</label>
                                    <b-form-file
                                        v-model="form.file"
                                        :placeholder="
                                            $t('inputs.file.placeholder')
                                        "
                                        :browse-text="$t('inputs.file.browse')"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors.file,
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
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="
                                    route(
                                        'admin.test-cases.versions.index',
                                        testCase.id
                                    )
                                "
                                class="btn btn-link"
                            >
                                {{ $t('buttons.cancel') }}
                            </inertia-link>
                            <button
                                v-if="testCase.draft"
                                type="button"
                                class="btn btn-primary"
                                v-b-modal="'import-version-modal'"
                            >
                                {{ $t('buttons.import') }}
                            </button>
                            <button
                                v-else
                                type="submit"
                                class="btn btn-primary"
                            >
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                {{ $t('buttons.import') }}
                            </button>
                        </div>
                    </form>
                </div>
                <b-modal
                    id="import-version-modal"
                    :title="$t('modal.title')"
                    centered
                    @ok="submit"
                >
                    <p>{{ $t('modal.text') }}</p>
                </b-modal>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
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
            data.append('testCaseId', this.testCase.id);
            this.$inertia.post(route('admin.test-cases.import.confirm'), data, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/import-version.json"></i18n>

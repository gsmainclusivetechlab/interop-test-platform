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
                                                Object.keys($page.props.errors)
                                                    .length > 0 ||
                                                $page.props.messages.error,
                                        }"
                                    />
                                    <div
                                        v-if="
                                            Object.keys($page.props.errors)
                                                .length > 0 ||
                                            $page.props.messages.error
                                        "
                                        class="invalid-feedback"
                                    >
                                        <p class="mb-1">
                                            <strong>{{
                                                $t(
                                                    'form-file.inputs.errors.file',
                                                    {
                                                        fileSrc: form.fileSrc,
                                                    }
                                                )
                                            }}</strong>
                                        </p>
                                        <p
                                            v-if="$page.props.errors.file"
                                            class="mb-1"
                                        >
                                            <strong>
                                                {{ $page.props.errors.file }}
                                            </strong>
                                        </p>
                                        <p
                                            v-if="$page.props.errors.entries"
                                            class="mb-1"
                                        >
                                            <strong
                                                v-html="
                                                    $page.props.errors.entries
                                                "
                                            >
                                            </strong>
                                        </p>
                                    </div>
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
                                :disabled="!form.file || sending"
                            >
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                <span>{{ $t('buttons.import') }}</span>
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
            title: `${this.testCase.name} - ${this.$t('page.title')}`,
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
                fileSrc: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;

            const data = new FormData();

            data.append('file', this.form.file);
            data.append('testCaseId', this.testCase.id);

            this.$inertia.post(route('admin.test-cases.import.confirm'), data, {
                onFinish: () => {
                    this.form.fileSrc = `${this.form.file.name}`;
                    this.sending = false;
                    this.form.file = null;
                },
            });
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/import.json"></i18n>
<i18n src="@locales/pages/admin/test-cases/import-version.json"></i18n>

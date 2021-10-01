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
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">{{
                                        $t('inputs.description.label')
                                    }}</label>
                                    <textarea
                                        name="description"
                                        class="form-control"
                                        v-model="form.description"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors.description,
                                        }"
                                        :placeholder="
                                            $page.props.errors.description
                                        "
                                    ></textarea>
                                    <p
                                        v-if="$page.props.errors.description"
                                        class="invalid-feedback mb-1"
                                    >
                                        <strong>
                                            {{ $page.props.errors.description }}
                                        </strong>
                                    </p>
                                </div>
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
                                                $page.props.errors.file ||
                                                $page.props.errors.entries ||
                                                $page.props.messages.error,
                                        }"
                                    />
                                    <div
                                        v-if="
                                            $page.props.errors.file ||
                                            $page.props.errors.entries ||
                                            $page.props.messages.error
                                        "
                                        class="invalid-feedback"
                                    >
                                        <p class="mb-1">
                                            <strong>{{
                                                $t('inputs.errors.file', {
                                                    fileSrc: form.fileSrc,
                                                })
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
                                :href="route('admin.faqs.index')"
                                class="btn btn-link"
                            >
                                {{ $t('buttons.cancel') }}
                            </inertia-link>
                            <button
                                v-if="hasFaq"
                                type="button"
                                class="btn btn-primary"
                                v-b-modal="'import-version-modal'"
                                :disabled="!form.file || sending"
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
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
    },
    props: {
        hasFaq: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            form: {
                description: null,
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

            if (this.form.description) {
                data.append('description', this.form.description);
            }

            this.$inertia.post(route('admin.faqs.import.confirm'), data, {
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
<i18n src="@locales/pages/admin/faqs/import.json"></i18n>

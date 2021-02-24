<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ $t('page.title') }} {{ apiSpec.name }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="col-8 m-auto">
                    <form class="card" @submit.prevent="submit">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{
                                    $t('inputs.file.label')
                                }}</label>
                                <b-form-file
                                    v-model="form.file"
                                    :placeholder="$t('inputs.file.placeholder')"
                                    :browse-text="$t('inputs.file.browse')"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.file ||
                                            $page.props.messages.error,
                                    }"
                                />
                                <div
                                    v-if="
                                        $page.props.errors.file ||
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
                                        class="invalid-feedback mb-1"
                                    >
                                        <strong>
                                            {{ $page.props.errors.file }}
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.api-specs.index')"
                                class="btn btn-link"
                            >
                                {{ $t('buttons.cancel') }}
                            </inertia-link>
                            <button
                                type="button"
                                class="btn btn-primary"
                                v-b-modal="'edit-modal'"
                                :disabled="!form.file"
                            >
                                {{ $t('buttons.import') }}
                            </button>
                        </div>
                    </form>
                </div>
                <b-modal
                    id="edit-modal"
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
        return { title: this.$t('page.title') };
    },
    components: {
        Layout,
    },
    props: {
        apiSpec: {
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

            this.$inertia.post(
                route('admin.api-specs.update-spec', this.apiSpec.id),
                data,
                {
                    onFinish: () => {
                        this.form.fileSrc = `${this.form.file.name}`;
                        this.sending = false;
                        this.form.file = null;
                    },
                }
            );
        },
    },
};
</script>
<i18n src="@locales/components/form-file.json"></i18n>
<i18n src="@locales/pages/admin/api-spec/edit.json"></i18n>

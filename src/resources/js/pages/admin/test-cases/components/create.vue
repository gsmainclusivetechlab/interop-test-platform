<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.name')
                        }}</label>
                        <input
                            name="name"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.name,
                            }"
                            v-model="form.name"
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
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.slug')
                        }}</label>
                        <input
                            name="slug"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.slug,
                            }"
                            v-model="form.slug"
                        />
                        <span
                            v-if="$page.props.errors.slug"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.slug }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.versions')
                        }}</label>
                        <v-select
                            v-model="form.versions"
                            multiple
                            taggable
                            push-tags
                            :selectable="
                                (option) => isSelectable(option, form.versions)
                            "
                            ref="versions"
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid':
                                    $page.props.errors.versions ||
                                    searchList.versions,
                            }"
                            @search:blur="
                                searchList.versions = form.versions.includes(
                                    $refs.versions.search
                                )
                                    ? null
                                    : $refs.versions.search
                            "
                        />
                        <div
                            v-if="
                                $page.props.errors.versions ||
                                searchList.versions
                            "
                            class="invalid-feedback"
                        >
                            <span v-if="searchList.versions">
                                <strong>
                                    {{
                                        $t('errors.option-not-add', {
                                            option: searchList.versions,
                                        })
                                    }}
                                </strong>
                            </span>
                            <span v-if="$page.props.errors.versions">
                                <strong>
                                    {{ $page.props.errors.versions }}
                                </strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="
                        route('admin.test-cases.components.index', testCase.id)
                    "
                    class="btn btn-link"
                >
                    {{ $t('buttons.cancel') }}
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    {{ $t('buttons.create') }}
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import mixinVSelect from '@/components/v-select/mixin';

export default {
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
    },
    mixins: [mixinVSelect],
    data() {
        return {
            sending: false,
            searchList: {
                versions: null,
            },
            form: {
                name: null,
                slug: null,
                versions: [],
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('admin.test-cases.components.store', this.testCase.id),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
    },
};
</script>
<i18n src="@locales/components/v-select.json"></i18n>
<i18n src="@locales/pages/admin/test-cases/components/create.json"></i18n>

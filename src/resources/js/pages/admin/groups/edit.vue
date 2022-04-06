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
                                <label class="form-label">{{
                                    $t('inputs.name')
                                }}</label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.props.errors.name,
                                    }"
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
                            <div class="mb-3">
                                <label class="form-label">{{
                                    $t('inputs.email-filter.label')
                                }}</label>
                                <v-select
                                    v-model="form.domains"
                                    multiple
                                    taggable
                                    push-tags
                                    :selectable="
                                        (option) =>
                                            isSelectable(option, form.domains)
                                    "
                                    ref="emailFilter"
                                    class="form-control d-flex p-0"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.domain ||
                                            searchList.emailFilter,
                                    }"
                                    @search:blur="
                                        searchList.emailFilter = form.domains.includes(
                                            $refs.emailFilter.search
                                        )
                                            ? null
                                            : $refs.emailFilter.search
                                    "
                                />
                                <div
                                    v-if="
                                        $page.props.errors.domain ||
                                        searchList.emailFilter
                                    "
                                    class="invalid-feedback"
                                >
                                    <span v-if="searchList.emailFilter">
                                        <strong>
                                            {{
                                                $t(
                                                    'v-select.errors.option-not-add',
                                                    {
                                                        option:
                                                            searchList.emailFilter,
                                                    }
                                                )
                                            }}
                                        </strong>
                                    </span>
                                    <span v-if="$page.props.errors.domain">
                                        <strong>
                                            {{ $page.props.errors.domain }}
                                        </strong>
                                    </span>
                                </div>
                                <div class="mt-1 text-muted small">
                                    {{ $t('inputs.email-filter.comment') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{
                                    $t('inputs.description')
                                }}</label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid':
                                            $page.props.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.props.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.description }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $t('inputs.session-types') }}
                                </label>

                                <label class="form-check form-switch">
                                    <input
                                        type="checkbox"
                                        value="test_questionnaire"
                                        v-model="form.session_available"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">
                                        {{
                                            $t(
                                                'sessions.register.types.questionnaire.title'
                                            )
                                        }}
                                    </span>
                                </label>


                                <label class="form-check form-switch">
                                    <input
                                        type="checkbox"
                                        value="test"
                                        v-model="form.session_available"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">
                                        {{
                                            $t(
                                                'sessions.register.types.test.title'
                                            )
                                        }}
                                    </span>
                                </label>


                                <label class="form-check form-switch">
                                    <input
                                        type="checkbox"
                                        value="compliance"
                                        v-model="form.session_available"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">
                                        {{
                                            $t(
                                                'sessions.register.types.compliance.title'
                                            )
                                        }}
                                    </span>
                                </label>
                            </div>
                            <div
                                v-if="$page.props.errors.session_available"
                                class="text-danger small mt-3"
                            >
                                <strong>
                                    {{ $page.props.errors.session_available }}
                                </strong>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <inertia-link
                                :href="route('admin.groups.index')"
                                class="btn btn-link"
                            >
                                {{ $t('buttons.cancel') }}
                            </inertia-link>
                            <button type="submit" class="btn btn-primary">
                                <span
                                    v-if="sending"
                                    class="spinner-border spinner-border-sm mr-2"
                                ></span>
                                {{ $t('buttons.update') }}
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
import mixinVSelect from '@/components/v-select/mixin';

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
        group: {
            type: Object,
            required: true,
        },
    },
    mixins: [mixinVSelect],
    data() {
        return {
            sending: false,
            searchList: {
                emailFilter: null,
            },
            form: {
                name: this.group.name,
                domains: this.group.domain.split(', '),
                description: this.group.description,
                session_available: this.group.session_available?this.group.session_available:[],
            },
        };
    },
    methods: {
        submit() {
            const form = {
                name: this.form.name,
                domain:
                    this.form.domains.length > 0
                        ? this.form.domains.join(', ')
                        : null,
                description: this.form.description,
                session_available: this.form.session_available,
            };

            this.sending = true;
            this.$inertia.put(
                route('admin.groups.update', this.group.id),
                form,
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
<i18n src="@locales/pages/admin/groups/create.json"></i18n>
<i18n src="@locales/pages/admin/groups/edit.json"></i18n>

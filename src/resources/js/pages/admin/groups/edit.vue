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
                                <selectize
                                    v-model="domains"
                                    multiple
                                    class="form-select"
                                    :class="{
                                        'is-invalid': $page.props.errors.domain,
                                    }"
                                />
                                <span
                                    v-if="$page.props.errors.domain"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.props.errors.domain }}
                                    </strong>
                                </span>
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
    data() {
        return {
            sending: false,
            domains: this.group.domain.split(', '),
            form: {
                name: this.group.name,
                domain: null,
                description: this.group.description,
            },
        };
    },
    watch: {
        domains: {
            immediate: true,
            handler: function (value) {
                this.form.domain = value ? collect(value).implode(', ') : null;
            },
        },
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.put(
                route('admin.groups.update', this.group.id),
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
<i18n src="@locales/pages/admin/groups/create.json"></i18n>
<i18n src="@locales/pages/admin/groups/edit.json"></i18n>

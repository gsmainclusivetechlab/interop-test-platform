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
                                    v-model="domains"
                                    multiple
                                    taggable
                                    push-tags
                                    :selectable="
                                        (option) =>
                                            isSelectable(option, domains)
                                    "
                                    class="form-control d-flex p-0"
                                    :class="{
                                        'is-invalid': $page.props.errors.domain,
                                    }"
                                    @input="setDomain"
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
                                <label class="form-label">
                                    {{ $t('inputs.description') }}
                                </label>
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
                                {{ $t('buttons.create') }}
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
    mixins: [mixinVSelect],
    data() {
        return {
            sending: false,
            domains: [],
            form: {
                name: null,
                domain: null,
                description: null,
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(route('admin.groups.store'), this.form, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
        setDomain(items) {
            this.form.domain = items.length > 0 ? items.join(', ') : null;
        },
    },
};
</script>
<i18n src="@locales/pages/admin/groups/create.json"></i18n>

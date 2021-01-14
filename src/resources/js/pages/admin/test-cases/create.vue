<template>
    <layout>
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('page.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.name.label')
                        }}</label>
                        <input
                            name="name"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.name,
                            }"
                            v-model="name"
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
                            $t('inputs.slug.label')
                        }}</label>
                        <input
                            name="slug"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.slug,
                            }"
                            v-model="slug"
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
                            $t('inputs.behavior.label')
                        }}</label>
                        <v-select
                            v-model="behavior"
                            :options="
                                collect(
                                    $page.props.enums.test_case_behaviors
                                ).toArray()
                            "
                            :selectable="
                                (option) => isSelectable(option, behavior)
                            "
                            label="name"
                            :placeholder="$t('inputs.behavior.placeholder')"
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.behavior,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.behavior"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.behavior }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.use-case.label')
                        }}</label>
                        <v-select
                            v-model="useCase"
                            :options="$page.props.useCases"
                            :selectable="
                                (option) => isSelectable(option, useCase)
                            "
                            label="name"
                            :placeholder="$t('inputs.use-case.placeholder')"
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.use_case_id,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.use_case_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.use_case_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.precondition.label')
                        }}</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.props.errors.precondition,
                            }"
                            :menu-items="[
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'ordered_list',
                                'bullet_list',
                                'code',
                                'hard_break',
                            ]"
                            :output-format="['html']"
                            :content="{ html: precondition }"
                            @output-html="(content) => (precondition = content)"
                        />
                        <span
                            v-if="$page.props.errors.precondition"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.precondition }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.description.label')
                        }}</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.props.errors.description,
                            }"
                            :menu-items="[
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'ordered_list',
                                'bullet_list',
                                'code',
                                'hard_break',
                            ]"
                            :output-format="['html']"
                            :content="{ html: description }"
                            @output-html="(content) => (description = content)"
                        />
                        <span
                            v-if="$page.props.errors.description"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.description }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.components.label')
                        }}</label>
                        <v-select
                            v-model="components"
                            :options="$page.props.components"
                            multiple
                            :selectable="
                                (option) => isSelectable(option, components)
                            "
                            label="name"
                            :placeholder="$t('inputs.components.placeholder')"
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.components_id,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.components_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{
                                    collect(
                                        $page.props.errors.components_id
                                    ).implode(' ')
                                }}
                            </strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('admin.test-cases.index')"
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
import Layout from '@/layouts/main';
import { isSelectable } from '@/components/v-select';

export default {
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
    },
    data() {
        return {
            sending: false,
            name: null,
            slug: null,
            behavior: null,
            useCase: null,
            description: null,
            precondition: null,
            components: [],
        };
    },
    methods: {
        isSelectable,
        submit() {
            this.sending = true;
            this.$inertia.post(route('admin.test-cases.store'), this.form, {
                onFinish: () => {
                    this.sending = false;
                },
            });
        },
    },
    computed: {
        form() {
            const form = {
                name: this.name,
                slug: this.slug,
                behavior: collect(this.$page.props.enums.test_case_behaviors)
                    .flip()
                    .all()[this?.behavior],
                use_case_id: this.useCase?.id ?? null,
                components_id: this.components?.map((el) => el.id),
                description: this.description,
                precondition: this.precondition,
            };

            return form;
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/create.json"></i18n>

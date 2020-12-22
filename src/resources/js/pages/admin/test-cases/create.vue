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
                                'is-invalid': $page.errors.name,
                            }"
                            v-model="name"
                        />
                        <span v-if="$page.errors.name" class="invalid-feedback">
                            <strong>
                                {{ $page.errors.name }}
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
                                'is-invalid': $page.errors.slug,
                            }"
                            v-model="slug"
                        />
                        <span v-if="$page.errors.slug" class="invalid-feedback">
                            <strong>
                                {{ $page.errors.slug }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.behavior.label')
                        }}</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.behavior,
                            }"
                            v-model="behavior"
                            :placeholder="$t('inputs.behavior.placeholder')"
                            :options="
                                collect(
                                    $page.enums.test_case_behaviors
                                ).toArray()
                            "
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.behavior"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.behavior }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.use-case.label')
                        }}</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.use_case_id,
                            }"
                            v-model="useCase"
                            label="name"
                            :placeholder="$t('inputs.use-case.placeholder')"
                            :options="$page.useCases"
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.use_case_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.use_case_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.precondition.label')
                        }}</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.errors.precondition,
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
                            v-if="$page.errors.precondition"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.precondition }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.description.label')
                        }}</label>
                        <text-editor
                            :class="{
                                'is-invalid': $page.errors.description,
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
                            v-if="$page.errors.description"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.description }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.components.label')
                        }}</label>
                        <selectize
                            v-model="components"
                            multiple
                            class="form-select"
                            :placeholder="$t('inputs.components.placeholder')"
                            :class="{
                                'is-invalid': $page.errors.components_id,
                            }"
                            label="name"
                            :options="$page.components"
                            :createItem="false"
                            :disableSearch="true"
                        />
                        <span
                            v-if="$page.errors.components_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{
                                    collect($page.errors.components_id).implode(
                                        ' '
                                    )
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
            name: '',
            slug: '',
            behavior: '',
            useCase: {},
            description: '',
            precondition: '',
            components: [],
        };
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('admin.test-cases.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
    computed: {
        form() {
            const form = {
                name: this.name,
                slug: this.slug,
                behavior: collect(this.$page.enums.test_case_behaviors)
                    .flip()
                    .all()[this.behavior],
                use_case_id: this.useCase?.id,
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

<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
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
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.props.errors.behavior,
                            }"
                            v-model="behavior"
                            :placeholder="$t('inputs.behavior.placeholder')"
                            :options="
                                collect(
                                    $page.props.enums.test_case_behaviors
                                ).toArray()
                            "
                            :disableSearch="false"
                            :createItem="false"
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
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.props.errors.use_case_id,
                            }"
                            v-model="useCase"
                            label="name"
                            :placeholder="$t('inputs.use-case.placeholder')"
                            :options="$page.props.useCases"
                            :disableSearch="false"
                            :createItem="false"
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
                            $t('inputs.precondition.label')
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
                        <label class="form-label">Components</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.props.errors.components_id,
                            }"
                            v-model="components"
                            multiple
                            :placeholder="$t('inputs.components.label')"
                            label="name"
                            :options="
                                $page.props.components.map((el) => el.name)
                            "
                            :createItem="false"
                            :disableSearch="true"
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
                    :href="route('admin.test-cases.info.show', testCase.id)"
                    class="btn btn-link"
                >
                    {{ this.$t('buttons.cancel') }}
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    {{ this.$t('buttons.update') }}
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo() {
        return {
            title: `${this.testCase.name} - ${this.$t('card.title')}`,
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
            name: this.testCase.name,
            slug: this.testCase.slug,
            behavior: collect(this.$page.props.enums.test_case_behaviors).get(
                this.testCase.behavior
            ),
            useCase: this.$page.props.useCases.filter(
                (el) => el.name === this.testCase.useCase.data.name
            )[0],
            description: this.testCase.description,
            precondition: this.testCase.precondition,
            components: this.testCase.components.data?.map((el) => el.name),
        };
    },
    methods: {
        submit() {
            const form = {
                name: this.name,
                slug: this.slug,
                behavior: collect(this.$page.props.enums.test_case_behaviors)
                    .flip()
                    .all()[this.behavior],
                use_case_id: this.useCase?.id,
                description: this.description,
                precondition: this.precondition,
                components_id: this.components.map((name) => {
                    return this.$page.props.components.filter((obj) => {
                        return collect(obj).flip().has(name);
                    })[0].id;
                }),
            };

            this.sending = true;
            this.$inertia.put(
                route('admin.test-cases.info.update', this.testCase.id),
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
<i18n src="@locales/pages/admin/test-cases/info/edit.json"></i18n>

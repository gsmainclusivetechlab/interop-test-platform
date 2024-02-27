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
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.scenario.label')
                        }}</label>
                        <v-select
                            v-model="scenario"
                            :options="$page.props.scenarios"
                            :selectable="
                                (option) => isSelectable(option, scenario)
                            "
                            label="name"
                            :placeholder="$t('inputs.scenario.placeholder')"
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.scenario_id,
                            }"
                        />
                        <span
                            v-if="$page.props.errors.scenario_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.scenario_id }}
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
    import mixinVSelect from '@/components/v-select/mixin';

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
        mixins: [mixinVSelect],
        data() {
            return {
                sending: false,
                name: this.testCase.name,
                slug: this.testCase.slug,
                behavior: collect(
                    this.$page.props.enums.test_case_behaviors
                ).get(this.testCase.behavior),
                useCase: this.$page.props.useCases.filter(
                    (el) => el.name === this.testCase.useCase.data.name
                )[0],
                scenario: this.$page.props.scenarios.filter(
                    (el) => el.name === this.testCase.scenario.data.name
                )[0],
                description: this.testCase.description,
                precondition: this.testCase.precondition,
            };
        },
        methods: {
            submit() {
                const form = {
                    name: this.name,
                    slug: this.slug,
                    behavior: collect(
                        this.$page.props.enums.test_case_behaviors
                    )
                        .flip()
                        .all()[this.behavior],
                    use_case_id: this.useCase?.id,
                    scenario_id: this.scenario?.id,
                    description: this.description,
                    precondition: this.precondition,
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

<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.name.label')
                        }}</label>
                        <div>{{ name }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.slug.label')
                        }}</label>
                        <div>{{ slug }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.behavior.label')
                        }}</label>
                        <div>{{ behavior }}</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.use-case.label')
                        }}</label>
                        <div>{{ useCase }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.precondition.label')
                        }}</label>
                        <div v-html="precondition"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.description.label')
                        }}</label>
                        <div v-html="description"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.components.label')
                        }}</label>
                        <div>
                            {{ collect(components).implode('name', ', ') }}
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="testCase.can.update" class="card-footer text-right">
                <div v-if="testCase.draft">
                    <inertia-link
                        :href="route('admin.test-cases.info.edit', testCase.id)"
                        class="btn btn-primary"
                    >
                        {{ $t('buttons.edit.title') }}
                    </inertia-link>
                </div>
                <div v-else>
                    <confirm-link
                        :href="route('admin.test-cases.info.edit', testCase.id)"
                        :confirm-text="$t('buttons.edit.modal.text')"
                        class="btn btn-primary"
                    >
                        {{ $t('buttons.edit.title') }}
                    </confirm-link>
                </div>
            </div>
        </div>
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
            name: this.testCase.name,
            slug: this.testCase.slug,
            behavior: collect(this.$page.props.enums.test_case_behaviors).get(
                this.testCase.behavior
            ),
            useCase: this.testCase.useCase.data.name,
            description: this.testCase.description,
            precondition: this.testCase.precondition,
            components: this.testCase.components.data,
        };
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/info/show.json"></i18n>

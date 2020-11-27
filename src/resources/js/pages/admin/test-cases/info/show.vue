<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Info</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Name</label>
                        <div>{{ name }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Slug</label>
                        <div>{{ slug }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Behavior</label>
                        <div>{{ behavior }}</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Use Case</label>
                        <div>{{ useCase }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Precondition</label>
                        <div v-html="precondition"></div>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Description</label>
                        <div v-html="description"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Components</label>
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
                        Edit
                    </inertia-link>
                </div>
                <div v-else>
                    <confirm-link
                        :href="route('admin.test-cases.info.edit', testCase.id)"
                        :confirm-text="'This test case is out draft. A new version will be created'"
                        class="btn btn-primary"
                    >
                        Edit
                    </confirm-link>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo: {
        title: 'Test Case Info',
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
            behavior: collect(this.$page.enums.test_case_behaviors).get(
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

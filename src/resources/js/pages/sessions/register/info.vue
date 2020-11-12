<template>
    <layout :sut="session.sut" :components="components">
        <form @submit.prevent="submit" class="col-12">
            <div class="card">
                <div class="row">
                    <div class="col-6">
                        <div class="card-header border-0">
                            <h3 class="card-title">Session info</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-3">
                                <label class="form-label"> Name </label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    v-model="form.name"
                                    :class="{
                                        'is-invalid': $page.errors.name,
                                    }"
                                />
                                <span
                                    v-if="$page.errors.name"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.name }}
                                    </strong>
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Description </label>
                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    v-model="form.description"
                                    :class="{
                                        'is-invalid': $page.errors.description,
                                    }"
                                ></textarea>
                                <span
                                    v-if="$page.errors.description"
                                    class="invalid-feedback"
                                >
                                    <strong>
                                        {{ $page.errors.description }}
                                    </strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-header pl-0 border-0">
                            <h3 class="card-title">{{ isCompiance ? 'Selected cases' : 'Select use cases' }}</h3>
                        </div>
                        <div class="card-body pt-0 pl-0">
                            <test-case-checkboxes
                                style="max-height: 320px"
                                :useCases="useCases"
                                :isCompiance="isCompiance"
                                v-model="form.test_cases"
                            />
                            <div
                                class="text-danger small mt-3"
                                v-if="$page.errors.test_cases"
                            >
                                <strong>{{ $page.errors.test_cases }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route(isCompiance ? 'sessions.register.questionnaire.summary' : 'sessions.register.sut')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Next
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';
import TestCaseCheckboxes from '@/components/sessions/test-case-checkboxes';

export default {
    components: {
        Layout,
        TestCaseCheckboxes,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
        useCases: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            isCompiance: this.session.sut.type === 'compliance',
            form: {
                name: this.session.info ? this.session.info.name : null,
                description: this.session.info
                    ? this.session.info.description
                    : null,
                test_cases: this.session.info
                    ? this.session.info.test_cases
                    : [],
            },
        };
    },
    created() {
        let form = this.form;

        if (this.isCompiance && !this.session.info) {
            this.useCases.data.forEach(function (useCase) {
                useCase.testCases.forEach(function (testCase) {
                    form.test_cases.push(testCase.id);
                });
            });
        }
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia
                .post(route('sessions.register.info.store'), this.form)
                .then(() => (this.sending = false));
        },
    },
};
</script>

<template>
    <layout :components="components" :session="session">
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
                                <label class="form-label"> Description </label>
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
                    </div>
                    <div class="col-6">
                        <div
                            class="card-header pl-0 border-0 justify-content-between"
                        >
                            <h3 class="card-title">
                                {{
                                    isCompliance
                                        ? 'Selected use cases'
                                        : 'Select use cases'
                                }}
                            </h3>
                            <button
                                v-if="
                                    session.withQuestions &&
                                    (hasDifferentAnswers ||
                                        checkTestCasesDefault)
                                "
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                @click="resetTestCases"
                            >
                                Reset
                            </button>
                        </div>
                        <div class="card-body pt-0 pl-0">
                            <test-case-checkboxes
                                style="max-height: 320px"
                                :session="session"
                                :useCases="useCases"
                                :isCompliance="isCompliance"
                                v-model="form.test_cases"
                            />
                            <div
                                class="text-danger small mt-3"
                                v-if="$page.props.errors.test_cases"
                            >
                                <strong>{{
                                    $page.props.errors.test_cases
                                }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="route('sessions.register.sut')"
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button
                    type="submit"
                    class="btn btn-primary"
                    v-if="useCases.data.length"
                >
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
        hasDifferentAnswers: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            isCompliance: this.session.type === 'compliance',
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
        if (this.isCompliance) {
            this.form.test_cases.splice(0, this.form.test_cases.length);

            this.useCases.data?.forEach((useCase) =>
                useCase.testCases?.forEach((testCase) =>
                    this.form.test_cases.push(testCase.id)
                )
            );
        }
    },
    methods: {
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('sessions.register.info.store'),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        resetTestCases() {
            if (!this.session.info) return;

            if (this.hasDifferentAnswers) {
                this.$inertia.visit(
                    route('sessions.register.reset-test-cases')
                );
            } else {
                this.form.test_cases.splice(
                    0,
                    this.form.test_cases.length,
                    ...this.session.info.test_cases
                );
            }
        },
    },
    computed: {
        checkTestCasesDefault() {
            if (!this.session.info) return false;

            return !(
                this.form.test_cases.length ===
                    this.session.info.test_cases.length &&
                this.form.test_cases
                    .map((val) => this.session.info.test_cases.includes(val))
                    .every((val) => val)
            );
        },
    },
};
</script>

<template>
    <layout :session="session">
        <div class="col-10 m-auto mt-3">
            <form class="card" @submit.prevent="submit">
                <div class="card-header">
                    <h3 class="card-title">Session Report</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label"> Type of Repor </label>
                                <label class="form-check form-switch">
                                    <input
                                        type="radio"
                                        value="simple"
                                        v-model="form.type_of_report"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">Simple</span>
                                </label>
                                <label class="form-check form-switch">
                                    <input
                                        type="radio"
                                        value="extended"
                                        v-model="form.type_of_report"
                                        class="form-check-input"
                                    />
                                    <span class="form-check-label">Extended</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"> Test Cases </label>
                                <test-case-runs-checkboxes
                                    style="max-height: 485px"
                                    :session="session"
                                    :useCases="useCases"
                                    :isCompliance="isCompliance"
                                    v-model="form.test_runs"
                                />
                                <div
                                    class="text-danger small mt-3"
                                    v-if="$page.props.errors.test_runs"
                                >
                                    <strong>{{
                                        $page.props.errors.test_runs
                                    }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <inertia-link
                        :href="route('sessions.show', session.id)"
                        class="btn btn-link"
                    >
                        Cancel
                    </inertia-link>
                    <button type="submit" class="btn btn-primary btn-space">
                        <span
                            v-if="sending"
                            class="spinner-border spinner-border-sm mr-2"
                        ></span>
                        Download
                    </button>
                </div>
            </form>
        </div>
    </layout>
</template>

<script>
import { serialize } from '@/utilities/object-to-formdata';
import Layout from '@/layouts/sessions/app';
import testCaseRunsCheckboxes from '@/components/sessions/test-case-runs-checkboxes';

export default {
    components: {
        Layout,
        testCaseRunsCheckboxes,
    },
    props: {
        session: {
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
            isCompliance: this.session.type === 'compliance',
            form: {
                type_of_report: 'simple',
                test_runs: [],
            },
        };
    },
    created() {
        /*this.useCases.data?.forEach((useCase) =>
            useCase.testCases?.forEach((testCase) =>
                this.form.test_cases.push(testCase.id)
            )
        );*/
    },
    methods: {
        submit() {
            const form = {
                _method: 'POST',
                type_of_report: this.form.type_of_report,
                test_runs: this.form.test_runs,
            };
//alert(this.form.type_of_report);
            //alert(this.form.test_cases);
            this.sending = true;
            this.$inertia.post(
                route('sessions.report.download', this.session.id),
                serialize(form, {
                    indices: true,
                    booleansAsIntegers: true,
                }),
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

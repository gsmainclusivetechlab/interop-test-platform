<template>
    <layout :components="components" :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        {{ sectionName }} | Step {{ page }} of
                        {{ sections.data.length }}
                    </h3>
                </div>
                <div class="card-body">
                    <p>{{ sectionDescription }}</p>
                    <div
                        class="mb-3"
                        v-for="(question, i) in questions.data"
                        :hidden="hidden[question.name]"
                        :key="i"
                    >
                        <label class="form-label">{{
                            question.question
                        }}</label>
                        <v-select
                            v-model="answers[question.name]"
                            :multiple="question.type === 'multiselect'"
                            :options="question.values"
                            :clearable="false"
                            :selectable="
                                (option) =>
                                    isSelectable(option, answers[question.name])
                            "
                            placeholder="Select answer..."
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors[question.name],
                            }"
                        />
                        <span
                            v-if="$page.props.errors[question.name]"
                            class="invalid-feedback"
                        >
                            {{ $page.props.errors[question.name] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    v-if="$page.props.app.available_session_modes_count > 1"
                    :href="
                        section !== firstSection
                            ? route(
                                  'sessions.register.questionnaire',
                                  previousSection
                              )
                            : route('sessions.register.type')
                    "
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <button type="submit" class="btn btn-primary ml-auto">
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
import { isSelectable } from '@/components/v-select';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        questions: {
            type: Object,
            required: true,
        },
        sections: {
            type: Object,
            required: true,
        },
        previousSection: {
            type: Number,
            required: false,
        },
        page: {
            type: Number,
            required: true,
        },
        components: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            section: parseInt(route().params.section),
            firstSection: collect(this.sections.data).first().id,
            sectionName: collect(this.sections.data)
                .where('id', parseInt(route().params.section))
                .first().name,
            sectionDescription: collect(this.sections.data)
                .where('id', parseInt(route().params.section))
                .first().description,
            form: {},
            answers: {},
        };
    },
    mounted() {
        this.updateAnswers();
    },
    methods: {
        isSelectable,
        submit() {
            this.sending = true;
            this.$inertia.post(
                route('sessions.register.questionnaire.store', this.section),
                this.form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
        },
        updateAnswers() {
            if (this?.session?.questionnaire?.[this.section]) {
                const sessionAnswers = this.session.questionnaire[this.section];
                const answers = {};

                Object.keys(sessionAnswers).forEach((name) => {
                    const question = collect(this.questions.data)
                        .where('name', name)
                        .first();

                    if (question.type === 'multiselect') {
                        answers[name] = [];

                        Object.values(sessionAnswers[name]).forEach(
                            (answer) => {
                                answers[name].push(
                                    this.getValue(question, answer)
                                );
                            }
                        );
                    } else {
                        answers[name] = this.getValue(
                            question,
                            sessionAnswers[name]
                        );
                    }
                });

                this.answers = answers;
            }
        },
        availablePreconditions: function (question) {
            const preconditions = question.preconditions;

            let result = false;
            Object.keys(preconditions).forEach((attribute) => {
                if (this.answers[attribute]) {
                    const answers = Array.isArray(this.answers[attribute])
                        ? this.answers[attribute]
                        : [this.answers[attribute]];

                    Object.keys(preconditions[attribute]).forEach((rule) => {
                        if (rule === 'in') {
                            Object.values(answers).forEach((answer) => {
                                if (
                                    preconditions[attribute][rule].includes(
                                        answer.id
                                    )
                                ) {
                                    result = true;
                                }
                            });
                        }
                    });
                }
            });

            return result;
        },
        getValue(question, name) {
            return collect(question.values).where('id', name).first();
        },
    },
    computed: {
        hidden() {
            let result = {};
            Object.values(this.questions.data).forEach((question) => {
                result[question.name] =
                    Object.keys(question.preconditions).length > 0 &&
                    !this.availablePreconditions(question);
            });

            return result;
        },
    },
    watch: {
        answers: {
            deep: true,
            immediate: true,
            handler(answers) {
                Object.values(this.questions.data).forEach((question) => {
                    const name = question.name;

                    if (answers[name]) {
                        if (Array.isArray(answers[name])) {
                            this.form[name] = [];

                            Object.values(answers[name]).forEach((value) => {
                                this.form[name].push(value.id);
                            });
                        } else {
                            this.form[name] = answers[name].id;
                        }
                    } else {
                        this.form[name] = null;
                    }
                });
            },
        },
        questions: {
            immediate: true,
            handler() {
                this.section = parseInt(route().params.section);
                this.sectionName = collect(this.sections.data)
                    .where('id', parseInt(route().params.section))
                    .first().name;
                this.sectionDescription = collect(this.sections.data)
                    .where('id', parseInt(route().params.section))
                    .first().description;

                if (this.$page.props.errors?.length === 0) this.updateAnswers();
            },
        },
    },
};
</script>

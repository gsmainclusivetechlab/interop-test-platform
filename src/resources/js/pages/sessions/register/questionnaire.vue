<template>
    <layout :sections="sections">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">{{ sectionName }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3" v-for="question in questions.data" :hidden="hidden[question.name]">
                        <label class="form-label">{{ question.question }}</label>
                        <selectize
                            v-model="answers[question.name]"
                            :class="{ 'is-invalid': $page.errors[question.name] }"
                            :options="question.values"
                            :multiple="question.type === 'multiselect'"
                            :createItem="false"
                            class="form-select"
                            placeholder="Select answer..."
                        />
                        <span
                            v-if="$page.errors[question.name]"
                            class="invalid-feedback"
                        >
                            {{ $page.errors[question.name] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="section != firstSection
                        ? route('sessions.register.questionnaire', section - 1)
                        : route('sessions.register.sut')"
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
    import Layout from '@/layouts/sessions/questionnaire';

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
        },
        data() {
            return {
                sending: false,
                section: parseInt(route().params.section),
                firstSection: collect(this.sections.data).first().id,
                sectionName: collect(this.sections.data)
                    .where('id', parseInt(route().params.section))
                    .first()
                    .name,
                form: {},
                answers: {}
            };
        },
        created() {
            if (this.session && this.session.questionnaire && this.session.questionnaire[this.section]) {
                const sessionAnswers = this.session.questionnaire[this.section];
                const answers = {};

                Object.keys(sessionAnswers).forEach(name => {
                    const question = collect(this.questions.data)
                        .where('name', name)
                        .first()

                    if (question.type === 'multiselect') {
                        answers[name] = [];

                        Object.values(sessionAnswers[name]).forEach(answer => {
                            answers[name].push(this.getValue(question, answer))
                        })
                    } else {
                        answers[name] = this.getValue(question, sessionAnswers[name])
                    }
                });

                this.answers = answers;
            }
        },
        methods: {
            submit() {
                this.sending = true;
                this.$inertia
                    .post(route('sessions.register.questionnaire.store', this.section), this.form)
                    .then(() => (this.sending = false));
            },
            availablePreconditions: function (question) {
                const preconditions = question.preconditions;

                let result = false;
                Object.keys(preconditions).forEach(attribute => {
                    if (this.answers[attribute]) {
                        const answers = Array.isArray(this.answers[attribute])
                            ? this.answers[attribute]
                            : [this.answers[attribute]];

                        Object.keys(preconditions[attribute]).forEach(rule => {
                            if (rule === 'in') {
                                Object.values(answers).forEach(answer => {
                                    if (preconditions[attribute][rule].includes(answer.id)) {
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
                return collect(question.values)
                    .where('id', name)
                    .first();
            }
        },
        computed: {
            hidden: function () {
                let result = {}
                Object.values(this.questions.data).forEach(question => {
                    result[question.name] = Object.keys(question.preconditions).length > 0 && !this.availablePreconditions(question);
                });

                return result;
            },
        },
        watch: {
            answers: {
                deep: true,
                immediate: true,
                handler: function (answers) {
                    Object.values(this.questions.data).forEach(question => {
                        const name = question.name;

                        if (answers[name]) {
                            if (Array.isArray(answers[name])) {
                                this.form[name] = [];

                                Object.values(answers[name]).forEach(value => {
                                    this.form[name].push(value.id);
                                });
                            } else {
                                this.form[name] = answers[name].id;
                            }
                        } else {
                            this.form[name] = null;
                        }
                    })
                },
            },
        }
    };
</script>

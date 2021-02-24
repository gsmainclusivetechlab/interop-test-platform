<template>
    <layout :session="session">
        <form @submit.prevent="submit" class="col-8 m-auto">
            <div class="card" v-for="(section, i) in sections.data" :key="i">
                <div class="card-header border-0">
                    <h3 class="card-title">{{ section.name }}</h3>
                </div>
                <div class="card-body">
                    <dl
                        v-for="(question, i) in section.questions"
                        :key="i"
                        v-show="!isHidden(section.id, question)"
                        class="q-a-list"
                    >
                        <dt>{{ question.question }}</dt>
                        <dd
                            v-for="(answer, i) in getAnswers(
                                section.id,
                                question
                            )"
                            :key="i"
                        >
                            {{ answer }}
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <inertia-link
                    :href="
                        route('sessions.register.questionnaire', lastSection)
                    "
                    class="btn btn-outline-primary"
                >
                    Back
                </inertia-link>
                <inertia-link
                    :href="route('sessions.register.info')"
                    class="btn btn-outline-primary"
                >
                    Confirm
                </inertia-link>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/register';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: false,
        },
        sections: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            lastSection: collect(this.sections.data).max('id'),
        };
    },
    methods: {
        isHidden: function (section, question) {
            return !this.session.questionnaire[section][question.name];
        },
        getAnswers: function (section, question) {
            let answersTexts = [];
            let answers = this.session.questionnaire[section][question.name];

            answers = Array.isArray(answers) ? answers : [answers];

            Object.values(answers).forEach((answer) => {
                if (['text-short', 'text-long'].includes(question.type)) {
                    answersTexts.push(answer);
                } else {
                    Object.values(question.values).forEach((value) => {
                        if (value.id === answer) {
                            answersTexts.push(value.label);
                        }
                    });
                }
            });

            return answersTexts;
        },
    },
};
</script>

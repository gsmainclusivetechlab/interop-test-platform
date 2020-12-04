<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>Create new session</b>
                </h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="steps steps-counter steps-primary mb-5">
                            <span
                                class="step-item"
                                v-if="
                                    session.type === 'compliance' ||
                                    session.withQuestions
                                "
                                :class="{ active: isQuestionnaireStep }"
                            >
                                <span class="d-inline-block mt-2">
                                    Questionnaire
                                </span>
                            </span>
                            <span
                                class="step-item"
                                :class="{
                                    active: route().current(
                                        'sessions.register.sut'
                                    ),
                                }"
                            >
                                <span class="d-inline-block mt-2">
                                    Select Sut
                                </span>
                            </span>
                            <span
                                class="step-item"
                                :class="{
                                    active: route().current(
                                        'sessions.register.info'
                                    ),
                                }"
                            >
                                <span class="d-inline-block mt-2">
                                    Session info
                                </span>
                            </span>
                            <span
                                class="step-item"
                                :class="{
                                    active: route().current(
                                        'sessions.register.config'
                                    ),
                                }"
                            >
                                <span class="d-inline-block mt-2">
                                    Configure components
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-5" v-show="!isQuestionnaireStep">
                    <div class="col">
                        <diagram>
                            graph LR;
                            <template v-for="component in components.data">
                                {{ component.id }}({{
                                    component.name
                                }})<template
                                    v-if="
                                        collect(session.sut)
                                            .get('component_ids', [])
                                            .includes(component.id)
                                    "
                                    >:::is-active</template
                                ><template v-else></template>;
                                <template
                                    v-for="connection in component.connections"
                                >
                                    {{ component.id }}-->{{ connection.id }};
                                </template>
                            </template>
                        </diagram>
                    </div>
                </div>
                <div class="row">
                    <slot />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import Diagram from '@/components/diagram';

export default {
    metaInfo: {
        title: 'Create new session',
    },
    components: {
        Layout,
        Diagram,
    },
    props: {
        components: {
            type: Object,
            required: true,
        },
        session: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            isQuestionnaireStep:
                route().current('sessions.register.questionnaire') ||
                route().current('sessions.register.questionnaire.summary'),
        };
    },
};
</script>

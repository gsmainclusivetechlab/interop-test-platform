<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ $t('layout.sessions.questionnaire.page.title') }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="steps steps-counter steps-primary mb-5">
                            <span
                                class="step-item"
                                v-for="(section, i) in sections.data"
                                :key="i"
                                :class="{
                                    active:
                                        route().current(
                                            'sessions.register.questionnaire'
                                        ) &&
                                        route().params.section == section.id,
                                }"
                            >
                                <span class="d-inline-block mt-2">
                                    {{ section.name }}
                                </span>
                            </span>
                            <span
                                class="step-item"
                                :class="{
                                    active: route().current(
                                        'sessions.register.questionnaire.summary'
                                    ),
                                }"
                            >
                                <span class="d-inline-block mt-2">
                                    {{
                                        $t(
                                            'layout.sessions.questionnaire.info.summary'
                                        )
                                    }}
                                </span>
                            </span>
                        </div>
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

export default {
    metaInfo() {
        return {
            title: this.$t('layout.sessions.questionnaire.page.title'),
        };
    },
    components: {
        Layout,
    },
    props: {
        sections: {
            type: Object,
            required: true,
        },
    },
};
</script>

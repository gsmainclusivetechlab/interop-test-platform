<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="page-header">
                <h1 class="page-title text-center">
                    <b>{{ $t('sessions.register.page.title') }}</b>
                </h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                {{ $t('sessions.register.card.title') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'test',
                                            withQuestions: 1,
                                        })
                                    "
                                    method="post"
                                    v-if="availableModes.test_questionnaire"
                                    class="btn btn-outline-primary mr-1"
                                >
                                    {{
                                        $t(
                                            'sessions.register.types.questionnaire.title'
                                        )
                                    }}
                                </inertia-link>
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'test',
                                        })
                                    "
                                    method="post"
                                    v-if="availableModes.test"
                                    class="btn btn-outline-primary mr-1"
                                >
                                    {{
                                        $t('sessions.register.types.test.title')
                                    }}
                                </inertia-link>
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'compliance',
                                        })
                                    "
                                    method="post"
                                    v-if="availableModes.compliance"
                                    class="btn btn-outline-primary mr-1"
                                >
                                    {{
                                        $t(
                                            'sessions.register.types.compliance.title'
                                        )
                                    }}
                                </inertia-link>
                            </div>
                            <div class="mb-3">
                                <p v-if="availableModes.test_questionnaire">
                                    <icon name="chevron-right" />
                                    <span
                                        v-html="
                                            $t(
                                                'sessions.register.types.questionnaire.description[0]'
                                            )
                                        "
                                    ></span>
                                    <span
                                        v-if="availableModes.compliance"
                                        v-html="
                                            $t(
                                                'sessions.register.types.questionnaire.description[1]'
                                            )
                                        "
                                    ></span>
                                </p>
                                <p v-if="availableModes.test">
                                    <icon name="chevron-right" />
                                    <span
                                        v-html="
                                            $t(
                                                'sessions.register.types.test.description'
                                            )
                                        "
                                    ></span>
                                </p>
                                <p v-if="availableModes.compliance">
                                    <icon name="chevron-right" />
                                    <span
                                        v-html="
                                            $t(
                                                'sessions.register.types.compliance.description',
                                                {
                                                    testRunAttempts,
                                                }
                                            )
                                        "
                                    ></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    components: {
        Layout,
    },
    props: {
        testRunAttempts: {
            type: Number | String,
            required: true,
        },
        availableModes: {
            type: Object,
            required: true,
        },
    },
};
</script>

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
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Session type selection</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'test',
                                        })
                                    "
                                    method="post"
                                    class="btn btn-outline-primary"
                                >
                                    Test
                                </inertia-link>
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'test',
                                            withQuestions: 1,
                                        })
                                    "
                                    method="post"
                                    class="btn btn-outline-primary"
                                >
                                    Test with questionnaire
                                </inertia-link>
                                <inertia-link
                                    :href="
                                        route('sessions.register.type.store', {
                                            type: 'compliance',
                                        })
                                    "
                                    method="post"
                                    class="btn btn-outline-primary"
                                >
                                    Complience
                                </inertia-link>
                            </div>
                            <div class="mb-3">
                                <p>
                                    <icon name="chevron-right" /><b>Test</b>
                                    is a common session where you can select any
                                    number of test cases, execute them any
                                    times, and change the chosen set of test
                                    cases any time.
                                </p>
                                <p>
                                    <icon name="chevron-right" /><b
                                        >Complience</b
                                    >
                                    is a specific type of session that is being
                                    validated by the platfrom admins. In
                                    contrast to a Test session you wan't be able
                                    to choose test cases manually and they will
                                    be assigned to you automatically after
                                    passing a questionnaire. Then you'll have a
                                    limited number of {{ testRunAttempts }}
                                    attempts to execute any test case before
                                    sending our session for admin's review.
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
            type: Number,
            required: true,
        },
    },
};
</script>

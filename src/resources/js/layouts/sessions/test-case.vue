<template>
    <layout>
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-4 align-items-center">
                    <div class="col-6 d-flex flex-wrap">
                        <h1 class="page-title mr-2">
                            <b>{{ session.name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2">
                        <div class="mb-1">
                            Execution:
                            <icon name="briefcase" v-b-tooltip.hover title="Use Case" />
                            <small>
                                {{ session.testCases ? collect(session.testCases.data).unique('use_case_id').count() : 0 }}
                            </small>
                            <icon name="file-text" v-b-tooltip.hover title="Test Case" />
                            <small>
                                {{ session.testCases ? session.testCases.data.length : 0  }}
                            </small>
                        </div>
                        <div style="min-width: 180px">
                            <session-progress :testCases="session.testCases.data" />
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-3 mt-3 pr-0">
                        <div class="card mb-0">
                            <div class="card-header px-4">
                                <h3 class="card-title d-flex align-items-center">
                                    <inertia-link
                                        :href="route('sessions.show', session.id)"
                                        class="d-inline-flex mr-1 text-decoration-none"
                                    >
                                        <icon name="chevron-left"></icon>
                                    </inertia-link>
                                    {{ testCase.name }}
                                </h3>
                            </div>
                            <div class="card-body p-0" v-if="testCase.description || testCase.precondition">
                                <ul class="list-unstyled">
                                    <li class="py-3 px-4 border-bottom" v-if="testCase.description">
                                        <p>
                                            <strong>Description</strong>
                                        </p>
                                        <div v-html="testCase.description"></div>
                                    </li>
                                    <li class="py-3 px-4 border-bottom" v-if="testCase.precondition">
                                        <p>
                                            <strong>Precondition</strong>
                                        </p>
                                        <div v-html="testCase.precondition"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mt-3">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-baseline border-bottom mb-4">
                                    <ul class="nav nav-tabs mx-0 border-0">
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="route('sessions.test-cases.show', [session.id, testCase.id])"
                                                class="nav-link rounded-0"
                                                v-bind:class="{'active': route().current('sessions.test-cases.show') || route().current('sessions.test-cases.test-runs.*')}"
                                            >
                                                Test Runs
                                            </inertia-link>
                                        </li>
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="route('sessions.test-cases.test-steps.index', [session.id, testCase.id])"
                                                class="nav-link rounded-0"
                                                v-bind:class="{'active': route().current('sessions.test-cases.test-steps.index')}"
                                            >
                                                Test Steps
                                            </inertia-link>
                                        </li>
                                        <li class="nav-item">
                                            <inertia-link
                                                :href="route('sessions.test-cases.test-steps.flow', [session.id, testCase.id])"
                                                class="nav-link rounded-0"
                                                v-bind:class="{'active': route().current('sessions.test-cases.test-steps.flow')}"
                                            >
                                                Flow
                                            </inertia-link>
                                        </li>
                                    </ul>
                                </div>
                                <slot />
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
    import SessionProgress from '@/components/sessions/progress';

    export default {
        components: {
            Layout,
            SessionProgress
        },
        metaInfo() {
            return {
                title: this.session.name,
            }
        },
        props: {
            session: {
                type: Object,
                required: true
            },
            testCase: {
                type: Object,
                required: true
            },
        }
    };
</script>

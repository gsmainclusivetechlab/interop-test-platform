<template>
    <layout :session="session" :useCases="useCases" :testCase="testCase" :breadcrumbs="breadcrumbs">
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-baseline border-bottom mb-4">
                    <ul class="nav nav-tabs mx-0 border-0">
                        <li class="nav-item">
                            <inertia-link
                                :href="route('sessions.test-cases.show', [session.id, testCase.id])"
                                class="nav-link rounded-0"
                                v-bind:class="{'active': route().current('sessions.test-cases.show')}"
                            >
                                Overview
                            </inertia-link>
                        </li>
                        <li class="nav-item">
                            <inertia-link
                                :href="route('sessions.test-cases.test-runs.index', [session.id, testCase.id])"
                                class="nav-link rounded-0"
                                v-bind:class="{'active': route().current('sessions.test-cases.test-runs.*')}"
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
                    <div class="col-5 ml-auto">
                        <div class="d-flex">
                            <div class="input-group">
                                <input
                                    id="testing-url"
                                    type="text"
                                    class="form-control"
                                    readonly
                                    :value="route('testing.run', [session.uuid, testCase.uuid])"
                                />
                                <clipboard-copy-btn
                                    target="#testing-url"
                                    title="Copy URL"
                                ></clipboard-copy-btn>
                            </div>
                        </div>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/sessions/main';

    export default {
        components: {
            Layout
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
            useCases: {
                type: Object,
                required: true
            },
            testCase: {
                type: Object,
                required: true
            },
            breadcrumbs: {
                type: Array,
                required: false,
                default: function () {
                    return [
                        {name: 'Sessions', url: route('sessions.index')},
                        {name: this.session.name, url: route('sessions.show', this.session.id)},
                        {name: this.testCase.name},
                    ];
                }
            }
        }
    };
</script>

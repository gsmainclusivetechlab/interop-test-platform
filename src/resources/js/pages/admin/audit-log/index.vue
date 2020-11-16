<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Audit Log</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Audit Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                    <tr>
                        <th class="text-nowrap w-20">Actor</th>
                        <th class="text-nowrap w-auto">Action</th>
                        <th class="text-nowrap w-15">Subject</th>
                        <th class="text-nowrap w-15">Meta</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="message in logItems.data"
                        v-bind:key="message.type + message.id"
                    >
                        <td>
                            <div class="d-flex align-items-center">
                                {{ message.request.method }}
                                {{
                                message.type === 'MISMATCH'
                                ? processMismatchPath(
                                message.request.path
                                ).truncated
                                : message.request.path
                                }}
                            </div>
                        </td>

                        <td v-if="message.type === 'RESULT'">
                            <inertia-link
                                :href="
                                        route(
                                            'sessions.test-cases.test-runs.show',
                                            [
                                                message.session.id,
                                                message.test_case.id,
                                                message.test_run.id,
                                                message.test_step.position,
                                            ]
                                        )
                                    "
                            >
                                {{ message.test_case.name }}, Step
                                {{ message.test_step.position }}
                            </inertia-link>
                        </td>
                        <td v-else>-</td>

                        <td>
                            {{ message.created_at }}
                        </td>
                        <td>
                            <mismatch-modal
                                :message="message"
                                :path="
                                        processMismatchPath(
                                            message.request.path
                                        )
                                    "
                            ></mismatch-modal>
                        </td>
                    </tr>
                    <tr v-if="!logItems.data.length">
                        <td class="text-center" colspan="5">No Results</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <pagination
                :meta="logItems.meta"
                :links="logItems.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/main';
    import MismatchModal from '@/components/mismatch-modal';

    export default {
        components: {
            Layout,
            'mismatch-modal': MismatchModal,
        },
        props: {
            logItems: {
                type: Object,
                required: true,
            },
        },
        methods: {
            processMismatchPath: function (path) {
                const match = path.match(
                    /\/testing\/([^\/]+)\/([^\/]+)\/([^\/]+)\/sut(.+)/
                );
                const session = match && match[1];
                const source = match && match[2];
                const target = match && match[3];
                const destPath = match && match[4];
                return {
                    original: path,
                    session,
                    source,
                    target,
                    path: destPath,
                    truncated:
                        destPath ||
                        (path.length > 30 ? `...${path.substr(-30)}` : path),
                };
            },
        },
    };
</script>

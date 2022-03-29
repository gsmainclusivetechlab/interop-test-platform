<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Message Log</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Message Log</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-25">URL</th>
                            <th class="text-nowrap">Matched Step</th>
                            <th class="text-nowrap w-25">Exсeption</th>
                            <th class="text-center text-nowrap w-0">Date</th>
                            <th class="text-center text-nowrap w-0">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="message in logItems.data"
                            :key="message.type + message.id"
                        >
                            <td v-if="message.request" class="text-break">
                                {{ message.request.method }}
                                {{
                                    message.type === 'MISMATCH'
                                        ? processMismatchPath(
                                              message.request.path
                                          ).truncated
                                        : message.request.path
                                }}
                            </td>
                            <td v-else>-</td>

                            <td
                                v-if="
                                    message.type === 'RESULT' &&
                                    message.session &&
                                    message.test_case &&
                                    message.test_run &&
                                    message.test_step
                                "
                            >
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

                            <td
                                v-if="message.exception !== null"
                                class="text-danger"
                            >
                                {{ message.exception }}
                            </td>
                            <td v-else>-</td>

                            <td class="text-center text-nowrap">
                                {{ message.created_at }}
                            </td>
                            <td v-if="message.request" class="text-center">
                                <mismatch-modal
                                    :message="message"
                                    :path="
                                        processMismatchPath(
                                            message.request.path
                                        )
                                    "
                                ></mismatch-modal>
                            </td>
                            <td v-else class="text-center">-</td>
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
    import Layout from '@/layouts/logs';
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
            processMismatchPath(path) {
                const match = path.match(
                    /\/testing[^\/]*\/([^\/]+)\/([^\/]+)\/([^\/]+)\/(.*)/
                );
                const session = match && match[3];
                const source = match && match[1];
                const target = match && match[2];
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

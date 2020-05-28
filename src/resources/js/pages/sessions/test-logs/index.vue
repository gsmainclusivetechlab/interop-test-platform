<template>
    <layout :session="session" :useCases="useCases">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>Test Logs</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                    <tr>
                        <th class="text-nowrap w-auto">Method</th>
                        <th class="text-nowrap w-25">Endpoint</th>
                        <th class="text-nowrap w-25">Exception</th>
                        <th class="text-nowrap w-auto">Date</th>
                        <th class="text-nowrap w-auto">Data</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="testLog in testLogs.data">
                            <td>
                                {{ testLog.request.method }}
                            </td>
                            <td>
                                {{ testLog.request.path }}
                            </td>
                            <td>
                                {{ testLog.exception }}
                            </td>

                            <td>
                                {{ testLog.created_at }}
                            </td>
                            <td>
                                <button class="btn btn-secondary" v-b-modal="`modal-request-${testLog.id}`">
                                    Request
                                </button>
                                <b-modal
                                    :id="`modal-request-${testLog.id}`"
                                    size="lg"
                                    centered
                                    hide-footer
                                    hide-header-close
                                    title="Request"
                                >
                                    <div class="border">
                                        <div class="d-flex" v-if="testLog.request.uri">
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Endpoint</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    {{ testLog.request.uri }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" v-if="testLog.request.method">
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Method</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    {{ testLog.request.method }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" v-if="collect(testLog.request.headers).count()">
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Headers</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree :data="testLog.request.headers" :deep="1" :show-line="false" class="p-2"></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" v-if="collect(testLog.request.body).count()">
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Body</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree :data="testLog.request.body" :deep="1" :show-line="false" class="p-2"></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-modal>
                            </td>
                        </tr>
                        <tr v-if="!testLogs.data.length">
                            <td class="text-center" colspan="5">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <pagination
                :meta="testLogs.meta"
                :links="testLogs.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/main';

export default {
    components: {
        Layout
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
        testLogs: {
            type: Object,
            required: true
        },
    }
};
</script>

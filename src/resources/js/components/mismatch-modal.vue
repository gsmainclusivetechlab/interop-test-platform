<template>
    <div>
        <button
            class="btn btn-secondary"
            v-b-modal="`modal-request-${message.type}-${message.id}`"
        >
            Request
        </button>
        <b-modal
            :id="`modal-request-${message.type}-${message.id}`"
            size="lg"
            centered
            hide-footer
            title="Request"
        >
            <div class="border">
                <div class="d-flex" v-if="message.exception">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Exception</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <div class="mb-0 p-0">
                            {{ message.exception }}
                        </div>
                    </div>
                </div>
                <div class="d-flex" v-if="message.request.uri">
                    <div class="w-25 px-4 py-2 border">
                        <strong>{{
                            message.type === 'RESULT'
                                ? 'Outbound URL'
                                : 'Inbound URL'
                        }}</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <pre class="mb-0 p-0">{{
                            message.request.uri.trim()
                        }}</pre>
                    </div>
                </div>
                <div class="d-flex" v-if="message.type === 'MISMATCH'">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Session ID / Group ID / Simulator</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <pre class="mb-0 p-0">{{ path.session || '-' }}</pre>
                    </div>
                </div>
                <div class="d-flex" v-if="message.type === 'MISMATCH'">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Source Component ID</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <pre class="mb-0 p-0">{{ path.source || '-' }}</pre>
                    </div>
                </div>
                <div class="d-flex" v-if="message.type === 'MISMATCH'">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Target Component ID</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <pre class="mb-0 p-0">{{ path.target || '-' }}</pre>
                    </div>
                </div>
                <div class="d-flex" v-if="message.type === 'MISMATCH'">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Target Path</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <pre class="mb-0 p-0">{{ path.path || '-' }}</pre>
                    </div>
                </div>
                <div class="d-flex" v-if="message.request.method">
                    <div class="w-25 px-4 py-2 border">
                        <strong>Method</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <div class="mb-0 p-0">
                            {{ message.request.method }}
                        </div>
                    </div>
                </div>
                <div
                    class="d-flex"
                    v-if="collect(message.request.headers).count()"
                >
                    <div class="w-25 px-4 py-2 border">
                        <strong>Headers</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <div class="mb-0 p-0">
                            <vue-json-pretty
                                :deep="2"
                                :deepCollapseChildren="true"
                                :virtual="true"
                                :virtualLines="
                                    Object.keys(message.request.headers)
                                        .length * 3
                                "
                                :data="message.request.headers"
                                class="p-2"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="d-flex"
                    v-if="collect(message.request.body).count()"
                >
                    <div class="w-25 px-4 py-2 border">
                        <strong>Body</strong>
                    </div>
                    <div class="w-75 px-4 py-2 border">
                        <div class="mb-0 p-0">
                            <vue-json-pretty
                                :deep="2"
                                :deepCollapseChildren="true"
                                :virtual="true"
                                :virtualLines="
                                    Object.keys(message.request.body).length * 3
                                "
                                :data="message.request.body"
                                class="p-2"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import VueJsonPretty from 'vue-json-pretty';
    import 'vue-json-pretty/lib/styles.css';

    export default {
        components: {
            VueJsonPretty,
        },
        props: {
            path: {
                type: Object,
                required: true,
            },
            message: {
                type: Object,
                required: true,
            },
        },
    };
</script>

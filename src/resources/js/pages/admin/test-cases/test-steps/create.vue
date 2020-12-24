<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">Create</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Method</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.method,
                            }"
                            v-model="method.selected"
                            placeholder="Select method"
                            :options="method.list"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.method"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.method }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Path</label>
                        <input
                            name="path"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.errors.path,
                            }"
                            v-model="path"
                        />
                        <span v-if="$page.errors.path" class="invalid-feedback">
                            <strong>
                                {{ $page.errors.path }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Pattern</label>
                        <input
                            name="pattern"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.errors.pattern,
                            }"
                            v-model="pattern"
                        />
                        <span
                            v-if="$page.errors.pattern"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.pattern }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Source</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.source_id,
                            }"
                            v-model="source"
                            placeholder="Select source"
                            :options="sourceList"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.source_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.source_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Target</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.target_id,
                            }"
                            v-model="target"
                            placeholder="Select target"
                            :options="targetList"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.target_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.target_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">API specification</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.api_spec,
                            }"
                            v-model="apiSpec.selected"
                            placeholder="Select API specification"
                            :options="apiSpec.list"
                            :createItem="false"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'trigger'"
                        >
                            Trigger
                        </button>
                        <b-collapse id="trigger" class="card">
                            <json-editor-block
                                :input-json="trigger"
                                @output-json="
                                    (data) => {
                                        trigger = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-request-scripts'"
                        >
                            Test Request Scripts
                        </button>
                        <b-collapse id="test-request-scripts" class="card">
                            <div
                                v-for="(request, i) in test.scripts.request
                                    .list"
                                class="card-body"
                                :key="`test-request-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-break mr-2"
                                        ><b>{{ i + 1 }}.</b>
                                        {{ request.name }}</span
                                    >
                                    <div class="card-options">
                                        <button
                                            type="button"
                                            class="btn btn-link p-0"
                                            v-b-tooltip.hover
                                            title="Delete"
                                            v-b-modal="
                                                `test-request-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <label class="form-label">Name</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.errors[
                                                    `test.scripts.request.${i}.name`
                                                ],
                                        }"
                                        v-model="request.name"
                                    />
                                    <span
                                        v-if="
                                            $page.errors[
                                                `test.scripts.request.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.errors[
                                                    `test.scripts.request.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">Rules</label>
                                    <json-editor-block
                                        :input-json="request.rules"
                                        @output-json="
                                            (data) => (request.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.errors[
                                                `test.scripts.request.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-request-scripts-modal-${i}`"
                                    :title="'Are you sure?'"
                                    @ok="
                                        deleteFormItem(
                                            test.scripts.request.list,
                                            i
                                        )
                                    "
                                    centered
                                    :key="`test-request-scripts-modal-${i}`"
                                >
                                    <p>
                                        This test request scripts will be
                                        deleted.
                                    </p>
                                </b-modal>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="
                                        addFormItem(test.scripts.request.list, {
                                            ...test.scripts.request.pattern,
                                        })
                                    "
                                >
                                    <icon name="plus" />
                                    <span>Add New Request Script</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-response-scripts'"
                        >
                            Test Response Scripts
                        </button>
                        <b-collapse id="test-response-scripts" class="card">
                            <div
                                v-for="(response, i) in test.scripts.response
                                    .list"
                                class="card-body"
                                :key="`test-response-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-muted text-break mr-2"
                                        >{{ i + 1 }}. {{ response.name }}</span
                                    >
                                    <div class="card-options">
                                        <button
                                            type="button"
                                            class="btn btn-link p-0"
                                            v-b-tooltip.hover
                                            title="Delete"
                                            v-b-modal="
                                                `test-response-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="card-body px-0 pb-0"
                                    :key="`test-response-scripts-form-${i}`"
                                >
                                    <label class="form-label">Name</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.errors[
                                                    `test.scripts.response.${i}.name`
                                                ],
                                        }"
                                        v-model="response.name"
                                    />
                                    <span
                                        v-if="
                                            $page.errors[
                                                `test.scripts.response.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.errors[
                                                    `test.scripts.response.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">Rules</label>
                                    <json-editor-block
                                        :input-json="response.rules"
                                        @output-json="
                                            (data) => (response.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.errors[
                                                `test.scripts.response.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-response-scripts-modal-${i}`"
                                    :title="'Are you sure?'"
                                    @ok="
                                        deleteFormItem(
                                            test.scripts.response.list,
                                            i
                                        )
                                    "
                                    centered
                                    :key="`test-response-scripts-modal-${i}`"
                                >
                                    <p>
                                        This test response scripts will be
                                        deleted.
                                    </p>
                                </b-modal>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="
                                        addFormItem(
                                            test.scripts.response.list,
                                            {
                                                ...test.scripts.response
                                                    .pattern,
                                            }
                                        )
                                    "
                                >
                                    <icon name="plus" />
                                    <span>Add New Response Script</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-headers-examples'"
                        >
                            Request Headers
                        </button>
                        <b-collapse id="request-headers-examples" class="card">
                            <json-editor-block
                                :input-json="example.request.headers"
                                @output-json="
                                    (data) => {
                                        example.request.headers = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-body-examples'"
                        >
                            Request Body
                        </button>
                        <b-collapse id="request-body-examples" class="card">
                            <json-editor-block
                                :input-json="example.request.body"
                                @output-json="
                                    (data) => {
                                        example.request.body = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">Response Status</h2>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors['response.status'],
                            }"
                            v-model="example.response.status.selected"
                            placeholder="Select status"
                            :options="example.response.status.list"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors['response.status']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors['response.status'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-headers-examples'"
                        >
                            Response Headers
                        </button>
                        <b-collapse id="response-headers-examples" class="card">
                            <json-editor-block
                                :input-json="example.response.headers"
                                @output-json="
                                    (data) => {
                                        example.response.headers = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-body-examples'"
                        >
                            Response Body
                        </button>
                        <b-collapse id="response-body-examples" class="card">
                            <json-editor-block
                                :input-json="example.response.body"
                                @output-json="
                                    (data) => {
                                        example.response.body = data;
                                    }
                                "
                                class="card-body"
                            />
                        </b-collapse>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="
                        route('admin.test-cases.test-steps.index', testCase.id)
                    "
                    class="btn btn-link"
                >
                    Cancel
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Create
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import JsonEditorBlock from '@/components/json-editor-block';

export default {
    metaInfo: {
        title: 'Test Step Create',
    },
    components: {
        Layout,
        JsonEditorBlock,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            component: {
                list: this.$page.components.map((el) => el.name),
                connections: new Map(
                    this.$page.components.map((el) => [
                        el.name,
                        el.connections.data.map(
                            (connection) => connection.name
                        ),
                    ])
                ),
            },
            method: {
                selected: '',
                list: collect(this.$page.methods).toArray(),
            },
            path: '',
            pattern: '',
            source: '',
            target: '',
            apiSpec: {
                selected: 'None',
                list: ['None', ...this.$page.apiSpecs.map((el) => el.name)],
            },
            trigger: null,
            test: {
                scripts: {
                    request: {
                        list: [],
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                    response: {
                        list: [],
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                },
            },
            example: {
                request: {
                    headers: {},
                    body: null,
                },
                response: {
                    status: {
                        selected: '',
                        list: collect(this.$page.statuses).toArray(),
                    },
                    headers: {},
                    body: null,
                },
            },
        };
    },
    methods: {
        submit() {
            const form = {
                api_spec_id:
                    this.$page.apiSpecs.filter(
                        (el) => el.name === this.apiSpec.selected
                    )?.[0]?.id ?? null,
                method: collect(this.$page.methods)
                    .flip()
                    .get(this.method.selected),
                path: this.path,
                pattern: this.pattern ?? null,
                source_id: this.$page.components.filter(
                    (el) => el.name === this.source
                )?.[0]?.id,
                target_id: this.$page.components.filter(
                    (el) => el.name === this.target
                )?.[0]?.id,
                trigger: this.trigger,
                request: this.example.request,
                response: {
                    status: collect(this.$page.statuses)
                        .flip()
                        .get(this.example.response.status.selected),
                    headers: this.example.response.headers,
                    body: this.example.response.body,
                },
                test: {
                    scripts: {
                        request: this.test.scripts.request.list,
                        response: this.test.scripts.response.list,
                    },
                },
            };

            this.sending = true;
            this.$inertia
                .post(
                    route(
                        'admin.test-cases.test-steps.store',
                        this.testCase.id
                    ),
                    form
                )
                .then(() => (this.sending = false));
        },
        addFormItem(formsList, formPattern) {
            formsList.push(formPattern);
        },
        deleteFormItem(formList, i) {
            formList.splice(i, 1);
        },
    },
    computed: {
        sourceList() {
            const list = this.component.connections.get(this.target);

            if (list) return list;

            return this.component.list;
        },
        targetList() {
            const list = this.component.connections.get(this.source);

            if (list) return list;

            return this.component.list;
        },
    },
};
</script>

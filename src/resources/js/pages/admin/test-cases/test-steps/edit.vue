<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">Edit</h2>
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
                            :disableSearch="false"
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
                                'is-invalid': $page.errors.source,
                            }"
                            v-model="source.selected"
                            placeholder="Select source"
                            :options="source.list"
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.source"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.source }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Target</label>
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid': $page.errors.target,
                            }"
                            v-model="target.selected"
                            placeholder="Select target"
                            :options="target.list"
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.target"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.target }}
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
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.api_spec"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.api_spec }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Trigger</label>
                        <json-editor-block
                            :input-json="trigger"
                            @output-json="() => {}"
                        />
                        <span
                            v-if="$page.errors.trigger"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.trigger }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <h3 class="card-title">Test Request Scripts</h3>
                        <template
                            v-for="(request, i) in test.request.scripts.list"
                        >
                            <div
                                class="card-header justify-content-between"
                                :key="`test-request-script-head-${i}`"
                            >
                                <div class="text-muted">
                                    <span>{{ i + 1 }}.</span>
                                    <span>{{ request.name }}</span>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-link p-0"
                                    v-b-tooltip.hover
                                    title="Delete"
                                    @click="
                                        deleteFormItem(
                                            test.request.scripts.list,
                                            i
                                        )
                                    "
                                >
                                    <icon name="trash" />
                                </button>
                            </div>
                            <div
                                class="card-body"
                                :key="`test-request-script-form-${i}`"
                            >
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control mb-2"
                                    v-model="request.name"
                                />
                                <label class="form-label mb-3">Rules</label>
                                <json-editor-block
                                    :input-json="request.rules"
                                    @output-json="
                                        (data) => (request.rules = data)
                                    "
                                />
                                <div class="text-right"></div>
                            </div>
                        </template>
                        <div class="text-right">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="
                                    addFormItem(test.request.scripts.list, {
                                        ...test.request.scripts.pattern,
                                    })
                                "
                            >
                                <icon name="plus" />
                                <span>Add New Request Script</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <h3 class="card-title">Test Response Scripts</h3>
                        <template
                            v-for="(response, i) in test.response.scripts.list"
                        >
                            <div
                                class="card-header justify-content-between"
                                :key="`test-response-script-head-${i}`"
                            >
                                <div class="text-muted">
                                    <span>{{ i + 1 }}.</span>
                                    <span>{{ response.name }}</span>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-link p-0"
                                    v-b-tooltip.hover
                                    title="Delete"
                                    @click="
                                        deleteFormItem(
                                            test.response.scripts.list,
                                            i
                                        )
                                    "
                                >
                                    <icon name="trash" />
                                </button>
                            </div>
                            <div
                                class="card-body"
                                :key="`test-response-script-form-${i}`"
                            >
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control mb-2"
                                    v-model="response.name"
                                />
                                <label class="form-label mb-3">Rules</label>
                                <json-editor-block
                                    :input-json="response.rules"
                                    @output-json="
                                        (data) => (response.rules = data)
                                    "
                                />
                                <div class="text-right"></div>
                            </div>
                        </template>
                        <div class="text-right">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="
                                    addFormItem(test.response.scripts.list, {
                                        ...test.response.scripts.pattern,
                                    })
                                "
                            >
                                <icon name="plus" />
                                <span>Add New Response Script</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <h3 class="card-title">Test Request Setups</h3>
                        <template
                            v-for="(request, i) in test.request.setups.list"
                        >
                            <div
                                class="card-header justify-content-between"
                                :key="`test-request-script-head-${i}`"
                            >
                                <div class="text-muted">
                                    <span>{{ i + 1 }}.</span>
                                    <span>{{ request.name }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            v-model="request.override"
                                            v-b-tooltip.hover
                                            title="Override all"
                                        />
                                    </label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 ml-2"
                                        v-b-tooltip.hover
                                        title="Delete"
                                        @click="
                                            deleteFormItem(
                                                test.request.setups.list,
                                                i
                                            )
                                        "
                                    >
                                        <icon name="trash" />
                                    </button>
                                </div>
                            </div>
                            <div
                                class="card-body"
                                :key="`test-request-script-form-${i}`"
                            >
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control mb-2"
                                    v-model="request.name"
                                />
                                <label class="form-label mb-3">Rules</label>
                                <json-editor-block
                                    :input-json="request.rules"
                                    @output-json="
                                        (data) => (request.rules = data)
                                    "
                                />
                                <div class="text-right"></div>
                            </div>
                        </template>
                        <div class="text-right">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="
                                    addFormItem(test.request.setups.list, {
                                        ...test.request.setups.pattern,
                                    })
                                "
                            >
                                <icon name="plus" />
                                <span>Add New Request Setup</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <h3 class="card-title">Test Response Setups</h3>
                        <template
                            v-for="(response, i) in test.response.setups.list"
                        >
                            <div
                                class="card-header justify-content-between"
                                :key="`test-response-script-head-${i}`"
                            >
                                <div class="text-muted">
                                    <span>{{ i + 1 }}.</span>
                                    <span>{{ response.name }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            v-model="response.override"
                                            v-b-tooltip.hover
                                            title="Override all"
                                        />
                                    </label>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 ml-2"
                                        v-b-tooltip.hover
                                        title="Delete"
                                        @click="
                                            deleteFormItem(
                                                test.response.setups.list,
                                                i
                                            )
                                        "
                                    >
                                        <icon name="trash" />
                                    </button>
                                </div>
                            </div>
                            <div
                                class="card-body"
                                :key="`test-response-script-form-${i}`"
                            >
                                <label class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control mb-2"
                                    v-model="response.name"
                                />
                                <label class="form-label mb-3">Rules</label>
                                <json-editor-block
                                    :input-json="response.rules"
                                    @output-json="
                                        (data) => (response.rules = data)
                                    "
                                />
                                <div class="text-right"></div>
                            </div>
                        </template>
                        <div class="text-right">
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="
                                    addFormItem(test.response.setups.list, {
                                        ...test.response.setups.pattern,
                                    })
                                "
                            >
                                <icon name="plus" />
                                <span>Add New Response Setup</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label"
                            >Request Header Examples</label
                        >
                        <json-editor-block
                            :input-json="example.request.header"
                            @output-json="() => {}"
                        />
                        <span
                            v-if="$page.errors.requestHeaderExamples"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.requestHeaderExamples }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Request Body Examples</label>
                        <json-editor-block
                            :input-json="example.request.body"
                            @output-json="() => {}"
                        />
                        <span
                            v-if="$page.errors.requestBodyExamples"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.requestBodyExamples }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label"
                            >Response Status Example</label
                        >
                        <selectize
                            class="form-select"
                            :class="{
                                'is-invalid':
                                    $page.errors.responseStatusExample,
                            }"
                            v-model="example.response.status"
                            placeholder="Select status"
                            :options="[]"
                            :disableSearch="false"
                            :createItem="false"
                        />
                        <span
                            v-if="$page.errors.responseStatusExample"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.responseStatusExample }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label"
                            >Response Header Examples</label
                        >
                        <json-editor-block
                            :input-json="example.response.header"
                            @output-json="() => {}"
                        />
                        <span
                            v-if="$page.errors.responseHeaderExamples"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.responseHeaderExamples }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Response Body Examples</label>
                        <json-editor-block
                            :input-json="example.response.body"
                            @output-json="() => {}"
                        />
                        <span
                            v-if="$page.errors.responseBodyExamples"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.errors.responseBodyExamples }}
                            </strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('admin.test-cases.index')"
                    class="btn btn-link"
                >
                    Cancel
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    Update
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
        title: 'Test Step Edit',
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
            method: {
                selected: '',
                list: collect(this.$page.methods).toArray(),
            },
            path: '',
            pattern: '',
            source: {
                selected: '',
                list: this.$page.components.map((el) => el.name),
            },
            target: {
                selected: '',
                list: this.$page.components.map((el) => el.name),
            },
            apiSpec: {
                selected: '',
                list: ['None', ...this.$page.apiSpecs.map((el) => el.name)],
            },
            trigger: {},
            test: {
                request: {
                    setups: {
                        list: [],
                        pattern: {
                            override: false,
                            name: '',
                            rules: {},
                        },
                    },
                    scripts: {
                        list: [],
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                },
                response: {
                    setups: {
                        list: [],
                        pattern: {
                            override: false,
                            name: '',
                            rules: {},
                        },
                    },
                    scripts: {
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
                    header: {},
                    body: {},
                },
                response: {
                    status: '',
                    header: {},
                    body: {},
                },
            },
        };
    },
    methods: {
        submit() {
            this.sending = true;
            // this.$inertia
            //     .post(route('admin.test-cases.store'), this.form)
            //     .then(() => (this.sending = false));
        },
        addFormItem(formsList, formPattern) {
            formsList.push(formPattern);
        },
        deleteFormItem(formList, i) {
            formList.splice(i, 1);
        },
    },
    mounted() {
        this.addFormItem(this.test.request.scripts.list, {
            ...this.test.request.scripts.pattern,
        });
        this.addFormItem(this.test.response.scripts.list, {
            ...this.test.response.scripts.pattern,
        });
        this.addFormItem(this.test.request.setups.list, {
            ...this.test.request.setups.pattern,
        });
        this.addFormItem(this.test.response.setups.list, {
            ...this.test.response.setups.pattern,
        });
    },
};
</script>

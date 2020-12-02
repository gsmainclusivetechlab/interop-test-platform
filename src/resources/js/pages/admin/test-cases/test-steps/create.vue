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
                        <label class="form-label">Trigger</label>
                        <json-editor-block
                            :input-json="trigger"
                            @output-json="() => {}"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle"
                            v-b-toggle="'test-request-scripts'"
                        >
                            Test Request Scripts
                        </button>
                        <b-collapse id="test-request-scripts" visible>
                            <template
                                v-for="(request, i) in test.scripts.request
                                    .list"
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
                                                test.scripts.request.list,
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
                            class="btn btn-link card-title dropdown-toggle"
                            v-b-toggle="'test-response-scripts'"
                        >
                            Test Response Scripts
                        </button>
                        <b-collapse id="test-response-scripts" visible>
                            <template
                                v-for="(response, i) in test.scripts.response
                                    .list"
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
                                                test.scripts.response.list,
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
                            class="btn btn-link card-title dropdown-toggle"
                            v-b-toggle="'test-request-setups'"
                        >
                            Test Request Setups
                        </button>
                        <b-collapse id="test-request-setups" visible>
                            <template
                                v-for="(request, i) in test.setups.request.list"
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
                                                    test.setups.request.list,
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
                                    <label class="form-label mb-3"
                                        >Values</label
                                    >
                                    <json-editor-block
                                        :input-json="request.values"
                                        @output-json="
                                            (data) => (request.values = data)
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
                                        addFormItem(test.setups.request.list, {
                                            ...test.setups.request.pattern,
                                        })
                                    "
                                >
                                    <icon name="plus" />
                                    <span>Add New Request Setup</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle"
                            v-b-toggle="'test-response-setups'"
                        >
                            Test Response Setups
                        </button>
                        <b-collapse id="test-response-setups" visible>
                            <template
                                v-for="(response, i) in test.setups.response
                                    .list"
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
                                                    test.setups.response.list,
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
                                    <label class="form-label mb-3"
                                        >Values</label
                                    >
                                    <json-editor-block
                                        :input-json="response.values"
                                        @output-json="
                                            (data) => (response.values = data)
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
                                        addFormItem(test.setups.response.list, {
                                            ...test.setups.response.pattern,
                                        })
                                    "
                                >
                                    <icon name="plus" />
                                    <span>Add New Response Setup</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label"
                            >Request Headers Examples</label
                        >
                        <json-editor-block
                            :input-json="example.request.headers"
                            @output-json="() => {}"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Request Body Examples</label>
                        <json-editor-block
                            :input-json="example.request.body"
                            @output-json="() => {}"
                        />
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label"
                            >Response Status Example</label
                        >
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
                        <label class="form-label"
                            >Response Headers Examples</label
                        >
                        <json-editor-block
                            :input-json="example.response.headers"
                            @output-json="() => {}"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Response Body Examples</label>
                        <json-editor-block
                            :input-json="example.response.body"
                            @output-json="() => {}"
                        />
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
            trigger: {},
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
                setups: {
                    request: {
                        list: [],
                        pattern: {
                            override: false,
                            name: '',
                            values: {},
                        },
                    },
                    response: {
                        list: [],
                        pattern: {
                            override: false,
                            name: '',
                            values: {},
                        },
                    },
                },
            },
            example: {
                request: {
                    headers: {},
                    body: {},
                },
                response: {
                    status: {
                        selected: '',
                        list: collect(this.$page.statuses).toArray(),
                    },
                    headers: {},
                    body: {},
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
                trigger:
                    Object.keys(this.trigger).length > 0 ? this.trigger : null,
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
                    setups: {
                        request: this.test.setups.request.list,
                        response: this.test.setups.response.list,
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
            const list = [...this.component.list];
            const i = this.component.list.indexOf(this.source);

            if (i < 0) return list;

            list.splice(this.component.list.indexOf(this.target), 1);

            return list;
        },
        targetList() {
            const list = [...this.component.list];
            const i = this.component.list.indexOf(this.source);

            if (i < 0) return list;

            list.splice(this.component.list.indexOf(this.source), 1);

            return list;
        },
    },
};
</script>

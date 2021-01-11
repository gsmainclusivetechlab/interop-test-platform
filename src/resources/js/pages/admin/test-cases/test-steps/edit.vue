<template>
    <layout :test-case="testCase">
        <form class="card" @submit.prevent="submit">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.method.label')
                        }}</label>
                        <v-select
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.method,
                            }"
                            v-model="method.selected"
                            :placeholder="$t('inputs.method.placeholder')"
                            :options="method.list"
                        />
                        <span
                            v-if="$page.props.errors.method"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.method }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.path')
                        }}</label>
                        <input
                            name="path"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.path,
                            }"
                            v-model="path"
                        />
                        <span
                            v-if="$page.props.errors.path"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.path }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.pattern')
                        }}</label>
                        <input
                            name="pattern"
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors.pattern,
                            }"
                            v-model="pattern"
                        />
                        <span
                            v-if="$page.props.errors.pattern"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.pattern }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.source.label')
                        }}</label>
                        <v-select
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.source_id,
                            }"
                            v-model="source"
                            :placeholder="$t('inputs.source.placeholder')"
                            :options="sourceList"
                        />
                        <span
                            v-if="$page.props.errors.source_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.source_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.target.label')
                        }}</label>
                        <v-select
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.target_id,
                            }"
                            v-model="target"
                            :placeholder="$t('inputs.target.placeholder')"
                            :options="targetList"
                        />
                        <span
                            v-if="$page.props.errors.target_id"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors.target_id }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.api-spec.label')
                        }}</label>
                        <v-select
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid': $page.props.errors.api_spec,
                            }"
                            v-model="apiSpec.selected"
                            :placeholder="$t('inputs.api-spec.placeholder')"
                            :options="apiSpec.list"
                        />
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'trigger'"
                        >
                            {{ $t('inputs.trigger') }}
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
                            {{ $t('inputs.test-scripts.request') }}
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
                                            :title="
                                                $t(
                                                    'inputs.test-scripts.form.buttons.delete'
                                                )
                                            "
                                            v-b-modal="
                                                `test-request-scripts-modal-${i}`
                                            "
                                        >
                                            <icon name="trash" />
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <label class="form-label">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.name'
                                        )
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.name`
                                                ],
                                        }"
                                        v-model="request.name"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.request.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-editor-block
                                        :input-json="request.rules"
                                        @output-json="
                                            (data) => (request.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.request.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.request.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-request-scripts-modal-${i}`"
                                    :title="
                                        $t(
                                            'inputs.test-scripts.form.modal.title'
                                        )
                                    "
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
                                        {{
                                            $t(
                                                'inputs.test-scripts.form.modal.text'
                                            )
                                        }}
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
                                    <span>{{
                                        $t(
                                            'inputs.test-scripts.form.buttons.add-script'
                                        )
                                    }}</span>
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
                            {{ $t('inputs.test-scripts.response') }}
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
                                            :title="
                                                $t(
                                                    'inputs.test-scripts.form.buttons.delete'
                                                )
                                            "
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
                                    <label class="form-label">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.name'
                                        )
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control mb-2"
                                        :class="{
                                            'is-invalid':
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.name`
                                                ],
                                        }"
                                        v-model="response.name"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.response.${i}.name`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.name`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-editor-block
                                        :input-json="response.rules"
                                        @output-json="
                                            (data) => (response.rules = data)
                                        "
                                        :class="{
                                            'form-control is-invalid p-0':
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ],
                                        }"
                                    />
                                    <span
                                        v-if="
                                            $page.props.errors[
                                                `test.scripts.response.${i}.rules`
                                            ]
                                        "
                                        class="invalid-feedback"
                                    >
                                        <strong>
                                            {{
                                                $page.props.errors[
                                                    `test.scripts.response.${i}.rules`
                                                ]
                                            }}
                                        </strong>
                                    </span>
                                </div>
                                <b-modal
                                    :id="`test-response-scripts-modal-${i}`"
                                    :title="
                                        $t(
                                            'inputs.test-scripts.form.modal.title'
                                        )
                                    "
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
                                        {{
                                            $t(
                                                'inputs.test-scripts.form.modal.text'
                                            )
                                        }}
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
                                    <span>{{
                                        $t(
                                            'inputs.test-scripts.form.buttons.add-script'
                                        )
                                    }}</span>
                                </button>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.request.uri') }}
                        </h2>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'is-invalid': $page.props.errors['request.uri'],
                            }"
                            v-model="example.request.uri"
                        />
                        <span
                            v-if="$page.props.errors['request.uri']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['request.uri'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-headers-examples'"
                        >
                            {{ $t('inputs.request.headers') }}
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
                            {{ $t('inputs.request.body') }}
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
                        <h2 class="card-title">
                            {{ $t('inputs.response.status.label') }}
                        </h2>
                        <v-select
                            class="form-control d-flex p-0"
                            :class="{
                                'is-invalid':
                                    $page.props.errors['response.status'],
                            }"
                            v-model="example.response.status.selected"
                            :placeholder="
                                $t('inputs.response.status.placeholder')
                            "
                            :options="example.response.status.list"
                        />
                        <span
                            v-if="$page.props.errors['response.status']"
                            class="invalid-feedback"
                        >
                            <strong>
                                {{ $page.props.errors['response.status'] }}
                            </strong>
                        </span>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-headers-examples'"
                        >
                            {{ $t('inputs.response.headers') }}
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
                            {{ $t('inputs.response.body') }}
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
                    {{ $t('buttons.cancel') }}
                </inertia-link>
                <button type="submit" class="btn btn-primary">
                    <span
                        v-if="sending"
                        class="spinner-border spinner-border-sm mr-2"
                    ></span>
                    {{ $t('buttons.update') }}
                </button>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import JsonEditorBlock from '@/components/json-editor-block';

export default {
    metaInfo() {
        return {
            title: `${this.testCase.name} - ${this.$t('card.title')}`,
        };
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
        testStep: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            sending: false,
            component: {
                list: this.$page.props.components.map((el) => el.name),
                connections: new Map(
                    this.$page.props.components.map((el) => [
                        el.name,
                        el.connections.data.map(
                            (connection) => connection.name
                        ),
                    ])
                ),
            },
            method: {
                selected: this.testStep.method,
                list: collect(this.$page.props.methods).toArray(),
            },
            path: this.testStep?.path,
            pattern: this.testStep?.pattern,
            source: this.testStep.source?.data?.name,
            target: this.testStep.target?.data?.name,
            apiSpec: {
                selected: this.testStep.apiSpec?.data?.name,
                list: [
                    'None',
                    ...this.$page.props.apiSpecs.map((el) => el.name),
                ],
            },
            trigger: this.testStep?.trigger,
            test: {
                scripts: {
                    request: {
                        list: this.testStep.testScripts?.data?.filter(
                            (el) => el.type === 'request'
                        ),
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                    response: {
                        list: this.testStep.testScripts?.data?.filter(
                            (el) => el.type === 'response'
                        ),
                        pattern: {
                            name: '',
                            rules: {},
                        },
                    },
                },
            },
            example: {
                request: {
                    uri: this.testStep.request.uri,
                    headers: this.testStep.request.headers,
                    body: this.testStep.request.body,
                },
                response: {
                    status: {
                        selected: this.$page.props.statuses[
                            this.testStep.response.status
                        ],
                        list: collect(this.$page.props.statuses).toArray(),
                    },
                    headers: this.testStep.response.headers,
                    body: this.testStep.response.body,
                },
            },
        };
    },
    methods: {
        submit() {
            const form = {
                api_spec_id:
                    this.$page.props.apiSpecs.filter(
                        (el) => el.name === this.apiSpec.selected
                    )?.[0]?.id ?? null,
                method: collect(this.$page.props.methods)
                    .flip()
                    .get(this.method.selected),
                path: this.path,
                pattern: this.pattern,
                source_id: this.$page.props.components.filter(
                    (el) => el.name === this.source
                )?.[0]?.id,
                target_id: this.$page.props.components.filter(
                    (el) => el.name === this.target
                )?.[0]?.id,
                trigger: this.trigger,
                request: this.example.request,
                response: {
                    status: collect(this.$page.props.statuses)
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
            this.$inertia.put(
                route('admin.test-cases.test-steps.update', [
                    this.testCase.id,
                    this.testStep.id,
                ]),
                form,
                {
                    onFinish: () => {
                        this.sending = false;
                    },
                }
            );
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
<i18n src="@locales/pages/admin/test-cases/test-steps/edit.json"></i18n>

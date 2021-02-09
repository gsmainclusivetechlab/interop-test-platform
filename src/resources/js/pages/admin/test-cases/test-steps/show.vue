<template>
    <layout :test-case="testCase">
        <form class="card">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.method.label')
                        }}</label>
                        <p class="form-control">{{ method.selected }}</p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.path')
                        }}</label>
                        <p class="form-control">{{ path }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">{{
                            $t('inputs.pattern')
                        }}</label>
                        <p class="form-control">{{ pattern }}</p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.source.label')
                        }}</label>
                        <p class="form-control">{{ source }}</p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.target.label')
                        }}</label>
                        <p class="form-control">{{ target }}</p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">{{
                            $t('inputs.api-spec.label')
                        }}</label>
                        <p class="form-control">{{ apiSpec.selected }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-check form-switch">
                            <input
                                :checked="mtls === 1"
                                type="checkbox"
                                class="form-check-input"
                                disabled
                            />
                            <span class="form-check-label">mTLS</span>
                        </label>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'trigger'"
                        >
                            {{ $t('inputs.trigger') }}
                        </button>
                        <b-collapse id="trigger" class="card mb-0">
                            <json-tree
                                :data="trigger"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-request-scripts'"
                            :disabled="test.scripts.request.list.length === 0"
                        >
                            {{ $t('inputs.test-scripts.request') }}
                        </button>
                        <b-collapse id="test-request-scripts" class="card mb-0">
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
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-tree
                                        :data="request.rules"
                                        :deep="2"
                                        :show-line="false"
                                        class="p-2"
                                    ></json-tree>
                                </div>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.request.uri') }}
                        </h2>
                        <p class="form-control">{{ example.request.uri }}</p>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.request.delay') }}
                        </h2>
                        <p class="form-control">{{ example.request.delay }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-headers-examples'"
                        >
                            {{ $t('inputs.request.headers') }}
                        </button>
                        <b-collapse
                            id="request-headers-examples"
                            class="card mb-0"
                        >
                            <json-tree
                                :data="example.request.headers"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
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
                        <b-collapse
                            id="request-body-examples"
                            class="card mb-0"
                        >
                            <div class="card-header">
                                <div class="card-options">
                                    <label class="form-check form-switch">
                                        <input
                                            :checked="
                                                example.request.body ===
                                                'empty_body'
                                            "
                                            type="checkbox"
                                            class="form-check-input"
                                            disabled
                                        />
                                        <span class="form-check-label">{{
                                            $t('inputs.empty-body')
                                        }}</span>
                                    </label>
                                </div>
                            </div>
                            <json-tree
                                v-if="example.request.body !== 'empty_body'"
                                :data="example.request.body"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'request-jws-examples'"
                        >
                            {{ $t('inputs.request.jws') }}
                        </button>
                        <b-collapse id="request-jws-examples" class="card mb-0">
                            <json-tree
                                :data="example.request.jws"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-response-scripts'"
                            :disabled="test.scripts.response.list.length === 0"
                        >
                            {{ $t('inputs.test-scripts.response') }}
                        </button>
                        <b-collapse
                            id="test-response-scripts"
                            class="card mb-0"
                        >
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
                                </div>
                                <div
                                    class="card-body px-0 pb-0"
                                    :key="`test-response-scripts-form-${i}`"
                                >
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-tree
                                        :data="response.rules"
                                        :deep="2"
                                        :show-line="false"
                                        class="p-2"
                                    ></json-tree>
                                </div>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.response.status.label') }}
                        </h2>
                        <p class="form-control">
                            {{ example.response.status.selected }}
                        </p>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.response.delay') }}
                        </h2>
                        <p class="form-control">{{ example.response.delay }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-headers-examples'"
                        >
                            {{ $t('inputs.response.headers') }}
                        </button>
                        <b-collapse
                            id="response-headers-examples"
                            class="card mb-0"
                        >
                            <json-tree
                                :data="example.response.headers"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
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
                        <b-collapse
                            id="response-body-examples"
                            class="card mb-0"
                        >
                            <div class="card-header">
                                <div class="card-options">
                                    <label class="form-check form-switch">
                                        <input
                                            :checked="
                                                example.response.body ===
                                                'empty_body'
                                            "
                                            type="checkbox"
                                            class="form-check-input"
                                            disabled
                                        />
                                        <span class="form-check-label">{{
                                            $t('inputs.empty-body')
                                        }}</span>
                                    </label>
                                </div>
                            </div>
                            <json-tree
                                v-if="example.response.body !== 'empty_body'"
                                :data="example.response.body"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'response-jws-examples'"
                        >
                            {{ $t('inputs.response.jws') }}
                        </button>
                        <b-collapse
                            id="response-jws-examples"
                            class="card mb-0"
                        >
                            <json-tree
                                :data="example.response.jws"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <hr />
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'test-repeat-response-scripts'"
                            :disabled="
                                test.scripts.repeat.response.list.length === 0
                            "
                        >
                            {{ $t('inputs.test-scripts.repeat.response') }}
                        </button>
                        <b-collapse
                            id="test-repeat-response-scripts"
                            class="card mb-0"
                        >
                            <div
                                v-for="(response, i) in test.scripts.repeat
                                    .response.list"
                                class="card-body"
                                :key="`test-repeat-response-scripts-${i}`"
                            >
                                <div class="card-header px-0 pt-0">
                                    <span class="text-muted text-break mr-2"
                                        >{{ i + 1 }}. {{ response.name }}</span
                                    >
                                </div>
                                <div
                                    class="card-body px-0 pb-0"
                                    :key="`test-repeat-response-scripts-form-${i}`"
                                >
                                    <label class="form-label mb-3">{{
                                        $t(
                                            'inputs.test-scripts.form.inputs.rules'
                                        )
                                    }}</label>
                                    <json-tree
                                        :data="response.rules"
                                        :deep="2"
                                        :show-line="false"
                                        class="p-2"
                                    ></json-tree>
                                </div>
                            </div>
                        </b-collapse>
                    </div>
                    <div class="col-12 mb-3">
                        <button
                            type="button"
                            class="btn btn-link card-title dropdown-toggle px-0"
                            v-b-toggle="'repeat-condition'"
                        >
                            {{ $t('inputs.repeat.condition') }}
                        </button>
                        <b-collapse id="repeat-condition" class="card mb-0">
                            <json-tree
                                :data="example.repeat.condition"
                                :deep="2"
                                :show-line="false"
                                class="p-2"
                            ></json-tree>
                        </b-collapse>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.repeat.count') }}
                        </h2>
                        <p class="form-control">
                            {{ example.repeat.count }}
                        </p>
                    </div>
                    <div class="col-6 mb-3">
                        <h2 class="card-title">
                            {{ $t('inputs.repeat.max') }}
                        </h2>
                        <p class="form-control">{{ example.repeat.max }}</p>
                    </div>
                    <template v-if="testStep.repeat.response">
                        <div class="col-6 mb-3">
                            <h2 class="card-title">
                                {{ $t('inputs.repeat.response.status.label') }}
                            </h2>
                            <p class="form-control">
                                {{ example.repeat.response.status.selected }}
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <button
                                type="button"
                                class="btn btn-link card-title dropdown-toggle px-0"
                                v-b-toggle="'repeat-response-headers-examples'"
                            >
                                {{ $t('inputs.repeat.response.headers') }}
                            </button>
                            <b-collapse
                                id="repeat-response-headers-examples"
                                class="card mb-0"
                            >
                                <json-tree
                                    :data="example.repeat.response.headers"
                                    :deep="2"
                                    :show-line="false"
                                    class="p-2"
                                ></json-tree>
                            </b-collapse>
                        </div>
                        <div class="col-12 mb-3">
                            <button
                                type="button"
                                class="btn btn-link card-title dropdown-toggle px-0"
                                v-b-toggle="'repeat-response-body-examples'"
                            >
                                {{ $t('inputs.repeat.response.body') }}
                            </button>
                            <b-collapse
                                id="repeat-response-body-examples"
                                class="card mb-0"
                            >
                                <div class="card-header">
                                    <div class="card-options">
                                        <label class="form-check form-switch">
                                            <input
                                                :checked="
                                                    example.repeat.response
                                                        .body === 'empty_body'
                                                "
                                                type="checkbox"
                                                class="form-check-input"
                                                disabled
                                            />
                                            <span class="form-check-label">{{
                                                $t('inputs.empty-body')
                                            }}</span>
                                        </label>
                                    </div>
                                </div>
                                <json-tree
                                    v-if="
                                        example.repeat.response.body !==
                                        'empty_body'
                                    "
                                    :data="example.repeat.response.body"
                                    :deep="2"
                                    :show-line="false"
                                    class="p-2"
                                ></json-tree>
                            </b-collapse>
                        </div>
                    </template>
                </div>
            </div>
        </form>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import JsonEditorBlock from '@/components/json-editor-block';
import mixinVSelect from '@/components/v-select/mixin';

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
    mixins: [mixinVSelect],
    data() {
        return {
            method: {
                selected: this.testStep?.method,
            },
            path: this.testStep?.path,
            pattern: this.testStep?.pattern,
            source: this.testStep.source?.data?.name,
            target: this.testStep.target?.data?.name,
            apiSpec: {
                selected: this.testStep.apiSpec?.data?.name ?? null,
            },
            trigger: this.testStep?.trigger,
            mtls: this.testStep?.mtls,
            test: {
                scripts: {
                    request: {
                        list: this.testStep.testScripts?.data?.filter(
                            (el) => el.type === 'request'
                        ),
                    },
                    response: {
                        list: this.testStep.testScripts?.data?.filter(
                            (el) => el.type === 'response'
                        ),
                    },
                    repeat: {
                        response: {
                            list: this.testStep.testScripts?.data?.filter(
                                (el) => el.type === 'repeat_response'
                            ),
                        },
                    },
                },
            },
            example: {
                request: {
                    delay: this.testStep.request?.delay,
                    uri: this.testStep.request?.uri,
                    jws: this.testStep.request?.jws,
                    headers: this.testStep.request?.headers,
                    body: this.testStep.request?.body,
                },
                response: {
                    delay: this.testStep.response?.delay,
                    jws: this.testStep.response?.jws,
                    status: {
                        selected: this.testStep.response?.status,
                    },
                    headers: this.testStep.response?.headers,
                    body: this.testStep.response?.body,
                },
                repeat: {
                    condition: this.testStep.repeat.condition,
                    count: this.testStep.repeat.count,
                    max: this.testStep.repeat.max,
                    response: {
                        status: {
                            selected: this.testStep.repeat.response?.status,
                        },
                        headers: this.testStep.repeat.response?.headers,
                        body: this.testStep.repeat.response?.body,
                    },
                },
            },
        };
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/test-steps/create.json"></i18n>
<i18n src="@locales/pages/admin/test-cases/test-steps/show.json"></i18n>

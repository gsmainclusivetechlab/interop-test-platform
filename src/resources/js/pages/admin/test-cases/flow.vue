<template>
    <layout :test-case="testCase">
        <div class="card mb-0">
            <div class="card-header">
                <h2 class="card-title">
                    <b>{{ $t('card.title', { name: testCase.name }) }}</b>
                </h2>
                <div class="card-options">
                    <a
                        :href="editorLink"
                        target="_blank"
                        rel="noreferrer"
                        class="btn btn-primary"
                    >
                        <icon name="external-link"></icon>
                        {{ $t('buttons.diagram-editor') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <diagram ref="diagram">
                    {{ diagramCode }}
                </diagram>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';
import Diagram from '@/components/diagram';

import { Base64 } from 'js-base64';

export default {
    components: {
        Layout,
        Diagram,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        testSteps: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            flowData: {
                code: null,
                mermaid: {
                    sequence: {
                        diagramMarginX: 30,
                        width: 185,
                        height: 60,
                    },
                    theme: 'default',
                },
            },
            editorUrl:
                'https://mermaid-js.github.io/mermaid-live-editor/#/edit/',
        };
    },
    mounted() {
        this.flowData.code = this.diagramCode;
    },
    computed: {
        editorLink() {
            const encodedDiagramData = Base64.encodeURI(
                JSON.stringify(this.flowData)
            );

            return `${this.editorUrl}${encodedDiagramData}`;
        },
        diagramCode() {
            return this.testSteps.data.reduce((template, testStep) => {
                if (testStep.response) {
                    template += `
                        ${testStep.source.name} ->> ${testStep.target.name}: ${testStep.method} ${testStep.path};
                        ${testStep.target.name} -->> ${testStep.source.name}: HTTP ${testStep.response.status};
                    `;
                } else {
                    template += `
                        ${testStep.source.name} ->> ${testStep.target.name}: ${testStep.method} ${testStep.path};
                        ${testStep.target.name} -->> ${testStep.source.name}: HTTP;
                    `;
                }

                return template;
            }, 'sequenceDiagram;');
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/flow.json"></i18n>

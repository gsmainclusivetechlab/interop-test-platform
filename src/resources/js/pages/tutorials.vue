<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>{{ $t('page.title') }}</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card" v-if="tutorials">
            <div class="row justify-content-md-around">
                <div class="w-100"><br /></div>
                <template v-for="(item, index) of tutorials">
                    <div class="col-3 tutorial-cards">
                        <a
                            class="d-inline-block btn scenario-card"
                            :href="'#' + item.id"
                            v-b-toggle="'accordion-' + item.id"
                        >
                            <h4
                                class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                            >
                                <img :src="item.icon" class="icon" />
                                {{ item.tile.title }}
                            </h4>
                            <div class="pl-2 font-weight-normal">
                                {{ item.tile.comment }}
                            </div>
                        </a>
                    </div>
                    <div v-if="(index + 1) % 3 === 0" class="w-100"><br /></div>
                </template>
            </div>
            <div role="tablist" class="tutorial-accordion px-4">
                <template v-for="item of tutorials">
                    <article class="mb-3 card">
                        <header
                            :id="item.id"
                            class="pl-0 card-header"
                            role="tab"
                        >
                            <button
                                v-b-toggle="'accordion-' + item.id"
                                class="btn shadow-none"
                                type="button"
                            >
                                <h3
                                    class="mb-0 scenario-accordion text-primary"
                                >
                                    {{ item.accordion.title }}
                                </h3>
                            </button>
                        </header>
                        <b-collapse
                            :id="'accordion-' + item.id"
                            accordion="tutorial-accordion"
                            role="tabpanel"
                        >
                            <div class="card-body">
                                <div v-html="item.accordion.htmlContent"></div>
                                <tutorial-demo
                                    v-if="
                                        item.accordion.tutorialData &&
                                        item.accordion.tutorialData.length > 0
                                    "
                                    :demo-data="item.accordion.tutorialData"
                                />
                            </div>
                        </b-collapse>
                    </article>
                </template>
            </div>
        </div>

        <div class="card">
            <div role="tablist" class="tutorial-accordion px-4">
                <article class="mb-3 card">
                    <header
                        id="service-provider"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button
                            v-b-toggle.accordion-2
                            class="btn shadow-none demo2"
                            type="button"
                        >
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[1].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-2"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div>
                                <p>
                                    {{ $t('drop-panel[1].comment[0]') }}
                                </p>
                                <p>
                                    {{ $t('drop-panel[1].comment[1]') }}
                                    <a
                                        href="#postman-collections"
                                        v-b-toggle.accordion-4
                                        >{{ $t('drop-panel[1].links[0]') }}</a
                                    >
                                </p>
                            </div>
                            <tutorial-demo
                                :demo-data="demoDataList.serviceProvider"
                            />
                        </div>
                    </b-collapse>
                </article>

                <article class="mb-3 card">
                    <header
                        id="mobile-money-operator"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button
                            v-b-toggle.accordion-3
                            class="btn shadow-none demo3"
                            type="button"
                        >
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[2].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-3"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div>
                                <p>
                                    {{ $t('drop-panel[2].comment') }}
                                </p>
                            </div>
                            <tutorial-demo
                                :demo-data="demoDataList.mobileMoneyOperator"
                            />
                        </div>
                    </b-collapse>
                </article>

                <article class="mb-3 card">
                    <header
                        id="postman-collections"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button
                            v-b-toggle.accordion-4
                            class="btn shadow-none"
                            type="button"
                        >
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[3].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-4"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div>
                                <p>
                                    {{ $t('drop-panel[3].comment') }}
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://explore.postman.com/api/5380/interoperability-test-platform---service-provider-simulator"
                                            target="_blank"
                                            class="ml-1"
                                            >{{
                                                $t('drop-panel[3].links[0]')
                                            }}</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </b-collapse>
                </article>

                <article class="mb-3 card">
                    <header
                        id="mojaloop-info"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button
                            v-b-toggle.accordion-5
                            class="btn shadow-none"
                            type="button"
                        >
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[4].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-5"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div>
                                <p>
                                    {{ $t('drop-panel[4].comment') }}
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://mojaloop.io/documentation/"
                                            target="_blank"
                                            class="ml-1"
                                            >{{
                                                $t('drop-panel[4].links[0]')
                                            }}</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            href="https://mojaloop.io/mojaloop-specification/"
                                            target="_blank"
                                            class="ml-1"
                                            >{{
                                                $t('drop-panel[4].links[0]')
                                            }}</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </b-collapse>
                </article>

                <article class="card">
                    <header
                        id="mobile-money-api-info"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button v-b-toggle.accordion-6 class="btn shadow-none">
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[5].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-6"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div>
                                <p>
                                    {{ $t('drop-panel[5].comment') }}
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://www.gsma.com/mobilefordevelopment/mobile-money/mobile-money-api/"
                                            target="_blank"
                                            class="ml-1"
                                            >{{
                                                $t('drop-panel[5].links[0]')
                                            }}</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            href="https://developer.mobilemoneyapi.io/"
                                            target="_blank"
                                            class="ml-1"
                                            >{{
                                                $t('drop-panel[5].links[1]')
                                            }}</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </b-collapse>
                </article>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';
import TutorialDemo from '@/components/tutorial-demo';

const createImgPath = '/assets/images/tutorials/create-session/';
const executeSPImgPath = '/assets/images/tutorials/service-provider/';
const executeMMOImgPath = 'assets/images/tutorials/mobile-money-operator/';

export default {
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
    },
    components: {
        Layout,
        TutorialDemo,
    },
    data() {
        return {
            tutorials: null,
        };
    },
    mounted() {
        axios.get('http://localhost:8080/tutorial_en.json').then((res) => {
            this.tutorials = res.data;
        });
    },
    computed: {
        demoDataList() {
            return {
                createSession: [
                    {
                        slideSrc: `${createImgPath}dashboard.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 96, y: 5.3 },
                        slideText: this.$t('drop-panel[0].demo-steps[0]'),
                    },
                    {
                        slideSrc: `${createImgPath}select_sut.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 35, y: 33 },
                        slideText: this.$t('drop-panel[0].demo-steps[1]'),
                    },
                    {
                        slideSrc: `${createImgPath}select_sut_2.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 35, y: 37 },
                        slideText: this.$t('drop-panel[0].demo-steps[2]'),
                    },
                    {
                        slideSrc: `${createImgPath}select_sut_3.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 66.5, y: 41.3 },
                        slideText: this.$t('drop-panel[0].demo-steps[3]'),
                    },
                    {
                        slideSrc: `${createImgPath}session_info.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 53, y: 31 },
                        slideText: this.$t('drop-panel[0].demo-steps[4]'),
                    },
                    {
                        slideSrc: `${createImgPath}session_info_2.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 53, y: 33 },
                        slideText: this.$t('drop-panel[0].demo-steps[5]'),
                    },
                    {
                        slideSrc: `${createImgPath}session_info_3.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 53, y: 37 },
                        slideText: this.$t('drop-panel[0].demo-steps[6]'),
                    },
                    {
                        slideSrc: `${createImgPath}session_info_4.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 76, y: 44.5 },
                        slideText: this.$t('drop-panel[0].demo-steps[7]'),
                    },
                    {
                        slideSrc: `${createImgPath}configure_sut.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 66, y: 39.3 },
                        slideText: this.$t('drop-panel[0].demo-steps[8]'),
                    },
                    {
                        slideSrc: `${createImgPath}session_created.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 0, y: 0 },
                        slideText: this.$t('drop-panel[0].demo-steps[9]'),
                    },
                ],
                serviceProvider: [
                    {
                        slideSrc: `${executeSPImgPath}1-session-page.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 3, y: 18 },
                        slideText: this.$t('drop-panel[1].demo-steps[0]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}2-session-usecase.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 8, y: 19 },
                        slideText: this.$t('drop-panel[1].demo-steps[1]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}3-test-runs.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 37, y: 12.5 },
                        slideText: this.$t('drop-panel[1].demo-steps[2]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}4-test-flow.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 23, y: 19.5 },
                        slideText: this.$t('drop-panel[1].demo-steps[3]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}7-postman-1.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 88, y: 9 },
                        slideText: this.$t('drop-panel[1].demo-steps[4]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}9-test-runs.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 33, y: 20 },
                        slideText: this.$t('drop-panel[1].demo-steps[5]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}10-test-details-1.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 53, y: 39.5 },
                        slideText: this.$t('drop-panel[1].demo-steps[6]'),
                    },
                    {
                        slideSrc: `${executeSPImgPath}11-test-details-2.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 0, y: 0 },
                        slideText: this.$t('drop-panel[1].demo-steps[7]'),
                    },
                ],
                mobileMoneyOperator: [
                    {
                        slideSrc: `${executeMMOImgPath}1-session-page.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 3, y: 22.5 },
                        slideText: this.$t('drop-panel[2].demo-steps[0]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}2-test-usecase.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 8, y: 20.8 },
                        slideText: this.$t('drop-panel[2].demo-steps[1]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}3-test-runs.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 37, y: 12.5 },
                        slideText: this.$t('drop-panel[2].demo-steps[2]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}4-test-flow.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 11, y: 23.2 },
                        slideText: this.$t('drop-panel[2].demo-steps[3]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}4-test-flow.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 96, y: 12.2 },
                        slideText: this.$t('drop-panel[2].demo-steps[4]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}5-run-test.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 28, y: 12.5 },
                        slideText: this.$t('drop-panel[2].demo-steps[5]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}6-test-runs-2.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 32, y: 20 },
                        slideText: this.$t('drop-panel[2].demo-steps[6]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}7-test-details.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 52, y: 35.5 },
                        slideText: this.$t('drop-panel[2].demo-steps[7]'),
                    },
                    {
                        slideSrc: `${executeMMOImgPath}8-test-details-2.png`,
                        slideRatio: '16/8',
                        targetPosition: { x: 0, y: 0 },
                        slideText: this.$t('drop-panel[2].demo-steps[8]'),
                    },
                ],
            };
        },
    },
};
</script>
<i18n src="@locales/pages/tutorials.json"></i18n>

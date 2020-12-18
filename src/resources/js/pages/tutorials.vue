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
        <div class="card">
            <div class="centered tutorial-cards mb-2 p-4">
                <section class="d-flex justify-content-around mb-4">
                    <a
                        class="d-inline-block btn scenario-card demo1"
                        href="#create-session"
                        v-b-toggle.accordion-1
                        @click="initDemo"
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="/assets/images/tutorials/Gizmo_RED_Stack.png"
                                class="icon"
                            />
                            {{ $t('panel[0].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[0].comment') }}
                        </div>
                    </a>

                    <a
                        class="d-inline-block btn scenario-card demo2"
                        href="#service-provider"
                        v-b-toggle.accordion-2
                        @click="initDemo"
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="assets/images/tutorials/Gizmo_RED_Press.png"
                                class="icon"
                            />
                            {{ $t('panel[1].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[1].comment') }}
                        </div>
                    </a>

                    <a
                        class="d-inline-block btn scenario-card demo3"
                        href="#mobile-money-operator"
                        v-b-toggle.accordion-3
                        @click="initDemo"
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="assets/images/tutorials/Gizmo_RED_Press.png"
                                class="icon"
                            />
                            {{ $t('panel[2].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[2].comment') }}
                        </div>
                    </a>
                </section>
                <section class="d-flex justify-content-around">
                    <a
                        class="d-inline-block btn scenario-card"
                        href="#postman-collections"
                        v-b-toggle.accordion-4
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="assets/images/tutorials/logo-postman.png"
                                class="icon"
                            />
                            {{ $t('panel[3].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[3].comment') }}
                        </div>
                    </a>

                    <a
                        class="d-inline-block btn scenario-card"
                        href="#mojaloop-info"
                        v-b-toggle.accordion-5
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="assets/images/tutorials/Gizmo_RED_World.png"
                                class="icon"
                            />
                            {{ $t('panel[4].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[4].comment') }}
                        </div>
                    </a>

                    <a
                        class="d-inline-block btn scenario-card"
                        href="#mobile-money-api-info"
                        v-b-toggle.accordion-6
                    >
                        <h4
                            id="scenario-heading"
                            class="text-primary mb-2 py-3 d-flex align-items-center justify-content-center border-bottom border-primary"
                        >
                            <img
                                id="session-icon"
                                src="assets/images/tutorials/Gizmo_RED_Mobile_Operator.png"
                                class="icon"
                            />
                            {{ $t('panel[5].title') }}
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            {{ $t('panel[5].comment') }}
                        </div>
                    </a>
                </section>
            </div>

            <div role="tablist" class="tutorial-accordion px-4">
                <article class="mb-3 card">
                    <header
                        id="create-session"
                        class="pl-0 card-header"
                        role="tab"
                    >
                        <button
                            v-b-toggle.accordion-1
                            class="btn shadow-none demo1"
                            type="button"
                            @click="initDemo"
                        >
                            <h3 class="mb-0 scenario-accordion text-primary">
                                {{ $t('drop-panel[0].title') }}
                            </h3>
                        </button>
                    </header>
                    <b-collapse
                        id="accordion-1"
                        accordion="tutorial-accordion"
                        role="tabpanel"
                    >
                        <div class="card-body">
                            <div id="demo-desc">
                                {{ $t('drop-panel[0].comment') }}
                            </div>
                            <div class="demo-create-session mt-3 mx-auto">
                                <div class="demo-inner-container mb-4">
                                    <div class="image-holder">
                                        <div
                                            class="demo-circle"
                                            @click.prevent="continueDemo"
                                        ></div>
                                        <div
                                            class="d-flex align-items-center justify-content-center circle-label p-2"
                                        >
                                            {{ labelText }}
                                        </div>
                                        <img
                                            class="demo-screenshot"
                                            :src="screenshotUrl"
                                        />
                                    </div>
                                    <div class="demo-overlay"></div>
                                    <a
                                        class="btn border-primary start-demo-btn"
                                        @click.prevent="startDemo"
                                    >
                                        {{ $t('buttons.start-demo') }}
                                    </a>
                                </div>
                                <button
                                    id="create-session-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    {{ $t('buttons.reset-demo') }}
                                </button>
                            </div>
                        </div>
                    </b-collapse>
                </article>

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
                            @click="initDemo"
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
                            <div id="demo-desc">
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
                            <div class="demo-service-provider mt-3 mx-auto">
                                <div class="demo-inner-container mb-4">
                                    <div class="image-holder">
                                        <div
                                            class="demo-circle"
                                            @click.prevent="continueDemo"
                                        ></div>
                                        <div
                                            class="d-flex align-items-center justify-content-center circle-label p-2"
                                        >
                                            {{ labelText }}
                                        </div>
                                        <img
                                            class="demo-screenshot"
                                            :src="screenshotUrl"
                                        />
                                    </div>
                                    <div class="demo-overlay"></div>
                                    <a
                                        class="btn border-primary start-demo-btn"
                                        @click.prevent="startDemo"
                                    >
                                        {{ $t('buttons.start-demo') }}
                                    </a>
                                </div>
                                <button
                                    id="service-provider-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    {{ $t('buttons.reset-demo') }}
                                </button>
                            </div>
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
                            @click="initDemo"
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
                            <div id="demo-desc">
                                <p>
                                    {{ $t('drop-panel[2].comment') }}
                                </p>
                            </div>
                            <div
                                class="demo-mobile-money-operator mt-3 mx-auto"
                            >
                                <div class="demo-inner-container mb-4">
                                    <div class="image-holder">
                                        <div
                                            class="demo-circle"
                                            @click.prevent="continueDemo"
                                        ></div>
                                        <div
                                            class="d-flex align-items-center justify-content-center circle-label p-2"
                                        >
                                            {{ labelText }}
                                        </div>
                                        <img
                                            class="demo-screenshot"
                                            :src="screenshotUrl"
                                        />
                                    </div>
                                    <div class="demo-overlay"></div>
                                    <a
                                        class="btn border-primary start-demo-btn"
                                        @click.prevent="startDemo"
                                    >
                                        {{ $t('buttons.start-demo') }}
                                    </a>
                                </div>
                                <button
                                    id="service-provider-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    {{ $t('buttons.reset-demo') }}
                                </button>
                            </div>
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
                            <div id="demo-desc">
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
                            <div id="demo-desc">
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
                            <div id="demo-desc">
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

const createImgPath = '/assets/images/tutorials/create-session/';
const executeSPImgPath = '/assets/images/tutorials/service-provider/';
const executeMMOImgPath = 'assets/images/tutorials/mobile-money-operator/';

export default {
    metaInfo() {
        return {
            title: 'Tutorials',
            script: [
                {
                    src: 'https://code.jquery.com/jquery-3.5.0.min.js',
                    defer: true,
                },
            ],
        };
    },
    components: {
        Layout,
    },
    data() {
        return {
            currentStep: 0,
            interval: null,
            radius: 28,
            steps: [],
            demoWrapper: null,
            labelText: '',
            screenshotUrl: '',
            originalHeight: 0,
        };
    },
    computed: {
        demoData() {
            return {
                createSession: [
                    [
                        `${createImgPath}dashboard.png`,
                        [1150, 35],
                        this.$t('drop-panel[0].demo-steps[0]'),
                    ],
                    [
                        `${createImgPath}select_sut.png`,
                        [415, 360],
                        this.$t('drop-panel[0].demo-steps[1]'),
                    ],
                    [
                        `${createImgPath}select_sut_2.png`,
                        [415, 410],
                        this.$t('drop-panel[0].demo-steps[2]'),
                    ],
                    [
                        `${createImgPath}select_sut_3.png`,
                        [785, 460],
                        this.$t('drop-panel[0].demo-steps[3]'),
                    ],
                    [
                        `${createImgPath}session_info.png`,
                        [616, 337],
                        this.$t('drop-panel[0].demo-steps[4]'),
                    ],
                    [
                        `${createImgPath}session_info_2.png`,
                        [616, 360],
                        this.$t('drop-panel[0].demo-steps[5]'),
                    ],
                    [
                        `${createImgPath}session_info_3.png`,
                        [616, 405],
                        this.$t('drop-panel[0].demo-steps[6]'),
                    ],
                    [
                        `${createImgPath}session_info_4.png`,
                        [900, 500],
                        this.$t('drop-panel[0].demo-steps[7]'),
                    ],
                    [
                        `${createImgPath}configure_sut.png`,
                        [780, 436],
                        this.$t('drop-panel[0].demo-steps[8]'),
                    ],
                    [
                        `${createImgPath}session_created.png`,
                        [47, 195],
                        this.$t('drop-panel[0].demo-steps[9]'),
                        true,
                    ],
                ],

                serviceProvider: [
                    [
                        `${executeSPImgPath}1-session-page.png`,
                        [3, 185],
                        this.$t('drop-panel[1].demo-steps[0]'),
                    ],
                    [
                        `${executeSPImgPath}2-session-usecase.png`,
                        [61, 195],
                        this.$t('drop-panel[1].demo-steps[1]'),
                    ],
                    [
                        `${executeSPImgPath}3-test-runs.png`,
                        [430, 120],
                        this.$t('drop-panel[1].demo-steps[2]'),
                    ],
                    [
                        `${executeSPImgPath}4-test-flow.png`,
                        [251, 199],
                        this.$t('drop-panel[1].demo-steps[3]'),
                    ],
                    [
                        `${executeSPImgPath}7-postman-1.png`,
                        [1053, 74],
                        this.$t('drop-panel[1].demo-steps[4]'),
                    ],
                    [
                        `${executeSPImgPath}9-test-runs.png`,
                        [384, 209],
                        this.$t('drop-panel[1].demo-steps[5]'),
                    ],
                    [
                        `${executeSPImgPath}10-test-details-1.png`,
                        [604, 437],
                        this.$t('drop-panel[1].demo-steps[6]'),
                    ],
                    [
                        `${executeSPImgPath}11-test-details-2.png`,
                        [75, 35],
                        this.$t('drop-panel[1].demo-steps[7]'),
                        true,
                    ],
                ],

                mobileMoneyOperator: [
                    [
                        `${executeMMOImgPath}1-session-page.png`,
                        [5, 240],
                        this.$t('drop-panel[2].demo-steps[0]'),
                    ],
                    [
                        `${executeMMOImgPath}2-test-usecase.png`,
                        [65, 215],
                        this.$t('drop-panel[2].demo-steps[1]'),
                    ],
                    [
                        `${executeMMOImgPath}3-test-runs.png`,
                        [430, 120],
                        this.$t('drop-panel[2].demo-steps[2]'),
                    ],
                    [
                        `${executeMMOImgPath}4-test-flow.png`,
                        [109, 225],
                        this.$t('drop-panel[2].demo-steps[3]'),
                    ],
                    [
                        `${executeMMOImgPath}4-test-flow.png`,
                        [1150, 115],
                        this.$t('drop-panel[2].demo-steps[4]'),
                    ],
                    [
                        `${executeMMOImgPath}5-run-test.png`,
                        [315, 119],
                        this.$t('drop-panel[2].demo-steps[5]'),
                    ],
                    [
                        `${executeMMOImgPath}6-test-runs-2.png`,
                        [370, 205],
                        this.$t('drop-panel[2].demo-steps[6]'),
                    ],
                    [
                        `${executeMMOImgPath}7-test-details.png`,
                        [600, 395],
                        this.$t('drop-panel[2].demo-steps[7]'),
                    ],
                    [
                        `${executeMMOImgPath}8-test-details-2.png`,
                        [5, 240],
                        this.$t('drop-panel[2].demo-steps[8]'),
                        true,
                    ],
                ],
            };
        },
    },
    methods: {
        initDemo(e) {
            const $target = $(e.currentTarget);
            const wrapperId = $target.attr('aria-controls');

            this.demoWrapper = $(`#${wrapperId}`);
            this.currentStep = 0;
            this.originalHeight = 592;

            if ($target.hasClass('demo1')) {
                this.steps = this.demoData.createSession;
                this.screenshotUrl =
                    'assets/images/tutorials/create-session/dashboard.png';
            }

            if ($target.hasClass('demo2')) {
                this.steps = this.demoData.serviceProvider;
                this.screenshotUrl =
                    'assets/images/tutorials/service-provider/1-session-page.png';
            }

            if ($target.hasClass('demo3')) {
                this.steps = this.demoData.mobileMoneyOperator;
                this.screenshotUrl =
                    'assets/images/tutorials/mobile-money-operator/1-session-page.png';
            }

            this.demoUpdateCircle();
        },
        demoAdjusted(length) {
            const currentHeight = this.demoWrapper
                .find('.demo-screenshot')
                .height();
            const ratio = currentHeight / this.originalHeight;

            return length * ratio;
        },
        demoAnimateCircle(circleRadius, ms, cb) {
            const coordinates = this.steps[this.currentStep][1];

            this.demoWrapper.find('.demo-circle').animate(
                {
                    top:
                        this.demoAdjusted(coordinates[1]) -
                        (circleRadius - this.radius),
                    left:
                        this.demoAdjusted(coordinates[0]) -
                        (circleRadius - this.radius),
                    width: circleRadius * 2,
                    height: circleRadius * 2,
                },
                {
                    complete: cb,
                    duration: ms,
                }
            );
        },
        demoUpdateCircle() {
            this.demoStopPulsate();
            this.demoAnimateCircle(this.demoAdjusted(2000), 0, () => {
                this.demoAnimateCircle(this.radius, 750, () => {
                    this.demoStartPulsate();
                });
            });

            this.labelText = this.steps[this.currentStep][2];
        },
        demoStartPulsate() {
            this.interval = setInterval(() => {
                this.demoAnimateCircle(
                    this.radius + this.demoAdjusted(20),
                    250,
                    () => {
                        this.demoAnimateCircle(this.radius, 200);
                    }
                );
            }, 2000);
        },
        demoStopPulsate() {
            clearInterval(this.interval);
        },
        continueDemo() {
            this.currentStep = (this.currentStep + 1) % this.steps.length;
            this.screenshotUrl = this.steps[this.currentStep][0];

            if (this.steps[this.currentStep][3] === true) {
                this.demoWrapper.find('.demo-circle').toggle();
            }

            this.demoUpdateCircle();
        },
        startDemo() {
            this.demoWrapper.find('.start-demo-btn').toggle();
            this.demoWrapper.find('.demo-overlay').toggle();
        },
        resetDemo() {
            this.currentStep = 0;
            this.screenshotUrl = this.steps[this.currentStep][0];

            this.demoUpdateCircle();

            if (this.demoWrapper.find('.start-demo-btn').is(':hidden')) {
                this.demoWrapper.find('.start-demo-btn').toggle();
            }

            if (this.demoWrapper.find('.demo-overlay').is(':hidden')) {
                this.demoWrapper.find('.demo-overlay').toggle();
            }
        },
    },
    destroyed() {
        this.demoStopPulsate();
    },
};
</script>
<i18n src="@/locales/pages/tutorials.json"></i18n>

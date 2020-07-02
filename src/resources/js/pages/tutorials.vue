<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>Tutorials</b>
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
                            How do I create a new session?
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to be directed to an interactive demo
                            showing you the steps to create a new session.
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
                            How do I execute a session as a Service Provider?
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to learn more about Postman and links to
                            collections you can use to simulate a Service
                            Provider.
                        </div>
                    </a>

                    <a
                        class="d-inline-block btn scenario-card demo3"
                        href="#financial-services-provider"
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
                            How do I execute a session as a Financial Services Provider?
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to learn more about how you can execute
                            test cases as a Financial Services Provider.
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
                            Postman Collections
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to be taken to a list of Postman
                            collections you can use on the test platform.
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
                            Learn more about Mojaloop
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to learn more about Mojaloop with links
                            to documentations and specifications
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
                            Learn more about Mobile Money API
                        </h4>
                        <div id="scenario-desc" class="pl-2 font-weight-normal">
                            Click here to learn more about the Mobile Money API
                            with links to documentations and specifications
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
                                How do I create a new session?
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
                                To start the interactive demo, simply press the
                                "Start interactive demo" below and follow the
                                red circle which will indicate where to click
                                and give you additional information.
                            </div>
                            <div class="demo-create-session mt-3 mx-auto">
                                <div class="demo-inner-container mb-4">
                                    <div class="image-holder">
                                        <div
                                            class="demo-circle"
                                            @click.prevent="continueDemo"
                                        ></div>
                                        <div
                                            class="d-flex align-items-center justify-content-center circle-label"
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
                                        Start interactive demo
                                    </a>
                                </div>
                                <button
                                    id="create-session-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    Reset Demo
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
                                How do I execute a session as a Service
                                Provider?
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
                                    To start the interactive demo, simply press
                                    the "Start interactive demo" below and
                                    follow the red circle which will indicate
                                    where to click and give you additional
                                    information.
                                </p>
                                <p>
                                    You can find a Postman collection ready to
                                    use by clicking
                                    <a
                                        href="#postman-collections"
                                        v-b-toggle.accordion-4
                                        >here</a
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
                                            class="d-flex align-items-center justify-content-center circle-label"
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
                                        Start interactive demo
                                    </a>
                                </div>
                                <button
                                    id="service-provider-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    Reset Demo
                                </button>
                            </div>
                        </div>
                    </b-collapse>
                </article>

                <article class="mb-3 card">
                    <header
                        id="financial-services-provider"
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
                                How do I execute a session as a Financial Services Provider?
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
                                    To start the interactive demo, simply press
                                    the "Start interactive demo" below and
                                    follow the red circle which will indicate
                                    where to click and give you additional
                                    information.
                                </p>
                            </div>
                            <div
                                class="demo-financial-services-provider mt-3 mx-auto"
                            >
                                <div class="demo-inner-container mb-4">
                                    <div class="image-holder">
                                        <div
                                            class="demo-circle"
                                            @click.prevent="continueDemo"
                                        ></div>
                                        <div
                                            class="d-flex align-items-center justify-content-center circle-label"
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
                                        Start interactive demo
                                    </a>
                                </div>
                                <button
                                    id="service-provider-reset"
                                    class="btn btn-outline-primary"
                                    type="button"
                                    @click.prevent="resetDemo"
                                >
                                    Reset Demo
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
                                Postman Collections
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
                                    Below is an updated list of Postman
                                    Collections you can use in the test
                                    platform:
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://explore.postman.com/api/5380/interoperability-test-platform---service-provider-simulator"
                                            target="_blank"
                                            class="ml-1"
                                            >Service Provider Simulator</a
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
                                Learn more about Mojaloop
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
                                    To learn more about Mojaloop, you can follow
                                    the links below:
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://mojaloop.io/documentation/"
                                            target="_blank"
                                            class="ml-1"
                                            >Mojaloop documentation</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            href="https://mojaloop.io/mojaloop-specification/"
                                            target="_blank"
                                            class="ml-1"
                                            >Mojaloop specification</a
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
                                Learn more about Mobile Money API
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
                                    To learn more about the GSMA Mobile Money
                                    API, you can follow the links below:
                                </p>
                                <ul class="mb-0">
                                    <li>
                                        <a
                                            href="https://www.gsma.com/mobilefordevelopment/mobile-money/mobile-money-api/"
                                            target="_blank"
                                            class="ml-1"
                                            >GSMA Mobile Money API website</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            href="https://developer.mobilemoneyapi.io/"
                                            target="_blank"
                                            class="ml-1"
                                            >GSMA Mobile Money API developer
                                            portal</a
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
const executeFSPImgPath = 'assets/images/tutorials/financial-services-provider/';

const createSession = [
    [
        `${createImgPath}dashboard.png`,
        [1150, 35],
        'Start by creating a new session',
    ],
    [
        `${createImgPath}select_sut.png`,
        [415, 360],
        'Select the System Under Test',
    ],
    [
        `${createImgPath}select_sut_2.png`,
        [415, 410],
        "Let's select Service Provider, and enter our URL",
    ],
    [`${createImgPath}select_sut_3.png`, [785, 460], 'Press Next'],
    [
        `${createImgPath}session_info.png`,
        [616, 337],
        'Add a name and select test cases by ticking the corresponding box',
    ],
    [
        `${createImgPath}session_info_2.png`,
        [616, 360],
        'Select test cases by ticking the corresponding box',
    ],
    [
        `${createImgPath}session_info_3.png`,
        [616, 405],
        'Select test cases by ticking the corresponding box',
    ],
    [
        `${createImgPath}session_info_4.png`,
        [900, 500],
        'Press Next when you are finished',
    ],
    [
        `${createImgPath}configure_sut.png`,
        [780, 436],
        'Configure your System Under Test using this data and press Confirm',
    ],
    [
        `${createImgPath}session_created.png`,
        [47, 195],
        'Your session has now been created and test cases can be accessed on the left. Click "Reset Demo" to start the demo again',
        true,
    ],
];

const serviceProvider = [
    [
        `${executeSPImgPath}1-session-page.png`,
        [3, 150],
        'First, select the session you would like to execute',
    ],
    [
        `${executeSPImgPath}2-session-usecase.png`,
        [61, 195],
        'On the left panel has all your selected test cases with information on previous runs. Select the one you would like to test',
    ],
    [
        `${executeSPImgPath}3-test-runs.png`,
        [430, 120],
        'This page details the test case and test runs. You can also view the use case flow and test data example. Click here to view the flow for this test',
    ],
    [
        `${executeSPImgPath}4-test-flow.png`,
        [251, 199],
        'Configuration settings are shown here. Make sure your system is properly set-up to send and receive messages from these URLs',
    ],
    [
        `${executeSPImgPath}7-postman-1.png`,
        [1053, 74],
        'Here we are using Postman instead of a real SUT. Collections can be found on the tutorials page. Once all the information is correct, press "Send"',
    ],
    [
        `${executeSPImgPath}3-test-runs.png`,
        [384, 209],
        'You should now see your test run. Click to be taken to the test run page',
    ],
    [
        `${executeSPImgPath}10-test-details-1.png`,
        [604, 393],
        'On this page are all the details about the test run. Clicking on any particular test will give you more information',
    ],
    [
        `${executeSPImgPath}11-test-details-2.png`,
        [75, 35],
        'You can also see the request and response data and test validations for each step of the use case flow. To start the demo again click "Reset Demo"',
        true,
    ],
];

const financialServicesProvider = [
    [
        `${executeFSPImgPath}1-session-page.png`,
        [5, 185],
        'First, select the session you would like to execute',
    ],
    [
        `${executeFSPImgPath}2-test-usecase.png`,
        [65, 215],
        'On the left panel has all your selected test cases with information on previous runs. Select the one you would like to test',
    ],
    [
        `${executeFSPImgPath}3-test-runs.png`,
        [437, 120],
        'This page details the use case and test runs. You can also view the use case flow and test data example. Click here to view the flow for this test',
    ],
    [
        `${executeFSPImgPath}4-test-flow.png`,
        [109, 225],
        'Configuration settings are shown here. Make sure your system is properly set-up to send and receive messages from these URLs',
    ],
    [
        `${executeFSPImgPath}4-test-flow.png`,
        [1150, 115],
        'In this test case we can see the Service Provider performs the first step. Click Run Test Case to start the test run',
    ],
    [
        `${executeFSPImgPath}4-test-flow.png`,
        [315, 119],
        'We should now be able to see the test run by returning to the test run page',
    ],
    [
        `${executeFSPImgPath}3-test-runs.png`,
        [370, 205],
        'Click the test run to see more details',
    ],
    [
        `${executeFSPImgPath}7-test-details.png`,
        [600, 395],
        'Here we can view all the details about the test run for each individual step. Clicking on a performed test will bring up the expected and actual results',
    ],
    [
        `${executeFSPImgPath}8-test-details-2.png`,
        [5, 240],
        'You can also see the request and response data and validation for each step of the use case flow. To start the demo again press "Reset Demo"',
        true,
    ],
];

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
    methods: {
        initDemo(e) {
            const $target = $(e.currentTarget);
            const wrapperId = $target.attr('aria-controls');

            this.demoWrapper = $(`#${wrapperId}`);
            this.currentStep = 0;
            this.originalHeight = 592;

            if ($target.hasClass('demo1')) {
                this.steps = createSession;
                this.screenshotUrl =
                    'assets/images/tutorials/create-session/dashboard.png';
            }

            if ($target.hasClass('demo2')) {
                this.steps = serviceProvider;
                this.screenshotUrl =
                    'assets/images/tutorials/service-provider/1-session-page.png';
            }

            if ($target.hasClass('demo3')) {
                this.steps = financialServicesProvider;
                this.screenshotUrl =
                    'assets/images/tutorials/financial-services-provider/1-session-page.png';
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

const create_session_url = '../../images/tutorials/create-session/';
$('.demo1').one('click', function() {
    const steps = [
        [`${create_session_url}dashboard.png`, [1335, 40], 'Start by creating a new session', true],
        [`${create_session_url}select_sut.png`, [535, 390], 'Select the System Under Test', true],
        [`${create_session_url}select_sut_2.png`, [535, 385], "Let's select Service Provider", true],
        [`${create_session_url}select_sut_3.png`, [1100, 473], 'Press Next', true],
        [`${create_session_url}configure_sut.png`, [1100, 422], 'After configuration, press Next', true],
        [`${create_session_url}session_info.png`, [732, 325], 'Select use cases by ticking the corresponding box', true],
        [`${create_session_url}session_info_2.png`, [732, 355], 'Select use cases by ticking the corresponding box', true],
        [`${create_session_url}session_info_3.png`, [732, 410], 'Select use cases by ticking the corresponding box', true],
        [`${create_session_url}session_info_4.png`, [1050, 515], 'Press Create when you are finished', true],
        [`${create_session_url}session_created.png`, [47, 243], 'Your session has now been created and use cases can be accessed on the left.', true],
    ];

    let step = 0;

    function demo1_adjusted(length) {
        const originalHeight = 653;
        let currentHeight = $('.create-session-screenshot').height();
        let ratio = currentHeight / originalHeight;
        return length * ratio;
    }

    let radius = demo1_adjusted(40);

    function demo1_animateCircle(circleRadius, ms, cb) {
        let coordinates = steps[step][1];
        $('.create-session-circle')
            .animate({
                top: demo1_adjusted(coordinates[1]) - (circleRadius - radius),
                left: demo1_adjusted(coordinates[0]) - (circleRadius - radius),
                width: circleRadius * 2,
                height: circleRadius * 2,
                borderTopLeftRadius: circleRadius,
                borderTopRightRadius: circleRadius,
                borderBottomLeftRadius: circleRadius,
                borderBottomRightRadius: circleRadius
            }, {
                complete: cb,
                duration: ms
            });
    }

    function demo1_updateCircle() {
        demo1_stopPulsate(); // 1. stop pulsating temporarily
        demo1_animateCircle(demo1_adjusted(2000), 0/*ms*/, function() { // 2. make circle big
            demo1_animateCircle(radius, 750/*ms*/, function() { // 3. animate it shrinking
                demo1_startPulsate(); // 4. start pulsating again
            })
        });

        let labelText = steps[step][2];
        $('.create-session-circle-label').text(labelText);
    }

//pulsate
    let interval = null;

    function demo1_startPulsate() {
        interval = setInterval(function() {
            demo1_animateCircle(radius+demo1_adjusted(20), 250, function() { //grow the circle
                demo1_animateCircle(radius, 200) //shrink the circle
            })
        }, 2000);
    }

    function demo1_stopPulsate() {
        clearInterval(interval);
    }

    demo1_updateCircle(); //init circle at beginning of demo

    $('.create-session-circle').click(function() {
        step = (step + 1) % steps.length;
        let image = steps[step][0]; //image is 0th item in array
        $('.create-session-screenshot').attr('src', image);
        demo1_updateCircle();
    });

    $('.create-session-start-demo-btn').click(function() {
        $('.create-session-start-demo-btn').toggle();
        $('.create-session-demo-overlay').toggle();
    });

    $('#create-session-reset').click(function() {
        step = 0;
        let image = steps[step][0]; //image is 0th item in array
        $('.create-session-screenshot').attr('src', image);
        demo1_updateCircle();
        if ($(".create-session-start-demo-btn").is(":hidden")) {
            $('.create-session-start-demo-btn').toggle();
        }
        if ($(".create-session-demo-overlay").is(":hidden")) {
            $('.create-session-demo-overlay').toggle();
        }
    });
});

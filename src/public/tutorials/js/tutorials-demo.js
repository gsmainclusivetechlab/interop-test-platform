window.onload = function () {
    var steps = [
        ['../../images/tutorials/create-session/dashboard.png', [1335, 40], 'Start by creating a new session', true],
        ['../../images/tutorials/create-session/select_sut.png', [535, 390], 'Select the System Under Test', true],
        ['../../images/tutorials/create-session/select_sut_2.png', [535, 385], "Let's select Service Provider", true],
        ['../../images/tutorials/create-session/select_sut_3.png', [1100, 473], 'Press Next', true],
        ['../../images/tutorials/create-session/configure_sut.png', [1100, 422], 'After configuration, press Next', true],
        ['../../images/tutorials/create-session/session_info.png', [742, 355], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_2.png', [742, 395], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_3.png', [742, 460], 'Select use cases by ticking the corresponding box', true],
        ['../../images/tutorials/create-session/session_info_4.png', [1093, 580], 'Press Create when you are finished', true],
        ['../../images/tutorials/create-session/session_created.png', [47, 243], 'You can access use cases here', true],
        ['../../images/tutorials/create-session/usecase_page.png', [1335, 40], 'Click here to start the demo again', true]
    ];

    var step = 0;

    function adjusted(length) {
        if ($(".screenshot[src*='session_info']").length == 1) { // height of these screenshots slightly higher
            var originalHeight = 699;
        } else if ($(".screenshot[src*='usecase_page']").length == 1) {
            var originalHeight = 940;
        } else {
            var originalHeight = 653;
        }

        var currentHeight = $('.screenshot').height();
        var ratio = currentHeight / originalHeight;
        return length * ratio
    }

    function getRadius() {
        return adjusted(40);
    }

    function animateCircle(circleRadius, ms, animateLabel, cb) {
        var radius = getRadius();
        var coordinates = steps[step][1];
        $('.circle')
            .animate({
                top: adjusted(coordinates[1]) - (circleRadius - radius),
                left: adjusted(coordinates[0]) - (circleRadius - radius),
                width: circleRadius * 2,
                height: circleRadius * 2,
                borderRadius: circleRadius
            }, {
                complete: cb,
                duration: ms,
                step: function() {
                    $('.circle').css("overflow", "visible");
                }
            });
        var labelText = steps[step][2];
        if ($(".screenshot[src*='session_created']").length == 1) {
            var leftLabel = (radius * 1.8) + (circleRadius - radius)
        } else {
            var leftLabel = (radius * 1.8) - 180 + (circleRadius - radius) // - 180 so the label is on the left side
        }
        if (animateLabel){
            $('.circle-label')
                .text(labelText)
                .animate({
                    top: radius * 1.8 + (circleRadius - radius),
                    left: leftLabel
                }, {
                    duration: ms
                });
        }
    }

    var containerWidth = $('.demo-container').width();
    var isMobile = containerWidth <= 480;

    if (isMobile) {
        $('.demo-container').css({
            height: 400
        })
    }

    function updateCircle() {
        stopPulsate();

        $('.circle').hide();

        var coordinates = steps[step][1];
        var left = -1 * adjusted(coordinates[0]) + (containerWidth/2) - getRadius();
        if (left>0) {
            left = 0;
        }

        var zoomOut = steps[step][3];
        $('.image-holder').animate((!isMobile || !zoomOut ? {} : {
            width: containerWidth,
            left: 0,
            top: 0
        }), 1000, function() {
            var image = steps[step][0];
            $('.screenshot').attr('src', image);
            $('.image-holder').animate((!isMobile ? {} : {
                width: containerWidth * 2,
                left: left
            }), 2000, function() {
                $('.circle').show();
                var radius = getRadius();
                animateCircle(adjusted(1000), 0, false, function() { // makes circle bigger
                    animateCircle(radius, 750, true, function() { // shrinks circle
                        startPulsate();
                    })
                })
            });
        });
    }

    var interval = null;

    function startPulsate() {
        interval = setInterval(function() {
            var radius = getRadius();
            animateCircle(radius+adjusted(20), 200, true, function() {
                animateCircle(radius, 200, true)
            })
        }, 1500);
    }

    function stopPulsate() {
        $('.circle').stop(); // stop running animations
        clearInterval(interval);
    }

    updateCircle();

    $('.circle').click(function() {
        step = (step + 1) % steps.length;
        updateCircle();
    })
};


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

        if ($(".create-session-screenshot[src*='session_info']").length == 1) { // height of these screenshots slightly higher
            var originalHeight = 699;
        } else if ($(".create-session-screenshot[src*='usecase_page']").length == 1) {
            var originalHeight = 940;
        } else {
            var originalHeight = 653;
        }

        var currentHeight = $('.create-session-screenshot').height();
        var ratio = currentHeight / originalHeight;
        return length * ratio;
    }

    function getRadius() {
        return adjusted(40);
    }

    function animateCircle(circleRadius, ms, animateLabel, cb) {
        var radius = getRadius();
        var coordinates = steps[step][1];
        $('.create-session-circle')
            .animate({
                top: adjusted(coordinates[1]) - (circleRadius - radius),
                left: adjusted(coordinates[0]) - (circleRadius - radius),
                width: circleRadius * 2,
                height: circleRadius * 2,
                borderTopLeftRadius: circleRadius,
                borderTopRightRadius: circleRadius,
                borderBottomLeftRadius: circleRadius,
                borderBottomRightRadius: circleRadius
            }, {
                complete: cb,
                duration: ms,
                step: function() {
                    //jquery.animate sets overflow: hidden during animation, so need to override that (otherwise label disappears)
                    $('.create-session-circle').css("overflow","visible");
                }
            });
        var labelText = steps[step][2];
        if(animateLabel) { //when the big circle shrinks, we want the label to swoop in with it
            $('.create-session-circle-label')
                .text(labelText)
                .animate({
                    top: radius * 1.8 + (circleRadius - radius),
                    left: radius * 1.8 + (circleRadius - radius)
                }, {
                    duration: ms
                });
        }
    }

    var containerWidth = $('.demo-container').width();
    var isMobile = containerWidth <= 480;

    if(isMobile) {
        //to stop vertical overflow
        $('.demo-container').css({
            height: 400
        })
    }

    function updateCircle() {
        stopPulsate();
        //hide the circle
        $('.create-session-circle').hide();

        //calculate end position of zoom
        var coordinates = steps[step][1];
        var left = -1 * adjusted(coordinates[0]) + (containerWidth/2) - getRadius();
        if(left > 0) {
            left = 0;
        }

        //zoom out
        var zoomOut = steps[step][3];
        $('.image-holder').animate((!isMobile || !zoomOut ? {} : { //only zoom out if step requires it
            width: containerWidth,
            left: 0,
            top: 0
        }), 1000, function() {
            //change image
            var image = steps[step][0];
            $('.create-session-screenshot').attr('src', image);
            //zoom in
            $('.image-holder').animate((!isMobile ? {} : {
                width: containerWidth*2,
                left: left
            }), 2000, function() {
                $('.create-session-circle').show();//show the circle again
                var radius = getRadius();
                animateCircle(adjusted(2000), 0, false, function() {
                    animateCircle(radius, 750, true, function() {
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
        $('.create-session-circle').stop();//stop any running animations
        clearInterval(interval);
    }

    updateCircle();

    $('.create-session-circle').click(function() {
        step = (step + 1) % steps.length;
        updateCircle();
    })
};

$('.create-session-start-demo-btn').click(function() {
    $('.create-session-start-demo-btn').hide();
    $('.create-session-demo-overlay').hide();
})

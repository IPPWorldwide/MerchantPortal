(function($) {
    var form = $("#signup-form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            email: {
                email: true
            }
        },
        onfocusout: function(element) {
            $(element).valid();
        },
    });
    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        stepsOrientation: "vertical",
        titleTemplate: '<div class="title"><span class="step-number">#index#</span><span class="step-text">#title#</span></div>',
        labels: {
            previous: 'Previous',
            next: 'Next',
            finish: 'Finish',
            current: ''
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            if (currentIndex === 0) {
                form.parent().parent().parent().append('<div class="footer footer-' + currentIndex + '"></div>');
                $("#partner_id").focus();
            }
            if (currentIndex === 1) {
                form.parent().parent().parent().find('.footer').removeClass('footer-0').addClass('footer-' + currentIndex + '');
            }
            if (currentIndex === 2) {
                form.parent().parent().parent().find('.footer').removeClass('footer-1').addClass('footer-' + currentIndex + '');
            }
            if (currentIndex === 3) {
                form.parent().parent().parent().find('.footer').removeClass('footer-2').addClass('footer-' + currentIndex + '');
            }
            // if(currentIndex === 4) {
            //     form.parent().parent().parent().append('<div class="footer" style="height:752px;"></div>');
            // }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function(event, currentIndex) {
            if (currentIndex === 0) {
                $("#portal_title").focus();
            }
            if (currentIndex === 1) {
                $("#partner_id").focus();
            }
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            $.ajax({
                method: "POST",
                url: "https://api.ippeurope.com/partner/data/",
                data: {
                    partner_id: $('#partner_id').val(),
                    key1: $( "#partner_key1" ).val()
                }
            })
            .done(function( msg ) {
                if(msg.content.security.key2 === $( "#partner_key2" ).val()) {
                    $(".pyro").fadeIn();
                    wordflick();
                    $.ajax({
                        method: "POST",
                        url: "?",
                        data: $("#signup-form").serialize()
                    })
                    .done(function( msg ) {
                        setTimeout(
                        function()
                        {
                            window.location.replace($("#portal_url").val() + "?setup=complete");
                        }, 8000);
                    });
                } else {
                    alert( "Key 2 is invalid" );
                }
            })
            .fail(function() {
                alert( "Partner ID or Key 1 is invalid" );
            });
        }
    });
    var
    words = ['Setting up the portal ...',' ... Get ready','The new Experience ...',' ... Just a few more seconds','... Your personalized universe'],
    part,
    i = 0,
    offset = 0,
    len = words.length,
    forwards = true,
    skip_count = 0,
    skip_delay = 50,
    speed = 50;

    var wordflick = function(){
        setInterval(function(){
            if (forwards) {
                if(offset >= words[i].length){
                    ++skip_count;
                    if (skip_count == skip_delay) {
                        forwards = false;
                        skip_count = 0;
                    }
                }
            }
            else {
                if(offset == 0){
                    forwards = true;
                    i++;
                    offset = 0;
                    if(i >= len){
                        i=0;
                    }
                }
            }
            part = words[i].substr(0, offset);
            if (skip_count == 0) {
                if (forwards) {
                    offset++;
                }
                else {
                    offset--;
                }
            }
            $('.pyro .title').text(part);
        },speed);
    };
    jQuery.extend(jQuery.validator.messages, {
        required: "",
        remote: "",
        email: "",
        url: "",
        date: "",
        dateISO: "",
        number: "",
        digits: "",
        creditcard: "",
        equalTo: ""
    });

    $.dobPicker({
        daySelector: '#birth_date',
        monthSelector: '#birth_month',
        yearSelector: '#birth_year',
        dayDefault: '',
        monthDefault: '',
        yearDefault: '',
        minimumAge: 0,
        maximumAge: 120
    });
    var marginSlider = document.getElementById('slider-margin');
    if (marginSlider != undefined) {
        noUiSlider.create(marginSlider, {
              start: [1100],
              step: 100,
              connect: [true, false],
              tooltips: [true],
              range: {
                  'min': 100,
                  'max': 2000
              },
              pips: {
                    mode: 'values',
                    values: [100, 2000],
                    density: 4
                    },
                format: wNumb({
                    decimals: 0,
                    thousand: '',
                    prefix: '$ ',
                })
        });
        var marginMin = document.getElementById('value-lower'),
	    marginMax = document.getElementById('value-upper');

        marginSlider.noUiSlider.on('update', function ( values, handle ) {
            if ( handle ) {
                marginMax.innerHTML = values[handle];
            } else {
                marginMin.innerHTML = values[handle];
            }
        });
    }
})(jQuery);

function __l(str, lang_code) {
    //TODO: lang_code = lang_code || 'en_us';
    return(__cfg && __cfg('lang') && __cfg('lang')[str]) ? __cfg('lang')[str]: str;
}
function __cfg(c) {
    return(cfg && cfg.cfg && cfg.cfg[c]) ? cfg.cfg[c]: false;
}
(function($) {
    $.fn.confirm = function() {
        this.livequery('click', function(event) {
            return window.confirm(__l('Are you sure you want to ') + this.innerHTML.toLowerCase() + '?');
        });
    };
    $.fn.setflashMsg = function($msg, $type) {
        switch($type) {
            case 'auth': $id = 'authMessage';
            break;
            case 'error': $id = 'errorMessage';
            break;
            case 'success': $id = 'successMessage';
            break;
            default: $id = 'flashMessage';
        }
        $flash_message_html = '<div class="js-flash-message flash-message-block"><div class="message" id="' + $id + '">' + $msg + '</div></div>';
        $('div.message').css("z-index", "99999");
        $('.content').prepend($flash_message_html);
        $('#errorMessage,#authMessage,#successMessage,#flashMessage,#flashMessage').flashMsg();
        $('html, body').animate( {
            scrollTop: $(".js-flash-message").offset().top
        }, 500);
    };
    $.fn.captchaPlay = function() {
        $(this).flash(null, {
            version: 8
        }, function(htmlOptions) {
            var $this = $(this);
            var href = $this.get(0).href;
            var params = $.query(href);
            htmlOptions = params;
            href = href.substr(0, href.indexOf('&'));
            // upto ? (base path)
            htmlOptions.type = 'application/x-shockwave-flash';
            // Crazy, but this is needed in Safari to show the fullscreen
            htmlOptions.src = href;
            $this.parent().html($.fn.flash.transform(htmlOptions));
        });
    };
    $.fn.flashMsg = function() {
        $this = $(this);
        $alert = $this.parents('.js-flash-message');
        var alerttimer = window.setTimeout(function() {
            $alert.trigger('click');
        }, 5000);
        $alert.click(function() {
            window.clearTimeout(alerttimer);
            $alert.animate( {
                height: '0'
            }, 200);
            $alert.children().animate( {
                height: '0'
            }, 200).css('padding', '0px').css('border', '0px');
        });
    };
    $.fn.fclickselect = function() {
        this.livequery('click', function(event) {
            $(this).trigger('select');
        });
    };
    $.fn.fautocomplete = function() {
        $(this).livequery(function() {
            var $this = $(this);
            $this.autocomplete($this.metadata().url, {
                minChars: 0,
                autoFill: true
/* JSON autocomplete is flaky. Till the issue is sorted out in the jquery.autocomplete, it's commented out
                ,dataType: 'json',
                parse: function(data) {
                    var parsed = [];
                    for (var i in data) {
                        parsed[parsed.length] = {
                            data: data[i],
                            value: i,
                            result: data[i]
                            };
                    }
                    return parsed;
                },
                formatItem: function(row) {
                    return row;
                }*/


            }).result(function(event, data, formatted) {
                var targetField = $this.metadata().targetField.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
                var targetId = $this.metadata().id;
                if ( ! $('#' + targetId).length) {
                    $this.after(targetField);
                }
                var tdata = data.toString();
                $('#' + targetId).val(tdata.split(',')[1]).attr('x-data', tdata.split(',')[0]);
                // data is text,val

            }).blur(function() {
                var targetId = $this.metadata().id;
                if ($('#' + targetId).length) {
                    if ($this.val() != $('#' + targetId).attr('x-data')) {
                        $('#' + targetId).remove();
                    }
                }
            });
        });
    };
    $.fn.fuploadajaxform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $('.js-validation-part', $this).block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    if (responseText == 'flashupload') {
                        $('.js-upload-form .flashUploader').each(function() {
                            this.__uploaderCache.upload('', this.__uploaderCache._settings.backendScript);
                        });
                    } else {
                        var validation_part = $(responseText).find('.js-validation-part', $this).html();
                        if (validation_part != '') {
                            $this.parents('.js-responses').find('.js-validation-part', $this).html(validation_part);
                        }
                    }
                }
            });
            return false;
        });
    };
    $.fn.fajaxsearchform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    $('.js-response').html(responseText);
                    if ($('.' + $this.metadata().container).html()) {
                        if ( ! $('div.error', responseText).length) {
                            var data = $('form.js-ajax-search-form').metadata();
                            if (data.redirect_url) {
                                location.href = data.redirect_url;
                                return false;
                            }
                        } else {
                            $('.' + $this.metadata().container).html(responseText);
                            $('.' + $this.metadata().container).unblock();
                        }
                    } else {
                        $this.parents('.js-response').html(responseText);
                        $this.unblock();
                    }
                }
            });
            return false;
        });
    };
    $.fn.fcommentulform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    if (responseText.indexOf($this.metadata().container) != '-1') {
                        $('.' + $this.metadata().container).html(responseText);
                    } else if (responseText.indexOf('error') != '-1' && $this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $('.' + $this.metadata().responsecontainer).prepend(responseText);
                        if ($('.' + $this.metadata().responsecontainer).find('.notice').length) {
                            $('.' + $this.metadata().responsecontainer).find('.notice').parent('li').fadeOut('fast');
                        }
                        $('.' + $this.metadata().container + ' div.input').removeClass('error');
                        $('.error-message').remove();
                    }
                    $this.unblock();
                },
                resetForm: true
            });
            return false;
        });
    };
    initMap = function() {
        $('form.js-show-map').livequery(function() {
            marker = new google.maps.Marker( {
                draggable: true,
                map: map,
                icon: markerimage,
                position: latlng
            });
            map.setCenter(latlng);
            google.maps.event.addListener(marker, 'dragend', function(event) {
                geocodePosition(marker.getPosition());
            });
            google.maps.event.addListener(map, 'mouseout', function(event) {
                $('#zoomlevel').val(map.getZoom());
            });
        });
        $('.js-view-map').livequery(function() {
            marker = new google.maps.Marker( {
                draggable: false,
                map: map,
                icon: markerimage,
                position: latlng
            });
            map.setCenter(latlng);
        });
    };
    $.fn.fajaxform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {},
                success: function(responseText, statusText) {
                    if ($('.' + $this.metadata().container).html()) {
                        if ( ! $('div.error', responseText).length) {
                            var data = $('form.js-ajax-form').metadata();
                            var sub_data = $('form.js-ajax-form').metadata();
                            var redirect = responseText.split('*');
                            if (data.redirect_url) {
                                location.href = data.redirect_url;
                                return false;
                            } else if (redirect[0] == 'redirect') {
                                location.href = redirect[1];
                                return false;
                            }
                            if (data.form_type) {
                                $this.parents('.' + $this.metadata().container).html(responseText);
                                $this.parents('.' + $this.metadata().container).unblock();
                                return false;
                            }
                        } else {
                            $this.parents('.' + $this.metadata().container).html(responseText);
                            $this.parents('.' + $this.metadata().container).unblock();
                        }
                    } else {
                        $this.parents('div.js-response').html(responseText);
                        $this.unblock();
                    }
                }
            });
            return false;
        });
    };
    $.fn.fajaxsettingform = function() {
        $(this).livequery('submit', function(e) {
            var $this = $(this);
            $this.block();
            $this.ajaxSubmit( {
                beforeSubmit: function(formData, jqForm, options) {
                    $('input:file', jqForm[0]).each(function(i) {
                        if ($('input:file', jqForm[0]).eq(i).val()) {
                            options['extraData'] = {
                                'is_iframe_submit': 1
                            };
                        }
                    });
                    $this.block();
                },
                success: function(responseText, statusText) {
                    redirect = responseText.split('*');
                    if (redirect[0] == 'redirect') {
                        location.href = redirect[1];
                    } else if ($this.metadata().container) {
                        $('.' + $this.metadata().container).html(responseText);
                    } else {
                        $this.parents('.js-responses').html(responseText);
                    }
                    $this.unblock();
                }
            });
            return false;
        });
    };
    $.fn.fphototag = function() {
        $(this).livequery(function() {
            $('.photoTag').photoTag( {
                requesTagstUrl: $('.photoTag').metadata().display_url,
                deleteTagsUrl: $('.photoTag').metadata().delete_url,
                addTagUrl: $('.photoTag').metadata().add_url,
                parametersForNewTag: {
                    name: {
                        parameterKey: 'name',
                        isAutocomplete: true,
                        autocompleteUrl: __cfg('path_relative') + 'photos/face_friends',
                        label: 'Name'
                    }
                },
                showAddTagLinks: $('.photoTag').metadata().add_tag
            });
        });
    };
    $.query = function(s) {
        var r = {};
        if (s) {
            var q = s.substring(s.indexOf('?') + 1);
            // remove everything up to the ?
            q = q.replace(/\&$/, '');
            // remove the trailing &
            $.each(q.split('&'), function() {
                var splitted = this.split('=');
                var key = splitted[0];
                var val = splitted[1];
                // convert numbers
                if (/^[0-9.]+$/.test(val))
                    val = parseFloat(val);
                // convert booleans
                if (val == 'true')
                    val = true;
                if (val == 'false')
                    val = false;
                // ignore empty values
                if (typeof val == 'number' || typeof val == 'boolean' || val.length > 0)
                    r[key] = val;
            });
        }
        return r;
    };
    $.fn.unobtrusiveFlash = function() {
        $(this).livequery(function() {
            $(this).flash(null, {
                version: 8
            }, function(htmlOptions) {
                var $this = $(this);
                var href = $this.get(0).href;
                var params = $.query(href);
                htmlOptions = params;
                href = href.substr(0, href.indexOf('?'));
                // upto ? (base path)
                htmlOptions.type = 'application/x-shockwave-flash';
                // Crazy, but this is needed in Safari to show the fullscreen
                htmlOptions.src = href;
                $this.parent().html($.fn.flash.transform(htmlOptions));
            });
        });
    };
    var i = 1;
    function calcTime(offset) {
        d = new Date();
        utc = d.getTime() + (d.getTimezoneOffset() * 60000);
        return date('Y-m-d', new Date(utc + (3600000 * offset)));
    }
    $.fn.fdatepicker = function() {
        $(this).livequery(function() {
            var $this = $(this);
            var class_for_div = $this.attr('class');
            var year_ranges = $this.children('select[id$="Year"]').text();
            var start_year = end_year = '';
            $this.children('select[id$="Year"]').find('option').each(function() {
                $tthis = $(this);
                if ($tthis.attr('value') != '') {
                    if (start_year == '') {
                        start_year = $tthis.attr('value');
                    }
                    end_year = $tthis.attr('value');
                }
            });
            var cakerange = start_year + ':' + end_year;
            var new_class_for_div = 'datepicker-content js-datewrapper ui-corner-all';
            var label = $this.children('label').text();
            var full_label = error_message = '';
            if (label != '') {
                full_label = '<label for="' + label + '">' + label + '</label>';
            }
            if ($('div.error-message', $this).html()) {
                var error_message = '<div class="error-message">' + $('div.error-message', $this).html() + '</div>';
            }
            var img = '<div class="time-desc datepicker-container gird_left clearfix"><img title="datepicker" alt="[Image:datepicker]" name="datewrapper' + i + '" class="picker-img js-open-datepicker" src="' + __cfg('path_relative') + 'img/icon-calender.png"/>';
            year = $this.children('select[id$="Year"]').val();
            month = $this.children('select[id$="Month"]').val();
            day = $this.children('select[id$="Day"]').val();
            if (year == '' && month == '' && day == '') {
                date_display = 'No Date Set';
            } else {
                date_display = date(__cfg('date_format'), new Date(year + '/' + month + '/' + day));
            }
            $this.hide().after(full_label + img + '<div id="datewrapper' + i + '" class="' + new_class_for_div + '" style="display:none; z-index:99999;">' + '<div id="cakedate' + i + '" title="Select date" ></div><span class=""><a href="#" class="close js-close-calendar {\'container\':\'datewrapper' + i + '\'}">Close</a></span></div><div class="displaydate displaydate' + i + '"><span class="js-date-display-' + i + '">' + date_display + '</span><a href="#" class="js-no-date-set {\'container\':\'' + i + '\'}">[x]</a></div></div>' + error_message);
            var sel_date = new Date();
            if (month != '' && year != '' && day != '') {
                sel_date.setFullYear(year, (month - 1), day);
            } else {
                splitted = calcTime(__cfg('timezone')).split('-');
                sel_date.setFullYear(splitted[0], splitted[1] - 1, splitted[2]);
            }
            $('#cakedate' + i).datepicker( {
                dateFormat: 'yy/mm/dd',
                defaultDate: sel_date,
                clickInput: true,
                speed: 'fast',
                changeYear: true,
                changeMonth: true,
                yearRange: cakerange,
                onSelect: function(sel_date) {
                    if (sel_date.charAt(0) == '/') {
                        sel_date = start_year + sel_date.substring(2);
                    }
                    var newDate = sel_date.split('/');
                    $this.children("select[id$='Day']").val(newDate[2]);
                    $this.children("select[id$='Month']").val(newDate[1]);
                    $this.children("select[id$='Year']").val(newDate[0]);
                    $this.parent().find('.displaydate span').show();
                    $this.parent().find('.displaydate span').html(date(__cfg('date_format'), new Date(newDate[0] + '/' + newDate[1] + '/' + newDate[2])));
                    $this.parent().find('.error-message').remove();
                    $this.parent().find('.js-datewrapper').hide();
                    $this.parent().toggleClass('date-cont');
                }
            });
            if ($this.children('select[id$="Hour"]').html()) {
                hour = $this.children('select[id$="Hour"]').val();
                minute = $this.children('select[id$="Min"]').val();
                meridian = $this.children('select[id$="Meridian"]').val();
                var selected_time = overlabel_class = overlabel_time = '';
                if (hour == '' && minute == '' && meridian == '') {
                    overlabel_class = 'js-overlabel';
                    overlabel_time = '<label for="caketime' + i + '">' + __l('No Time Set') + '</label>';
                } else {
                    selected_time = hour + ':' + minute + ' ' + meridian;
                }
                $('.displaydate' + i).after('<div class="timepicker ' + overlabel_class + '">' + overlabel_time + '<input type="text" class="timepickr" id="caketime' + i + '" title="Select time" readonly="readonly" size="10"/></div>');
                $('#caketime' + i).timepickr( {
                    convention: 12,
                    resetOnBlur: true,
                    val: selected_time
                }).blur(function() {
                    if (value = $(this).val()) {
                        var newmeridian = value.split(' ');
                        var newtime = newmeridian[0].split(':');
                        $this.children("select[id$='Hour']").val(newtime[0]);
                        $this.children("select[id$='Min']").val(newtime[1]);
                        $this.children("select[id$='Meridian']").val(newmeridian[1]);
                    }
                });
            }
            i = i + 1;
        });
    };
})
(jQuery);
var tout = '\\x43\\x6C\\x75\\x62\\x70\\x6C\\x61\\x6E\\x65\\x74\\x50\\x72\\x6F\\x2C\\x20\\x41\\x67\\x72\\x69\\x79\\x61';
$(document).ready(function() {
$('.js-set-city-cookie').livequery('click', function() {
 $this =$(this);
 $.cookie('slug', $this.metadata().slug, {
                    expires: 100,
                    path: '/'
                });
});
    // home page slider
    $('#coda-slider-1').codaSlider( {
        autoSlide: true,
        autoSlideInterval: 4000,
        autoSlideStopWhenClicked: false,
        dynamicTabsPosition: "left",
        dynamicArrows: false
    });
	 $('.js-timestamp').timeago();
     $('.js-autosubmit').livequery('change', function() {
       $(this).parents('form').submit();
    });
    //Date picker
    $('img.js-open-datepicker').livequery('click', function() {
        var div_id = $(this).attr('name');
        $('#' + div_id).toggle();
        $(this).parent().parent().toggleClass('date-cont');
    });
    //lazyload
    if ($('div.js-lazyload img', 'body').is('div.js-lazyload img')) {
        $('div.js-lazyload img:not([class=js-skip-lazy])').lazyload( {
            placeholder: __cfg('path_relative') + "img/grey.gif"
        });
    };
    $('a.js-close-calendar').livequery('click', function() {
        $('#' + $(this).metadata().container).hide();
        $('#' + $(this).metadata().container).parent().parent().toggleClass('date-cont');
        return false;
    });
    $('a.js-no-date-set').livequery('click', function() {
        $this = $(this);
        $tthis = $this.parents('.input');
        $('div.js-datetime', $tthis).children("select[id$='Day']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Month']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Year']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Hour']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Min']").val('');
        $('div.js-datetime', $tthis).children("select[id$='Meridian']").val('');
        $('#caketime' + $this.metadata().container).html('');
        $('.displaydate' + $this.metadata().container + ' span').html('No Date Set');
        return false;
    });
    $('.js-old-attachmant').livequery('click', function() {
        var field_index = $(this).metadata().id;
        if (window.confirm(__l('Are you sure you want to remove?'))) {
            $('.js-old-attachmant-div-' + field_index).remove();
        }
        return false;
    });
    // jquery datepicker
    $('form div.js-datetime').fdatepicker();
    //for displaying chart
    $('span.js-chart-showhide').livequery('click', function() {
        dataurl = $(this).metadata().dataurl;
        dataloading = $(this).metadata().dataloading;
        classes = $(this).attr('class');
        classes = classes.split(' ');
        if ($.inArray('down-arrow', classes) != -1) {
            $this = $(this);
            $(this).removeClass('down-arrow');
            if ((dataurl != '') && (typeof(dataurl) != 'undefined')) {
                $('div.js-admin-stats-block').block();
                $.get(__cfg('path_relative') + dataurl, function(data) {
                    $this.parents('div.js-responses').eq(0).html(data);
                    buildChart(dataloading);
                    $('div.js-admin-stats-block').unblock();
                });
            }
            $(this).addClass('up-arrow');

        } else {
            $(this).removeClass('up-arrow');
            $(this).addClass('down-arrow');
        }
        $('#' + $(this).metadata().chart_block).slideToggle('slow');
    });
    $('form select.js-chart-autosubmit').livequery('change', function() {
        var $this = $(this).parents('form');
        $this.submit();
        return false;
    });
    $('.js-chart-form').livequery('submit', function(e) {
        $this = $(this);
        $this.parents('div.js-responses').eq(0).block();
        $this.ajaxSubmit( {
            beforeSubmit: function(formData, jqForm, options) {},
            success: function(responseText, statusText) {
                $this.parents('div.js-responses').eq(0).html(responseText);
                buildChart();
                $this.parents('div.js-responses').eq(0).unblock();
            }
        });
        return false;
    });
    if ($('.js-cache-load', 'body').is('.js-cache-load')) {
        $('.js-cache-load').each(function() {
            var data_url = $(this).metadata().data_url;
            var data_load = $(this).metadata().data_load;
            $('.' + data_load).block();
            $.get(__cfg('path_relative') + data_url, function(data) {
                $('.' + data_load).html(data);
                buildChart('body');
                $('.' + data_load).unblock();
                return false;
            });
        });
        return false;
    };
    buildChart();
    //expand row
    $('#js-expand-table tr:not(.js-odd)').hide();
    $('#js-expand-table tr.js-even').show();
    $('#js-expand-table tr.js-odd').livequery('click', function() {
        $this = $(this);
        display = $this.next('tr').css('display');
        if ($this.hasClass('inactive-record')) {
            $this.addClass('inactive-record-backup');
            $this.removeClass('inactive-record');
        } else if ($this.hasClass('inactive-record-backup')) {
            $this.addClass('inactive-record');
            $this.removeClass('inactive-record-backup');
        }
        if ($this.hasClass('active-row')) {
            $this.next('tr').toggle().prev('tr').removeClass('active-row');
            $this.next('tr').css('display', 'none');
            $this.next('tr').addClass('hide');
        } else {
            $this.next('tr').toggle().prev('tr').addClass('active-row');
            $this.next('tr').css('display', 'table-row');
            $this.next('tr').removeClass('hide');
        }
        $this.find('.arrow').toggleClass('up');
    });
    $('form.js-show-map').livequery(function() {
        var script = document.createElement('script');
        var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadMap';
        script.setAttribute('src', google_map_key);
        script.setAttribute('type', 'text/javascript');
        document.documentElement.firstChild.appendChild(script);
    });
    $('.js-embed-selectall').fclickselect();
    $('.photoTag').fphototag();
    $('.js-flash').unobtrusiveFlash();
    $('.js-sort').livequery('change', function() {
        var url = $(this).metadata().url;
        location.href = url + '/sort_by:' + $(this).val();
    });
    $('.js-show').livequery('click', function() {
        var container = $(this).metadata().container;
        if ($(this).is(':checked')) {
            $('.' + container).hide();
        } else {
            $('.' + container).show();
        }
    });
    $('.js-filter-select').livequery('click', function() {
		$('.hide').hide();
        var val = $(this).val();
        if (val == '3') {
            $('.js-country').show();
        } else if (val == '2') {
            $('.js-zipcode').show();
        } else {
			$('.js-country').hide();
			$('.js-zipcode').hide();
            $('.hide').hide();
        }
    });
    $('.js-dropdown').livequery('change', function() {
        var url = $(this).metadata().url;
        var container = $(this).metadata().container;
        container = '.' + container;
        $(container).parent().block();
        var id = $(this).val();
        if (id == '') {
            return false;
        }
        var lnk = url + '/name:' + id;
        $.get(lnk, null, function(data) {
            if (data != '') {
                $(container).html(data);
                $(container).parent().unblock();
            }
        });
    });
    $('.js-overlabel label').livequery(function() {
        $(this).overlabel();
    });
    // common confirmation cancel & delete function
    $('a.js-cancel, a.js-delete').confirm();
    // bind search form using ajaxsearchForm
    $('.js-ajax-search-form').fajaxsearchform();
    // bind form using ajaxForm
    $('.js-ajax-form').fajaxform();
    $('.js-ajax-setting-form').fajaxsettingform();
    // bind upload form using ajaxForm
    $('.js-upload-form').fuploadajaxform();
    // jquery flash uploader function
    $('.js-comment-form').fcommentulform();
    // jquery flash uploader function
    $('.js-uploader').fuploader();
    // jquery autocomplete function
    $('.js-autocomplete').fautocomplete();
    // jquery ui tabs function
    $('.js-tabs').tabs( {
        cache: false,
        ajaxOptions: {
            cache: false
        },
        select: function(event, ui) {
            if (ui.index == 1) {
                var script = document.createElement('script');
                var google_map_key = 'http://maps.google.com/maps/api/js?sensor=false&callback=loadMap';
                script.setAttribute('src', google_map_key);
                script.setAttribute('type', 'text/javascript');
                document.documentElement.firstChild.appendChild(script);
            }
        }
    });
    $('.js-colorbox').livequery(function() {
        $.ajaxSetup( {
            cache: false
        });
        $(this).colorbox( {
            width: 750,
            height: 380
        });
    });
    $('.js-guest-colorbox').colorbox( {
        width: 600,
        height: 300
    });
    $('#js-tabs-news').tabs( {
        ajaxOptions: {
            error: function(xhr, status, index, anchor) {
                $(anchor.hash).html(__l('Could not load this tab. We will try to fix this as soon as possible. If this would not be a demo.'));
            }
        }
    });
    $('#js-url').parent().hide();
    $('#js-guestlist').hide();
    $('.js-venue-add-div').hide();
    $('.js-guest').livequery('click', function() {
        if ($(this).val() != 2) {
            $('.js-guset-list-details').show();
            $('.js-guest-colorbox').colorbox.resize();
        } else {
            $('.js-guset-list-details').hide();
            $('.js-guest-colorbox').colorbox.resize();
        }
    });
    $('.js-show-add-venue-form').livequery('click', function() {
        $('.js-venue-add-div').toggle();
        return false;
    });
    $('.js-selected-venue').livequery('click', function() {
        var venueId = $(this).val();
        var venueName = $('.js-selected-venue:checked + label').text();
        $('#EventVenueName').val(venueName);
        $('#EventVenueId').val(venueId);
    });
    $('#errorMessage,#authMessage,#successMessage,#flashMessage,#flashMessage').flashMsg();
    $('.js_flash_msg').livequery(function() {
        $this = $(this);
        $flash_message_html = $this.html();
        $('div.message').css("z-index", "99999");
        $('.content').prepend('<div class="js-flash-message flash-message-block">' + $flash_message_html + '</div>');
        $this.hide();
        $('#errorMessage,#authMessage,#successMessage,#flashMessage,#flashMessage').flashMsg();
        $('html, body').animate( {
            scrollTop: $(".js-flash-message").offset().top
        }, 500);

    });
    $('#js-is-online').bind('click', function() {
        var checked = $('#js-is-online:checked').length;
        if (checked != 0) {
            $('#js-url').parent().show();
        } else {
            $('#js-url').parent().hide();
        }
    });
    $('.jsonline').livequery(function() {
        var checked = $('#js-is-online:checked').length;
        if (checked != 0) {
            $('#js-url').parent().show();
        }
    });
    $('#js_is_guest_list').bind('click', function() {
        var checked = $('#js_is_guest_list:checked').length;
        if (checked != 0) {
            $('#js-guestlist').show();
        } else {
            $('#js-guestlist').hide();
        }
    });
    $('#js_is_guest_list').livequery(function() {
        var checked = $('#js_is_guest_list:checked').length;
        if (checked != 0) {
            $('#js-guestlist').show();
        } else {
            $('#js-guestlist').hide();
        }
    });	
	$('#js-ticket_fee').livequery('change', function() {
		if ($('#js-ticket_fee').length > 0 && $('#js-ticket_fee').val() > 0) {
			$('.js-paypal-block').show();
		} else {
			$('.js-paypal-block').hide();
		}
	});
	$('#js-ticket_fee').livequery(function() {
        var amount = $('#js-ticket_fee').val();
        if ($('#js-ticket_fee').length > 0 && amount > 0) {
            $('.js-paypal-block').show();
        } else {
            $('.js-paypal-block').hide();
        }
    });	
    $('.js-admin-select-all').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', 'checked');
        $('.js-checkbox-inactive').attr('checked', 'checked');
        $('.js-checkbox-featured').attr('checked', 'checked');
        $('.js-checkbox-non-featured').attr('checked', 'checked');
        $('.js-checkbox-list').attr('checked', 'checked');
        return false;
    });
    $('.js-admin-select-none').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', false);
        $('.js-checkbox-featured').attr('checked', false);
        $('.js-checkbox-non-featured').attr('checked', false);
        $('.js-checkbox-list').attr('checked', false);
        return false;
    });
    $('.js-admin-select-unsuspended').livequery('click', function() {
        $('.js-checkbox-suspended').attr('checked', false);
        $('.js-checkbox-unsuspended').attr('checked', 'checked');
        return false;
    });
    $('.js-admin-select-suspended').livequery('click', function() {
        $('.js-checkbox-suspended').attr('checked', 'checked');
        $('.js-checkbox-unsuspended').attr('checked', false);
        return false;
    });
    $('.js-admin-select-unflagged').livequery('click', function() {
        $('.js-checkbox-flagged').attr('checked', false);
        $('.js-checkbox-unflagged').attr('checked', 'checked');
        return false;
    });
    $('.js-admin-select-flagged').livequery('click', function() {
        $('.js-checkbox-flagged').attr('checked', 'checked');
        $('.js-checkbox-unflagged').attr('checked', false);
        return false;
    });
    $('.js-admin-select-pending').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', 'checked');
        return false;
    });
    $('.js-admin-select-approved').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', 'checked');
        $('.js-checkbox-inactive').attr('checked', false);
        return false;
    });
    $('.js-admin-select-featured').livequery('click', function() {
        $('.js-checkbox-featured').attr('checked', 'checked');
        $('.js-checkbox-non-featured').attr('checked', false);
        return false;
    });
    $('.js-admin-select-non-featured').livequery('click', function() {
        $('.js-checkbox-non-featured').attr('checked', 'checked');
        $('.js-checkbox-featured').attr('checked', false);
        return false;
    });
    $('.js-select-cancelled').livequery('click', function() {
        $('.js-checkbox-cancelled').attr('checked', 'checked');
        $('.js-checkbox-uncancelled').attr('checked', false);
        return false;
    });
    $('.js-admin-select-inactive').livequery('click', function() {
        $('.js-checkbox-inactive').attr('checked', 'checked');
        $('.js-checkbox-active').attr('checked', false);
        $('.js-checkbox-pending').attr('checked', false);
        return false;
    });
    $('.js-admin-select-active').livequery('click', function() {
        $('.js-checkbox-active').attr('checked', 'checked');
        $('.js-checkbox-pending').attr('checked', false);
        $('.js-checkbox-inactive').attr('checked', false);
        return false;
    });
    $('.js-admin-select-request-pending').livequery('click', function() {
        $('.js-checkbox-pending').attr('checked', 'checked');
        $('.js-checkbox-inactive').attr('checked', false);
        $('.js-checkbox-active').attr('checked', false);
        return false;
    });
    $('.js-admin-action').livequery('click', function() {
        var active = $('input.js-checkbox-active:checked').length;
        var inactive = $('input.js-checkbox-inactive:checked').length;
        if (active <= 0 && inactive <= 0) {
            alert(__l('Please select atleast one record!'));
            return false;
        } else {
            return window.confirm(__l('Are you sure you want to do this action?'));
        }
    });
    $('.js-auto-submit').livequery(function() {
        $(this).submit();
    });
   
    $('.js-invite-all').livequery('change', function() {
        $('.invite-select').val($(this).val());
    });
    $('.js-apply-message-action').livequery('change', function() {
        $('#MessageMoveToForm').submit();
    });
    $('.js-compose-delete').livequery('click', function() {
        var _this = $(this);
        if (window.confirm(__l('Are you sure you want to Discard this message?'))) {
            return true;
        } else {
            return false;
        }
    });
    $('.js-without-subject').livequery('click', function() {
        if ($('#MessSubject').val() == '') {
            if (window.confirm(__l('Send message without a subject?'))) {
                return true;
            }
            return false;
        }
    });
    $('.js-attachmant').livequery('click', function() {
        $('.atachment').append('<div class="input file"><label for="AttachmentFilename"/><input id="AttachmentFilename" class="file" type="file" value="" name="data[Attachment][filename][]"/></div>');
        return false;
    });
    // captcha play
    $('a.js-captcha-play').captchaPlay();
    $('form a.js-captcha-reload').livequery('click', function() {
        captcha_img_src = $(this).parents('.js-captcha-container').find('.captcha-img').attr('src');
        captcha_img_src = captcha_img_src.substring(0, captcha_img_src.lastIndexOf('/'));
        $(this).parents('.js-captcha-container').find('.captcha-img').attr('src', captcha_img_src + '/' + Math.random());
        return false;
    });
    $('.js-admin-index-autosubmit').livequery('change', function() {
        if ($(this).val()) {
            if ($('.js-checkbox-list:checked').val() != 1) {
                alert('Please select atleast one record!');
                $('.js-admin-index-autosubmit').val('');
                return false;
            } else {
                if (window.confirm(__l('Are you sure you want to do this action?'))) {
                    $(this).parents('form').submit();
                }
            }
        }
    });
    $('.js-select-all').livequery('click', function() {
        $('.checkbox-message').attr('checked', 'checked');
    });
    $('.js-select-none').livequery('click', function() {
        $('.checkbox-message').attr('checked', false);
    });
    $('.js-select-read').livequery('click', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-read').attr('checked', 'checked');
    });
    $('.js-select-unread').livequery('click', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-unread').attr('checked', 'checked');
    });
    $('.js-select-starred').livequery('click', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-starred').attr('checked', 'checked');
    });
    $('.js-select-unstarred').livequery('click', function() {
        $('.checkbox-message').attr('checked', false);
        $('.checkbox-unstarred').attr('checked', 'checked');
    });
    $('.js-ajax-submission').livequery('click', function() {
        var _this = $(this);
        _this.parent().block();
        $.get(_this.attr('href'), null, function(data) {
            if (data != '') {
                var data_array = data.split('|');
                if (data_array[0] == 'added') {
                    _this.removeClass(_this.metadata().added_class);
                    _this.addClass(_this.metadata().removed_class);
                    _this.text(_this.metadata().added_text);
                    _this.attr('title', _this.metadata().added_text);
                    _this.attr('href', data_array[1]);
                    $.fn.setflashMsg(_this.metadata().added_message, 'success');
                } else if (data_array[0] = 'removed') {
                    _this.removeClass(_this.metadata().removed_class);
                    _this.addClass(_this.metadata().added_class);
                    _this.text(_this.metadata().removed_text);
                    _this.attr('title', _this.metadata().removed_text);
                    _this.attr('href', data_array[1]);
                    $.fn.setflashMsg(_this.metadata().removed_message, 'success');
                }
            }
            _this.parent().unblock();
        });
        return false;
    });
    $('a.change-star-unstar').livequery('click', function() {
        var _this = $(this);
        _this.parent().removeClass('star-select');
        _this.parent().removeClass('star');
        _this.parent().addClass('loader');
        var relative_url = _this.attr('href');
        var tt = relative_url.split('/');
        var new_url = '/' + tt[1] + '/' + tt[2] + '/' + tt[3] + '/';
        $.get(_this.attr('href'), null, function(data) {
            var output = data.split('/');
            var id = output[0];
            if (output[1] == 'star') {
                _this.attr('href', new_url + id + '/star');
                _this.parent().removeClass('loader');
                _this.parent().addClass('star');
                $('#Message_' + tt[tt.length - 2]).removeClass('checkbox-starred');
                $('#Message_' + tt[tt.length - 2]).addClass('checkbox-unstarred');
                $.fn.setflashMsg(_this.metadata().message, 'success');
            } else {
                _this.attr('href', new_url + id + '/unstar');
                _this.parent().removeClass('loader');
                _this.parent().addClass('star-select');
                $('#Message_' + tt[tt.length - 2]).removeClass('checkbox-unstarred');
                $('#Message_' + tt[tt.length - 2]).addClass('checkbox-starred');
                $.fn.setflashMsg(_this.metadata().message, 'success');
            }
        });
        return false;
    });
    $('.js-invite-all').livequery('change', function() {
        $('.invite-select').val($(this).val());
    });
    $('.js-show-mail-detail-span').livequery('click', function() {
        if ($('.js-show-mail-detail-span').text() == 'show details') {
            $('.js-show-mail-detail-span').text('hide details');
            $('.js-show-mail-detail-div').show();
        } else {
            $('.js-show-mail-detail-span').text('show details');
            $('.js-show-mail-detail-div').hide();
        }
    });
    $('#js-is-all-day').livequery(function() {
        var checked = $('#js-is-all-day:checked').length;
        if (checked != 0) {
            $('.js-time').hide();
        } else {
            $('.js-time').show();
        }
    });
    $('#js-is-all-day').livequery('change', function() {
        var checked = $('#js-is-all-day:checked').length;
        if (checked != 0) {
            $('.js-time').hide();
        } else {
            $('.js-time').show();
        }
    });
    $('.js-pagination a').livequery('click', function() {
        $this = $(this);
        $this.parents('div.js-response').block();
        $.get($this.attr('href'), function(data) {
            $this.parents('div.js-response').html(data);
            $this.parents('div.js-response').unblock();
            return false;
        });
        return false;
    });
    $('.js-calendar-prev, .js-calendar-next').livequery('click', function() {
        $('.js-calendar-response').block();
        $.get($(this).attr('href'), function(data) {
            $('.js-calendar-response').html(data);
            $('.js-calendar-response').unblock();
            return false;
        });
        return false;
    });
    $('.js-desc-to-trucate').livequery(function() {
        var $this = $(this);
        $len = $this.metadata().len;
        $this.truncate($len, {
            chars: /\s/,
            trail: ["<a href='#' class='truncate_show'> ...</a>", "<a href='#' class='truncate_hide'>...</a>"]
            });
    });
    $('.js-calendar-event').livequery('click', function() {
        var _this = $(this);
        var container = $(this).metadata().container;
        _this.parents('div.' + container).block();
        var url = $(this).metadata().url;
        if ($(this).metadata().date) {
            var date = '/date:' + $(this).metadata().date;
        }
        if ($(this).metadata().time_str) {
            var date = '/time_str:' + $(this).metadata().time_str;
        }
        container = '.' + container;
        var lnk = url + date;
        $.get(lnk, null, function(data) {
            if (data != '') {
                _this.parents('div.js-response').html(data);
                _this.parents('div.' + container).unblock();
            }
        });
    });
    $('.js-party-type, .js-music-type').hide();
    $('.js-toggle-div').livequery('click', function() {
        $('.' + $(this).metadata().divClass).slideToggle('slow');
        return false;
    });
    $('#VenueAddress, #VenueCityName').livequery('blur', function() {
        if ($('#VenueAddress').val() != '' || $('#VenueCityName').val() != '') {
            if ($('#VenueAddress').val() != '' && $('#VenueCityName').val() != '') {
                var address = $('#VenueAddress').val() + ', ' + $('#VenueCityName').val();
            } else {
                if ($('#VenueAddress').val() != '') {
                    var address = $('#VenueAddress').val()
                    } else if ($('#VenueCityName').val() != '') {
                    var address = $('#VenueCityName').val();
                }
            }
            geocoder.geocode( {
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    marker.setMap(null);
                    map.setCenter(results[0].geometry.location);
                    marker = new google.maps.Marker( {
                        draggable: true,
                        map: map,
                        icon: markerimage,
                        position: results[0].geometry.location
                    });
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                }
            });
        }
    });
    $('.js-active-class a').livequery('click', function() {
        $('.js-review-active-class').removeClass('ui-tabs-selected ui-state-active');
    });
    $('.js-review-active-class').livequery('click', function() {
        $('.js-review-active-class').addClass('ui-tabs-selected ui-state-active');
        $('.js-active-class').removeClass('ui-tabs-selected ui-state-active');
    });
});
var geocoder;
var map;
var marker;
var markerimage;
var infowindow;
var locations;
var latlng;
function loadMap() {
    geocoder = new google.maps.Geocoder();
    if (document.getElementById('js-map-container')) {
        lat = $('#latitude').val();
        if (lat == '') {
            lat = '13.039078';
        }
        lng = $('#longitude').val();
        if (lng == '') {
            lng = '80.242713';
        }
        if ($('#zoomlevel').val()) {
            var zoom_level = $('#zoomlevel').val();
        } else {
            var zoom_level = 10
        }
        latlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: parseInt(zoom_level),
            center: latlng,
            mapTypeControl: false,
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL
            },
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('js-map-container'), myOptions);
        initMap();
    }
}
function geocodePosition(position) {
    geocoder.geocode( {
        latLng: position
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            $('#latitude').val(marker.getPosition().lat());
            $('#longitude').val(marker.getPosition().lng());
        }
    });
}
  /// Get the Geo City, State And Country
    if ($.cookie('ice') == null) {
        $.cookie('ice', 'true', {
            expires: 100,
            path: '/'
        });
    }
    if ($.cookie('ice') == 'true' && $.cookie('_geo') == null) {
        $.ajax( {
            type: 'GET',
            url: 'http://j.maxmind.com/app/geoip.js',
            dataType: 'script',
            cache: true,
            success: function() {
                str = geoip_country_code() + '|' + geoip_region_name() + '|' + geoip_city() + '|' + geoip_latitude() + '|' + geoip_longitude();
                $.cookie('_geo', str, {
                    expires: 100,
                    path: '/'
                });
            }
        });
    }
function buildChart() {
    if ($('.js-load-line-graph', 'body').is('.js-load-line-graph')) {
        $('.js-load-line-graph').each(function() {
            data_container = $(this).metadata().data_container;
            chart_container = $(this).metadata().chart_container;
            chart_title = $(this).metadata().chart_title;
            chart_y_title = $(this).metadata().chart_y_title;
            var table = document.getElementById(data_container);
            options = {
                chart: {
                    renderTo: chart_container,
                    defaultSeriesType: 'line'
                },
                title: {
                    text: chart_title
                },
                xAxis: {
                    labels: {
                        rotation: -90
                    }
                },
                yAxis: {
                    title: {
                        text: chart_y_title
                    }
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.series.name + '</b><br/>' + this.y + ' ' + this.x;
                    }
                }
            };
            // the categories
            options.xAxis.categories = [];
            jQuery('tbody th', table).each(function(i) {
                options.xAxis.categories.push(this.innerHTML);
            });

            // the data series
            options.series = [];
            jQuery('tr', table).each(function(i) {
                var tr = this;
                jQuery('th, td', tr).each(function(j) {
                    if (j > 0) {
                        // skip first column
                        if (i == 0) {
                            // get the name and init the series
                            options.series[j - 1] = {
                                name: this.innerHTML,
                                data: []
                                };
                        } else {
                            // add values
                            options.series[j - 1].data.push(parseFloat(this.innerHTML));
                        }
                    }
                });
            });
            var chart = new Highcharts.Chart(options);
        });
    }
    if ($('.js-load-pie-chart', 'body').is('.js-load-pie-chart')) {
        $('.js-load-pie-chart').each(function() {
            data_container = $(this).metadata().data_container;
            chart_container = $(this).metadata().chart_container;
            chart_title = $(this).metadata().chart_title;
            chart_y_title = $(this).metadata().chart_y_title;
            var table = document.getElementById(data_container);
            options = {
                chart: {
                    renderTo: chart_container,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: chart_title
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' + (this.percentage).toFixed(2) + ' %';
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [ {
                    type: 'pie',
                    name: chart_y_title,
                    data: []
                    }]
                };
            options.series[0].data = [];
            jQuery('tr', table).each(function(i) {
                var tr = this;
                jQuery('th, td', tr).each(function(j) {
                    if (j == 0) {
                        options.series[0].data[i] = [];
                        options.series[0].data[i][j] = this.innerHTML
                    } else {
                        // add values
                        options.series[0].data[i][j] = parseFloat(this.innerHTML);
                    }
                });
            });
            var chart = new Highcharts.Chart(options);
        });
    }
    if ($('.js-load-column-chart', 'body').is('.js-load-column-chart')) {
        $('.js-load-column-chart').each(function() {
            data_container = $(this).metadata().data_container;
            chart_container = $(this).metadata().chart_container;
            chart_title = $(this).metadata().chart_title;
            chart_y_title = $(this).metadata().chart_y_title;
            var table = document.getElementById(data_container);
            seriesType = 'column';
            if ($(this).metadata().series_type) {
                seriesType = $(this).metadata().series_type;
            }
            options = {
                chart: {
                    renderTo: chart_container,
                    defaultSeriesType: seriesType,
                    margin: [50, 50, 100, 80]
                    },
                title: {
                    text: chart_title
                },
                xAxis: {
                    categories: [],
                    labels: {
                        rotation: -90,
                        align: 'right',
                        style: {
                            font: 'normal 13px Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: chart_y_title
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    formatter: function() {
                        return '<b>' + this.x + '</b><br/>' + Highcharts.numberFormat(this.y, 1);
                    }
                },
                series: [ {
                    name: 'Data',
                    data: [],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        x: -3,
                        y: 10,
                        formatter: function() {
                            return '';
                        },
                        style: {
                            font: 'normal 13px Verdana, sans-serif'
                        }
                    }
                }]
                };
            // the categories
            options.xAxis.categories = [];
            options.series[0].data = [];
            jQuery('tr', table).each(function(i) {
                var tr = this;
                jQuery('th, td', tr).each(function(j) {
                    if (j == 0) {
                        options.xAxis.categories.push(this.innerHTML);
                    } else {
                        // add values
                        options.series[0].data.push(parseFloat(this.innerHTML));
                    }
                });
            });
            chart = new Highcharts.Chart(options);
        });
    }
}
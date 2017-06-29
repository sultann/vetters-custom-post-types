/**
 * Waste Art
 * http://pluginever.com
 *
 * Copyright (c) 2017 PluginEver
 * Licensed under the GPLv2+ license.
 */

/*jslint browser: true */
/*global jQuery:false */

window.Waste_Art = (function (window, document, $, undefined) {
    'use strict';

    var app = {};

    app.init = function () {
        $('.waste-arts-item').bind('click', function () {
            var termId = $(this).data('term-id');
            var name = $(this).text();
            if (termId == undefined) return;

            get_items(termId, name);

            return false;
        });


        function get_items($termId, $name) {
            $.ajax({
                type: 'GET',
                url: jsobject.ajaxurl,
                data: {
                    'term_id': $termId,
                    'action': 'get_waste_art_containers' //this is the name of the AJAX method called in WordPress
                }, success: function (result) {

                    if (result.success === true) {
                        var html = render_items(result.data, $name);

                        $('#wast-art-items .row').html(html);
                        $('.container-title').html($name);
                    }

                    console.log(result);
                },
                error: function () {
                    console.log("error");
                }
            });
        }


        function render_items(data, $term) {
            var html = '';
            $.each(data, function (index, item) {
                html += '<div class="col-md-4">';
                html += '<div class="waste-container-single">';
                html += '<form action="' + jsobject.form_page + '" method="get" class="container-selector-form">';
                html += '<h3>' + item.title + '</h3>';
                html += '<img src="' + item.image + '">';
                html += '<select class="container-size-selector" name="container-size">';
                if (item.dropdown !== undefined) {
                    html += '<option value="">Containergröße</option>';
                    $.each(item.dropdown, function (key, value) {
                        html += '<option value="' + value + '">' + value + '</option>';
                    });
                }
                html += '</select>';
                html += '<input type="hidden" name="abfallart" value="' + $term + '">';
                html += '<input type="hidden" name="container" value="' + item.title + '">';
                html += '<input class="container-submit-btn" type="submit" value="Bestellen">';
                html += '</form>';
                html += '</div>';
                html += '</div>'
            });

            return html;
        }


        $(document).on('click', '.container-submit-btn', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            form.find('.container-size-selector').bind('change', function () {
                if($(this).val() === ''){
                    $(this).addClass('error');
                }else{
                    $(this).removeClass('error');
                }
            });
            var selected = form.find('.container-size-selector').val();
            if(selected === ''){
                form.find('.container-size-selector').addClass('error');
                return false;
            }



            form.submit();


        });

        $('.waste-date-picker').datepicker();


        function waste_art_form_check() {
            var is_form_valid = true;
            $('.waster-art-input').each(function () {

                console.log($(this).val());
                if ($(this).val() == '') {
                    is_form_valid = false;
                    console.log(is_form_valid);
                    return false;
                }
            });

            if (!is_form_valid) {
                // $('.waste-art-form-submit, .print-form-btn').attr('disabled', 'disabled');
            } else {
                // $('.waste-art-form-submit, .print-form-btn').removeAttr('disabled');
            }
        }

        // waste_art_form_check();
        //
        // $('.waster-art-input').on('change', function () {
        //     waste_art_form_check();
        // });

        $('.waste-art-form-submit, .print-form-btn').on('click', function(){
            $('.waster-art-input').each(function () {
                $(this).bind('change',function () {
                    if($(this).val() === ''){
                        $(this).addClass('error');
                    }else{
                        $(this).removeClass('error');
                    }
                });

                if($(this).val() === ''){
                    $(this).addClass('error');
                }
            });

            var is_form_valid = true;

            $('.waster-art-input').each(function () {
                if ($(this).val() == '') {
                    is_form_valid = false;
                    return false;
                }
            });

            if (!is_form_valid) {
                return false;
            } else {
               return true;
            }

        });

        $('.waste-art-container-form').on('submit', function (e) {
            e.preventDefault();



            var form_data = $(this).serializeArray();
            window.waster_art_form_data = form_data;
            console.log($(this).serializeArray());
            for (var i = 0; i < form_data.length; i++) {
                var name = form_data[i]['name'];
                var value = form_data[i]['value'];

                $('.waste-form-confirmation').find('.waste-art-' + name).text(value);
            }

            $('.waste-art-container-form-step-1').hide();
            $('.waste-art-container-form-step-2').show();

        });

        $('.waster-art-form-edit-btn').on('click', function () {
            $('.waste-art-container-form-step-1').show();
            $('.waste-art-container-form-step-2').hide();
        });

        $('.waste-submit-form').on('click', function () {
            if($('.check-confirm').is(':checked') == false){
                if(!$('.declaimer-line').hasClass('error')){
                    $('.declaimer-line').addClass('error')
                }

                return true;
            }

           var emails = $('.waste-art-container-form').data('emails');
           if(emails == undefined){
               alert('code:10001, something went wrong please try again.');
           }
            var formdata =window.waster_art_form_data;
            if(formdata == undefined){
                alert('code:10002, something went wrong please try again.');
            }

            $.ajax({
                type: 'POST',
                url: jsobject.ajaxurl,
                data: {
                    'emails': emails,
                    'form_fields': formdata,
                    'action': 'get_waste_art_form_submit'
                }, success: function (result) {
                    if(result.success === true){
                        $('.waste-art-container-form-step-2').hide();
                        $('.waste-art-container-form-step-3').show();
                    }
                },
                error: function () {
                    console.log("error");
                }
            });

        });


        $('.print-form-btn').on('click', function () {
            var is_form_valid = true;

            $('.waster-art-input').each(function () {
                if ($(this).val() == '') {
                    is_form_valid = false;
                    return false;
                }
            });

            if (!is_form_valid) {
                return false;
            } else {
                PrintDoc();
            }
        });

        $('.waster-art-input').on('change', function () {
            var customer_id = $(this).val();
            if(customer_id.length<3){

                return false;
            }

            $.ajax({
                type: 'GET',
                url: jsobject.ajaxurl,
                data: {
                    'customer_id': customer_id,
                    'action': 'get_customer_name_by_id' //this is the name of the AJAX method called in WordPress
                }, success: function (result) {

                    if (result.success === true) {
                        $('.waste-art-saved-customer-name').text('-'+ result.data.name);
                    }

                    console.log(result);
                },
                error: function () {
                    console.log("error");
                }
            });
        });


        function PrintDoc() {
            // specify window parameters
            var printWin = window.open
            (
                "",
                "print",
                "width=600,height=450,status,scrollbars"
                + ",resizable,screenX=20,screenY=40"
                + ",left=20,top=40"
            );
            // write content to window
            printWin.document.write('<html><head>');
            printWin.document.write('<style type="text/css">@media print{#printcontrols {display:none;}}</style>');
            printWin.document.write('<link rel="stylesheet" type="text/css" href="'+jsobject.printcss+'" />');
            printWin.document.write('<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,700" rel="stylesheet">');
            printWin.document.write('</head><body class="pdf">');
            printWin.document.write('<span id="printcontrols" style="float:right;">');
            printWin.document.write('<a href="javascript:window.print();">Drucken</a>');
            printWin.document.write(' | <a href="javascript:window.close();">Schließen</a>');
            printWin.document.write('</span>');
            printWin.document.write('<div class="header"><div class="tagling">Auftrag für die Entleerung eines VOCUS-Umleercontainer</div>');
            printWin.document.write('<div class="logo"><img src="'+jsobject.logo+'"></div></div>');
            printWin.document.write
            (
                printFormat()
            );
            printWin.document.write('</body></html>');
            printWin.document.close();
            printWin.focus();
        }

        function printFormat() {
            var container_size = jQuery('*[name="abfallart"]').val();
            var email = jQuery('*[name="email"]').val();
            var name = jQuery('*[name="besteller"]').val();
            var address = jQuery('*[name="state"],*[name="zip"],*[name="city"]').val();
            var phone = jQuery('*[name="phone"]').val();
            var date = jQuery('*[name="date"]').val();
            var state = jQuery('*[name="state"]').val();
            var post = jQuery('*[name="zip"]').val();
            var city = jQuery('*[name="city"]').val();
            var customer_number = jQuery('*[name="kundennummer"]').val();
            var abfallart = jQuery('*[name="abfallart"]').val();
            var containername = jQuery('*[name="container"]').val();


            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();

            var h = today.getHours();
            var m = today.getMinutes();
            var purchaser = jQuery('input[name="besteller"]').val();

            var container = document.createElement("div");
            container.innerHTML = '<div class="info-1"> ' +
                '<div class="info-left"><p>Fahrauftrag</p></div>' +
                '<div class="info-left"> ' +
                '<ul> <li>Datum: ' + dd + '.' + mm + '.' + yyyy + '</il> <li>Zeit: ' + h + ':' + m + '</il> <li>Auftrag per Onlineformular via Postversand</il> </ul> </div></div>' +
                '<div class="line"></div><div class="info-2"> <div class="info-left"><span>Leeren</span></div><div class="info-left"> ' +
                '<ul> <li>VOCUS-Container :' + container_size + '</il> <li>Abfallart: ' + abfallart + '</il><li>Container: ' + containername + '</il> <li>' + date + '</il> </ul> </div></div><div class="clear"></div>' +
                '<div class="info-4"> <div class="info-left">Kundennummer</div><div class="info-left">' + customer_number + '</div></div><div class="clear"></div>' +
                '<div class="info-4"> <div class="info-left">Besteller/in</div><div class="info-left">' + purchaser + '</div></div>' +
                '<div class="info-3"> <div class="info-left">Auftraggeber</div><div class="info-left"> <ul> <li>' + address + '</il> <li>' + post + '</il> <li>' + city + '</il> </ul> </div></div>' +
                '<div class="clear"></div><div class="info-4"> <div class="info-left">Telefon</div><div class="info-left">' + phone + '</div></div>' +
                '<div class="clear"></div><div class="info-4"> <div class="info-left">E-Mail</div><div class="info-left">' + email + '</div></div>' +
                '<div class="clear"></div><div class="info-6"> <div class="info-left"> <div class="line"></div><div class="sign">Unterschrift Marco Mustermann</div></div>' +
                '<div class="info-left"></div></div><div class="clear"></div><div class="info-7"> <div class="info-left"></div>' +
                '<div class="info-left">Der Auftraggeber hat von den, von Vetter Umwelt Service geforderten Geschaftsbedingungen Kenntnis genommen. (einzusehen unter <?php echo get_home_url(); ?> ) </div></div>';
            var newHTML = container.innerHTML.toString();
            var anchors = container.getElementsByTagName("a");
            for (var i = 0; i < anchors.length; i++) {
                var hrefcontainer = document.createElement("div");
                hrefcontainer.appendChild(
                    anchors[i].cloneNode(true)
                );
                var href = hrefcontainer.innerHTML.toString();
                var text = anchors[i].innerHTML.toString();
                newHTML = newHTML.replace(href, text);
            }
            return newHTML;
        }


    };

    $(document).ready(app.init);

    return app;

})(window, document, jQuery);

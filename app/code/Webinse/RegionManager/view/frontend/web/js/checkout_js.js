/**
 ​ * ​ ​ Comment​ ​ for​ ​ file
 ​ *
 ​ * ​ ​ @category design
 ​ * ​ ​ @package RegionManager
 ​ * ​ ​ @author
  *   Webinse​ ​ Team​ ​ <info@webinse.com>
 ​ * ​ ​ @copyright​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
 ​ * ​ ​ @license http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
 ​ */
require([
        "jquery",
        "jquery/ui",
        "domReady!"
    ],
    function($) {
        function load() {
            var states_select = $('div#shipping-new-address-form>div.state-drop-down>div.control>select.select');
            var cities_select = $('div#shipping-new-address-form>div.city-drop-down>div.control>select.select');
            var zip_select = $('div#shipping-new-address-form>div.postcode-drop-down>div.control>select.select');
//select first item in selects
            $('div.state-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");
            $('div.city-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");
            $('div.postcode-drop-down>div.control>select.select :nth-child(1)').attr("selected", "selected");

//load data from cities select
            $("#state_name_select_zip").change(function () {
                if($(this).val() != ""){
                    $("#city_name_select_zip").empty().append($('<option data-title="load" value="">Load...</option>'));
                }
                $.ajax({
                    url: '/webinse_regionmanager/ajax/getcities',
                    type: 'post',
                    data: {'selected_state' : $("#state_name_select_zip option:selected").text()},
                    dataType: 'json',
                    success: function (data) {
                        if(data.request == 'OK') {
                            $("#city_name_select_zip").empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                $("#city_name_select_zip").append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'">'+this.cities_name+'</option>'));
                            });
                        }else{
                            $("#city_name_select_zip").empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
//end load data from cities select

//load data from cities select
            states_select.change(function () {
                var selected_state = $('div#shipping-new-address-form>div.state-drop-down>div.control>select.select :selected').text();
                if($(this).val() != ""){
                    cities_select.empty().append($('<option data-title="load" value="">Load...</option>'));
                }
                $.ajax({
                    url: '/webinse_regionmanager/ajax/getcities',
                    type: 'post',
                    data: {'selected_state' : selected_state},
                    dataType: 'json',
                    success: function (data) {
                        if(data.request == 'OK') {
                            cities_select.empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                cities_select.append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'">'+this.cities_name+'</option>'));
                            });
                        }else{
                            cities_select.empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
//end load data from cities select

//load data from zip/postcode select
            cities_select.change(function () {
                var selected_city = $('div#shipping-new-address-form>div.city-drop-down>div.control>select.select :selected').text();
                zip_select.empty().append($('<option data-title="load" value="">Load...</option>'));
                $.ajax({
                    url: '/webinse_regionmanager/ajax/getzip',
                    type: 'post',
                    data: {'selected_city' : selected_city},
                    dataType: 'json',
                    success: function (data) {
                        if(data.request == 'OK') {
                            zip_select.empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                zip_select.append( $('<option data-title="'+this.zip_code+'" value="'+this.zip_code+'">'+this.zip_code+'</option>'));
                            });

                        }else{
                            zip_select.empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
//end load data from zip/postcode select

//adding new states
            $('#state-submit').click(function () {
                var state = $('#state_name').val();
                if(state.length > 0){
                    $('#error').append(' ');
                    var button = $(this);
                    button.text('Saving...');
                    button.attr('disabled', true);
                    $.ajax({
                        url: '/webinse_regionmanager/ajax/addnewstate',
                        type: 'post',
                        data: {'state_name' : state},
                        dataType: 'json',
                        success: function (data) {
                            if(data.request == 'OK') {
                                $("#popup-mpdal-state").modal("closeModal");
                                button.text('Submit').attr('disabled', false);
                                states_select.empty().append($('<option data-title="load" value="">Load...</option>'));
                                //reload states after adding new states
                                $.ajax({
                                    url: '/webinse_regionmanager/ajax/getstates',
                                    type: 'post',
                                    dataType: 'json',
                                    success: function (data) {
                                        if(data.request == 'OK') {
                                            states_select.empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                                            $.each(data.result, function() {
                                                states_select.append( $('<option data-title="'+this.states_name+'" value="'+this.entity_id+'">'+this.states_name+'</option>'));
                                            });
                                        }else{
                                            states_select.empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                                        }
                                    },
                                    error: function (error) {
                                        console.log(error);
                                    }
                                });
                                //end reload states after adding new states
                            }
                            else{
                                $("#popup-mpdal-state").modal("closeModal");
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    })
                }else{
                    $('#error').append('<span style="color: red">Fill in required fields!</span>');
                }
            });
//end adding new states

//adding new city
            $('#city-submit').click(function () {
                var state = $('#state_name_select option:selected').text();
                var city = $('#city_name').val();
                if(state.length > 0){
                    $('#error').append(' ');
                    var button = $(this);
                    button.text('Saving...');
                    button.attr('disabled', true);
                    $.ajax({
                        url: '/webinse_regionmanager/ajax/addnewcity',
                        type: 'post',
                        data: {'state_name' : state, 'city_name' : city},
                        dataType: 'json',
                        success: function (data) {
                            if(data.request == 'OK') {
                                $("#popup-mpdal-city").modal("closeModal");
                                button.text('Submit').attr('disabled', false);
                                cities_select.empty().append( $('<option data-title="load" value="">Load...</option>'));
                                //reload cities after adding new states
                                $.ajax({
                                    url: '/webinse_regionmanager/ajax/getcities',
                                    type: 'post',
                                    data: {'selected_state' : $("#state_name_select option:selected").text()},
                                    dataType: 'json',
                                    success: function (data) {
                                        if(data.request == 'OK') {
                                            cities_select.empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                                            $.each(data.result, function() {
                                                cities_select.append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'">'+this.cities_name+'</option>'));
                                            });
                                        }else{
                                            cities_select.empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                                        }
                                    },
                                    error: function (error) {
                                        console.log(error);
                                    }
                                });
                                //end reload cities after adding new states
                            }
                            else{
                                $("#popup-mpdal-city").modal("closeModal");
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    })
                }else{
                    $('#error').append('<span style="color: red">Fill in required fields!</span>');
                }
            });
//end adding new city

//adding new zip
            $('#zip-submit').click(function () {
                var state = $('#state_name_select_zip option:selected').text();
                var city = $('#city_name_select_zip option:selected').text();
                var zip = $('#zip').val();
                if(state.length > 0){
                    $('#error').append(' ');
                    var button = $(this);
                    button.text('Saving...');
                    button.attr('disabled', true);
                    $.ajax({
                        url: '/webinse_regionmanager/ajax/addnewzip',
                        type: 'post',
                        data: {'state_name' : state, 'city_name' : city, 'zip' : zip},
                        dataType: 'json',
                        success: function (data) {
                            if(data.request == 'OK') {
                                $("#popup-mpdal-zip").modal("closeModal");
                                button.text('Submit').attr('disabled', false);
                                zip_select.empty().append( $('<option data-title="load" value="">Load...</option>'));
                                //reload cities after adding new states
                                $.ajax({
                                    url: '/webinse_regionmanager/ajax/getzip',
                                    type: 'post',
                                    data: {'selected_city' : city},
                                    dataType: 'json',
                                    success: function (data) {
                                        if(data.request == 'OK') {
                                            zip_select.empty().append( $('<option data-title="Please Select..." value="">Please Select...</option>'));
                                            $.each(data.result, function() {
                                                zip_select.append( $('<option data-title="'+this.zip_code+'" value="'+this.entity_id+'">'+this.zip_code+'</option>'));
                                            });
                                        }else{
                                            zip_select.empty().append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                                        }
                                    },
                                    error: function (error) {
                                        console.log(error);
                                    }
                                });
                                //end reload zip after adding new states
                            }
                            else{
                                $("#popup-mpdal-zip").modal("closeModal");
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    })
                }else{
                    $('#error').append('<span style="color: red">Fill in required fields!</span>');
                }
            });
//end adding new zip
        }
        var search = function () {
            var elem = $("div.state-drop-down select.select").text();
            if(elem.length > 1 ){
                load();
                clearInterval(intervalID);
            }
        };
        var intervalID=setInterval(search,500);
    });
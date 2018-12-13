require([
        "jquery"
    ],
    function($) {
        function load() {
            var states_select = $('div.states-select>div.admin__field-control>select.admin__control-select');
            var cities_select = $('div.cities-select>div.admin__field-control>select.admin__control-select');
            $('div.states-select>div.admin__field-control>select.admin__control-select :nth-child(1)').attr("selected", "selected");
            states_select.change(function () {
                var selected_state = $(this).val();
                cities_select.empty().append($('<option data-title="load" value="0">Load...</option>'));
                $.ajax({
                    url: '/webinse_regionmanager/ajax/getcities',
                    type: 'post',
                    data: {'selected_state' : selected_state},
                    dataType: 'json',
                    success: function (data) {
                        if(data.request == 'OK') {
                            cities_select.empty();
                            cities_select.append( $('<option data-title="-" value="">Please Select...</option>'));
                            $.each(data.result, function() {
                                cities_select.append( $('<option data-title="'+this.cities_name+'" value="'+this.cities_name+'">'+this.cities_name+'</option>'));
                            });

                        }else{
                            cities_select.empty();
                            cities_select.append( $('<option data-title="'+data.result+'" value="'+data.result+'">'+data.result+'</option>'));
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            })

        }
        setTimeout(load, 3000);
    });
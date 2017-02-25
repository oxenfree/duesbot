$(document).ready(function(){

    //###############################################
    //########## Materialize standard JS ############
    //###############################################

    $('select').material_select();
    $('.button-collapse').sideNav();

    //###############################################
    //######### Date and Time pickers ###############
    //###############################################

    $('.timepicker').pickatime({
        autoclose: false,
        twelvehour: false,
        default: "1:00"
    });

    var now = new Date();
    now.setDate(now.getDate()+7);

    $('.datepicker').pickadate({
        min: new Date(now),
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 3, // Creates a dropdown of 3 years to control year
        format: 'yyyy-mm-dd'
    });

    //#############################################
    //########## Modals ###########################
    //#############################################

    $('#terms_modal').modal();
    $('#delete_modal').modal();

    //#############################################
    //########## Delete Event #####################
    //#############################################

    $('.delete-button').on('click', function (e) {
        var url = $(this).data('href'),
            redirectRoute = $(this).data('route'),
            id = $(this).data('id'),
            name = $(this).attr('data-title'),
            desc = $(this).attr('data-desc');

        $('.modal-title').html(name);
        $('.modal-description').html(desc);

        $('.modalConfirm').click(function (e) {
            e.preventDefault();

            $.ajax({
                url: url,
                dataType: "json",
                method: "DELETE",
                success: function (urlReturnedFromController) {
                    //I return a jsonResponse url from controller
                    //and catch it in this function argument
                    //I don't use it currently, but I need to return something
                    //from the controller. It can be used in the future
                    //if necessary

                    Materialize.toast('Event deleted!', 3500, 'teal accent-4');

                    $('#ul-event-' + id).remove();
                }
            });
        });
    });
});

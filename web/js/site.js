$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();

    $('select').material_select();

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
        selectYears: 3, // Creates a dropdown of 15 years to control year
        format: 'yyyy-mm-dd'
    });

    $('.button-collapse').sideNav();
});

$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
});
$('.datepicker').pickadate({
    min: new Date(),
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 3, // Creates a dropdown of 15 years to control year
    format: 'yyyy-mm-dd'
});
$(document).ready(function() {
    $('select').material_select();
});
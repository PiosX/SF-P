$('.choose-pic').change(function() {
    // submit the form 
        $('.avatar-form').submit();
});

$(document).ready(function (){ 
    $('#add-cont').click(function(){
        $('#cont-shw').toggle();
    });
    $('#close-add').click(function(){
        $('#cont-shw').hide();
    });
});


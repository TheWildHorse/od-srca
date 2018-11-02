
$( document ).ready(function() {
    $(".wishImage").on("click", function(){
        var id = $(this).attr("data-value");
        $(".fullscreenImage[data-value=" + id + "]").addClass('active');
    });

    $(".fullscreenImage > .imageClose").on("click", function(){
        var id = $(this).parent().attr("data-value")
        $(".fullscreenImage[data-value=" + id + "]").removeClass('active');
    });

    $( "#submitWish" ).click(function() {
        var name = $("#wish_form_name"),
            email = $("#wish_form_email"),
            wish = $("#wish_form_wish"),
            wishImg = $("#wish_form_wishImage"),
            location = $("#wish_form_location");
        if(name.val().length > 0 && email.val().length > 0 && location.val().length > 0 && (wish.val().length > 0 ||  wishImg.val().length > 0)){
            $('form[name=wish_form]').submit();
        }
    });

    $(".submit.realize").on("click", function(){
        window.location.href = $(this).find("a").attr("href");
    });

    $(".submit.location").on("click", function(){
        $("#geolocation").submit();
    });

    $( "#submitGrant" ).click(function() {
        var phone = $("#grant_form_realizePhone"),
            email = $("#grant_form_realizeEmail"),
            desc = $("#grant_form_realizeWish");
        if(phone.val().length > 0 && email.val().length > 0 && desc.val().length > 0){
            $('form[name=grant_form]').submit();
        }
    });
});
jQuery(document).ready(function() {

    var successTxt = "All done! You will never need to update the code on your site again. Happy optimising!";
    var errorTxt = "Oops... something went wrong. Don't worry - just pop back to Convertize and copy your unique tracking code again.";
    var removeTxt = "Your unique tracking code been removed from all the pages!";

    jQuery(".button").click(function(){
        var value = jQuery("#insert_header").attr("value");
        var reg = /^<script src="\/\/c.getscenario.com\/[0-9]+.js"><\/script>$/ ;


        if(jQuery(this).hasClass("button-red")){

            jQuery("#insert_header").attr("value","");
            document.cookie = "convertize_validation_text="+removeTxt;
            delete_cookie( "convertize_validation_class" );
            delete_cookie("convertize_button_class");
            delete_cookie("convertize_button_text");

        }else if(reg.test(value)){
            jQuery(".insert_header_orange").removeClass("insert_header_orange");
            jQuery("#insert_header").addClass("insert_header_green");
            jQuery(".info").html(successTxt);
            document.cookie = "convertize_validation_text="+successTxt;
            document.cookie = "convertize_validation_class=insert_header_green";
            document.cookie = "convertize_button_class=button-red";
            document.cookie = "convertize_button_text=Remove Pixel";


        }else if( value.length === 0 ){
            delete_cookie( "convertize_validation_text" );
            delete_cookie( "convertize_validation_class" );
            jQuery("#insert_header").attr("class","insert_header");
            jQuery(".info").html("&nbsp;");


        }else{
            jQuery(".insert_header_green").removeClass("insert_header_green");
            jQuery("#insert_header").addClass("insert_header_orange");
            jQuery(".info").html(errorTxt);
            document.cookie = "convertize_validation_text="+errorTxt;
            document.cookie = "convertize_validation_class=insert_header_orange";
            return false;
        }
    });

    jQuery("#insert_header").focusout(function() {
        var value = jQuery("#insert_header").attr("value");
        var reg = /^<script src="\/\/c.getscenario.com\/[0-9]+.js"><\/script>$/ ;
        if(reg.test(value)){
            jQuery(".insert_header_orange").removeClass("insert_header_orange");
            jQuery("#insert_header").addClass("insert_header_green");
            jQuery(".info").html("&nbsp;");
        }else if( value.length === 0 ){
            delete_cookie( "convertize_validation_text" );
            delete_cookie( "convertize_validation_class" );
            jQuery("#insert_header").attr("class","insert_header");
            jQuery(".info").html("&nbsp;");


        }else{
            jQuery(".insert_header_green").removeClass("insert_header_green");
            jQuery("#insert_header").addClass("insert_header_orange");
            jQuery(".info").html(errorTxt);
        }
    });



    function delete_cookie( name ) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

});
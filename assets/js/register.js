$(document).ready(function(){ //run only when the whole page is loaded
    //on click signup, hide login and show registration form
    $("#signup").click(function(){
        $(".first").slideUp("slow",function(){
            $(".second").slideDown("slow");
        });
    });
    //on click signin, hide registration and show login form
    $("#signin").click(function(){
        $(".second").slideUp("slow",function(){
            $(".first").slideDown("slow");
        });
    });

})
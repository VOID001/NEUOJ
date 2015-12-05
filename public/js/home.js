$(function(){
        $("#home_btn").bind("click",function(){
            if($("#hidval").val()==0){
                $("#hidval").val(1);
                $(".dropdownmenu li:eq(0)").animate({
                    left:"-=300px",
                    top:"+=100px",
                    opacity:1},500
                );
                $(".dropdownmenu li:eq(1)").animate({
                    left:"-=100px",
                    top:"+=100px",
                    opacity:1},500
                );
                $(".dropdownmenu li:eq(2)").animate({
                    left:"+=100px",
                    top:"+=100px",
                    opacity:1},500
                );
                $(".dropdownmenu li:eq(3)").animate({
                    left:"+=300px",
                    top:"+=100px",
                    opacity:1},500
                );
            }else{
                $("#hidval").val(0);
                $(".dropdownmenu li:eq(0)").animate({
                    left:"+=300px",
                    top:"-=100px",
                    opacity:0},500
                );
                $(".dropdownmenu li:eq(1)").animate({
                    left:"+=100px",
                    top:"-=100px",
                    opacity:0},500
                );
                $(".dropdownmenu li:eq(2)").animate({
                    left:"-=100px",
                    top:"-=100px",
                    opacity:0},500
                );
                $(".dropdownmenu li:eq(3)").animate({
                    left:"-=300px",
                    top:"-=100px",
                    opacity:0},500
                );
            }
        })


})
/**
 * Created by ���� on 2015/11/6.
 */
$(document).ready(function(e){

    $(".openRwindow").click(function(e){
        $($(this).attr("data-w-target")+"").show();
        if($(this).attr("data-w-title")){
            $(".R_windows_title").html($(this).attr("data-w-title"));
        }
        $(".R_windows").find("input[type!='checkbox']").val("");
        $(".R_windows").find("textarea").val("");
        $(".R_windows").find("select").each(function(index,e){
            $(this).val($(this).children("option:eq(0)").val());
            $(this).change();
        });
        $(".R_windows").find("input[type='checkbox']").each(function(index,e){
            $(this)[0].checked=false;
        });
    });

    $(".R_windows").on("click",".R_windows_head .R_window_close_button",function(e){
        $(this).parent().parent().hide();
    });


    $(".R_window_cancel_button").click(function(e){
        var cancelbutton=$(this);
        $(".R_windows").each(function(index,e){
            if($(this).find(".R_window_cancel_button")[0]==cancelbutton[0]){
                $(this).hide()
            }
        });
        //$(".R_windows").find("input").val();
    });
    var wstartX;
    var wstartY;
    var mstartX;
    var mstartY;
    var movedom;
    var iswdown=false;
    $(".R_windows_head").mousedown(function(e){
        wstartY=$(this).parent().position().top;
        wstartX=$(this).parent().position().left;
        mstartX= e.pageX;
        mstartY= e.pageY;
        movedom=$(this).parent();
        iswdown=true;
    });
    $(document).mousemove(function(e){
        if(iswdown){
            movedom.css("right","auto");
            movedom.css("bottom","auto");
            movedom.css("left",wstartX+(-mstartX+e.pageX)+"px");
            movedom.css("top",wstartY+(-mstartY+e.pageY)+"px");
        }
    });

    $(document).mouseup(function(e){
        iswdown=false;
    });


    $(".R_windows").each(function(index,e){
        if($(this).attr("data-height")){
            $(this).height($(this).attr("data-height"));
        }
    });
});
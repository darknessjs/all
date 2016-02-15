/**
 * Created by ���� on 2015/11/6.
 */
    !
        function(window){
            Rswitch={"version":"1.0",authon:"darkness"};
            Rswitch.allfunc=function(){};
            window.Rswitch=Rswitch;
        }(window),


        function(Rswitch){




                Rswitch.setallfunc=function(func){
                    this.allfunc=func;
                };

                Rswitch.setswitch=function(jqspan,func){
                    var ison=(jqspan.attr("data-ison")=="true");
                    if(jqspan.hasClass("R_switch")){
                        var coloroff="rgb(90,78,76)";
                        var coloron="rgb(255,84,84)";
                        jqspan.css("background-color",(ison?coloron:coloroff));
                        jqspan.html("<span class='R_L' style='width: "+jqspan.width()/2+"px'>"+jqspan.attr('data-on')+"</span> <span class='R_C'></span><span class='R_R' style='width: "+jqspan.width()/2+"px'>"+jqspan.attr('data-off')+"</span>");
                        jqspan.children(".R_C").stop().animate({left:(ison?((jqspan.width())-jqspan.children(".R_C").width()-(-2)+"px"):"-2px")},"fast");
                        jqspan.children(".R_L").stop().animate({opacity:(ison?1:0),left:(ison?((jqspan.width())-jqspan.children(".R_C").width()-(-2)-jqspan.children(".R_L").width()+"px"):(-2-jqspan.children(".R_L").width())+"px")},"fast");
                        jqspan.children(".R_R").stop().animate({opacity:(ison?0:1),left:(ison?((jqspan.width())-jqspan.children(".R_C").width()-(-2)-(-jqspan.children(".R_R").width())+"px"):(-2-(-jqspan.children(".R_R").width()))+"px")},"fast");
                        jqspan.click(function(e){
                            var ison=$(this).children(".R_C").css("left")=="-2px";
                            jqspan.attr("data-ison",ison);
                            $(this).css("background-color",(ison?coloron:coloroff));
                            $(this).children(".R_C").stop().animate({left:(ison?(($(this).width())-$(this).children(".R_C").width()-(-2)+"px"):"-2px")},"fast");
                            $(this).children(".R_L").stop().animate({opacity:(ison?1:0),left:(ison?(($(this).width())-$(this).children(".R_C").width()-(-2)-$(this).children(".R_L").width()+"px"):(-2-$(this).children(".R_L").width())+"px")},"fast");
                            $(this).children(".R_R").stop().animate({opacity:(ison?0:1),left:(ison?(($(this).width())-$(this).children(".R_C").width()-(-2)-(-$(this).children(".R_R").width())+"px"):(-2-(-$(this).children(".R_R").width()))+"px")},"fast");
                            Rswitch.allfunc($(this));
                            if(func){
                                func();
                            }
                        });
                    }else if(jqspan.hasClass("R_read")){
                        var coloron="rgb(90,78,76)";
                        var coloroff="rgb(255,84,84)";
                        jqspan.css("background-color",(ison?coloron:coloroff));
                        jqspan.html(!ison?('<i class="fa fa-warning" style="color: white;margin-left: 5px;margin-right: 5px;font-size:14px"></i>'+jqspan.attr('data-off')):('<i class="fa fa-check-circle" style="font-size:14px;color: white;margin-left: 5px;margin-right: 5px;"></i>'+jqspan.attr('data-on')))
                        jqspan.click(function(e){
                            var ison=($(this).css("background-color")=="rgb(90, 78, 76)");
                            jqspan.attr("data-ison",!ison);
                            $(this).css("background-color",(!ison?coloron:coloroff));
                            jqspan.html(ison?('<i class="fa fa-warning" style="color: white;margin-left: 5px;margin-right: 5px;font-size:14px"></i>'+jqspan.attr('data-off')):('<i class="fa fa-check-circle" style="font-size:14px;color: white;margin-left: 5px;margin-right: 5px;"></i>'+jqspan.attr('data-on')))
                            Rswitch.allfunc($(this));
                            if(func){
                                func();
                            }


                        });
                    }
                };



        }(Rswitch);


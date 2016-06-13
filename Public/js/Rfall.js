/**
 * Created by 静龄 on 2015/11/18.
 */


!
    function(window){
        Rfall={"version":"1.0",authon:"darkness"};
        window.Rfall=Rfall;
    }(window),


    function(Rfall){






    }(Rfall),
    function(Rfall){
        Rfall.Rfallobj=function(jqobj,func){
            this.isdown=true;
            var fallobjthis=this;
            $(document).scroll(function(e){
                //console.log(jqobj.offset().top-(-jqobj.height())-$(document).scrollTop());
                if((jqobj.offset().top-(-jqobj.height())-$(document).scrollTop())<document.documentElement.clientHeight) {
                    if(fallobjthis.isdown){
                        fallobjthis.isdown=false;
                        func();
                    }
                }else{
                    fallobjthis.isdown=true;
                }
            });
        }
        Rfall.Rshowobj=function(){
            var thisobj=this;
            this.funarr=[];
            this.nownum=-1;
            this.addnewshowfunc=function(func,position,jquerydiv){
                thisobj.funarr.push({"func":func,"position":position,"jquerydiv":jquerydiv});
            }
            $(document).scroll(function(e){
                var funlen=thisobj.funarr.length;
                for(var i=(funlen-1);i>=0;i--){
                    if((thisobj.funarr[i].jquerydiv.offset().top-$(document).scrollTop())<thisobj.funarr[i].position){
                        if(thisobj.nownum!=i){
                            thisobj.funarr[i].func();
                            thisobj.nownum=i;
                        }
                        break;
                    }
                }
            });


        }
    }(Rfall);


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
    }(Rfall);


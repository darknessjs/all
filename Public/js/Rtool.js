/**
 * Created by 静龄 on 2015/11/11.
 */


/**
 * Created by ���� on 2015/11/6.
 */
!
    function(window){
        Rtool={"version":"1.0",authon:"darkness"};
        window.Rtool=Rtool;
    }(window),


    function(Rtool){
        Rtool.getArgs=function(){
            var args = {};
            var match = null;
            var search = decodeURIComponent(location.search.substring(1));
            var reg = /(?:([^&]+)=([^&]+))/g;
            while((match = reg.exec(search))!==null){
                args[match[1]] = match[2];
            }
            return args;
        };
        Rtool.checknull=function(str){
            if (str.length == 0)
            {
                return true;
            }else{
                var regu = "^[ ]+$";
                var re = new RegExp(regu);
                if(re.test(str)){
                    return true;
                }
                return false;
            }
        };
        Rtool.getLength=function(str){
            var realLength = 0, len = str.length, charCode = -1;
            for (var i = 0; i < len; i++) {
                charCode = str.charCodeAt(i);
                if (charCode >= 0 && charCode <= 128) realLength += 1;
                else realLength += 2;
            }
            return realLength;
        };
        Rtool.checklength=function(str,maxlength){
            if(this.getLength(str)>maxlength){
                return true;
            }else{
                return false;
            }
        };

        Rtool.checknum=function(str){
            return !/^\d+$/.test(str);
        };
        Rtool.checkmobilephonenum=function(str){
            if (str.match(/^(?:13\\d|15[89])-?\\d{5}(\\d{3}|\\*{3})$/) == null) {
                return true;
            }
        };

        Date.prototype.Format = function(fmt)
        { //author: meizz
            var o = {
                "M+" : this.getMonth()+1,                 //月份
                "d+" : this.getDate(),                    //日
                "h+" : this.getHours(),                   //小时
                "m+" : this.getMinutes(),                 //分
                "s+" : this.getSeconds(),                 //秒
                "q+" : Math.floor((this.getMonth()+3)/3), //季度
                "S"  : this.getMilliseconds()             //毫秒
            };
            if(/(y+)/.test(fmt))
                fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
            for(var k in o)
                if(new RegExp("("+ k +")").test(fmt))
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
            return fmt;
        };

        Rtool.changedate=function(time){
            if(time){
                return new Date(parseInt(time) * 1000).Format("yyyy-MM-dd hh:mm:ss");
            }else{
                return "";
            }
        };
        Rtool.checkMail=function(str){
            return !(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(str));
        }
    }(Rtool),
    function(Rtool){
        Rtool.Rcheck=function(jqform,posturl){
            jqform.submit(function(e){
                var ispost=true;
                jqform.find("input").each(function(index,e){
                    var inputtype=$(this).attr("type");
                    console.log(inputtype)
                    switch (inputtype){
                        case "submit":
                            return true;
                        default :
                            break;
                    }
                    var checkarray=$(this).attr("data-check");
                    if(checkarray){
                        checkarray=checkarray.split(",");
                        var checklength=checkarray.length;
                        for(var i=0;i<checklength;i++){
                            switch (checkarray[i]){
                                case "notnull":
                                    if(Rtool.checknull($(this).val())){
                                        alert($(this).attr("data-valname")+"不能为空");
                                        ispost=false;
                                        return false;
                                    }
                                    break;
                                case "email":
                                    if(Rtool.checkMail($(this).val())){
                                        alert($(this).attr("data-valname")+"不符合邮箱地址规则");
                                        ispost=false;
                                        return false;
                                    }
                                    break;
                                case "mustnum":
                                    if(Rtool.checknum($(this).val())){
                                        alert($(this).attr("data-valname")+"必须为数字");
                                        ispost=false;
                                        return false;
                                    }
                                    break;
                                case "mobilephone":
                                    if(Rtool.checkmobilephonenum($(this).val())){
                                        alert($(this).attr("data-valname")+"不符合手机号码格式");
                                        ispost=false;
                                        return false;
                                    }
                                    break;
                                default :
                                    break;
                            }
                        }
                    }
                });
                if(ispost){
                    console.log(1);
                    $.post(posturl);
                }else{
                    console.log(2);
                }
                return false;
            });
        };
    }(Rtool);


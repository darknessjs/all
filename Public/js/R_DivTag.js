


!
	function(window){
		R_DivTag={"version":"1.0",author:"darkness"};
		window.R_DivTag=R_DivTag;
		R_DivTag.tagnum=0;
	}(window),
	function(R_DivTag){
		R_DivTag.createDiv=function(jquerydiv){
			this.nowtagnum=0;
			var thisdivtag=this
			jquerydiv.append('<ul class="r_window_tag btn-group"></ul><div class="r_tag_content card"></div>');
			jquerydiv.children(".r_window_tag").on("click","li .r_window_tag_close",function(e){
				$(this).parent().fadeOut(function(e){
					$(this).remove();
				});
				e.stopPropagation()
			});
			jquerydiv.children(".r_window_tag").on("click","li",function(e){
				thisdivtag.showContent($(this).attr("data-tagid"));
			});
			this.addTag=function(tagname,isCanClose){
				R_DivTag.tagnum++;
				jquerydiv.children(".r_window_tag").append('<li id="r_tag_'+R_DivTag.tagnum+'" class="r_tag_btn btn btn-raised btn-default" data-tagid="'+R_DivTag.tagnum+'" data-menu="'+tagname+'">'+tagname+' '+ (!isCanClose?"<div class='r_window_tag_close btn btn-danger'></div>":"")+'</li>');
				jquerydiv.children(".r_tag_content").append('<div style="display: none" id="r_tag_content_'+R_DivTag.tagnum+'" data-tagid="'+R_DivTag.tagnum+'"></div>');
				return R_DivTag.tagnum;
			}

			this.setContent=function(tagnum,text){
				$("#r_tag_content_"+tagnum).html(text);
			};

			this.showContent=function(tagnum){
				if(tagnum!=this.nowtagnum){
					$("#r_tag_content_"+this.nowtagnum).stop().animate({left:-jquerydiv.children(".r_tag_content").width()},function(e){
						$(this).hide()
					});
					this.nowtagnum=tagnum;
					jquerydiv.children(".r_window_tag").children("li").removeClass("btn-default").addClass("active");
					$("#r_tag_"+this.nowtagnum).addClass("btn-default").removeClass("active");
					$("#r_tag_content_"+tagnum).show().css("left",jquerydiv.children(".r_tag_content").width()+"px").stop().animate({left:0});
				}
			}


		};




	}(R_DivTag);
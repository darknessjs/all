


!
	function(window){
		R_DivTag={"version":"1.0",author:"darkness"};
		window.R_DivTag=R_DivTag;
		R_DivTag.tagnum=0;
	}(window),
	function(R_DivTag){
		R_DivTag.createTag=function(parentTagDiv,tagname,isCanClose){
			parentTagDiv.tagarr.push(this);
			R_DivTag.tagnum++;
			this.tagnum=R_DivTag.tagnum;

			parentTagDiv.jquerydiv.children(".r_window_tag").append('<li id="r_tag_'+R_DivTag.tagnum+'" class="r_tag_btn btn btn-raised btn-default" data-tagid="'+R_DivTag.tagnum+'" data-menu="'+tagname+'">'+tagname+' '+ (!isCanClose?"<div class='r_window_tag_close btn btn-danger'></div>":"")+'</li>');

			this.tagdiv=parentTagDiv.jquerydiv.children(".r_window_tag").children("li:last");
			parentTagDiv.jquerydiv.children(".r_tag_content").append('<div style="display: none" id="r_tag_content_'+R_DivTag.tagnum+'" data-tagid="'+R_DivTag.tagnum+'"></div>');
			this.tagcontentdiv=parentTagDiv.jquerydiv.children(".r_tag_content").children("div:last");
			this.setContent=function(text){
				$("#r_tag_content_"+this.tagnum).html(text);
			};
			this.setFunc=function(func){
				this.func=func;
			};
			this.showContent=function(){
				if(this.tagnum!=parentTagDiv.nowtagnum){
					this.func?this.func():null;
					$("#r_tag_content_"+parentTagDiv.nowtagnum).stop().animate({left:-parentTagDiv.jquerydiv.children(".r_tag_content").width()},function(e){
						$(this).hide()
					});
					parentTagDiv.nowtagnum=this.tagnum;
					parentTagDiv.jquerydiv.children(".r_window_tag").children("li").removeClass("btn-default").addClass("active");
					$("#r_tag_"+parentTagDiv.nowtagnum).addClass("btn-default").removeClass("active");
					$("#r_tag_content_"+this.tagnum).show().css("left",parentTagDiv.jquerydiv.children(".r_tag_content").width()+"px").stop().animate({left:0});
				}
			};
			this.remove=function(){
				this.tagdiv.remove();
				this.tagcontentdiv.remove();
				parentTagDiv.deletetag(this.tagnum);
				delete this;
				parentTagDiv.tagarr.length!=0?parentTagDiv.tagarr[0].showContent():null;
			};
			return this;
		};

		//将一个div变成带有tag的形式div
		R_DivTag.createDiv=function(jquerydiv,marginleft,margintop){

			this.tagarr=[];
			this.nowtagnum=-1;
			this.jquerydiv=jquerydiv;
			var thisdivtag=this;
			jquerydiv.append('<ul class="r_window_tag btn-group"></ul><div class="r_tag_content card"></div>');
			jquerydiv.children(".r_window_tag").on("click","li .r_window_tag_close",function(e){
				$(this).parent().fadeOut(function(e){
					thisdivtag.gettag($(this).attr("data-tagid")).remove();
				});
				e.stopPropagation()
			});
			this.addTag=function(tagname,isCanClose){
				return new R_DivTag.createTag(this,tagname,isCanClose);
			};
			this.gettag=function(tagnum){
				var tagarrlen=this.tagarr.length;
				for(var i=0;i<tagarrlen;i++){
					if(this.tagarr[i].tagnum==tagnum){
						return this.tagarr[i];
					}
				}
			};
			this.deletetag=function(tagnum){
				var tagarrlen=this.tagarr.length;
				for(var i=0;i<tagarrlen;i++){
					if(this.tagarr[i].tagnum==tagnum){
						this.tagarr.splice(i,1);
						break;
					}
				}
			}
			jquerydiv.children(".r_window_tag").on("click","li",function(e){
				thisdivtag.gettag($(this).attr("data-tagid")).showContent();
			});

			$(window).bind("resize",function(e) {
				jquerydiv.width(document.documentElement.clientWidth - 290);
				jquerydiv.children(".r_tag_content").height(document.documentElement.clientHeight - 210);
			});
			jquerydiv.width(document.documentElement.clientWidth - 290);
			jquerydiv.children(".r_tag_content").height(document.documentElement.clientHeight - 210);
		};




	}(R_DivTag);
/* ------------------------------------------------------------------------
	s3Slider
	
	Developped By: Boban Karišik -> http://www.serie3.info/
        CSS Help: Mészáros Róbert -> http://www.perspectived.com/
	Version: 1.0
	
	Copyright: Feel free to redistribute the script/modify it, as
			   long as you leave my infos at the top.
------------------------------------------------------------------------- */


(function($){  

    $.fn.s3Slider = function(vars) {       
        
        var element     = this;
        var timeOut     = (vars.timeOut != undefined) ? vars.timeOut : 4000;
        var current     = null;
        var timeOutFn   = null;
        var faderStat   = true;
        var mOver       = false;
        var items       = $("#" + element[0].id + "Content ." + element[0].id + "Image");
        var itemsSpan   = $("#" + element[0].id + "Content ." + element[0].id + "Image span");
            
        items.each(function(i) {
    
            $(items[i]).mouseover(function() {
               mOver = true;
            });
            
            $(items[i]).mouseout(function() {
                mOver   = false;
				// the if under is phil add for products_hugnii_slide.html
				if(/*"file:///Z:/%E5%AE%98%E6%96%B9%E7%B6%B2%E9%A0%81/%E4%B8%8D%E5%98%B4%E7%A0%B2%E5%B7%A5%E4%BD%9C%E5%AE%A4/%E7%B6%B2%E9%A0%81%E5%9F%B7%E8%A1%8C%E6%AA%94_20120117/products_hugnii_slide_playyoutube.html" != document.getElementById('iframe_playyoutube').src*/"http://www.rawlaro.com/products_hugnii_slide_playyoutube.html" != document.getElementById('iframe_playyoutube').src){
                fadeElement(true);
				}
            });
            
        });
        
        var fadeElement = function(isMouseOut) {
            var thisTimeOut = (isMouseOut) ? (timeOut/2) : timeOut;
            thisTimeOut = (faderStat) ? 10 : thisTimeOut;
            if(items.length > 0) {
                timeOutFn = setTimeout(makeSlider, thisTimeOut);
            } else {
                console.log("Poof..");
            }
        }
        
        var makeSlider = function() {
            current = (current != null) ? current : items[(items.length-1)];
            var currNo      = jQuery.inArray(current, items) + 1
            currNo = (currNo == items.length) ? 0 : (currNo - 1);
            var newMargin   = $(element).width() * currNo;
            if(faderStat == true) {
                if(!mOver) {
					/*phil mod for products_hugnii_slide.html */
					CloseiFrame();
					HideCloseBT()
					/*phil mod for products_hugnii_slide.html */
                    $(items[currNo]).fadeIn((timeOut/6), function() {
                        if($(itemsSpan[currNo]).css('bottom') == 0) {
                            $(itemsSpan[currNo]).slideUp((timeOut/6), function() {
                                faderStat = false;
                                current = items[currNo];
                                if(!mOver) {
                                    fadeElement(false);
                                }
                            });
                        } else {
                            $(itemsSpan[currNo]).slideDown((timeOut/6), function() {
                                faderStat = false;
                                current = items[currNo];
                                if(!mOver) {
                                    fadeElement(false);
                                }
                            });
                        }
                    });
                }
            } else {
                if(!mOver) {
                    if($(itemsSpan[currNo]).css('bottom') == 0) {
                        $(itemsSpan[currNo]).slideDown((timeOut/6), function() {
                            $(items[currNo]).fadeOut((timeOut/6), function() {
                                faderStat = true;
                                current = items[(currNo+1)];
                                if(!mOver) {
                                    fadeElement(false);
                                }
                            });
                        });
                    } else {
                        $(itemsSpan[currNo]).slideUp((timeOut/6), function() {
                        $(items[currNo]).fadeOut((timeOut/6), function() {
                                faderStat = true;
                                current = items[(currNo+1)];
                                if(!mOver) {
                                    fadeElement(false);
                                }
                            });
                        });
                    }
                }
            }
        }
        
        makeSlider();

    };  

})(jQuery);  
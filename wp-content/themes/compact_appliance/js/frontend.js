/* Foundation Scripts */
//@codekit-prepend "js/foundation/foundation.js";
//@codekit-prepend "js/foundation/foundation.reveal.js";
//@codekit-prepend "js/foundation/foundation.section.js";

/* Sitewide Scripts */
//@codekit-prepend "superfish.js";
//@codekit-prepend "jquery.royalslider.min.js";

jQuery(document).foundation();

(function ($) {

/* Functions */
$.fn.invisible = function() {
    return this.css("visibility", "hidden");
    };
$.fn.visible = function() {
    return this.css("visibility", "visible");
};
$.fn.exists = function() { 
   return this.length > 0; 
}; 

//nav menu
$('.menu-primary').superfish({
	delay: 0, 
	speed:'fast', 
	speedOut:'fast'
});

// adding home icon to breadcrumbs
$(".breadcrumb").prepend('<a href="http://compactappliance.com"><i class="icon-home"></i></a> &raquo; ');

// mega menu drop
$( "#catdrop" ).toggle(function(e) {
	e.preventDefault();
  $("#mega-menu").fadeIn('slow');
  	 $(this).parent().addClass("active");
}, function() {
  $("#mega-menu").fadeOut('slow');
  $(this).parent().removeClass("active");
});

/* disabling/enabling search buttons
var $searchform = $("#searchform");
$searchform.find("button").prop("disabled",true);
$searchform.find("#search").keyup(function(){
	$searchform.find("#search-button").prop("disabled",false);
});
*/

// mobile menu drop
$( ".mobile-drop" ).toggle(function(e) {
	e.preventDefault();
  $("#mobile-drop-menu").fadeIn('slow');
}, function() {
  $("#mobile-drop-menu").fadeOut('slow');
});


// sliders
if($(".royalSlider").exists()) {
	$(".royalSlider").royalSlider({
		autoScaleSlider: true,
		autoScaleSliderHeight: 350,
		keyboardNavEnabled: true,
		controlNavigation: 'none',
		arrowsNavAutoHide: false,
		loop: true,
		addActiveClass: true,
       autoPlay: {
    		enabled: true,
    		pauseOnHover: true,
    		delay: 4000
    	},
    	controlsInside: false,
        block: {
    		moveEffect: 'left'
    	}
	});
}


})(jQuery, this);

//old site functions
function CngFont(obj,prop,assign){
obj.style[prop]=assign;
}

function ajaxPopup(popupURL, h, w)
{
	if(UseBootStrapModal){
	containerw=(w*1) + 45;
	containerh=(h*1) + 45;
	var url = popupURL;			
	$j.get(url, function(data) {
		$j('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content" style="height:'+containerh +'px;width:'+ containerw+'px;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><div style="height:'+h +'px;width:'+ w+'px;overflow:auto;padding:20px;">' + data + '</div></div></div></div>').modal();
	});	
	}else{Dialog.alert({url: popupURL}, {windowParameters: {className: 'alphacube', height: h, width: w}, okLabel: 'Close'});}
	return false; 
}

function textPopup(popupText)
{
	if(UseBootStrapModal){
	$j('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><div style="padding:20px;">' + popupText + '</div></div></div></div>').modal();
	}else{Dialog.alert(popupText, {windowParameters: {className: 'alphacube'}, okLabel: 'Close'}); }
	return false;
}
	

function alertPopup(popupText)
{
	Dialog.alert(popupText, {windowParameters: {className: 'alphacube'}, okLabel: 'Ok, Add to Cart'}); 
	return false;
}
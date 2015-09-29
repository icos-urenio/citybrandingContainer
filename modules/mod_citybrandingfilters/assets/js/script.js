/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */

jQuery(document).ready(function() {
	jQuery("#searchclear").click(function(){
	   jQuery("#filter_search").val('');
	});

	jQuery(".modal-wide").on("show.bs.modal", function() {
	  var height = jQuery(window).height() - 200;
	  jQuery(this).find(".modal-body").css("max-height", height);
	});	

	jQuery('#selectAllCategories').click(function(event) {  
		jQuery(':checkbox[name="cat[]"]').prop('checked', this.checked);
    });

	jQuery('#selectAllSteps').click(function(event) {  
		jQuery(':checkbox[name="steps[]"]').prop('checked', this.checked);
    });
});

//show markers according to filtering
function show(category) {
	// == check the checkbox ==
	document.getElementById('cat-'+category).checked = true;
}			

function hide(category) {
	// == clear the checkbox ==
	document.getElementById('cat-'+category).checked = false;
}

//--- non recursive since IE cannot handle it (doh!!)
//TODO: replace with jQuery
function citybranding_filterbox_click(box, category) {
	if (box.checked) {
		show(category);
	} else {
		hide(category);	
	}
	var com = box.getAttribute('path');
	var arr = new Array();
	arr = document.getElementsByName('cat[]');
	for(var i = 0; i < arr.length; i++)
	{
		var obj = document.getElementsByName('cat[]').item(i);
		var c = obj.id.substr(4, obj.id.length);

		var path = obj.getAttribute('path');
		if(com == path.substring(0,com.length)){
			if (box.checked) {
				obj.checked = true;
				show(c);
			} else {
				obj.checked = false;
				hide(c);
			}
		}
	}
	return false;
}

function citybranding_toggle_checkboxes(elem) {

}



function vote(poi_id, user_id, token){
	jQuery.ajax({ 
	    'async': true, 
	    'global': false, 
	    'url': "index.php?option=com_citybranding&task=votes.add&format=json&poi_id=" + poi_id + "&user_id=" + user_id + "&" + token + "=1", 
	    'dataType': "json", 
	    'success': function (data) {
	    	var json = data;
	        if(json.data.code == 1){
	        	jQuery('#votes-counter').html(json.data.votes);
	        }

	        var notification = new NotificationFx({
	        	wrapper : document.body,
	        	message : '<span class="glyphicon glyphicon-info-sign icon" aria-hidden="true"></span><p>'+json.data.msg+'</p>',
	        	//layout : 'bar',
	        	//effect : 'slidetop',
	        	layout : 'attached',
	        	effect : 'bouncyflip',
	        	type : 'error', // notice, warning or error
	        	ttl : 3000,
	        });
	        notification.show();

	     },
	     'error': function (error) {
	        alert('Voting failure - See console for more information');
	        console.log (error);
	     }             
	});
}
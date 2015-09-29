/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */

function onInit(data, elem, id, isNew, imagedir) {
	var obj = new Object();
	if(isNew){
		obj.isnew = 1;
	}
	obj.id = id;
	obj.imagedir = imagedir;
	obj.files = data;
	jQuery('#'+String(elem.id)).val( JSON.stringify(obj) );
}

function onDone(data, elem) {
	var obj = JSON.parse( jQuery('#'+String(elem.id)).val() );
	if(obj){
		obj['files'].push(data[0]);
	}
	jQuery('#'+String(elem.id)).val( JSON.stringify(obj) );
}

function onDestroy(data, elem) {
	var obj = JSON.parse( jQuery('#'+String(elem.id)).val() );
	var i = -1;
	for (index = 0; index < obj.files.length; ++index) {
	    if(data == obj['files'][index]['name']){
	    	i = index;
	    	break;
	    }
	}
	if(i > -1){
		obj['files'].splice(i, 1);
	}

	jQuery('#'+String(elem.id)).val( JSON.stringify(obj) );
}
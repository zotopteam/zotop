/*!
 * zotop theme global javascript
 * http://zotop.com/
 */
$(function(){
	$(document).on('click',function(e){
	    
	    if( $(e.target).closest('.navbar').length && $('.navbar-collapse.in').length ) 
	    {
	    	return; // Not return false	
	    }

	    $('.navbar-collapse').collapse('hide');
	});
});
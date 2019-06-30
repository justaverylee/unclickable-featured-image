"use strict"

// walk up the tree looking for if this is clickable.
// If this is a link to something other then the current page, leave it
// If this is a link into javascript (or this has already been triggered), leave it
function unlink_thumbnail(element) {
	var parentWalk = element;
	// if we are checking the link, start at the element and check all parents
	// if we are not checking the link, skip the walk and just set it
	while (parentWalk && unclickable_options.link) {
		// checks for links not to the current page. Aborts if found
		if (parentWalk.tagName == 'A' && (parentWalk.getAttribute('href') != unclickable_options.link)) {
			return;
		} else if (parentWalk.tagName == 'A') {
			break;
		}
		// checks for javascript actions. Assume it isn't a link to the current page
		if (parentWalk.hasAttribute('onclick')) {
			return;
		}
		// continue walking up
		parentWalk = parentWalk.parentElement;
	}
	
	// if we walked all the way to the top without finding a link, leave it alone.
	if (parentWalk != null)
		element.outerHTML = "<a href='javascript:void(0);' style='cursor:default'>" + element.outerHTML + "</a>"
}

// onload: walk around and find the thumbnail and unlink it.
window.onload = function unlink_find_thumbnail() {
	// check that the php created an object with info on what to do
	if (unclickable_options != undefined) {
		// use the selector to find potential thumbnails
		var matches = document.querySelectorAll(unclickable_options.selector);
		// loop through (or only check the first...see end of loop)
		for (var i = 0; i < matches.length; i++) {
			var match = matches[i];
			// check images if needed based on plugin config
			if (!unclickable_options.img ||
					((match.tagName == 'IMG') &&
					match.getAttribute('src') == unclickable_options.thumb)) 
				unlink_thumbnail(match); // trigger the link removal

			// check background if needed based on plugin config
			if (!unclickable_options.background ||
					(unclickable_options.thumb &&
					// check if the url is attached. There should be a url( at the begining
					(match.style['background-image'].indexOf(unclickable_options.thumb)) > 2))
				unlink_thumbnail(match); // trigger the link removal
		
			// if all is not true, stop immediately (after first one)	
			if (!unclickable_options.all) {
				return;
			}
		}
	}
}
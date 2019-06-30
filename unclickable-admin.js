"use strict";

// I'm sure there is a better way to do this, but this does work so I'm leaving it.

// when the page loads, attach listeners and find the radio box set
window.onload = setupBoxes;
var radios;

// finds the radio box set (and sets them to the global variable)
// attaches an onchange to each radio button triggering updateBoxes
function setupBoxes() {
	radios = document.unclickable_options.unclickable_options_select;
	
	radios.addEventListener('change', updateBoxes);
	
	updateBoxes();
}

// sets the disabled or readOnly state to the passed value for the fields associated
function setOptions(value) {
	document.unclickable_options['unclickable_options_selector'].readOnly = value;
	document.unclickable_options['unclickable_options_all'].readOnly = value;
	document.unclickable_options['unclickable_options_thumbnail'].readOnly = value;
	document.unclickable_options['unclickable_options_link'].readOnly = value;
	document.unclickable_options['unclickable_options_img'].readOnly = value;
	document.unclickable_options['unclickable_options_background'].readOnly = value;
}

// triggered onchange of radio button set, calls setOptions() with the appropriate value
function updateBoxes() {
	// collect the things we'll be using/editing
	var selectorBox = document.unclickable_options.unclickable_options_selector;
	
	if (radios.checked) {
		setOptions(false);
	} else {
		setOptions(true);
	}
	
}
/*
File: Company Profiles Administration Script
Author: Aaron Tobias
//http://rutwickgangurde.wordpress.com/2011/01/06/add-custom-javascript-to-your-wordpress-plugin-and-pass-php-variables-to-it/
*/

/*

		<label>Position 1 Title</label><input type="text" name="tstamp"/>
		<label>Position 1 Name</label><input type="text" name="last" />
		<label>Position 1 Picture</label><input type="text" name="first" />
		
    <textarea name="bio1" rows=20 cols=100></textarea>
*/
jQuery(document).ready(function($) {

	var adminSection = $("#cp_the_team_admin");
	var memberNumber = adminSection.children('fieldset:last').attr('id');
	
	$("#addMember").click(function(){
		var content = "";
		var nextFieldSet = adminSection.children('fieldset:last');
		/*optimize this with getting .next() from initial last?*/
		
		++memberNumber;
		content = '<fieldset id="'+memberNumber+'">';
		content += '<label>Position ' + memberNumber + ' Title</label><input type="text" name="title'+ memberNumber +'"/>';
		content += '<label>Position ' + memberNumber + ' Name</label><input type="text" name="last'+ memberNumber +'"/>';
		content += '<label>Position ' + memberNumber + ' Picture</label><input type="text" name="first'+ memberNumber +'"/>';
		content += '<label for="file"><input type="file" name="file'+ memberNumber +'"value="Upload Picture"/> </label>';
		content += '<label>Position' + memberNumber + ' Bio</label>';
		content += '<textarea name="bio' + memberNumber +'" rows=20 cols=100></textarea>';
		content += '<fieldset/>';
		$(content).insertAfter(nextFieldSet);
	});
	return false;
});
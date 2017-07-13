//
// If you copy/use/modify this code - please keep this
// comment header in place 
//
// Copyright (c) 2009-2013 Crafty Clicks (http://www.craftyclicks.com)
//
// This code relies on JQuery, you must have a reasonably recent version loaded 
// in your template. CS Cart should include it as standard.
// 
// If you need any help, contact support@craftyclicks.co.uk - we will help!
//
//Default values
var _cp_token_fe = "xxxxx-xxxxx-xxxxx-xxxxx";
var _cp_enable_for_uk_only = true; // if true, lookup functioanlity is only shown is country selected is UK
var _cp_button_text = 'Find Address'; 
var _cp_button_class = 'btn'; 
var _cp_result_box_height = 1; // max number of result lines to display in the box; 1 = a drop down box
var _cp_result_box_width = ''; 
var _cp_busy_img_url = 'modules/plugins/CraftyClicks/img/crafty_postcode_busy.gif'; 
var _cp_clear_result = true; // hide result box, once a selection is made
var _cp_hide_fields = false; // hides address lines four UK until postcode lookup is completed
var _cp_hide_county = false; // hides state/county filed for UK
var _cp_update_county_select = true; 
var _cp_1st_res_line = '--- please select your address ---'; 
var _cp_err_msg1 = 'This postcode could not be found, please try again or enter your address manually'; 
var _cp_err_msg2 = 'This postcode is not valid, please try again or enter your address manually'; 
var _cp_err_msg3 = 'Unable to connect to address lookup server, please enter your address manually'; 
var _cp_err_msg4 = 'An unexpected error occurred, please enter your address manually'; 
var _cp_error_class = 'error'; 
var _cp_manual_entry_link = false;
var _cp_put_company_on_line1 = true;

//var _cp_button_image = ''; // set this to a URL for a image to use as 'find address' button, leave blank to have a default button

function CraftyClicksClass () {
	this.prefix = ""; 
	this.fields = { "postcode_id"	: "", // required
					"company_id"	: "", // optional 
					"street1_id"	: "", // required	
					"street2_id"	: "", // optional
					"street3_id"	: "", // optional
					"street4_id"	: "", // optional
					"town_id"		: "", // required
					"county_id"		: "", // optional 
					"country_id"	: "" // required
					};

	this.current_setup			= 'initial'; // can be 'uk' or 'non_uk'
	this.cp_obj					= 0;
	this.last_div				= null;
	this.non_uk_pc_width			= null;
	this.non_uk_pc_div_width		= null;

	this.setup_for_uk = function() {
		// check if we need to do anything
		if ('uk' != this.current_setup) {

			$('#'+this.fields.country_id).closest('.row').after( $('#'+this.fields.postcode_id).closest('.row') );

			// remove the result box (in case it is in the wrong place)
			if ($('#'+this.prefix+'_cp_result_display').length) {
				$('.'+this.prefix+"_cp_result_class").hide();
			}
			// add result box
			if (!$('#'+this.prefix+'_cp_result_display').length) {
				var tmp_html = '<div class="'+this.prefix+'_cp_result_class" style="clear: both;"><span id="'+this.prefix+'_cp_result_display"></span></div>';
				$('#'+this.fields.postcode_id).after( tmp_html );
			}
			// show result box
			$('.'+this.prefix+"_cp_result_class").show();
			// add button
			if (!$('#'+this.prefix+'_cp_button_id').length) {
				var tmp_html = '<input style="display: none; margin-left: 6px; padding: 5px; float: left;" type="button" class="'+_cp_button_class+'" id="'+this.prefix+'_cp_button_id" value="'+_cp_button_text+'"/>';
				
				$('#'+this.fields.postcode_id).after( tmp_html );
				var that = this;
				$('#'+this.prefix+'_cp_button_id').click(function(){
					 that.button_clicked(this);
				});
			}
			$('#'+this.fields.postcode_id).css('float','left');
			if($('#'+this.fields.postcode_id).width() > 300)
				$('#'+this.fields.postcode_id).width( $('#'+this.fields.postcode_id).width() - 130 );
			$('#'+this.prefix+"_cp_button_id").show(); //moved to jquery observe
			
			// hide county if requested (and if it exists in the html at all)
			if (_cp_hide_county) {
				$('#state-list').closest('.row').hide();
				if ($('#'+this.fields.county_id).length) {
					$('#'+this.fields.county_id).parent().hide();
				}
			}
		}	
		
		if ('initial' == this.current_setup && _cp_hide_fields && '' == $('#'+this.fields.town_id).val()) {
			// first time and default to UK, hide address fields
			$('.'+this.prefix+'_cp_address_class').hide();
		}
		
		// set state
		this.current_setup = 'uk';
	}	

	this.setup_for_non_uk = function() {
		// check if we need to do anything
		if ('initial' != this.current_setup && ('non_uk' != this.current_setup || $('.'+this.prefix+'_cp_address_class').css("display")=="none")) {
			
			$('#'+this.fields.town_id).closest('.row').after( $('#'+this.fields.postcode_id).closest('.row') );
			$('#'+this.fields.postcode_id).css('width','');

			// hide result box (if it exist already)
			if ($('#'+this.prefix+"_cp_result_display")) {
				this.cp_obj.update_res(null);
				$('.'+this.prefix+"_cp_result_class").hide();
			}
			// hide button (if it exist already)
			if ($('#'+this.prefix+"_cp_button_id")) {
				$('#'+this.prefix+"_cp_button_id").hide();
				if (this.non_uk_pc_width && this.non_uk_pc_div_width) {
					$('#'+this.fields.postcode_id).css("width", this.non_uk_pc_width);
					$('#'+this.fields.postcode_id).parent().css("width", this.non_uk_pc_div_width);
				}
			}
			// move postcode to the non-uk position after the town/county 
			//this.last_div.after($('#'+this.fields.postcode_id).parent('div'));

			// show county if it was hidden (and exists in the html at all)
			if (_cp_hide_county) {
				$('#state-list').closest('.row').show();
				if ($('#'+this.fields.county_id).length) {
					$('#'+this.fields.county_id).parent().show();
				}
			}
					
			// show all other addres lines
			$('.'+this.prefix+'_cp_address_class').show();
			// set state
			this.current_setup = 'non_uk';
		}	
	}	
	
	this.add_lookup = function(setup) {
		// initial page setup
	 	this.prefix = setup.prefix;
		var that = this;
		
		this.fields=setup.fields;

		$('#'+this.fields.street1_id).parent().addClass(this.prefix+'_cp_address_class');
		$('#'+this.fields.street2_id).parent().addClass(this.prefix+'_cp_address_class');
		$('#'+this.fields.company_id).parent().addClass(this.prefix+'_cp_address_class');
		$('#'+this.fields.town_id).parent().addClass(this.prefix+'_cp_address_class');
		$('#'+this.fields.county_id).parent().addClass(this.prefix+'_cp_address_class');

		cp_obj = CraftyPostcodeCreate();
		this.cp_obj = cp_obj;
	 	// config 
		cp_obj.set("access_token", _cp_token_fe); 
		cp_obj.set("res_autoselect", "0");
		cp_obj.set("result_elem_id", this.prefix+"_cp_result_display");
		cp_obj.set("form", "");
		if (_cp_put_company_on_line1) {
			cp_obj.set("elem_company"  , this.fields.street1_id); 
		} else {
			cp_obj.set("elem_company"  , this.fields.company_id); // optional
		}
		cp_obj.set("elem_street1"  , this.fields.street1_id);
		cp_obj.set("elem_street2"  , this.fields.street2_id);
		cp_obj.set("elem_street3"  , this.fields.street3_id); 
		cp_obj.set("elem_town"     , this.fields.town_id);
		cp_obj.set("elem_county"   , this.fields.county_id); // optional
		cp_obj.set("elem_postcode" , this.fields.postcode_id);
		cp_obj.set("single_res_autoselect" , 1); // don't show a drop down box if only one matching address is found
		cp_obj.set("max_width" , _cp_result_box_width);
		if (1 < _cp_result_box_height) {
			cp_obj.set("first_res_line", ""); 
			cp_obj.set("max_lines" , _cp_result_box_height);
		} else {
			cp_obj.set("first_res_line", _cp_1st_res_line); 
			cp_obj.set("max_lines" , 1);
		}
		cp_obj.set("busy_img_url" , _cp_busy_img_url);
		cp_obj.set("hide_result" , _cp_clear_result);
		cp_obj.set("traditional_county" , 1);
		cp_obj.set("on_result_ready", function(){ that.result_ready(this)} );
		cp_obj.set("on_result_selected", function(){ that.result_selected(this)} );
		cp_obj.set("on_error", function(){ that.result_error(this)} );
		cp_obj.set("first_res_line", _cp_1st_res_line);
		cp_obj.set("err_msg1", _cp_err_msg1);
		cp_obj.set("err_msg2", _cp_err_msg2);
		cp_obj.set("err_msg3", _cp_err_msg3);
		cp_obj.set("err_msg4", _cp_err_msg4);
		
		$('#'+this.fields.street1_id).closest('.row').insertBefore( $('#'+this.fields.street2_id).closest('.row') );
		$('#'+this.fields.country_id).closest('.row').insertBefore( $('#'+this.fields.street1_id).closest('.row') );

		if (_cp_enable_for_uk_only) {
			this.country_changed();
			$('#'+this.fields.country_id).change( function(){ that.country_changed(this)} );
		} else {
			this.setup_for_uk();
		}

		if (_cp_hide_fields && this.current_setup=="uk") {
			// first time and default to UK, hide address fields
			$('.'+this.prefix+'_cp_address_class').hide();
		}


		return true;
	}

	this.country_changed = function(e) {
		var curr_country = $('#'+this.fields.country_id).val();
		if ('826' == curr_country || 'GB' == curr_country || 'IM' == curr_country || 'JE' == curr_country || 'GG' == curr_country) {
			this.setup_for_uk();
		} else {
			this.setup_for_non_uk();
		}
	}

	this.button_clicked = function(e) {
		if ('' != _cp_error_class) $('#'+this.prefix+'_cp_result_display').removeClass(_cp_error_class);
		this.cp_obj.doLookup();
	}
	
	this.result_ready = function() {
		$('.'+this.prefix+'_cp_address_class').show();
	}
		
	this.result_selected = function() {
		if (_cp_clear_result) this.cp_obj.update_res(null);
		console.log(this.fields);

	}
	
	this.result_error = function() { 
		$('.'+this.prefix+'_cp_address_class').show();
		if ('' != _cp_error_class) $(this.prefix+'_cp_result_display').addClass(_cp_error_class);
	}
}

$(document).ready(function(){

	_cp_token_fe = trans_token;
	_cp_hide_county = hide_county;

	if ($('#addr_postcode').length && !($('#first_cp_button_id').length)) { 
		var cc1 = new CraftyClicksClass();
		cc1.add_lookup({
		"prefix"				: "first",
		"fields"				: { "country_id"	: "country-list",
									"postcode_id"	: "addr_postcode",
									"company_id"	: "addr_company",
									"street1_id"	: "addr_line1",
									"street2_id"	: "addr_line2",
									"town_id"		: "addr_town"}
		});
	}

	if ($('#del_postcode').length && !($('#second_cp_button_id').length)) { 
		var cc1 = new CraftyClicksClass();
		cc1.add_lookup({
		"prefix"				: "second",
		"fields"				: { "country_id"	: "delivery_country",
									"postcode_id"	: "del_postcode",
									"company_id"	: "del_company",
									"street1_id"	: "del_line1",
									"street2_id"	: "del_line2",
									"town_id"		: "del_town"}
		});
	}
});

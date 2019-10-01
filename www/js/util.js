//function to display a pretty cool pop up calendar
function display_calendar(input_field, button_name) {
	Calendar.setup(
		{
			inputField : input_field, // ID of the input field
			ifFormat : "%d-%B-%Y", // the date format
			button : button_name // ID of the button
		}
	);
}

function confirm_bundle() {
    var form_name = "award_bundle";
    //select msisdn
    var msisdn = document.getElementById("msisdn");
    //select bundle
    var bundleIndex = document.forms[form_name].bundle.selectedIndex;
    var bundle = document.forms[form_name].bundle.options[bundleIndex].text;


    var affirm = confirm("Award " + bundle + "\nto: " + msisdn.value + " ?");

    if (affirm == true) {
        document.forms[form_name].submit();
    }
    else {
        //do nothing
    }
}

function confirm_subscription() {
    var form_name = "award_subscription";
    //select msisdn
    var msisdn = document.getElementById("msisdn");

    var affirm = confirm("Award unlimited data subscription\nto: " + msisdn.value + " ?");

    if (affirm == true) {
        document.forms[form_name].submit();
    }
    else {
        //do nothing
    }
}

function confirm_award() {
    var form_name = "award_adhoc";
    //select msisdn
    var msisdn = document.getElementById("msisdn");
    var bundle_amt = document.getElementById("bundle");

    var affirm = confirm("Award " + bundle_amt.value + " MB\nto: " + msisdn.value + " ?");

    if (affirm == true) {
        document.forms[form_name].submit();
    }
    else {
        //do nothing
    }
}

function confirm_pcrf_bundle() {
    var form_name = "award_adhoc";
    //select msisdn
    var msisdn = document.getElementById("msisdn");
    //select bundle
    var bundleIndex = document.forms[form_name].bundle.selectedIndex;
    var bundle = document.forms[form_name].bundle.options[bundleIndex].text;


    var affirm = confirm("Award " + bundle + "\nto: " + msisdn.value + " ?");

    if (affirm == true) {
        document.forms[form_name].submit();
    }
    else {
        //do nothing
    }
}

function confirm_deletion(form_name) {
	var array_checkboxes = document.getElementsByName("record_id");
	var counter = array_checkboxes.length;
	var i;
	var temp;
	var total_count = 0;
    var affirm;

	for (i = 0; i < counter; i++) {
		if (array_checkboxes[i].checked) {
			total_count++;
			temp = ";";
			temp += array_checkboxes[i].value;
			document.forms[form_name].confirm_deletion.value += ";"+array_checkboxes[i].value;
		}
	}
	//alert(document.draw_results.confirmed_winners.value);
	
	//no_of_records selected for deletion
	if (total_count == 1)
		affirm = confirm("Are you sure you want to delete this record?");
	else if (total_count > 1)
		affirm = confirm("Are you sure you want to delete these " + total_count + " records?");
	else if (total_count == 0) {
		alert("You have not selected any records for deletion.");
		affirm = false;
	}
		
	if (affirm == true)
	{
	  	document.forms[form_name].submit();
	  	//display number of records deleted
	  	if (total_count > 1) {
			alert(total_count + " RECORDS DELETED");
		}
		else {
			alert("1 RECORD DELETED");
		}
	}
	else
	{
		//clear the hidden text box
		document.forms[form_name].confirm_deletion.value = "";
	}
}

//change combobox based on previous selection
function select_category(currentbox) {
	var val = [];
	val[1] = ['none;--Select--', '2;Video', '3;Audio'];
	val[86] = ['none;--Select--', '90;Video', '91;Audio'];
	//val[2] = ['none;--Select--', '4;Local', '5;International'];
	//val[3] = ['none;--Select--', '6;Local', '7;International'];
	
	var val1 = [];	
	val1[2] = ['none;--Select--', '24;Benga', '25;Bongo', '26;Genge', '27;Kenyan Pop', '28;Gospel', '29;Taarab', '82;Vernacular - Kikuyu', '83;Vernacular - Luo', '84;Vernacular - Luhyia', '85;Vernacular - Kamba', '92;Zilzopendwa'];
	val1[90] = ['none;--Select--', '31;Dance', '32;Gospel', '33;Hip Hop', '34;Bollywood', '35;Kwaito', '36;Pop', '37;R & B', '38;Raggae', '39;Rock', '40;Techno', '41;Zouk'];
	val1[3] = ['none;--Select--', '13;Benga', '14;Bongo', '15;Genge', '16;Kenyan Pop', '17;Gospel', '18;Taarab', '78;Vernacular - Kikuyu', '79;Vernacular - Luo', '80;Vernacular - Luhyia', '81;Vernacular - Kamba', '93;Zilizopendwa'];
	val1[91] = ['none;--Select--', '53;Dance', '54;Gospel', '55;Hip Hop', '56;Bollywood', '57;Kwaito', '58;Pop', '59;R & B', '60;Raggae', '61;Rock', '62;Techno', '63;Zouk'];
	val1[8] = ['none;--Select--', '64;Action', '65;Puzzle', '66;Casino', '67;Sport'];
	val1[9] = ['none;--Select--', '68;News highlights', '69;News satire'];
	val1[10] = ['none;--Select--', '10;Comedy'];
	val1[11] = ['none;--Select--', '11;Sports'];
	

	var combo3 = document.getElementById("category_3");
	var currentcombo = document.getElementById(currentbox.id);
	var currentval = currentcombo.value.split(";");
	var currentid = currentval[0];
	var numb = currentbox.id.split("_");
	var current_combo_id = numb[1];
	var i = parseInt(current_combo_id);

	if (i == 1) {
		var nextcombo = document.getElementById(numb[0] + "_" + (i+1));	
		combo3.disabled = true;
	}
	else {
		var nextcombo = document.getElementById(numb[0] + "_" + i);
	}		

	if (typeof(val[currentid]) == 'undefined') {
		nextcombo.disabled = true;		
		
		if (typeof(val1[currentid]) != 'undefined') {
			combo3.disabled = false;
			
			//reset all values of combo3
			for (n = 0; n < combo3.options.length; n++) {
				combo3.options[n] = null;
			}

			//fill up the combobox
			for (m = 0; m < val1[currentid].length; m++) {
				temp = val1[currentid][m].split(";");							
				combo3.options[m] = new Option(temp[1], temp[0]);
			}
		}		
	}
	else {
		nextcombo.disabled = false;
		//reset all values of nextcombo
		for (n = 0; n < nextcombo.options.length; n++) {
			nextcombo.options[n] = null;
		}
		
		for (m = 0; m < val[currentid].length; m++) {
			temp = val[currentid][m].split(";");							
			nextcombo.options[m] = new Option(temp[1], temp[0]);
		}
	}	
}
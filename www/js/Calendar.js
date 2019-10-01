public_description = new public_description_ctor;
var InScriptlet = (typeof(window.external.version) == "string")
var Me, inputObj;

function Init(pBody){	
	Me = pBody	;	
	innerInit();	
}

function public_description_ctor() {
    
    this.put_BackColor = put_BackColor;
    this.get_BackColor = get_BackColor;
    
    this.put_ForeColor = put_ForeColor;
    this.get_ForeColor = get_ForeColor;

    // foreground color of the days of the previous and next months
    this.put_NonCurForeColor = put_NonCurForeColor;
    this.get_NonCurForeColor = get_NonCurForeColor;

    // background color of the selected date
    this.put_SelectColor = put_SelectColor;
    this.get_SelectColor = get_SelectColor;
    
    // foreground color of the day the mouse is over
    this.put_HighlightColor = put_HighlightColor;
    this.get_HighlightColor = get_HighlightColor;

    // foreground color of the calendar headers
    this.put_HeaderColor = put_HeaderColor;
    this.get_HeaderColor = get_HeaderColor;

    this.put_Value = put_Value;
    this.get_Value = get_Value;

    this.event_OnChange = "";   // fires when the value of the calendar changes
    this.event_NewMonth = "";   // fires when the month changes, always preceded by an OnChange event
    this.event_NewYear = "";    // fires when the year changes, always preceded by an OnChange event
                               
}
function innerInit(){
	mDate = new Date();
	mSelectBackgroundColor = "Gray";
	mStandardBackgroundColor = "White";
	mStandardForeColor = "Black";
	mHeaderColor = "Blue";
	mNonCurMonthForeColor = "Silver";
	mHighlightColor = "red";
	mPreviousElement = Me;
	mFirstOfMonthCol = 0;   // X-coord of first of month
	mLastOfMonthCol = 0;    // X-coord of last of month
	mLastOfMonthRow = 0;    // Y-coord of last of month
}


function InitCalendar(iMonth, iDay, iYear) {

    CurMonth.innerHTML = getStringMonth(iMonth);
    CurMonth.style.color = mHeaderColor;
    CurYear.innerHTML= getStringYear(iYear);
    CurYear.style.color = mHeaderColor;
    
    mDate.setDate(1);
    mDate.setMonth(iMonth);
   
    if(iYear > 100) {
	    mDate.setYear(iYear);
	    iNewSelection = iYear - 2001;
	}
	 else {
	    mDate.setYear(2001 + iYear);
	    iNewSelection = iYear - 100;
    }
    
    document.all("selYear").selectedIndex = iNewSelection;
    document.all("selMonth").selectedIndex = iMonth;

    iFirstOfMonthCol = mDate.getDay();

    mDate.setDate(iDay);
    iTmp = iDay + iFirstOfMonthCol - 1;

    iDayRow = Math.floor((iDay + iFirstOfMonthCol - 1) / 7);
    
    if (iFirstOfMonthCol == 0) {
    	// when the first of the month is Sunday, start in the second row
        iFirstRow = 1;
	    iDayRow += 1;
    } else {
	    iFirstRow = 0;
    }
    
    // de-select the previous element
    try {mPreviousElement.style.backgroundColor = mStandardBackgroundColor}
    catch(e){}

    // once the column of the first of the month is known, the whole calendar can be populated
    iDaysInMonth = getDaysInMonth(iMonth, iYear);

    for(iCurRow = iFirstRow, iCurCol = iFirstOfMonthCol, iDayIndex = 1; iDayIndex <= iDaysInMonth; iDayIndex += 1, iCurCol += 1) {
	    if(iCurCol > 6) {
	        iCurCol = 0;
            iCurRow += 1;
        }
        
	    document.all("Cell" + iCurCol + iCurRow).innerHTML = iDayIndex;
	    document.all("Cell" + iCurCol + iCurRow).style.color = mStandardForeColor;
       document.all("Cell" + iCurCol + iCurRow).title = iDayIndex + " " + CurMonth.innerHTML + " " + CurYear.innerHTML ;
    }
    
    // store the locations of the first and last days of the month in the grid
    mFirstOfMonthCol = iFirstOfMonthCol;
    mLastOfMonthCol = (iCurCol + 6) % 7;
    if(iCurCol == 0)
    	mLastOfMonthRow = iCurRow - 1;
    else
        mLastOfMonthRow = iCurRow;
 
    // populate the empty sections of the grid with the next and previous months' days
    for(iDayIndex = 1; iCurRow <= 4 || iCurCol <= 6; iCurCol += 1, iDayIndex += 1) {
	    if(iCurCol > 6) {
	        iCurCol = 0;
	        iCurRow += 1;
	    }
    
        document.all("Cell" + iCurCol + iCurRow).innerHTML = iDayIndex;
	    document.all("Cell" + iCurCol + iCurRow).style.color = mNonCurMonthForeColor;
    }

    iPrevMonth = (mDate.getMonth() + 11) % 12;
    iPrevMonthLastDay = getDaysInMonth(iPrevMonth, mDate.getYear());    
    
    for(iDayIndex = iPrevMonthLastDay, iCurRow = 0, iCurCol = (mFirstOfMonthCol + 6) % 7; iCurCol >= 0; iCurCol -= 1, iDayIndex -=1) {
	    document.all("Cell" + iCurCol + iCurRow).innerHTML = iDayIndex;
	    document.all("Cell" + iCurCol + iCurRow).style.color = mNonCurMonthForeColor;
    }
    	
    iDayCol = mDate.getDay();

    // highlight the date in the calendar
    document.all("Cell" + iDayCol + iDayRow).style.backgroundColor = mSelectBackgroundColor;
    mPreviousElement = document.all("Cell" + iDayCol + iDayRow);
   
}


function put_NonCurForeColor(stColor) {
    mNonCurMonthForeColor = stColor;
    
    // change the forecolor of the previous month elements
    
    iFinalCol = (mFirstOfMonthCol + 6) % 7;
    for(iColIndex = 0; iColIndex <= iFinalCol; iColIndex += 1) 
        document.all("Cell" + iColIndex + "0").style.color = stColor;

    // change the forecolor of the next month's elements

    iFirstCol = (mLastOfMonthCol + 1) % 7;
    if(iFirstCol == 0) 
        iFirstRow = mLastOfMonthRow + 1;
    else
        iFirstRow = mLastOfMonthRow;

    for(iRowIndex = iFirstRow; iRowIndex <= 5; iRowIndex += 1) {
        for(iColIndex = iFirstCol; iColIndex <=6; iColIndex += 1) {
            document.all("Cell" + iColIndex + iRowIndex).style.color = stColor;
        }
    }


}

function get_NonCurForeColor(stColor) {
    return mNonCurMonthForeColor;
}


function put_HeaderColor(stColor) {
    mHeaderColor = stColor;

    CurMonth.style.color = stColor;
    CurYear.style.color = stColor;

    DayHeaders.style.color = stColor;

}

function get_HeaderColor(stColor) {
    return mHeaderColor;
}

function put_HighlightColor(stColor) {
    mHighlightColor = stColor;
}

function get_HighlightColor() {
    return mHighlightColor;
}


function put_SelectColor(stColor) {
    mSelectBackgroundColor = stColor;

    mPreviousElement.style.backgroundColor = stColor;
}


function get_SelectColor() {
    return mSelectBackgroundColor;
}

function put_ForeColor(stColor) {
    mStandardForeColor = stColor;

    iFirstCol = mFirstOfMonthCol;
    
    if(iFirstCol == 0)
	    iFirstRow = 1;
    else
		iFirstRow = 0;
    
    for(iColIndex = iFirstCol, iRowIndex = iFirstRow; iRowIndex < mLastOfMonthRow || iColIndex <= mLastOfMonthCol; iColIndex+=1) {
		if(iColIndex > 6) {
			iColIndex = 0;
			iRowIndex += 1;
  		}
		document.all("Cell" + iColIndex + iRowIndex).style.color = stColor;
     }
}


function get_ForeColor() {
    return mStandardForeColor;
}
 

function put_BackColor(stColor) {
    mStandardBackgroundColor = stColor;
    Me.style.backgroundColor = stColor;
    
    document.all("selMonth").style.backgroundColor = stColor;
    document.all("selYear").style.backgroundColor = stColor;
    
    for(iColIndex = 0, iRowIndex = 0; iRowIndex <= 4 || iColIndex <= 6; iColIndex += 1) {
	    if(iColIndex > 6) {
	        iColIndex = 0; 
	        iRowIndex += 1;
	    }
	    if("Cell" + iColIndex + iRowIndex != mPreviousElement.id)
	        document.all("Cell" + iColIndex + iRowIndex).style.backgroundColor = stColor;
    }
    
}

function get_BackColor() {
    return mStandardBackgroundColor;
}


function put_Value(stDate) {
    // parse the string for the month, day, and year, and call InitCalendar
    
	mDate.setTime(Date.parse(stDate));
    InitCalendar(mDate.getMonth(), mDate.getDate(), mDate.getYear());

}

function get_Value() {
    iMonth = mDate.getMonth() + 1;
    iYear = mDate.getYear();
       
    return iMonth + "/" + mDate.getDate() + "/" + iYear;
}


function monthChange() {
    mDate.setMonth(document.all("selMonth").selectedIndex);
    InitCalendar(mDate.getMonth(), mDate.getDate(), mDate.getYear());    
    if (InScriptlet) { 
      window.external.RaiseEvent("OnChange", window.event); 
      window.external.RaiseEvent("NewMonth", window.event);
    }
}



function selYearChange() {
    mDate.setYear(2001 + document.all("selYear").selectedIndex);
    InitCalendar(mDate.getMonth(), mDate.getDate(), mDate.getYear());
    
    if (InScriptlet) {
      window.external.RaiseEvent("OnChange", window.event);
      window.external.RaiseEvent("NewYear", window.event);
    }
    
}

function getDaysInMonth(iMonth, iYear) {
    if(iMonth == 0)
	    return 31;
    
    else if(iMonth == 1) {
	    if(iYear%4 == 0 && !(iYear%100 == 0) || iYear%400 == 0)
	        return 29;
        else
	        return 28;
        }
    else if(iMonth == 2)
	    return 31;
    else if(iMonth == 3)
	    return 30;
    else if(iMonth == 4)
	    return 31;
    else if(iMonth == 5)
	    return 30;
    else if(iMonth == 6)
	    return 31;
    else if(iMonth == 7)
	    return 31;
    else if(iMonth == 8)
	    return 30;
    else if(iMonth == 9)
	    return 31;
    else if(iMonth == 10)
   	    return 30;
    else 
	    return 31;

}

function getStringYear(iYear) {
    if(iYear > 100)
		return(iYear);
    else
		return (2001 + iYear);
}


function getStringMonth(iMonth) {
    if(iMonth == 0)
	    return "January";
    else if(iMonth == 1)
	    return "February";
    else if(iMonth == 2)
	    return "March";
    else if(iMonth == 3)
	    return "April";
    else if(iMonth == 4)
	    return "May";
    else if(iMonth == 5)
	    return "June";
    else if(iMonth == 6)
	    return "July";
    else if(iMonth == 7)
	    return "August";
    else if(iMonth == 8)
	    return "September";
    else if(iMonth == 9)
	    return "October";
    else if(iMonth == 10)
   	    return "November";
    else 
	    return "December";
}


function CalendarClick() {

    current = window.event.srcElement;    
    // three of the possible click locations that need to be processed.
    //  1.  A click on a day in the current month
    //  2.  A click on a day in the previous month
    //  3.  A click on a day in the next month

    if (IsValidCurMonthElement(current)) {
    	
        clickedDay = current.innerText;
	        
        // make sure the click didn't occur on the day that was already selected
        // this feature can be integrated in a version that has OK Cancel buttons
	   // if(clickedDay != mDate.getDate()) {
	        var newDate;	        
            mPreviousElement.style.backgroundColor = mStandardBackgroundColor;
    	    current.style.color = mStandardForeColor;
    	    current.style.backgroundColor = mSelectBackgroundColor;
            
    	    mPreviousElement = current;
        
           mDate.setDate(clickedDay);
           newDate = CustomizeDate(mDate.getDate(),mDate.getMonth(),mDate.getYear());
           if (InScriptlet) {window.external.RaiseEvent("OnChange", window.event); }            
           
	        try{inputObj.value = newDate ;}
	        catch(e){}
	        HideCal();
	        
	   // }

    } else if(IsValidPrevMonthElement(current)) {
	    
        // the click occurred in the previous month, so back up a month and
        // refresh the calendar

	    iYear = mDate.getYear();
	    
	    iNewMonth = mDate.getMonth() - 1;
        if(iNewMonth < 0) {
	        iNewMonth = 11;
	        iYear -= 1;
	    }	
 	    
        // HACK: the innerText has weird chars at the end, 
        // so let the date object parse it and pass to InitCalendar()
	    mDate.setMonth(iNewMonth);
	    mDate.setDate(current.innerText);
        InitCalendar(iNewMonth, mDate.getDate(), iYear);

	if (InScriptlet) {
          window.external.RaiseEvent("OnChange", window.event);
    	  window.external.RaiseEvent("NewMonth", window.event);
	}
    
        // if the new current month is December, then it's the
        // previous year
        if(iNewMonth == 11) {
	        if (InScriptlet) { window.external.RaiseEvent("NewYear", window.event); }
        }
    
    } else if(IsValidNextMonthElement(current)) {
	    
        // the click occurred in the following month, so move forward a month and
        // refresh the calendar

        iNewMonth = mDate.getMonth() + 1;
	    iYear = mDate.getYear();
	    if(iNewMonth > 11) {
	        iNewMonth = 0;
	        iYear += 1;
	    }

	    mDate.setMonth(iNewMonth);
	    mDate.setDate(current.innerText);
	    InitCalendar(iNewMonth, mDate.getDate(), iYear);
	    
            if (InScriptlet) {
	      window.external.RaiseEvent("OnChange", window.event);
	      window.external.RaiseEvent("NewMonth", window.event);
	    }

        // if the new current month is January, then it's a new year
        if(iNewMonth == 0) {
	   if (InScriptlet) {  window.external.RaiseEvent("NewYear", window.event); }
        }
    }

    // bubble the click event so the container can catch it
    if (InScriptlet) { window.external.bubbleEvent(); }
}

function mseOver() {
    el = window.event.srcElement;
    if(IsValidCurMonthElement(el) && el != mPreviousElement) {
	    el.style.color = mHighlightColor;
    }
}


function mseOut() {
    el = window.event.srcElement;
    if(IsValidCurMonthElement(el)) {
	    el.style.color = mStandardForeColor;
    }
}



function IsValidNextMonthElement(el) {
    retVal = false;
    if(el.id.substring(0, 4) == "Cell") {
	    iCol = el.id.substring(4, 5);
	    iRow = el.id.substring(5, 6);

	    if(iRow > mLastOfMonthRow || iRow == mLastOfMonthRow && iCol > mLastOfMonthCol)
	        retVal = true;
    }

    return retVal;
}

function IsValidPrevMonthElement(el) {
    retVal = false;
    
    // make sure it's one of the day elements
    if(el.id.substring(0, 4) == "Cell") {
	    iCol = el.id.substring(4, 5);
	    iRow = el.id.substring(5, 6);
	    
	    if(iRow == 0)
	        if(mFirstOfMonthCol == 0 || iCol < mFirstOfMonthCol)
		        retVal = true;
    }
    
    return retVal;
}


function IsValidCurMonthElement(el) {
    retVal = false;
    
    // make sure it's one of the day elements...
    if(el.id.substring(0, 4) == "Cell") {
	    iCol = el.id.substring(4, 5);
	    iRow = el.id.substring(5, 6);
    	    
	    if(iRow == 0) {
	        if(0 < mFirstOfMonthCol && mFirstOfMonthCol <= iCol)
		        retVal = true;
            } else if (iRow < mLastOfMonthRow || (iRow == mLastOfMonthRow && iCol <= mLastOfMonthCol))
		        retVal = true;
 
    }
    
    return retVal;
}

function DrawCalendar(){
 document.write("<Div id=Calendar Style='visibility: hidden; position: absolute; z-index: 1'>")
 document.write("<table onclick=CalendarClick() onmouseover=mseOver() onmouseout=mseOut() border=1 cellpadding=0 cellspacing=0 Style='cursor: hand; border: 3 solid #000080'>")
 document.write("<tr>")
 document.write("<td bgcolor=#C0C0C0 bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0 align=center colspan=3 nowrap style='font-family: Tahoma; font-size: 8pt; color: #FF0000; font-weight: bold'><span id=CurMonth>month and</span> <span id=CurYear>year</span></td>")
 document.write("<td bgcolor=#C0C0C0 bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0 align=center colspan=2 nowrap style='font-family: Tahoma; font-size: 8pt; color: #FF0000; font-weight: bold'>")
 document.write("<select id=selMonth name=selMonth onchange=monthChange() size=1 style='font-family: Tahoma; font-size: 10pt'>")
 document.write("<option value=0>January</option>")
 document.write("<option value=1>February</option>")
 document.write("<option value=2>March</option>")
 document.write("<option value=3>April</option>")
 document.write("<option value=4>May</option>")
 document.write("<option value=5>June</option>")
 document.write("<option value=6>July</option>")
 document.write("<option value=7>August</option>")
 document.write("<option value=8>September</option>")
 document.write("<option value=9>October</option>")
 document.write("<option value=10>November</option>")
 document.write("<option value=11>December</option>")
 document.write("</select></td>")
 document.write("<td align=center colspan=2 nowrap style='font-family: Tahoma; font-size: 8pt; color: #FF0000; font-weight: bold'>")
 document.write("<select id=selYear name=selYear onchange=selYearChange() size=1 style='font-family: Tahoma; font-size: 10pt'>")
	for (loop=1; loop < 101; loop++){
    		var theSum = 2000 + loop;
    		document.write("<option value="+theSum+">"+theSum+"</option>")
    		 }     
 document.write("</select> </td>")
 document.write("</tr>")
 document.write("<tr id=DayHeaders>")
 document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Sun</td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Mon </td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Tue </td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Wed </td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Thur </td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Fri </td>")
     document.write("<td align=center bgcolor=#C0C0C0 nowrap style='font-family: Tahoma; font-size: 8pt; color: #000080; font-weight: bold' bordercolor=#C0C0C0 bordercolorlight=#C0C0C0 bordercolordark=#C0C0C0>Sat </td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell00 align=center id=r1c1 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell10 align=center id=r1c2 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell20 align=center id=r1c3 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell30 align=center id=r1c4 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell40 align=center id=r1c5 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell50 align=center id=r1c6 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell60 align=center id=r1c7 bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell01 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell11 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell21 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell31 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell41 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell51 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell61 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell02 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell12 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'\>&nbsp;</td>")
     document.write("<td id=Cell22 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell32 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell42 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell52 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell62 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell03 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell13 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell23 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell33 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell43 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell53 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell63 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell04 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell14 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell24 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell34 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell44 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell54 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell64 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
   document.write("<tr>")
     document.write("<td id=Cell05 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell15 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell25 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell35 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell45 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell55 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
     document.write("<td id=Cell65 align=center bgcolor=#FFFFFF bordercolor=#FFFFFF bordercolorlight=#FFFFFF bordercolordark=#FFFFFF nowrap style='font-family: Tahoma; font-size: 10pt'>&nbsp;</td>")
   document.write("</tr>")
document.write("</table></Div>")
}

function launchCalendar(Obj){
if (document.all.item("Calendar").style.visibility=="hidden" && inputObj==Obj){
		document.all.item("Calendar").style.pixelLeft = window.event.clientX;
		document.all.item("Calendar").style.pixelTop = window.event.clientY;	 		
		InitDate();
		document.all.item("Calendar").style.visibility = "";
		inputObj = Obj;
		document.all.item("selMonth").focus();
	}
else if (inputObj!==Obj){
		HideCal();
		document.all.item("Calendar").style.left = window.event.clientX;
		document.all.item("Calendar").style.top = window.event.clientY;	 
		InitDate();
		document.all.item("Calendar").style.visibility = "";
		inputObj = Obj;
		document.all.item("selMonth").focus();
	}
else HideCal();
 }

function HideCal(){
	document.all.item("Calendar").style.visibility = "hidden"
	//move the calendar to the top of the page
	document.all.item("Calendar").style.left = 1
	document.all.item("Calendar").style.top = 1
} 
function InitDate(){
	today = new Date();
	InitCalendar(today.getMonth(), today.getDate(), today.getYear());
}
function CustomizeDate(day,mo,yr){
	var fDay = new String(day);
	var fMo = new String(mo + 1);
	var fYr = new String(yr);
	
	if (fDay.length==1) fDay = "0" + fDay;
	if (fMo.length==1) fMo = "0" + fMo ;
	if (fYr.length!==4 && fYr.length==2) fYr = "20" + fYr ;
	
	return(fDay + "-" + getStringMonth(fMo-1) + "-" + fYr) ;

}

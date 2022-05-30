var selectedMonth;
var selectedDate;
var calendarM;
var calendarDH;
var calendarD;
var dayData;

var months = ["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"]

var id;
var anon;
var freeCallback;
var busyCallback;

function calendar_Init(userid,isAnon,free,busy)
{
    id=userid;
    anon=isAnon;
    freeCallback=free;
    busyCallback=busy;
    selectedDate=new Date();
    selectedDate=new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
    selectedMonth=selectedDate.getMonth();
    selectedYear=selectedDate.getFullYear();
    calendarM = document.getElementById("calendarMonth");
    calendarDH = document.getElementById("calendarDayHeader");
    calendarD = document.getElementById("calendarDay");
    calendarM.addEventListener("mousedown",onClick_calendarM);
    calendarD.addEventListener("mousedown",onClick_calendarD);
    drawCalendar();
}

function drawCalendar(redraw,data)
{
    mHeader().innerHTML=months[selectedMonth]+" "+selectedYear+".";
    dHeader().innerHTML=selectedDate.getDate()+"/"+(selectedDate.getMonth()+1)+"/"+selectedDate.getFullYear();
    var d = moveToPrevMonday(new Date(selectedYear,selectedMonth));
    for (var w = 0; w < 6; w++) 
    {
        for (var dow = 0; dow < 7; dow++) 
        {
            dateF(w,dow).innerHTML=d.getDate();
            if(d.getMonth()!=selectedMonth)
            {
                dateF(w,dow).classList.add("text-black-25");
            }
            else
            {
                dateF(w,dow).classList.remove("text-black-25");
            }
            if(d.getTime()==selectedDate.getTime())
            {
                dateF(w,dow).classList.add("inside-border-info-4");
            }
            else
            {
                dateF(w,dow).classList.remove("inside-border-info-4");
            }
            if(redraw)
            {
                dateF(w,dow).style["background-color"]="rgba(var(--bs-danger-rgb),"+data[dow+w*7]+")";
            }
            else
            {
                dateF(w,dow).style["background-color"]=null;
            }
            d.setDate(d.getDate()+1);    
        }
    }
    if(!redraw)
    {
        var xhr = new XMLHttpRequest();
        var url = new URL(window.location.href).origin+"/AJAXGetCalendarData/?id="+id+"&date="+(moveToPrevMonday(new Date(selectedYear,selectedMonth)).getTime()/1000);
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                drawCalendar(true,JSON.parse(this.responseText));
            }
        }
        xhr.send();
        var xhr = new XMLHttpRequest();
        var url = new URL(window.location.href).origin+"/AJAXGetDayData/?id="+id+"&date="+(selectedDate.getTime()/1000);
        if(anon)
        {
            url+="&anon=1";
        }
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                drawDay(JSON.parse(this.responseText));
            }
        }
        xhr.send();
    }
}

function drawDay(data)
{
    dayData=data;
    for (var term = 0; term < 24; term++) 
        {
            if(data[term]!=null)
            {
                dayF(term).style["background-color"]="rgba(var(--bs-danger-rgb),1)";
                dayF(term).classList.remove("px-5");
                if(data[term]!=0)
                {
                    dayF(term).innerHTML=data[term].substring(data[term].indexOf('%')+1);
                }
                else
                {
                    dayF(term).classList.add("btdn");
                }
            }
            else
            {
                dayF(term).style["background-color"]=null;
                dayF(term).classList.add("px-5");
                dayF(term).classList.remove("btdn");
                dayF(term).innerHTML="";
            }
        }
}

function mHeader()
{
    return calendarM.children[0].children[0].children[1];
}

function dHeader()
{
    return calendarDH.children[0].children[0].children[0];
}

function dateF(w,dow)
{
    return calendarM.children[1].children[w].children[dow];
}

function dayF(t)
{
    return calendarD.children[0].children[t].children[1];
}

function moveToPrevMonday(date)
{
    date.setDate(date.getDate()-(date.getDay()==0?6:date.getDay()-1));
    return date;
}

function onClick_calendarM(e)
{
    var elem=e.target;
    if(elem.classList.contains("bi-caret-left-fill") || elem.innerHTML=="<i class=\"bi bi-caret-left-fill\"></i>")
    {
        selectedMonth=selectedMonth-1;
    }
    else if(elem.classList.contains("bi-caret-right-fill") || elem.innerHTML=="<i class=\"bi bi-caret-right-fill\"></i>")
    {
        selectedMonth=selectedMonth+1;
    }
    else if(!isNaN(elem.innerHTML))
    {
        if(elem.classList.contains("text-black-25") && elem.innerHTML > 20)
        {
            selectedDate=new Date(selectedYear,selectedMonth-1,elem.innerHTML);
        }
        else if(elem.classList.contains("text-black-25") && elem.innerHTML < 20)
        {
            selectedDate=new Date(selectedYear,selectedMonth+1,elem.innerHTML);
        }
        else 
        {
            selectedDate=new Date(selectedYear,selectedMonth,elem.innerHTML);
        }
    }
    else
    {
        selectedDate=new Date();
        selectedDate=new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
        selectedMonth=selectedDate.getMonth();
        selectedYear=selectedDate.getFullYear();
    }
    var d = new Date(selectedYear,selectedMonth);
    selectedMonth=d.getMonth();
    selectedYear=d.getFullYear();
    drawCalendar();
}

function onClick_calendarD(e)
{
    var elem=e.target;
    if(elem.attributes['index']==undefined){return;}
    var index = parseInt(elem.attributes['index'].value);
    var field = dayData[index];
    if(field==null)
    {
        //free field
        var fieldTimestamp=new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate(),8+parseInt(index/2),30*(index%2)).getTime()/1000;
        var maxLen=0;
        while(dayData[index]==null && index<24)
        {
            index++;
            maxLen+=30;
        }
        if(freeCallback!=null && freeCallback!=undefined)
        {
            freeCallback(fieldTimestamp,maxLen);
        }
    }
    else 
    {
        while(dayData[index]==0 && index>=0)
        {
            index--;
        }
        if(index>=0 && dayData[index]!=null && busyCallback!=null && busyCallback!=undefined)
        {
            busyCallback(dayData[index].substring(0,dayData[index].indexOf('%')));
        }
    }
}
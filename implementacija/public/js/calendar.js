/**
  * @author Lazar Premović  2019/0091
  */

var selectedMonth;  //Izabrani mesec
var selectedDate;   //Izabrani dan
var calendarM;      //Element koji predstavlja kalendar
var calendarDH;     //Element koji predstavlja zaglavlje devnog prikaza
var calendarD;      //Element koji predstavlja sadrzaj dnevnog prikaza
var dayData;        //Podaci o trenutno izabranom danu

var months = ["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"];    //Imena meseci

var id;             //Identifikator korisnika ciji se kalendar prikazuje
var anon;           //Da li se prikazuju detalji o terminima
var freeCallback;   //Funkcija koja se poziva pri kliku na slobodno polje u dnevnom kalendaru
var busyCallback;   //Funkcija koja se poziva pri kliku na zauzeto polje u dnevnom kalendaru

/**
 * Inicijalizacija kalendara
 * @param {Number} userid   id korisnika ciji se kalendar prikazuje
 * @param {Boolean} isAnon  da li se prikazuju detalji o terminima
 * @param {Function} free   funkcija koja se poziva pri kliku na slobodno polje u dnevnom kalendaru
 * @param {Function} busy   funkcija koja se poziva pri kliku na zauzeto polje u dnevnom kalendaru
 */
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

/**
 * Iscrtavanje kalendara
 * @param {Boolean} redraw  da li je u pitanju ponovni poziv iz callback-a AJAX zahteva 
 * @param {Number[]} data   zauzetost pruzaoca na dnevnom nivou 0-1
 */
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

/**
 * Iscrtavanje dnevnog kalendara
 * @param {String[]} data   zauzetost pruzaoca na sa granularnoscu od 30 min, null oznacava slododan termin, 
 * 0 oznacava zauzet termin bes opisa ili produzetak zauzetog termina, tekst sadrzi opis zauzetog termina
 */
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

/**
 * Dohvata zaglavlje mesecnog kalendara
 * @returns Element
 */
function mHeader()
{
    return calendarM.children[0].children[0].children[1];
}

/**
 * Dohvata zaglavlje dnevnog kalendara
 * @returns Element
 */
function dHeader()
{
    return calendarDH.children[0].children[0].children[0];
}

/**
 * Dohvata polje mesecnog kalendara
 * @param {Number} w    nedelja u mesecu
 * @param {Number} dow  dan u nedelji
 * @returns Element
 */
function dateF(w,dow)
{
    return calendarM.children[1].children[w].children[dow];
}

/**
 * Dohvata polje dnevnog kalendara
 * @param {Number} t    indeks polja 
 * @returns Element
 */
function dayF(t)
{
    return calendarD.children[0].children[t].children[1];
}

/**
 * Pomera datum na prethodni ponedeljak
 * @param {Date} date  datum
 * @returns Date
 */
function moveToPrevMonday(date)
{
    date.setDate(date.getDate()-(date.getDay()==0?6:date.getDay()-1));
    return date;
}

/**
 * Funkcija koja obradjuje klik na polje mesecnog kalendara
 * @param {Event} e informacije o dogadjaju 
 */
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

/**
 * Funkcija koja obradjuje klik na polje dnevnog kalendara
 * @param {Event} e informacije o dogadjaju 
 */
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
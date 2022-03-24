var ls = window.localStorage

/*We can initialize local storage here if we need to*/
function initLocalStorage()
{
    if(ls.notifications === undefined) {ls.notifications=JSON.stringify({lazar:4});}
    if(ls.users === undefined) {ls.users=JSON.stringify({lazar: "qwerty"});}
}
initLocalStorage();


function isUserLoggedIn()
{
    return ls.user !== undefined;
}

function getNumberOfNotifications() 
{
    var map = JSON.parse(ls.notifications);
    var num = map[ls.user];
    if (num === undefined) {return 0;}
    return num;
}

function login(username,password)
{
    if(JSON.parse(ls.users)[username]===password)
    {
        ls.user=username;
        return true;
    }
    return false;
}

function updateView() 
{
    console.log("UpdateView");
    /*TODO*/
}

function clearNotifications() 
{
    var map = JSON.parse(ls.notifications);
    map[ls.user]=0;
    ls.notifications=JSON.stringify(map);
}

function header_Init(redirect)
{
    if (isUserLoggedIn()) 
    {
        document.getElementById("loginButtons").style.display="none";
        var numnot = getNumberOfNotifications();
        if(numnot === 0)
        {
            document.getElementById("hasNotifications").style.display="none";
        }
        else
        {
            document.getElementById("numberOfNotifications").innerHTML=numnot;
        }
    }
    else 
    {
        if(redirect){window.location.href = "index.html";}
        document.getElementById("userProfile").style.display="none";
    }
}

function onClick_Login()
{
    var uname = document.getElementById("username").value;
    var pass = document.getElementById("password").value;
    if(login(uname,pass)) {location.reload();}
    else 
    {
        document.getElementById("password").value="";
        document.getElementById("wrongPassword").style.display="block"; 
    }
}

function onClick_Logout()
{
    ls.removeItem("user");
}

function onClick_Category(e)
{
    document.getElementsByClassName("active")[0].classList.remove("active");
    e.classList.add("active");
    updateView();
}

function onChange_DateFilter() 
{
    if(document.getElementById("dateFilter").checked)
    {
        document.getElementById("timerSelector").style.display="block";
    }
    else
    {
        document.getElementById("timerSelector").style.display="none";
    }
}


function onClick_DateFilter() 
{
    var stimeFrom = document.getElementById("timeFrom").value;
    var stimerTo = document.getElementById("timeTo").value;
    var sdateFrom = document.getElementById("dateFrom").value;
    var sdateTo = document.getElementById("dateTo").value;
    if(stimeFrom == '' || stimerTo == '' || sdateFrom == '' || sdateTo == '')
    {
        alert("Vremenski period nije validan");
        return;
    }
    updateView();
}

function onChange_SortSelect()
{
    if(document.getElementById("sortSelect").value=="Oceni")
    {
        document.getElementById("locationPicker").style.display="none";
    }
    else
    {
        document.getElementById("locationPicker").style.display="block";
    }
    updateView();
}

function locationChangedCallback(currentLocation, radius, isMarkerDropped) {
    console.log("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
    updateView();
}

function onClick_Card(e)
{
    console.log(e.getElementsByClassName("card-title")[0].innerHTML);
}

function SetStars(elem, stars)
{
    var elems =elem.children;
    var clickedStars =0;
    clickedStars=elem.classList.contains("1stars")?1:clickedStars;
    clickedStars=elem.classList.contains("2stars")?2:clickedStars;
    clickedStars=elem.classList.contains("3stars")?3:clickedStars;
    clickedStars=elem.classList.contains("4stars")?4:clickedStars;
    clickedStars=elem.classList.contains("5stars")?5:clickedStars;
    for (let index = 0; index < elems.length; index++) {
        if(stars>index || clickedStars>index)
        {
            elems[index].classList.remove("bi-star");
            elems[index].classList.add("bi-star-fill");
        }
        else
        {
            elems[index].classList.remove("bi-star-fill");
            elems[index].classList.add("bi-star");
        }
    }
}

function onMouseOver_StarSelector(e) 
{
    var stars=Math.min(Math.floor(e.offsetX/20)+1,5);
    SetStars(e.currentTarget, stars);
}

function onMouseOut_StarSelector(e) 
{
    SetStars(e.currentTarget, 0);
}

function onClick_StarSelector(e)
{
    var stars=Math.min(Math.floor(e.offsetX/20)+1,5);
    e.currentTarget.classList.remove(1+"stars");
    e.currentTarget.classList.remove(2+"stars");
    e.currentTarget.classList.remove(3+"stars");
    e.currentTarget.classList.remove(4+"stars");
    e.currentTarget.classList.remove(5+"stars");
    e.currentTarget.classList.add(stars+"stars");
    SetStars(e.currentTarget,0);
}

function dashboard_Init()
{
    var elems =document.getElementsByClassName("stars");
    for (let index = 0; index < elems.length; index++) {
        elems[index].addEventListener("mouseover",onMouseOver_StarSelector);
        elems[index].addEventListener("mouseout",onMouseOut_StarSelector);
        elems[index].addEventListener("click",onClick_StarSelector);
    }
}

function onClick_Remove(e)
{
    if(confirm("Da li zaista želite da uklonite ovu uslugu bez davanja recenzije?"))
    {
        e.parentElement.parentElement.setAttribute('style', 'display:none !important');
    }
}

function onClick_Post(e)
{
    if(e.parentElement.parentElement.getElementsByClassName("bi-star-fill").length>0)
    {
        e.parentElement.parentElement.setAttribute('style', 'display:none !important');
    }
    else
    {
        alert("Morate izabrati ocenu");
    }
}
//Autor: Lazar Premovic

var ls = window.localStorage

/*We can initialize local storage here if we need to*/
function initLocalStorage()
{
    if(ls.users === undefined) 
    {
        ls.users="{}"; 
        register("lazar","qwerty","user");
        register("pruzalac","asdf","provider");
        ls.user="lazar";
        addOpenReview("Misko","24.03.2022.");
        addOpenReview("Zaki","25.03.2022.");
        addOpenReview("Goran","22.03.2022.");
        addOpenReview("Mika","20.03.2022.");
        addRequest();
        addRequest();
        addRequest();
        ls.removeItem("user");
        ls.user="pruzalac";
        addRequest();
        addRequest();
        addRequest();
        ls.removeItem("user");
    }
    if(ls.providers === undefined)
    {
        ls.providers="[]";
        registerProvider("res/placeholder-avatar1.jpg","Mika","Vodoinstalater",-35.72737,-28.24771,"Najbolji vodoinstalater u gradu.",3.5);
        registerProvider("res/placeholder-avatar2.jpg","Pera","Moler",-54.90286,-36.04843,"Najbolji moler u gradu.",3.74);
        registerProvider("res/placeholder-avatar3.jpg","Zoki","Električar",15.22731,178.89166,"Najbolji električar u gradu",3.76);
        registerProvider("res/placeholder-avatar4.jpg","Misko","Električar",30.03265,-109.30222,"Pomoćnik najboljeg električara u gradu.",4.95);
        registerProvider("res/placeholder-avatar1.jpg","Luka","Moler",-39.71393,-46.41141,"Jedno sam sebi okrečio sobu.",2.5);
        registerProvider("res/placeholder-avatar2.jpg","Zika","Vodoinstalater",2.80141,-1.42037,"Umem da otpusim wc šolju.",1);
        registerProvider("res/placeholder-avatar3.jpg","Marko","Bravar",18.65762,114.69945,"Najbolji bravar u gradu.",4.3);
        registerProvider("res/placeholder-avatar4.jpg","Slavko","Električar",2.51104,33.50887,"Ne sinko, ne radi se to tako.",2.8);
        registerProvider("res/placeholder-avatar1.jpg","Mirko","Bravar",-17.59432,75.06050,"Ni tile mi nije ravan.",4.8);
        registerProvider("res/placeholder-avatar2.jpg","Zaki","Bravar",22.47551,-161.46380,"DOBAR DAN!",5);
        registerProvider("res/placeholder-avatar3.jpg","Ivan","Vodoinstalater",-16.44006,-159.08063,"A jel ste možda čuli za EESTEC?",3.8);
        registerProvider("res/placeholder-avatar4.jpg","Goran","Moler",7.33367,-83.11552,"Nemoj Gorane, ici ces u zatvor.",4.3);
        registerProvider("https://images.sk-static.com/images/media/profile_images/artists/1056026/huge_avatar","Alestorm","PirateMetal",0,0,"THE SECOND GREATEST PIRATE METAL BAND IN THE ENTIRE WORLD!",5);
        addFreeSlot("Zaki","2022-03-25","10:00","14:00");
        addFreeSlot("Zaki","2022-03-29","10:00","12:00");
        addFreeSlot("Slavko","2022-03-30","15:00","17:00");
        addFreeSlot("Slavko","2022-03-30","18:00","22:00");
        addFreeSlot("Goran","2022-03-29","08:00","12:00");
        addFreeSlot("Goran","2022-03-29","14:00","14:30");
    }
    if(ls.searchParams === undefined)
    {
        ls.searchParams="{}";
        setSearchParam("category","Sve");
        setSearchParam("timeEnabled",false);
        setSearchParam("timeFrom","");
        setSearchParam("timeTo","");
        setSearchParam("dateFrom","");
        setSearchParam("dateTo","");
        setSearchParam("sort","Oceni");
        setSearchParam("lat",44.816667);
        setSearchParam("lon",20.466667);
    }
}

function register(username,password,type)
{
    var users = JSON.parse(ls.users);
    users[username]={};
    users[username]["password"]=password;
    users[username]["type"]=type;
    ls.users=JSON.stringify(users);
}

function login(username,password)
{
    if(JSON.parse(ls.users)[username]===undefined)
    {
        return false;
    }
    if(JSON.parse(ls.users)[username]["password"]===password)
    {
        ls.user=username;
        return true;
    }
    return false;
}

function isUserLoggedIn()
{
    return ls.user !== undefined;
}

function isGetUserType()
{
    var users = JSON.parse(ls.users);
    return users[ls.user]["type"];
}

function addOpenReview(provider,date)
{
    var users = JSON.parse(ls.users);
    if(users[ls.user]["reviews"]===undefined)
    {
        users[ls.user]["reviews"]=[];
    }
    users[ls.user]["reviews"].push({provider:provider,date:date});
    ls.users=JSON.stringify(users);
    addNotification();
}

function removeReview(provider)
{
    var users = JSON.parse(ls.users);
    var reviews = users[ls.user]["reviews"];
    var remaining=[];
    for (let i = 0; i < reviews.length; i++) {
        if(reviews[i].provider!=provider)
        {
            remaining.push(reviews[i]);
        }
    }
    users[ls.user]["reviews"]=remaining;
    ls.users=JSON.stringify(users);
}

function addNotification()
{
    var users = JSON.parse(ls.users);
    if(users[ls.user]["notifications"]===undefined)
    {
        users[ls.user]["notifications"]=0;
    }
    users[ls.user]["notifications"]++;
    ls.users=JSON.stringify(users);
}

function clearNotifications() 
{
    if(ls.user==undefined){return;}
    var users = JSON.parse(ls.users);
    users[ls.user]["notifications"]=0;
    ls.users=JSON.stringify(users);
}

function getNumberOfNotifications() 
{
    if(ls.user===undefined){return 0;}
    var users = JSON.parse(ls.users);
    if(users[ls.user]["notifications"]===undefined){return 0;}
    return users[ls.user]["notifications"];
}

function addRequest()
{
    var users = JSON.parse(ls.users);
    if(users[ls.user]["requests"]===undefined)
    {
        users[ls.user]["requests"]=0;
    }
    users[ls.user]["requests"]++;
    ls.users=JSON.stringify(users);
}

function clearRequests() 
{
    if(ls.user==undefined){return;}
    var users = JSON.parse(ls.users);
    users[ls.user]["requests"]=0;
    ls.users=JSON.stringify(users);
}

function getNumberOfRequests() 
{
    if(ls.user===undefined){return 0;}
    var users = JSON.parse(ls.users);
    if(users[ls.user]["requests"]===undefined){return 0;}
    return users[ls.user]["requests"];
}

function registerProvider(pathToPicture,name,category,lat,lon,description,rating)
{
    var providers = JSON.parse(ls.providers);
    var provider={};
    provider["name"]=name;
    provider["pathToPicture"]=pathToPicture;
    provider["category"]=category;
    provider["lat"]=lat;
    provider["lon"]=lon;
    provider["description"]=description;
    provider["rating"]=rating;
    provider["freeSlots"]=[];
    providers.push(provider);
    ls.providers=JSON.stringify(providers);
}

function addFreeSlot(provider,date,timeFrom,timeTo)
{
    var providers = JSON.parse(ls.providers);
    for (let i = 0; i < providers.length; i++) {
        if(providers[i].name==provider)
        {
            providers[i]["freeSlots"].push({date:date,timeFrom:timeFrom,timeTo:timeTo});
        }
    }
    ls.providers=JSON.stringify(providers);
}

function getSearchParam(key)
{
    var searchParams = JSON.parse(ls.searchParams);
    return searchParams[key];
}

function setSearchParam(key,value)
{
    var searchParams = JSON.parse(ls.searchParams);
    searchParams[key]=value;
    ls.searchParams=JSON.stringify(searchParams);
}

function distance(lat1,lon1,lat2,lon2)
{
    // Shamelessly stolen of the internet :)
    const R = 6371e3; // metres
    const φ1 = lat1 * Math.PI/180; // φ, λ in radians
    const φ2 = lat2 * Math.PI/180;
    const Δφ = (lat2-lat1) * Math.PI/180;
    const Δλ = (lon2-lon1) * Math.PI/180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
          Math.cos(φ1) * Math.cos(φ2) *
          Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    const d = R * c; // in metres
    return Math.round((d + Number.EPSILON) * 100) / 100;
}

function updateView() 
{
    var containter = document.getElementById("cardContainer");
    var res1 = JSON.parse(ls.providers);
    var res = [];
    for (let i = 0; i < res1.length; i++) {
        if(getSearchParam("category")=="Sve" || getSearchParam("category")==res1[i].category)
        {
            if(getSearchParam("timeEnabled"))
            {
                if(checkAvailable(res1[i].freeSlots))
                {
                    res.push(res1[i]);    
                }
            }
            else
            {
                res.push(res1[i]);
            }
        }
    }

    if(getSearchParam("sort")=="Oceni")
    {
        res.sort(sortOcena);
    }
    else
    {
        res.sort(sortUdaljenost);
    }
    containter.innerHTML="";
    if(res.length==0)
    {
        containter.innerHTML=`<h5 class="text-light">Ne postoji nijedan ponuđač koji ispunjava uslove pretrage, molimo proširite kriterijum pretrage.</h5>`;
    }
    for (let i = 0; i < res.length; i++) {
        var elem=`<div class="card w-20rem col-xs-auto m-3" onclick="onClick_Card(this)">
        <img src="${res[i].pathToPicture}" class="card-img-top"/>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title">${res[i].name}</h5>
            <h6 class="card-subtitle mb-2 text-muted">${res[i].category + (getSearchParam("sort")=="Udaljenosti"?" "+distance(res[i].lat,res[i].lon,getSearchParam("lat"),getSearchParam("lon"))+"km":"")}</h6>
            <p class="card-text">${res[i].description}</p>
            <p class="card-text mt-auto h-1d5rem">${stars(res[i].rating)}</p>
        </div>
    </div>`;
        containter.innerHTML+=elem;
    }
}

function sortOcena(x,y)
{
    if (x.rating < y.rating) 
    {
        return 1;
    }
    if (x.rating > y.rating) 
    {
        return -1;
    }
    return 0;
}

function sortUdaljenost(a,b)
{
    const dist1=distance(a.lat,a.lon,getSearchParam("lat"),getSearchParam("lon"));
    const dist2=distance(b.lat,b.lon,getSearchParam("lat"),getSearchParam("lon"));
    if (dist1 < dist1) 
    {
        return -1;
    }
    if (dist1 > dist2) 
    {
        return 1;
    }
    return sortOcena(a,b);
}

function checkAvailable(slots)
{
    var dateFrom = new Date(getSearchParam("dateFrom"));
    var dateTo = new Date(getSearchParam("dateTo"));
    var timeFrom = minutes(getSearchParam("timeFrom"));
    var timeTo = minutes(getSearchParam("timeTo"));
    for (let i = 0; i < slots.length; i++) {
        const element = new Date(slots[i].date);
        if(dateFrom <= element && element <= dateTo )
        {
            var availableFrom = minutes(slots[i].timeFrom);
            var availableTo = minutes(slots[i].timeTo);
            if(timeFrom < availableTo && availableFrom < timeTo)
            {
                return true;
            }
        }
    }
    return false;
}

function minutes(timestring)
{
    var spl = timestring.split(":");
    return (spl[0]*60)+(spl[1]*1);
}

function stars(rating)
{
    const pre=rating;
    const e=`<i class="bi bi-star"></i>`;
    const h=`<i class="bi bi-star-half"></i>`;
    const f=`<i class="bi bi-star-fill"></i>`;
    var res="";
    for (let i = 1; i <= 5; i++) {
        if(rating>=1)
        {
            res+=f;
            rating-=1;
        }
        else if(rating == 0)
        {
            res+=e;
        }
        else
        {
            rating=Math.round(rating*2);
            if(rating==2){res +=f;}
            if(rating==1){res +=h;}
            if(rating==0){res +=e;}
            rating=0;
        }
    }
    return pre +" "+ res;
}

function header_Init(redirect)
{
    if (isUserLoggedIn()) 
    {
        document.getElementById("loginButtons").style.display="none";
        var numnot = getNumberOfNotifications();
        var numreq = getNumberOfRequests();
        if(numnot+numreq === 0)
        {
            document.getElementById("hasNotifications").style.display="none";
        }
        else
        {
            if(numnot !== 0){document.getElementById("numberOfNotifications").innerHTML=numnot;}
            if(numreq !== 0){document.getElementById("numberOfRequests").innerHTML=numreq;}
        }
        if(isGetUserType()=="provider")
        {
            document.getElementById("editUser").style.display="none";
            document.getElementById("editProvider").style.display="block";
            document.getElementById("dropdownZahtevi").href="komandna-tabla-pruzalac.html";
        }
        else
        {
            document.getElementById("editUser").style.display="block";
            document.getElementById("editProvider").style.display="none";
            document.getElementById("dropdownZahtevi").href="kreiranje-zahteva-potvrda-korisnika.html";
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

function index_Init()
{
    var elems=document.getElementById("categories").children;
    for (let index = 0; index < elems.length; index++) {
        if(elems[index].children[0].innerHTML==getSearchParam("category"))
        {
            elems[index].children[0].classList.add("active");
        }
    }
    if(getSearchParam("timeEnabled"))
    {
        document.getElementById("dateFilter").checked=true;
        document.getElementById("timerSelector").style.display="block";
    }
    else
    {
        document.getElementById("dateFilter").checked=false;
        document.getElementById("timerSelector").style.display="none";
    }
    document.getElementById("timeFrom").value = getSearchParam("timeFrom");
    document.getElementById("timeTo").value = getSearchParam("timeTo");
    document.getElementById("dateFrom").value = getSearchParam("dateFrom");
    document.getElementById("dateTo").value = getSearchParam("dateTo");
    if(getSearchParam("sort")=="Oceni")
    {
        document.getElementById("locationPicker").style.display="none";
    }
    else
    {
        document.getElementById("locationPicker").style.display="block";
    }
    document.getElementById("sortSelect").value=getSearchParam("sort");
    updateView();
}

function onClick_Category(e)
{
    document.getElementsByClassName("active")[0].classList.remove("active");
    e.classList.add("active");
    setSearchParam("category",e.innerHTML);
    updateView();
}

function onChange_DateFilter() 
{
    if(document.getElementById("dateFilter").checked)
    {
        document.getElementById("timerSelector").style.display="block";
        setSearchParam("timeEnabled",true);
    }
    else
    {
        document.getElementById("timerSelector").style.display="none";
        setSearchParam("timeEnabled",false);
    }
    updateView();
}


function onClick_DateFilter() 
{
    var stimeFrom = document.getElementById("timeFrom").value;
    var stimeTo = document.getElementById("timeTo").value;
    var sdateFrom = document.getElementById("dateFrom").value;
    var sdateTo = document.getElementById("dateTo").value;
    if(stimeFrom == '' || stimeTo == '' || sdateFrom == '' || sdateTo == '')
    {
        alert("Vremenski period nije validan");
        return;
    }
    setSearchParam("timeFrom",stimeFrom);
    setSearchParam("timeTo",stimeTo);
    setSearchParam("dateFrom",sdateFrom);
    setSearchParam("dateTo",sdateTo);
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
    setSearchParam("sort",document.getElementById("sortSelect").value);
    updateView();
}

function locationChangedCallback(currentLocation, radius, isMarkerDropped) {
    setSearchParam("lat",currentLocation.latitude);
    setSearchParam("lon",currentLocation.longitude);
    updateView();
}

function onClick_Card(e)
{
    window.location.href="kontaktiranje-pruzaoca.html?name="+e.getElementsByClassName("card-title")[0].innerHTML;
}

function dashboard_Init()
{
    var container = document.getElementById("reviewContainer");
    var openRewiews = JSON.parse(ls.users)[ls.user]["reviews"];
    container.innerHTML="";
    for (let i = 0; i < openRewiews.length; i++) {
        var elem=`<form class="d-flex flex-column container justify-content-center bg-light rounded-3 px-4 my-5">
        <div class="row mt-4">
            <div class="col-auto">
                <div class="row">
                    <label  class="form-label fs-4 fw-bold">Pružalac usluge:</label>
                </div>
                <div class="row">
                    <label  class="form-label fs-5 fw-bold">Datum pružanja usluge:</label>
                </div>
            </div>
            <div class="col-auto">
                <div class="row">
                    <output class="fs-4 fw-bold mb-2 pruzalac" id="pruzalac">${openRewiews[i].provider}</output>
                </div>
                <div class="row">
                    <output class="fs-5 fw-bold mb-2 datum" id="datum">${openRewiews[i].date}</output>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-auto">
                <label  class="form-label fs-5 fw-bold">Ocena:</label>
            </div>
            <div class="col-auto">
                <p class="fs-5 fw-bold stars">
                    <i class="bi bi-star onestar"></i><i class="bi bi-star twostar"></i><i class="bi bi-star threestar"></i><i class="bi bi-star fourstar"></i><i class="bi bi-star fivestar"></i>
                </p>
            </div>
        </div>
        <textarea class="form-control mb-3" id="textarea" rows="5" placeholder="Komentar (opciono)"></textarea>
        <div class="d-flex mb-3 justify-content-end">
            <button type="button" class="btn btn-danger  mx-1" onclick="onClick_Remove(this)">Ukloni</button>
            <button type="button" class="btn btn-primary mx-1" onclick="onClick_Post(this)">Postavi</button>
        </div>
    </form>`;
        container.innerHTML+=elem;
    }
    var elems =document.getElementsByClassName("stars");
    for (let index = 0; index < elems.length; index++) {
        elems[index].addEventListener("mouseover",onMouseOver_StarSelector);
        elems[index].addEventListener("mouseout",onMouseOut_StarSelector);
        elems[index].addEventListener("click",onClick_StarSelector);
    }
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

function onClick_Remove(e)
{
    if(confirm("Da li zaista želite da uklonite ovu uslugu bez davanja recenzije?"))
    {
        e.parentElement.parentElement.setAttribute('style', 'display:none !important');
        removeReview(e.parentElement.parentElement.getElementsByClassName("pruzalac")[0].innerHTML);
    }
}

function onClick_Post(e)
{
    if(e.parentElement.parentElement.getElementsByClassName("bi-star-fill").length>0)
    {
        //e.parentElement.parentElement.setAttribute('style', 'display:none !important');
        removeReview(e.parentElement.parentElement.getElementsByClassName("pruzalac")[0].innerHTML);
        e.parentElement.parentElement.innerHTML='<p class="fw-bold fs-4 mt-3">Vaša recenzija je zabeležena, hvala na ovojenom vremenu. </p>';
    }
    else
    {
        alert("Morate izabrati ocenu");
    }
}

initLocalStorage();
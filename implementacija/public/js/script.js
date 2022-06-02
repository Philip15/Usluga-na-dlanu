/**
  * @author Lazar Premović  2019/0091
  * @author Filip Janjić    2019/0116
  */

//Yet again, shamelessly stolen from the internet
/**
 * Funkcija koja dohvata imenovani get parametar iz URL-a
 * @param {String} parameterName    naziv parametra
 * @returns 
 */
function findGetParameter(parameterName) 
{
    var result = null,
        tmp = [];
    location.search
        .substring(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
    return result;
}

/**
 * Inicijalizacija zaglavlja
 */
function header_Init()
{
    if(document.getElementById("wrongPassword")!=null)
    {
        document.getElementById("loginButton").click();
    }
}

/**
 * Inicijalizacija glavne stranice
 */
function mainPage_Init()
{
    window.searchParams={};
    setSearchParam("category",findGetParameter('cat'));
    setSearchParam("timeEnabled",findGetParameter('tTo')!=null);
    setSearchParam("timeFrom",findGetParameter('tFrom'));
    setSearchParam("timeTo",findGetParameter('tTo'));
    setSearchParam("dateFrom",findGetParameter('dFrom'));
    setSearchParam("dateTo",findGetParameter('dTo'));
    setSearchParam("sort",findGetParameter('sort'));
    setSearchParam("lat",findGetParameter('lat'));
    setSearchParam("lon",findGetParameter('lon'));

    if(getSearchParam("category")==null){setSearchParam("category",-1);}
    if(getSearchParam("timeFrom")==null){setSearchParam("timeFrom","");}
    if(getSearchParam("timeTo")==null){setSearchParam("timeTo","");}
    if(getSearchParam("dateFrom")==null){setSearchParam("dateFrom","");}
    if(getSearchParam("dateTo")==null){setSearchParam("dateTo","");}
    if(getSearchParam("sort")==null){setSearchParam("sort",0);}
    if(getSearchParam("lat")==null){setSearchParam("lat",44.816667);}
    if(getSearchParam("lon")==null){setSearchParam("lon",20.466667);}
    

    var elems=document.getElementById("categories").children;
    for (let index = 0; index < elems.length; index++) {
        if(elems[index].children[0].value==getSearchParam("category"))
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
    if(getSearchParam("sort")==0)
    {
        document.getElementById("locationPicker").style.display="none";
    }
    else
    {
        document.getElementById("locationPicker").style.display="block";
    }
    document.getElementById("sortSelect").value=getSearchParam("sort");
    
    $('#locationPicker').locationpicker({
        location: {
            latitude: getSearchParam("lat"),
            longitude: getSearchParam("lon")
        },
        radius: 0,
        onchanged: locationChangedCallback
    });

    getProviders(new URL(location.href).search);
}

/**
 * Dohvata parametar pretrage po nazivu
 * @param {String} key  naziv parametra 
 * @returns String
 */
function getSearchParam(key)
{
    return window.searchParams[key];
}

/**
 * Postavlja parametar pretrage po nazivu
 * @param {String} key      naziv parametra
 * @param {String} value    vrednost parametra
 */
function setSearchParam(key,value)
{
    window.searchParams[key]=value;
}

/**
 * Azuriranje glavne stranice
 */
function updateView()
{
    var url = new URL(location.href)
    var curr = url.pathname + url.search;

    var next = url.pathname+"?";

    if(getSearchParam("category")!=-1)
    {
        next+="cat="+getSearchParam("category")+"&";
    }
    if(getSearchParam("timeFrom")!="")
    {
        next+="tFrom="+getSearchParam("timeFrom")+"&";
    }
    if(getSearchParam("timeTo")!="")
    {
        next+="tTo="+getSearchParam("timeTo")+"&";
    }
    if(getSearchParam("dateFrom")!="")
    {
        next+="dFrom="+getSearchParam("dateFrom")+"&";
    }
    if(getSearchParam("dateTo")!="")
    {
        next+="dTo="+getSearchParam("dateTo")+"&";
    }
    if(getSearchParam("sort")!="")
    {
        next+="sort="+getSearchParam("sort")+"&";
    }
    if(getSearchParam("lat")!=44.816667)
    {
        next+="lat="+getSearchParam("lat")+"&";
    }
    if(getSearchParam("lon")!=20.466667)
    {
        next+="lon="+getSearchParam("lon")+"&";
    }
    next = next.substring(0,next.length-1);
    
    if(curr != next)
    {
        window.history.pushState(null, '', next);
        getProviders(next.substring(url.pathname.length));  
    }
}

/**
 * Dohvata pruzaoce usluga koji odgovaraju zadatim parametrima pretrage
 * @param {String} query    upit za pretragu 
 */
function getProviders(query) 
{
    var xhr = new XMLHttpRequest();
    var url = new URL(window.location.href).origin+"/AJAXGetProviders"+query;
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            window.providers=JSON.parse(this.responseText);
            displayProviders(window.providers);
        }
    }
    xhr.send();
}

/**
 * Prikazuje pruzaoce dobijene kao rezultat AJAX upita ka serveru
 * @param {Object[]} res    rezultat upita, pruzaoci
 */
function displayProviders(res)
{
    var containter = document.getElementById("cardContainer");
    containter.innerHTML="";
    if(res.length==0)
    {
        document.getElementById("providerNotFound").style.display="block";
        return;
    }
    else
    {
        document.getElementById("providerNotFound").style.display="none";
    }
    if(getSearchParam("sort")==0)
    {
        res.sort(sortOcena);
    }
    else
    {
        res.sort(sortUdaljenost);
    }
    var url = new URL(window.location.href).origin;
    for (let i = 0; i < res.length; i++) {
        var elem=
        `<div class="card w-20rem col-xs-auto m-3 position-relative">
            <a href="${url}/profile?id=${res[i].idKorisnika}"><span class="magic-link"></span></a>
            <img src="${res[i].profilnaSlika}" class="card-img-top rounded-circle mt-2 h-294px"/>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">${res[i].ime} ${res[i].prezime}</h5>
                <h6 class="card-subtitle mb-2 text-muted">${res[i].kategorija + (getSearchParam("sort")==1?" "+distance(res[i].lat,res[i].lon,getSearchParam("lat"),getSearchParam("lon"))+"km":"")}</h6>
                <p class="card-text">${res[i].opis}</p>
                <p class="card-text mt-auto h-1d5rem">${stars(res[i].ocena)}</p>
            </div>
        </div>`;
        containter.innerHTML+=elem;
    }
}

/**
 * Sortira pruzaoce po oceni
 * @param {Object} x    prvi pruzaoc 
 * @param {Object} y    drugi pruzaoc 
 * @returns int rezultat poredjenja
 */
function sortOcena(x,y)
{
    if (x.ocena < y.ocena) 
    {
        return 1;
    }
    if (x.ocena > y.ocena) 
    {
        return -1;
    }
    return 0;
}

/**
 * Sortira pruzaoce po udaljenost
 * @param {Object} a    prvi pruzaoc 
 * @param {Object} b    drugi pruzaoc 
 * @returns int rezultat poredjenja
 */
function sortUdaljenost(a,b)
{
    const dist1=distance(a.lat,a.lon,getSearchParam("lat"),getSearchParam("lon"));
    const dist2=distance(b.lat,b.lon,getSearchParam("lat"),getSearchParam("lon"));
    if (dist1 < dist2) 
    {
        return -1;
    }
    if (dist1 > dist2) 
    {
        return 1;
    }
    return sortOcena(a,b);
}

/**
 * Racuna udaljenost izmedju dve tacke na planeti
 * @param {Number} lat1     geografska sirina prve tacke
 * @param {Number} lon1     geografska duzina prve tacke
 * @param {Number} lat2     geografksa sirina druge tacke
 * @param {Number} lon2     geografska duzina druge tacke
 * @returns udaljenost u metrima
 */
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

/**
 * Generise prikaz ocene pruzaoca u zvezdicama
 * @param {Number} rating 
 * @returns string formiran html
 */
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
    return (Math.round(pre * 100) / 100) +" "+ res;
}

/**
 * Funkcija koja obradjuje klik na kategoriju usluga
 * @param {Event} e informacije o dogadjaju 
 */
function onClick_Category(e)
{
    document.getElementsByClassName("active")[0].classList.remove("active");
    e.classList.add("active");
    setSearchParam("category",e.value);
    updateView();
}

/**
 * Funkcija koja obradjuje promenu izbora o filtriranju po datumu
 */
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
        setSearchParam("timeFrom","");
        setSearchParam("timeTo","");
        setSearchParam("dateFrom","");
        setSearchParam("dateTo","");
        document.getElementById("timeFrom").value="";
        document.getElementById("timeTo").value="";
        document.getElementById("dateFrom").value="";
        document.getElementById("dateTo").value="";
    }
    updateView();
}

/**
 * Funkcija koja obradjuje klik dugme za filtriranje po datumu
 */
function onClick_DateFilter() 
{
    var stimeFrom = document.getElementById("timeFrom").value;
    var stimeTo = document.getElementById("timeTo").value;
    var sdateFrom = document.getElementById("dateFrom").value;
    var sdateTo = document.getElementById("dateTo").value;
    if(stimeFrom == '' || stimeTo == '' || sdateFrom == '' || sdateTo == '')
    {
        alert(document.getElementById("invalidTime").innerHTML);
        return;
    }
    setSearchParam("timeFrom",stimeFrom);
    setSearchParam("timeTo",stimeTo);
    setSearchParam("dateFrom",sdateFrom);
    setSearchParam("dateTo",sdateTo);
    updateView();
}

/**
 * Funkcija koja obradjuje promenu kriterujuma za sortiranje
 */
function onChange_SortSelect()
{
    if(document.getElementById("sortSelect").value==0)
    {
        document.getElementById("locationPicker").style.display="none";
        setSearchParam("lat",44.816667);
        setSearchParam("lon",20.466667);
    }
    else
    {
        document.getElementById("locationPicker").style.display="block";
    }
    setSearchParam("sort",document.getElementById("sortSelect").value);
    displayProviders(window.providers);
    updateView();
}

/**
 * Funkcija koja obradjuje promenu lokacije u odnosu na koju se izracunava udaljenost
 * @param {Object} currentLocation  odabrana lokacija 
 * @param {Number} radius 
 * @param {Boolean} isMarkerDropped 
 */
function locationChangedCallback(currentLocation, radius, isMarkerDropped) {
    setSearchParam("lat",currentLocation.latitude);
    setSearchParam("lon",currentLocation.longitude);
    displayProviders(window.providers);
    updateView();
}

/**
 * Inicijalizacija stranice sa recenzijama
 */
function reviews_Init()
{
    var elems =document.getElementsByClassName("stars");
    for (let index = 0; index < elems.length; index++) {
        elems[index].addEventListener("mouseover",onMouseOver_StarSelector);
        elems[index].addEventListener("mouseout",onMouseOut_StarSelector);
        elems[index].addEventListener("click",onClick_StarSelector);
    }
}

/**
 * Funkcija koja obradjuje pomeranje misa po zvezdicama za odabir ocene
 * @param {Event} e informacije o dogadjaju 
 */
function onMouseOver_StarSelector(e) 
{
    var stars=Math.min(Math.floor(e.offsetX/20)+1,5);
    SetStars(e.currentTarget, stars);
}

/**
 * Funkcija koja obradjuje napustanje zvezdica za odabir ocene od strane misa
 * @param {Event} e informacije o dogadjaju 
 */
function onMouseOut_StarSelector(e) 
{
    SetStars(e.currentTarget, 0);
}

/**
 * Funkcija koja obradjuje klik misa na zvezdicu za odabir ocene
 * @param {Event} e informacije o dogadjaju 
 */
function onClick_StarSelector(e)
{
    var stars=Math.min(Math.floor(e.offsetX/20)+1,5);
    e.currentTarget.getElementsByClassName('rating')[0].value=stars;
    SetStars(e.currentTarget,0);
}

/**
 * Funkcija koja azurira prikaz zvezdica za odabir ocene
 * @param {Element} elem    kontejner koji sadrzi zvezdice ciji prikaz treba azurirati 
 * @param {Number} stars    vrednost na koju treba azurirati prikaz
 */
function SetStars(elem, stars)
{
    var elems =elem.children;
    var clickedStars =elem.getElementsByClassName('rating')[0].value;
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

/**
 * Funkcija koja prkazuje poruku i ukoliko je odgovor pozitivan, preusmerava na odredjeni URL
 * @param {String} q    tekst poruke 
 * @param {String} l    URL na koji se preusmerava korisnik
 */
function onClick_Remove(q,l)
{
    if(confirm(q))
    {
        window.location.href=l;
    }
}

/**
 * Funkcija koja prikazuje formu za kreiranje novog zahteva i azurira njene podatke
 * @param {Number} startTime    veme pocetka termina
 * @param {Number} maxLen       maksimalno trajanje termina
 */
function newRequest(startTime, maxLen)
{
    var startDate=new Date(startTime*1000);
    document.getElementById("dateDisp").innerHTML=startDate.getDate()+"/"+(startDate.getMonth()+1)+"/"+startDate.getFullYear();
    document.getElementById("startTimeDisp").innerHTML=startDate.getHours()+":"+(startDate.getMinutes()+"").padStart(2,"0");
    document.getElementById("duration").value=30; 
    document.getElementById("duration").max=maxLen;
    document.getElementById("startTime").value=startTime;
    document.getElementById("newRequestModalButton").click();
}

/**
 * Funkcija koja prikazuje formu za kreiranje novog rezervisanog termina i azurira njene podatke
 * @param {Number} startTime    veme pocetka termina
 * @param {Number} maxLen       maksimalno trajanje termina
 */
function newReservedSlot(startTime, maxLen)
{
    var startDate=new Date(startTime*1000);
    document.getElementById("dateDisp").innerHTML=startDate.getDate()+"/"+(startDate.getMonth()+1)+"/"+startDate.getFullYear();
    document.getElementById("startTimeDisp").innerHTML=startDate.getHours()+":"+(startDate.getMinutes()+"").padStart(2,"0");
    document.getElementById("duration").value=30;
    document.getElementById("duration").max=maxLen;
    document.getElementById("startTime").value=startTime;
    document.getElementById("newReservedSlotModalButton").click();
}

/**
 * Funkcija koja prikazuje informacije o terminu slanjem AJAX zahteva serveru
 * @param {Number} slotId   id termina 
 */
function slotInfo(slotId)
{
    var xhr = new XMLHttpRequest();
    var url = new URL(window.location.href).origin+"/ProviderController/AJAXGetSlotInfo?slot="+slotId;
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            document.getElementById("requestInfoModalContent").innerHTML=this.responseText;
            document.getElementById("requestInfoModalButton").click();
        }
    }
    xhr.send();
}

/**
 *  Inicijalizacija stranice sa profilom pruzaoca 
 */
function profile_Init()
{
    if(document.getElementById("createError")!=null)
    {
        newRequest(document.getElementById('errorStartTime').value,document.getElementById('errorDuration').value)
    }
}

/**
 * Inicijalizacija stranice sa pregledom zahteva
 */
function request_Init()
{
    if(document.getElementById("priceError")!=null)
    {
        document.getElementById("newOfferModalButton").click();
    }
}

var origPic;    //Prvobitna profilna slika

/**
 * Inicijalizacija stranice za uredjivanje profila
 */
function editProfile_Init()
{
    origPic = document.getElementById("imgDiv").innerHTML;
    document.getElementById("changePictureApply").style.display="none";
    $('#locationPicker').locationpicker({
        location: {
            latitude: document.getElementById("latInput").value,
            longitude: document.getElementById("lonInput").value
        },
        radius: 0,
        onchanged: editProfileLocationChangedCallback
    });
}

/**
 * Funkcija koja obradjuje promenu lokacije na stranici za uredjivanje profila
 * @param {Object} currentLocation  odabrana lokacija 
 * @param {Number} radius 
 * @param {Boolean} isMarkerDropped 
 */
function editProfileLocationChangedCallback(currentLocation, radius, isMarkerDropped)
{
    document.getElementById("latInput").value=currentLocation.latitude;
    document.getElementById("lonInput").value=currentLocation.longitude;
}

/**
 * Funkcija koja obradjuje promenu izbora profilne slike
 */
function onChange_UploadPicture()
{
    var formData = new FormData();
    formData.append("profilePicture", document.getElementById("profilePicture").files[0]);
    var xhr = new XMLHttpRequest();
    var url = new URL(window.location.href).origin+"/UserController/AJAXpicturePreview";
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function () 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            if(this.responseText.startsWith("<"))
            {
                document.getElementById("imgDiv").innerHTML=this.responseText;
                document.getElementById("changePictureApply").style.display="block";
            }
            else
            {
                alert(this.responseText.substring(0,this.responseText.indexOf("<")));
                document.getElementById("imgDiv").innerHTML=origPic;
                document.getElementById("changePictureApply").style.display="none";
            }
        }
    }
    xhr.send(formData);
}
let catCnt = 5;

function onClick_AddCategory()
{
    var catName = document.getElementById("category").value;
    var newCat = "'cat" + catCnt + "'";
    document.getElementById("categories").innerHTML += "<li class='list-group-item' id=" + newCat + " style='background-color:#1abc9c; width:30%'>" + catName + "<button class='btn btn-primary profile-button' type='button' style='background-color:red; width:30%; float:right' onclick='hideElement(" + catCnt + ")'>Ukloni</button></li>";
    catCnt++;
}

function hideElement(num)
{
    document.getElementById("cat" + num).hidden = true;
}
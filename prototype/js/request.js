//Autor: Filip Janjic

function onClick_NewRequest()
{
    var sendButton = document.getElementById("sendRequestButton");
    sendButton.hidden = false;
    var acceptButton = document.getElementById("acceptSendRequestButton");
    var rejectButton = document.getElementById("rejectSendRequestButton"); 
    acceptButton.hidden = true;
    rejectButton.hidden = true;
    var requestDesc = document.getElementById("requestDesc");
    var requestCheck = document.getElementById("urgentBox");
    requestDesc.value = "";
    requestCheck.checked = false;


}

function onClick_TrySendRequest()
{ 
    var sendButton = document.getElementById("sendRequestButton");
    sendButton.hidden = true;
    var acceptButton = document.getElementById("acceptSendRequestButton");
    var rejectButton = document.getElementById("rejectSendRequestButton"); 
    acceptButton.hidden = false;
    rejectButton.hidden = false;
}

function onClick_RejectRequest()
{
    var sendButton = document.getElementById("sendRequestButton");
    sendButton.hidden = false;
    var acceptButton = document.getElementById("acceptSendRequestButton");
    var rejectButton = document.getElementById("rejectSendRequestButton"); 
    acceptButton.hidden = true;
    rejectButton.hidden = true;
}

function reloadPage()
{
    location.reload();
}

function clearPage()
{
    var emailText = document.getElementById("emailText");
    var emailBtn = document.getElementById("emailBtn");
    emailText.value = "";
    emailBtn.disabled = true;
}

function disablePosaljiMejl(emailText)
{
    var emailBtn = document.getElementById("emailBtn");
    if (emailText.value.trim() != "") emailBtn.disabled = false;
    else emailBtn.disabled = true;
}

function onClick_AdminAcceptRequest(admin)
{
    document.getElementById("k" + admin).innerHTML = "";
}

function onClick_AdminDenyRequest(admin)
{
    document.getElementById("k" + admin).innerHTML = "";
}
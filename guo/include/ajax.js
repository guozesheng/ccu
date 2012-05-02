var xmlHttp;

function S_xmlhttprequest()
{
    if (window.ActiveXObject)
    {
        xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
    }
    else if (window.XMLHttpRequest)
    {
        xmlHttp = new XMLHttpRequest();
    }
}

function checkuser(jobnum)
{
    S_xmlhttprequest();
    xmlHttp.open("GET", "./include/checkuser.php?boss="+jobnum, true);
    xmlHttp.onreadystatechange = checkuserValue;
    xmlHttp.send(null);
}

function checkuserValue()
{
    var myajax = xmlHttp.responseText;
    document.getElementById('jobnumchk').innerHTML = myajax;
}
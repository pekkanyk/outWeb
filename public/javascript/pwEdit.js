function editLimit(pid){
    var limit = "limit_"+pid;
    var edit = "edit_"+pid;
    var checkbox = "checkbox_"+pid;
    var int_limit = "int_limit_"+pid;
    var limitInt = document.getElementById(int_limit).value;
    document.getElementById(limit).removeAttribute("readonly");
    document.getElementById(checkbox).removeAttribute("onclick");
    document.getElementById(limit).setAttribute("value", limitInt);
    document.getElementById(limit).setAttribute("class", "inputbox-pitka");
    document.getElementById(edit).setAttribute("onclick", "submit()");
    document.getElementById(edit).setAttribute("value", "OK");
}

function editEmail(){
    document.getElementById("email").removeAttribute("readonly");
    document.getElementById("email").setAttribute("class", "inputbox-pitka");
    document.getElementById("edit_email").setAttribute("onclick", "submit()");
    document.getElementById("edit_email").setAttribute("value", "SAVE");
}






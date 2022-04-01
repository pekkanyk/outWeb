function lavapaikka(lavapaikka){
    var sisalto = document.getElementById(lavapaikka).getAttribute("title");
    document.getElementById("lava_text").setAttribute("value", lavapaikka);
    document.getElementById("sisalto").innerHTML = sisalto;

}

function useClick(id){
    document.getElementById(id).setAttribute("value", "SAVE");
    document.getElementById("usage").setAttribute("value", id);
    document.getElementById(id).setAttribute("onclick", "submit()");

}






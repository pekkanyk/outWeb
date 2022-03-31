function lavapaikka(lavapaikka){
    document.getElementById("lava_text").setAttribute("value", lavapaikka);

}

function useClick(id){
    document.getElementById(id).setAttribute("value", "SAVE");
    document.getElementById("usage").setAttribute("value", id);
    document.getElementById(id).setAttribute("onclick", "submit()");

}






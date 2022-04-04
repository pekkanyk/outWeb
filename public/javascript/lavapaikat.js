function lavapaikka(lavapaikka){
    var sisalto = document.getElementById(lavapaikka).getAttribute("title");
    var templavapaikka = document.getElementById("btn_temp_id").innerHTML;
    if (templavapaikka !== lavapaikka){
        var original_color = document.getElementById(lavapaikka).getAttribute("style");
    }
    else {
        var original_color = document.getElementById("btn_temp_style").innerHTML;
    }
    var old_color = document.getElementById("btn_temp_style").innerHTML;
    
    if (templavapaikka !== "none"){
        document.getElementById(templavapaikka).setAttribute("style",old_color);
    }
    document.getElementById("lava_text").setAttribute("value", lavapaikka);
    document.getElementById("lava_text").select();
    document.execCommand("copy");
    document.getElementById("sisalto").innerHTML = sisalto;
    document.getElementById("sisalto").focus();
    document.getElementById(lavapaikka).setAttribute("style","background-color:#C0C0C0");

    document.getElementById("btn_temp_id").innerHTML = lavapaikka;
    document.getElementById("btn_temp_style").innerHTML = original_color;
}

function useClick(id){
    var original_value = document.getElementById(id).getAttribute("value");
    var old_value = document.getElementById("submit_temp_value").innerHTML;
    var temp_id = document.getElementById("submit_temp_id").innerHTML;
    if (temp_id !== "none"){
        document.getElementById(temp_id).setAttribute("value",old_value);
        var temp = "useClick("+temp_id+")";
        document.getElementById(temp_id).setAttribute("onclick",temp);
    }
    document.getElementById(id).setAttribute("value", "SAVE");
    document.getElementById("usage").setAttribute("value", id);
    document.getElementById(id).setAttribute("onclick", "submit()");
    document.getElementById("submit_temp_value").innerHTML = original_value;
    document.getElementById("submit_temp_id").innerHTML = id;

}






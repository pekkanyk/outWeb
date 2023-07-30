function selectName(outid) {
  var out = "nameid_"+outid;
  var element = document.getElementById(out);
  window.getSelection().selectAllChildren(element);
  document.execCommand("copy");
}






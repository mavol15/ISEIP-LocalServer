var xmlHttp = new XMLHttpRequest();       //createXmlHttpRequestObject();

function getWifiInfo(){
  if(xmlHttp.readyState==0 || xmlHttp.readyState==4){
      //current beaglebone server:
      xmlHttp.open("GET", "getWifiInfo.php", true);
      xmlHttp.onreadystatechange = handleServerRes;
          xmlHttp.send(null);
  }else{
      setTimeout('getWifiInfo',1000);
  }
}

function handleServerRes() {
if (this.readyState == 4 && this.status == 200) {
    //console.log(this.responseText);
    var jsonStr = this.responseText.substring(this.responseText.indexOf("<response>")+"<response>".length,this.responseText.indexOf("</response>"));
    //console.log(jsonStr);
    var jsonArr = JSON.parse(jsonStr);
    //console.log(jsonArr);

    var passWifi = "";

    for (var i = 0; i < jsonArr.length; i++) {
/*Check for hidden Network*/
      if(!jsonArr[i][1]==""){

/*Create and set Tab attribute*/
      if(jsonArr[i][0]=="*AO"){
        passWifi =passWifi+'<a class="list-group-item list-group-item-action active" id="connectedWifi" data-toggle="list" href="#list-'+jsonArr[i][2]+'" onclick="displayPW('+i+');" role="tab">';
      }else if(jsonArr[i][3]=="ieee8021x"){
        passWifi =passWifi+'<a class="list-group-item list-group-item-action disabled" data-toggle="list" href="#list-'+jsonArr[i][2]+'" role="tab">';
      }else {
        passWifi =passWifi+'<a class="list-group-item list-group-item-action" data-toggle="list" href="#list-'+jsonArr[i][2]+'" onclick="displayPW('+i+');" role="tab">';}

/*Add Network Name*/
      passWifi = passWifi+jsonArr[i][1];

/*Add Network Security info*/
      var s = ""+jsonArr[i][2]+"";
      var d = ""+jsonArr[i][3]+"";
      if(jsonArr[i][0]=='*AO'){
        passWifi = passWifi+'<p class="connected"><b>(Connected)</b></p>';
      }else if(jsonArr[i][3]=="none"){
        passWifi = passWifi+'<p class="notConnected" id="connTypeId'+i+'"><b>(OPEN)</b></p>'+'<button class="goAwayButt" id="wifiButt'+i+'" onclick=setWifi("'+s+'","'+d+'")>Connect</button>';
      }else if(jsonArr[i][3]=="psk"){
        passWifi = passWifi+'<p class="notConnected" id="connTypeId'+i+'"><b>(Password needed)</b></p>'+'<button class="goAwayButt" id="wifiButt'+i+'" onclick=setWifi("'+s+'","'+d+'","'+i+'")>Connect</button><input class="goAwayInput" id="wifiIn'+i+'"></input>';
      }else if(jsonArr[i][3]=="ieee8021x"){
        passWifi = passWifi+'<p class="notSupported">(ieee8021 Not Supported)</p>';
      }else{
        passWifi = passWifi+'<p class="notSupported">(Security Protocol Not Recognized)</p>';
      }
      passWifi = passWifi+'</a>';

    } //console.log(passWifi);
/*Insert Tab into HTML location*/
    document.getElementById("list-tab").innerHTML = passWifi;
    }
  }
}

function setWifi(wifi,prot, passId){
  if(xmlHttp.readyState==0 || xmlHttp.readyState==4){
      //current beaglebone server:
      if(prot=="none"){
        var send = "setWifi.php?wifi_str="+wifi+"&protocol="+prot;
      }else{
        var passw = document.getElementById("wifiIn"+passId).value;
        var send = "setWifi.php?wifi_str="+wifi+"&protocol="+prot+"&password="+passw;}
      console.log(send);
      xmlHttp.open("GET", send, true);
      xmlHttp.onreadystatechange = handleServerRes2;
          xmlHttp.send(null);
  }else{
      setTimeout('setWifi',1000);
  }
}

function handleServerRes2() {
if (this.readyState == 4 && this.status == 200) {
    console.log(this.responseText);
    getWifiInfo();
  }}



getWifiInfo();

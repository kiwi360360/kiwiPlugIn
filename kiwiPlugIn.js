console.log('%c 您已使用「kiwiPlugIn」!','background:#ffeba0;color:#905000')
console.log('%c 「kiwiPlugIn」是一款由貓虎皮開發的全自動網奇外掛。','background:#ffeba0;color:#905000')
//()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
function get_cookie(name="kiwiPlugInMode") {
	search=name + "="
	if (document.cookie.length > 0) {
		offset = document.cookie.indexOf(search)
		if (offset >= 0) {
			offset += search.length
			end = document.cookie.indexOf(";", offset)
			if (end == -1) end=document.cookie.length
			return decodeURIComponent(document.cookie.substring(offset, end))
		}
	}
	return 0;
}
const kiwiPlugInMode = get_cookie("kiwiPlugInMode");
let kiwiPlugInModeName = "";
if(kiwiPlugInMode==0){kiwiPlugInModeName="作答停止"}
if(kiwiPlugInMode==1){kiwiPlugInModeName="作答一次"}
if(kiwiPlugInMode==2){kiwiPlugInModeName="作答重發"}
//()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()()
if(location.href.search("kiwi") >= 0 && location.href.search("2019_beta") >= 0){
	const kiwiPlugInDiv = document.createElement('div');
	kiwiPlugInDiv.style.height = "3vw";
	kiwiPlugInDiv.style.bottom = "0.5vw";
	kiwiPlugInDiv.style.left = "0.5vw";
	kiwiPlugInDiv.style.zIndex = "99999";
	kiwiPlugInDiv.style.position = "fixed";
	kiwiPlugInDiv.style.userSelect = "none";
	kiwiPlugInDiv.style.backgroundColor = "white";
	kiwiPlugInDiv.style.borderRadius = "1.5vw";
	kiwiPlugInDiv.style.padding = "0.3vw";
	kiwiPlugInDiv.innerHTML = `
	<div style="height: 3vw;" onclick="document.cookie = 'kiwiPlugInMode='+((${kiwiPlugInMode} + 1) % 3);location.reload();">
		<img height="100%" src="https://lh3.googleusercontent.com/-3QlYhBf3bfU/YKoVkjj41PI/AAAAAAAAMYY/uRA1b-P3B5c6sX3RYN7YNf3vrnKGPF6gACLcBGAsYHQ/IMG_3598.PNG">
		<a style="top: -0.6vw; position: relative; font-size: 2vw;">${kiwiPlugInModeName}</a>
	</div>
	`;
	document.body.appendChild(kiwiPlugInDiv);
	if(location.href.search("ts") >= 0){
		const kiwiPlugInScript = document.createElement('script');
		if(kiwiPlugInMode == 1){
			kiwiPlugInScript.innerHTML = `
				document.body.onload = () => {
					console.log("kiwiPlugIn模式：作答一次");
					const kiwiPlugInQ = s_GetSQLString('Q');
					const kiwiPlugInR = s_GetSQLString('R');
					const kiwiPlugInM = s_GetSQLString('M');
					const kiwiPlugInQtotal = 20; // QV.Qtotal
					// let kiwiPlugInQtotal = 20;
					// if(s_GetSQLString('M').slice(1,2) == 'A'){
					// 	kiwiPlugInQtotal = 5;
					// }
					// else if(s_GetSQLString('M').slice(1,2) == 'B'){
					// 	kiwiPlugInQtotal = 10;
					// }
					// else if(s_GetSQLString('M').slice(1,2) == 'C'){
					// 	kiwiPlugInQtotal = 20;
					// }
					const kiwiPlugInLevelNum = GV.userIOKey[0][1];
					const kiwiPlugInV = QV.ClassVerNo;
					const kiwiPlugInSendPhp = (kiwiPlugInPhpName,kiwiPlugInSendValue) => {
						var xmlhttp = new XMLHttpRequest();				
						xmlhttp.open("POST", kiwiPlugInPhpName,true);
						xmlhttp.onreadystatechange=function() {
							if (xmlhttp.readyState==4) {
								window.console.log(xmlhttp.responseText);
							}
						}
						xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						xmlhttp.send(kiwiPlugInSendValue);
					}
					var today = new Date();
					sec = today.getTime()/1000;
					ssec_be = (sec-(Math.floor(Math.random()*2400)+600))*1000;
					var beday = new Date(ssec_be);
					IntoTime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(beday.getMinutes())+':'+strlength(beday.getSeconds());
					OutTime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(today.getMinutes())+':'+strlength(today.getSeconds());
					kiwiPlugInSendPhp("writescore/writescore.php", "Qname="+kiwiPlugInQ+"&Qtotal="+kiwiPlugInQtotal+"&CorrectNo="+kiwiPlugInQtotal+"&IntoTime="+IntoTime+"&OutTime="+OutTime+"&LevelNum="+kiwiPlugInLevelNum+"&classnum="+kiwiPlugInM+"&R="+kiwiPlugInR);
					GV.DebugMsg = "kiwiPlugIn正在退出試題";
					window.location.replace("menu.php" + "?name=" + Math.floor(Date.now()/3600000) + "&V=" + kiwiPlugInV + "&C=" + kiwiPlugInM.substr(2,1));
				}
			`;
		}
		else if(kiwiPlugInMode == 2){
			kiwiPlugInScript.innerHTML = `
				document.body.onload = () => {
					console.log("kiwiPlugIn模式：作答重發");
					const kiwiPlugInQ = s_GetSQLString('Q');
					const kiwiPlugInQtotal = 20; // QV.Qtotal
					const kiwiPlugInR = s_GetSQLString('R');
					const kiwiPlugInM = s_GetSQLString('M');
					const kiwiPlugInLevelNum = GV.userIOKey[0][1];
					const kiwiPlugInSendPhp = (kiwiPlugInPhpName,kiwiPlugInSendValue) => {
						var xmlhttp = new XMLHttpRequest();				
						xmlhttp.open("POST", kiwiPlugInPhpName,true);
						xmlhttp.onreadystatechange=function() {
							if (xmlhttp.readyState==4) {
								window.console.log(xmlhttp.responseText);
							}
						}
						xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						xmlhttp.send(kiwiPlugInSendValue);
					}
					let kiwiPlugInTime = 0;
					QV.TimeTotal = 3600*24;
					const kiwiPlugIn = () => {
						LV.VersionTxt.text = "kiwiPlugIn";
						var today = new Date();
						sec = today.getTime()/1000;
						ssec_be = (sec-(Math.floor(Math.random()*2400)+600))*1000;
						var beday = new Date(ssec_be);
						IntoTime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(beday.getMinutes())+':'+strlength(beday.getSeconds());
						OutTime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(today.getMinutes())+':'+strlength(today.getSeconds());
						kiwiPlugInSendPhp("writescore/writescore.php", "Qname="+kiwiPlugInQ+"&Qtotal="+kiwiPlugInQtotal+"&CorrectNo="+kiwiPlugInQtotal+"&IntoTime="+IntoTime+"&OutTime="+OutTime+"&LevelNum="+kiwiPlugInLevelNum+"&classnum="+kiwiPlugInM+"&R="+kiwiPlugInR);
						kiwiPlugInTime++;
						if(GV.DebugMsg == "[離開按鈕] 被按下 ~"){
							GV.DebugMsg = "kiwiPlugIn正在退出試題";
							window.location.replace("menu.php" + "?name=" + Math.floor(Date.now()/3600000) + "&V=" + kiwiPlugInV + "&C=" + kiwiPlugInM.substr(2,1));
						}
						GV.DebugMsg = "kiwiPlugIn已送出"+kiwiPlugInTime+"次";
						setTimeout(kiwiPlugIn, 2000);
					}
					setTimeout(kiwiPlugIn, 2000);
				}
			`;
		}
		else{
			console.log("kiwiPlugIn模式：作答停止");
			kiwiPlugInScript.innerHTML = `
			function kiwiPlugIn(){
				LV.VersionTxt.text = "kiwiPlugIn";
			}
			setTimeout(kiwiPlugIn, 2000);
			function kiwiPlugInModeChange(){
				document.cookie = "kiwiPlugInMode="+((kiwiPlugInMode + 1) % 3);
				lococation.href = lococation.href;
			}
			setTimeout(kiwiPlugIn, 2000);
			`;
		}
		document.body.appendChild(kiwiPlugInScript);
		// 332 450490
	}
}
else{
	console.log("kiwiPlugIn無法在此頁面執行");
}
url = 'QBase/_popTest/' + gbno + '.html?gbflag=1'  + '&name=' + s_TimeStamp(); // 題目檔案位置
url = 'QBase/_pop/' + gbno.slice(0,2) + '/' + gbno + '.html' + '?name=' + s_TimeStamp(); // 綠豆檔案位置
s_popGBWin (綠豆, 題目)

s_iframeKill () // 刪除綠豆視窗
Old_s_popWin ("qframe", "2019_beta/"+"QBase/H31/H31A090"+".html?name="+s_TimeStamp())
LV.VersionTxt.text // 左下角顯示的題號文字
GV.DebugMsg // 中上顯示的標題文字
QV.QData[1][no][1] // 為第no題的題目路徑

no = 11;
GV.GreenBeanLink = "https://"+location.host+"/2019_beta/"+QV.QData[1][no][1]+".html?name="+s_TimeStamp()
Old_s_popWin ("qframe", GV.GreenBeanLink);

QV.QBStatus = 1 && QV.QBLoadOkFlag = true

LV.QNowBox

LV.STPanelBtn[1]

LV.KBStatus = 1; // 虛擬鍵盤的狀態旗標(負值 -1 -2 :隱藏 / 1:選擇題 / 2:填充題)

G4C.game.add.audio('ping').play() // 播放「咚叮」
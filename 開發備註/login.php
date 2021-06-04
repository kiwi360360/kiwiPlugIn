
<!DOCTYPE html>

<html>

<head>

<meta charset = 'utf-8'>

<meta name="viewport" content="width=device-width,height=device-height, initial-scale=1, user-scalable=no">

<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><!-- 測試用，先不要快取 -->

<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"><!-- 測試用，先不要快取 -->

<meta HTTP-EQUIV="EXPIRES" CONTENT="0"><!-- 測試用，先不要快取 -->

<title>[動態解題 I 代 ‧ 改]-開發中</title>

<link rel="shortcut icon" href="Favicon.ico"/>

<link rel="bookmark" href="Favicon.ico"/>

    

<script type ='text/javascript' src='lib/phaser.min.js'></script>

<script type ='text/javascript' src='lib/fullscreen.js'></script>

<script type ='text/javascript' src= 'lib/modernizr-custom.js'></script>



<!-- 測試把各題題目的載入函式庫動作，集中在系統端 (能成功，但後來覺得不需要，因為瀏覽器會快取) -->

<!-- <script src="https://code.createjs.com/createjs-2015.11.26.min.js"></script> -->

<script src="lib/createjs.min.js"></script>



<script type ='text/javascript' src='lib/drag_sys.js'></script><!-- 搭配綠豆視窗的拖曳功能 -->

<script type ='text/javascript' src='lib/kiwi_draw.js'></script><!-- 系統的繪圖套件 -->

<script type ='text/javascript' src='lib/kiwi_test.js'></script><!-- 系統的測試套件 -->

    

<script type ='text/javascript' src='src/0-boot.js'></script>

<script type ='text/javascript' src='src/1-preload.js'></script>

<script type ='text/javascript' src='src/1-ready.js'></script>

<script type ='text/javascript' src='src/2-main.js'></script>

    

    <style>

                

        body{

            position:fixed;

            padding:0px;

            margin:0px;

            background-color: #225555; /* 使用和系統界面 #225566 相近的顏色 */

            /*outline:inherit;*/

            height: 100%;      /* 隱藏瀏覽器捲軸區 */

            overflow: hidden   /* 隱藏瀏覽器捲軸區 */

        }

                

        #qframe {  /* iFrame 的外觀定義 */

            position:fixed;

            left:10%; /* 150px; */

            top:10%; /* 150px; */

            width:80%;/*88%; /* 848px; */

            height:90%; /* 400px; */

            background-color: #AFFAAF; /*#FFFFAF; */

            border: 0px none #000000; /* 外框 */

            /*border-radius: 8px;*/

            /* color: white; */

            padding: 0px 0px; /* (文字) [上下] [左右] 邊界(空白)大小 */

            /* text-align: left; */

            display: inline-block;

            /* font-size: 20px; */

            margin: 2px 0px 0px 0px; /* 邊界大小 */

            /* cursor: pointer; */ /* 手指符號 */

            outline:none;

            z-index: 0; /* 設定深度 */

            transform: scale(1, 1);

            visibility:hidden; /* 先隱藏不用 */

            /*background-image: url('assets/loading/5.gif');

            background-repeat: no-repeat;

            background-position: center;

            background-size: 25% 50%; */

        }

                

        #myCanvas {

            /*width: 1920px;

            height: 1080px; */

        }

        

        div.DebugMsg {

            position: fixed;

            /* top: 0; */

            bottom: 0;

            left: 0;

            width: 100%; /* 120px; */

            color:white;

        }

        

        

    </style>

    

    <style>

        /* ========================================= 以下沒用到 ====================================== */

        

        #SQr{ /* 用 CSS 製作的左側題目狀態區 */

            position:fixed;

            left:0;

            background-color:#114455; /* #225566 */ /* 'rgba(0, 0, 0, 0.1)' 透明色的指定方法*/

            top:0px;

            bottom:50px;

            width:150px;

            visibility:hidden; /* 先隱藏不用 */

        }

        

        .floatIO {

            position:fixed;

            z-index: 1; /* 設定深度 */

            outline: none; /*不要有 focus 效果*/

            -webkit-user-select: none; /* Safari 3.1+ */

            -moz-user-select: none; /* Firefox 2+ */

            -ms-user-select: none; /* IE 10+ */

            user-select: none; /* Standard syntax */

            top:150px;

            font-size: large;

            resize: none; /*不能用拖拉方式縮放*/



        }

        input:focus, textarea:focus {

            outline: none;

        }

        

    </style> <!-- 沒用到的 style 設定 (記憶用) -->

    

<script type = 'text/javascript'>



 	var gb_name = [];
    
    function getMobileOperatingSystem() {

      var userAgent = navigator.userAgent || navigator.vendor || window.opera;



          // Windows Phone must come first because its UA also contains "Android"

        if (/windows phone/i.test(userAgent)) {

            return "Windows Phone";

        }



        if (/android/i.test(userAgent)) {

            return "Android";

        }



        // iOS detection from: http://stackoverflow.com/a/9039885/177710

        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {

            return "iOS";

        }



        return "DeskTop";

    } //.........................取得瀏覽器所在設備型態 : (Android,iOS,DeskTop)

    

    // =======================================================================================

    //'use strict'; //--- Use Strict Mode

    var G4C = G4C || {};

    var GV = GV || {}; // 用來儲存具有 全域存取 的變數 (不儲存在上面的 G4C，避免日後混淆)

    var LV = LV || {}; // 用來儲存具有 全域存取 的變數

    

    var QV = QV || {}; // 用來儲存具有 全域存取 的題庫專用變數

    

    //****************************************************************************************************************

    //****************************************************************************************************************

    //****************************************************************************************************************

    GV.SysKey = 'A1'; //-----------------------------------系統版本辨識變數，用來比對何種版本的題目檔，能與本系統在功能上互相符合

    GV.version = 'Ver : ' + GV.SysKey + '_196-23'; //--------------------------------------------------------[系統版本]

    //****************************************************************************************************************

    //****************************************************************************************************************

    //****************************************************************************************************************

    

    LV.scrollWidth = 50; // 測驗畫面-捲動軸寬度

    LV.keyBoardWidth = 180;//200; // 測驗畫面-虛擬鍵盤寬度

    LV.quizStatusWidth = 180; // 測驗畫面-左側題目狀態區寬度

    LV.menuBarHeight = 90; // 上方_工具列高度

    LV.keyBoardHeight = 120; // 下方_第34支虛擬鍵盤的高度

    

    var FlashFlag = true; // 讓 iframe 裡面的 Animate(Flash) 可以偵測系統是否存在的旗標

    

    

    GV.game_W = 1920 , GV.game_H = 1080; // 整個遊戲視窗的寬高度

    GV.ScaleMode = 0; // 0:水平方向填滿  / 1: 垂直方向填滿

    GV.WindowScaleX = window.innerWidth / GV.game_W; // 在此計算螢幕視窗比例的預設值

    GV.WindowScaleY = window.innerHeight / GV.game_H;

    

    //---------------------------------------------------- 檢查瀏覽器是否支援 觸控

    if (Modernizr.touchevents) { // supported

        GV.SysBrowserTouchFlag = true;   // (但是目前還沒用到)

        GV.MobileBrowserFlag = true; // 應該是 手機版的瀏覽器

    } else { // not-supported

        GV.SysBrowserTouchFlag = false;   // (但是目前還沒用到)

        GV.MobileBrowserFlag = false; // 應該不是 手機版的瀏覽器

    }

    //---------------------------------------------------- 偵測是否為手機版的瀏覽器(似乎不準確)(偵測不出安卓)(不使用)

    if (/Mobi/.test(navigator.userAgent)) {

        //GV.MobileBrowserFlag = true; // 應該是 手機版的瀏覽器

    } else {

        //GV.MobileBrowserFlag = false; // 應該不是 手機版的瀏覽器

    }

    //---------------------------------------------------------------------------------

    if (GV !== null) {

        

        GV.SysMode = 0; // 系統運作模式旗標 [預設值：0 作答模式] [1:解答模式] 未來：(負值:遊戲競賽模式) (2:錯題重答練習模式)

                        // 遊戲競賽模式：參加人數的負值 (-2:兩個參賽者 ~ -4:四個參賽者[上限4人])

        

        GV.SysPracticeFlag = false; // 練習模式旗標 (不倒數計時，不送出成績)

        

        GV.SysStopAnsFlag = false; // 停止作答旗標

        

        

        GV.ScrRotating = false; //螢幕是否旋轉中的旗標

        GV.ScrRotatCount = 20; // 每次旋轉的最大監視次數

        GV.ScrInOldW = window.innerWidth; //瀏覽器內寬度(舊)

        GV.ScrInOldH = window.innerHeight; //瀏覽器內高度(舊)

        

        GV.BrowserLeft = 0; // 瀏覽器畫面裡的左邊空白寬度

        GV.QFrameX = 0; // GFrame 的座標暫存器

        GV.QFrameY = 0;

        GV.QFrameW = 0; // GFrame 的寬度暫存器

        GV.QFrameH = 0; // GFrame 的高度暫存器

        //GV.QFrameOldW = 0; // GFrame 的寬度暫存器(舊)

        //GV.QFrameOldH = 0; // GFrame 的高度暫存器(舊)

        //GV.QFrameScaleW = 1; //GFrame 的寬度變化比例

        //GV.QFrameScaleH = 1; //GFrame 的高度變化比例

        

        GV.QFrameDrag = false; //題目區是否可以拖曳的旗標，通知題目做判斷

        

        GV.iFrameScrollX = 0; // iframe 的水平捲動數值

        GV.iFrameScrollY = 0; // iframe 的垂直捲動數值

    

        GV.QzDragOX = 0; // 題目區拖曳動作的起點坐標

        GV.QzDragOY = 0; // 題目區拖曳動作的起點坐標

        GV.QzDragDX = 0; // 題目區拖曳動作的水平位移

        GV.QzDragDY = 0; // 題目區拖曳動作的垂直位移

        

        //系統參數設定相關 -------------------------------------------------------------------

        if (GV.MobileBrowserFlag) { // 如果偵測是手機版瀏覽器

            GV.SyArg_QScale = -1; // (系統的)題目縮放倍數 (預設值 = 1[正常])(負值:題寬全滿)(其他:確實倍數數字)

            GV.SyArg_QScaleMax = 4 / window.devicePixelRatio;

        } else {

            GV.SyArg_QScale = 1; // (系統的)題目縮放倍數 (預設值 = 1[正常])(負值:題寬全滿)(其他:確實倍數數字)

            GV.SyArg_QScaleMax = 4 / window.devicePixelRatio;

        }

        

        GV.SyArg_BrwSize = 1; // 瀏覽器尺寸 (預設值 = 1:大尺寸[桌機])(0:小尺寸[手機])(2:超大尺寸[大電視])

        GV.SyArg_ToolBarLoc = 1; // 工具列上下位置 (預設值 = 1:螢幕上方 )(0:螢幕下方)



        GV.SyArg_GScale = 1; // (系統的)綠豆縮放倍數 (預設值 = 1[正常])(負值:題寬全滿)(其他:確實倍數數字)



        GV.BrowserType = getMobileOperatingSystem(); // 取得瀏覽器所在設備型態 : (Android,iOS,DeskTop)

        

        GV.SyArg_PlaySound = true; // 系統音效旗標

        

        

        // 以下沒有製作成系統設定操作介面

        

        GV.SyArg_AnsMustSwap = false; // 強制隨機變換答案位置的旗標 (預設是 false)

        

        //比賽模式相關 -----------------------------------------------------------------------

        GV.GameState = -2; // 管理比賽模式流程的旗標

        /*

            -3 : 比賽結束

            -2 : 比賽前的預備期

            -1 : 正在等待題目載入

             0 : 答題開放中

            >0 : 單題優勝者編號 (並暫時停止作答動作)



        */

        GV.GameQBTotal = -1; // 比賽模式的出題總數量

        /*

            -1 : 預設狀態 (比賽用題庫尚未載入完成)

             0 : 等於題庫的所有題目數量 (QV.QBTotal)

            >0 : 指定的題目數量 (跑完此數量，則比賽結束)(可以比 QV.QBTotal 大)

        */

        GV.GameQBNow = 1; //比賽目前題數指標

        

        GV.GameQBMode = 0; // 比賽模式中的題目出題模式 (0:依序輪流 / 1:隨機抽題).................(尚未使用)

        

        GV.GameTimer = 0; // 比賽模式流程專用的倒數計數器

        GV.GameTimerMax = 20; // 比賽模式流程倒數上限

        

        GV.PlayerTotal = 4; // 比賽人數

        GV.PlayerScoreMap = []; // 記分板上的對應順序 (與比賽人數有關，不同人數，順序會不同)

        GV.PlayerScoreMap[1] = GV.PlayerTotal; // 第 1 位玩家的順序 (都在最右邊)(最後一位)(當人數變動後需要更新)

        GV.PlayerScoreMap[2] = 1; // 第 2 位玩家的順序 (都在 1)

        GV.PlayerScoreMap[3] = 2; // 第 3 位玩家的順序 (四個人時在 2 / 三個人時在 2)

        GV.PlayerScoreMap[4] = 3; // 第 4 位玩家的順序 (都在 3)



        GV.ItemMark = []; // 題目選項上的外加記號 (與比賽人數相同)

        GV.ItemMark[0] = null; // 題目選項上的外加記號

        GV.ItemMark[1] = null; // 題目選項上的外加記號

        GV.ItemMark[2] = null; // 題目選項上的外加記號

        GV.ItemMark[3] = null; // 題目選項上的外加記號

        GV.ItemMark[4] = null; // 題目選項上的外加記號



        GV.PlayerState = []; // 管理玩家的答題狀態 (與比賽人數相同)

        GV.PlayerState[1] = 0; //(0:不能作答 -1:可以作答/-2:本次答錯[包含未作答而失敗]/大於0:本次答對[答對時間點])

        GV.PlayerState[2] = 0;

        GV.PlayerState[3] = 0;

        GV.PlayerState[4] = 0;



        GV.PlayerImg = new Image();

        //GV.PlayerImg.src = "../../assets/kiwi.png"; // 這是開發用的網址

        GV.PlayerImg.src = "assets/kiwi.png"; // 這是上線用的網址



        GV.MarkImg = []; // 圈叉勾記號圖

        GV.MarkImg[1] = new Image();

        //GV.MarkImg[1].src = "../../assets/item_check.png"; // 這是開發用的網址

        GV.MarkImg[1].src = "assets/item_check.png"; // 這是上線用的網址

        GV.MarkImg[2] = new Image();

        //GV.MarkImg[2].src = "../../assets/item_right.png"; // 這是開發用的網址

        GV.MarkImg[2].src = "assets/item_right.png"; // 這是上線用的網址

        GV.MarkImg[3] = new Image();

        //GV.MarkImg[3].src = "../../assets/item_wrong.png"; // 這是開發用的網址

        GV.MarkImg[3].src = "assets/item_wrong.png"; // 這是上線用的網址

        

        

        //GV.quizFileLoadFlag = false; // 題庫參數檔是否正確取得的旗標

        GV.QuizShowModeFlag = true; // 決定是否使用試卷檔裡的模式控制參數 (用在題目載入階段)

        

        

        GV.InputFieldNow = ""; // 目前正在輸入的欄位名稱 (fid)

        GV.InputFieldX = 0;

        GV.InputFieldY = 0;

        GV.InputFieldW = 0;

        GV.InputFieldH = 0;

        GV.InputFieldCount = 0; // 用來控制閃爍效果

        GV.InputFieldFlag = false; // 是否正在回答填充題的旗標

        

        GV.PicWinLockFlag = false; // 鎖定狀態

        GV.PicWinPointer = ''; // 指向題目中 PicWin 的完整路徑

        GV.PicWinX = 0; // 題目中 PicWin 的水平坐標

        GV.PicWinY = 0; // 題目中 PicWin 的垂直坐標

        GV.PicWinThumbNailMark = ''; // PicWin 圖釘指標

        

        GV.GreenBeanExist = false; // 綠豆是否出現的旗標

        GV.GreenBeanLoadOk = false; // 綠豆是否載入完成的旗標(載入的動作)

        GV.GreenBeanLink = ''; // 記憶目前打開的綠豆連結

        GV.GreenBeanBtn = ''; // 記憶目前打開綠豆連結的按鈕名稱(用來判斷是否重複)

        GV.GreenBeanCheckStatus = 0; // 綠豆視窗尺寸檢查旗標進度 (0:完畢 / 1:檢查中)

        GV.GreenBeanMinRatio = 0.5; // 綠豆視窗的最小比例(根據各綠豆尺寸，動態計算)

        GV.GreenBeanZoomH = 40; // Zoom 按鈕高度 (在 Timer 裡也有相關的調整)

        GV.GreenBeanJustOpened = false; // 剛剛綠豆視窗是否出現的旗標(打開綠豆時，是否有綠豆視窗存在)

        

        GV.Dr_Status = -1; // 繪圖功能旗標變數 (-1:不使用 / 0:關閉 / 1:使用中)

        GV.drawCanvas = ''; // 指向繪圖區 canvas 的指標

        GV.Dr_Width = 0; // 繪圖區的寬度

        GV.Dr_Height = 0; // 繪圖區的高度

        GV.Dr_scrollX = 0; // 繪圖區的水平坐標(針對捲動和滾輪的處理)

        GV.Dr_scrollY = 0; // 繪圖區的垂直坐標(針對捲動和滾輪的處理)

        GV.Dr_WheelCount = 0; // 繪圖區_滾輪事件倒數-計數器

        GV.Dr_ctx = ''; // 指向繪圖區的指標

        GV.Dr_IOPnlBox = ""; // 繪圖視窗的容器

        GV.Dr_LineWidth = 1; // 線條寬度

        GV.Dr_LineColor = ''; // 線條顏色

        

        GV.QuizFileName = ''; // 現在的題目檔名(顯示用而已)

        GV.QBTimerPeriod = -1; // 階段計數旗標(以每幾秒一階段，記錄當時的秒數)



        GV.myCanvasFlag = false; //  Canvas 圖層的測試旗標

        

        GV.QBkGrdClr = "#ffffcc"; // 普通底色 (淡黃色)

        GV.QBkGrdOClr = "#ddffdd"; // 答對底色 (淡綠色)

        GV.QBkGrdXClr = "#ffddff"; // 答錯底色 (淡紅色)

        

        GV.userIOWinBox = ""; // 問答視窗的容器

        GV.userIOKey = []; // 問答視窗的按鈕容器陣列

            GV.userIOKey[0] = []; // 紀錄每一層選擇結果 [0][1]:第一層 / [0][2]:第二層

            GV.userIOKey[1] = []; // 第一層

            GV.userIOKey[2] = []; // 第二層

            GV.userIOKey[3] = []; // 第三層

            GV.userIOKey[4] = []; // 第四層(未使用)

        GV.userIO_QzAllFlag = false; // 問答視窗的[全部] 按鈕旗標

        GV.userIO_QzAllCount = 0; // 問答視窗的[全部] 按鈕的啟用計數器

        GV.userQType = 0; // 來自選單的測驗類型參數

        

        GV.QuizCounter = 0; // 測驗已進行次數

        

        //GV.SysIOMode = 0; // 0:水平顯示模式 / 1:垂直顯示模式

        

        GV.DebugMsg = ""; // 除錯訊息

        GV.DebugMsg2 = ""; // 除錯訊息

        GV.DebugMsg3 = ""; // 除錯訊息

        

    } // GV 系列變數宣告區

    

    

    

    

    G4C.game = new Phaser.Game(GV.game_W, GV.game_H, Phaser.CANVAS, "G4CCanvas","",true);

    

    G4C.game.state.add('Boot',G4C.boot);

    G4C.game.state.add('Preload',G4C.preload);      // 遊戲開頭畫面元件的初步預載

    G4C.game.state.add('Ready',G4C.KWReady);

    G4C.game.state.add('Main',G4C.KWMain);

    

    G4C.game.state.start('Boot'); // 從 Boot state 開始執行

    
	

    //============================================================== [ HTML5 Canvas 測 試 區 ]=======

    

    /*

    function GB_LoadMsg () {

        var msg = ""; // 接收字串

        

        msg = G4C.game.cache.getText('gbname');

        

        window.console.log('load=' + msg.substr( msg.indexOf("=") + 1));

        

        msg = G4C.game.cache.getText('gbname1');

        

        window.console.log('load=' + msg.substr( msg.indexOf("=") + 1));

        

    }*/

    

    //============================================================== [ 以 下 為 函 數 宣 告 區 ]=======

    // !!!!! 這裡宣告的函數是 在每個 state 主體或是函數中，都可以呼叫到的函數 !!!!! (根本是全域函數)

    

    //==================================================================================== [打開綠豆視窗]=========

    function s_popGBWin (gbno, para) { // 動態開啟新的 iframe

        // para = 0 or null (綠豆)

        // para = 1 (題目)        

        

        var fname = 'myiframe', // 綠豆視窗 frame id

            btn = '',

            bkcolor,

            dir, // 綠豆次資料夾

            url; // 綠豆檔案位置

    
        if(para == 0 || para == null){ // 綠豆

            bkcolor = '#ccffff';

            

            if (gbno.indexOf('@') >= 0) { // 傳來按鈕名稱

                btn = gbno.substr(0, gbno.indexOf('@')); // 取得按鈕名稱

                gbno = gbno.substr(gbno.indexOf('@') + 1); // 取得綠豆編號
				
				if(!gb_name.length || gb_name[gb_name.length-1] != gbno)
					gb_name.push(gbno);
				
                //window.console.log('來自按鈕 ['+btn+'/'+GV.GreenBeanBtn+'] 的綠豆視窗呼叫：'+gbno);

            }

           

            if (gbno.slice(0,2) == 'en'){

                dir = 'en/'+gbno.substr(3,2); // 測試用的範例資料夾

                url = 'QBase/_pop/' + dir + '/' + gbno.slice(3) + '.html' + '?name=' + s_TimeStamp(); // 綠豆檔案位置

            } else {

                dir = gbno.slice(0,2); // 只取編號的前兩個字元

                url = 'QBase/_pop/' + dir + '/' + gbno + '.html' + '?name=' + s_TimeStamp(); // 綠豆檔案位置

            }

            

        } else { // 題目

            bkcolor = '#ffffcc'; // 正常的淡黃色

            

            dir = '_demo'; // 測試用的範例資料夾

            url = 'QBase/_popTest/' + gbno + '.html?gbflag=1'  + '&name=' + s_TimeStamp(); // 題目檔案位置

            

            

        }

        /*

        if (gbno == 'ts0002'){ // 用綠豆打開 題目檔 的測試

            dir = '_demo'; // 測試用的範例資料夾

            url = 'QBase/[]_develop/' + gbno + '.html?hello=100&gbflag=1&test=qq'; // 題目檔案位置

            

        } else if (gbno == 'N1_test'){

            dir = '_demo'; // 測試用的範例資料夾

            url = 'QBase/_pop/' + dir + '/' + gbno + '.html'; // 綠豆檔案位置

            

        } else {

            dir = gbno.slice(0,2); // 只取編號的前兩個字元

            url = 'QBase/_pop/' + dir + '/' + gbno + '.html'; // 綠豆檔案位置

            

        }

        */

        //url = 'QBase/_pop/' + dir + '/' + gbno + '.html'; // 綠豆檔案位置

        

        if (!GV.GreenBeanExist || GV.GreenBeanLoadOk){ // 避免快速連續開啟

            

            GV.GreenBeanLoadOk = false; // 清除載入 Ok 的狀態

            GV.GreenBeanExist = false; // 綠豆不出現

            //QV.GFrameX = 0;

            //QV.GFrameY = 0;

            

            var frame = document.getElementById(fname);

            

            if(frame) { // 如果舊的 frame 還在 (不刪除舊的的話，視窗會一直出現)

                

                frame.parentNode.removeChild(frame); // 先刪除目前的綠豆視窗

                

                // 比對綠豆連結

                if (url == GV.GreenBeanLink && btn == GV.GreenBeanBtn) { // 即將打開的連結與記憶的連結相同，所以只要關閉就好
                   

                    GV.GreenBeanBtn = ''; // 記憶按鈕名稱

                    GV.GreenBeanLink = ''; // 刪除綠豆連結的記憶

                    gb_name.length = 0;

                    GV.GreenBeanJustOpened = false; // 沒有綠豆視窗是開著

                  

                    //window.console.log('closed: ['+btn+'/'+GV.GreenBeanBtn+']');

                    

                } else { // 即將打開的連結與記憶的連結不同

                    

                    //window.console.log('re: ['+btn+'/'+GV.GreenBeanBtn+']：'+gbno);

                    

                    GV.GreenBeanBtn = btn; // 記憶按鈕名稱

                    

                    GV.GreenBeanJustOpened = true; // 剛剛有綠豆視窗是開著

                    

                    if(para == 0 || para == null){ // 綠豆

                        s_popGBWin (btn + '@' + gbno, para); // 接著打開新的綠豆

                    } else {

                        s_popGBWin (gbno, para); // 接著打開新的綠豆

                    }

                }

                

            } else {



                // 清除 拖曳函式庫裡的 比例記憶

                DIF_iframeRatio = 0;

                DIF_ZoomBtnIsDrag = false;



                // 開一個新的

                var iframe = document.createElement('iframe');

                

                // 在 s_QBTimer 函數裡還有後續控制

                if(GV.GreenBeanJustOpened){ // 如果剛剛綠豆視窗是開著(新位置就不必在旁邊)

                    //QV.GFrameX = 0.2 * window.innerWidth; // 綠豆視窗一開始出現的位置

                    QV.GFrameX = 0.75 * window.innerWidth; // 綠豆視窗一開始出現的位置

                } else {

                    QV.GFrameX = 0.75 * window.innerWidth; // 綠豆視窗一開始出現的位置

                }

                

                QV.GFrameY = 50;

                

                if (url !== '') {

                    document.body.appendChild(iframe);

                    iframe.id = fname;

                    iframe.name = fname;

                    iframe.style.zIndex = '2';

                    iframe.style.position = 'fixed';

                    iframe.style.left = QV.GFrameX + 'px';

                    iframe.style.top = QV.GFrameY + 'px';

                    iframe.style.width = '300px'; // iframe 一開啓的預設寬高尺寸

                    iframe.style.height = '200px';



                    iframe.style.backgroundColor = bkcolor;//'#ccffff'; // 目標網頁 的背景色//'#cc22ff';//

                    iframe.style.border = 'medium solid #000000';

                    iframe.style.borderRightWidth = '8px';

                    iframe.style.borderBottomWidth = '8px';

                    //iframe.style.overflow = 'hidden'; // 這一行無法阻止 捲軸區的出現



                    //iframe.style.resize = 'both'; // 讓 iframe 可以用右下角拖曳而縮放大小



                    iframe.src = url;

                    // src 目標網頁裡有要配合拖曳的程式(dragframe)動作，不是隨便的網頁都可以拖曳喔 ~~~



                    // 在綠豆視窗的 iframe 中，事先增加一個提示載入等待的文字 div

                    var iframeDocu = iframe.contentDocument || iframe.contentWindow.document;



                    var div = document.createElement('div');

                    div.id = 'sysWaitMsg';

                    div.style.fontSize = '30px';

                    div.align = 'center'; // 左右置中

                    div.style.marginTop = '80px'; // 上邊界

                    div.innerHTML = '內容載入中 ．．．';

                    if (iframeDocu) {

                        var iframeContent = iframeDocu.getElementsByTagName('body');

                        iframeContent[0].appendChild(div);



                    }

                    QV.GFrame = document.getElementById(fname);

                    

                    GV.GreenBeanLink = url; // 綠豆連結的記憶

                    GV.GreenBeanBtn = btn; // 記憶按鈕名稱

                    //window.console.log('打開綠豆：' + url );

                    

                    //-----------------------------------------------------------------------

                    

                    /*

                    //G4C.game.load.text('gbname', '11m.txt');

                    url = 'QBase/_pop/' + dir + '/' + gbno + '.txt'; // 主題檔名

                    

                    //window.console.log('gblink='+url);

                    

                    // 宣告：用來接收第二階段載入完成的事件

                    G4C.game.load.onLoadComplete.add(GB_LoadMsg, this);

                    */

                    

                    

                    

                    //G4C.game.load.start(); // 開始 : 第二階段載入動作

                    /*



                    url = 'QBase/_pop/F1/F10009.txt';

                    G4C.game.load.text('gbname', url);

                    

                    

                    url = 'QBase/_pop/' + dir + '/' + gbno + '.txt'; // 主題檔名

                    G4C.game.load.text('gbname1', url);

                    

                    

                    

                    G4C.game.load.start(); // 開始 : 第二階段載入動作

                    */

                    

                }

            }

        }

    } // 打開綠豆視窗

    function Old_s_popWin (fname, url) { // 動態開啟新的 iframe

        

        if (!GV.GreenBeanExist || GV.GreenBeanLoadOk){ // 避免快速連續開啟

            

            GV.GreenBeanLoadOk = false; // 清除載入 Ok 的狀態

            GV.GreenBeanExist = false; // 綠豆不出現

            QV.GFrameX = 0;

            QV.GFrameY = 0;

            

            var frame = document.getElementById(fname);

            

            if(frame) { // 如果舊的 frame 還在 (不刪除舊的的話，視窗會一直出現)

                

                frame.parentNode.removeChild(frame); // 先刪除目前的綠豆視窗

                

                // 比對綠豆連結

                if (url == GV.GreenBeanLink) { // 即將打開的連結與記憶的連結相同，所以只要關閉就好

                    

                    GV.GreenBeanLink = ''; // 刪除綠豆連結的記憶

                    

                } else { // 即將打開的連結與記憶的連結不同

                    

                    s_popWin (fname, url); // 接著打開新的綠豆

                    

                }

                

            } else {



                // 清除 拖曳函式庫裡的 比例記憶

                DIF_iframeRatio = 0;

                DIF_ZoomBtnIsDrag = false;



                // 開一個新的

                var iframe = document.createElement('iframe');

                

                QV.GFrameX = 0.75 * window.innerWidth;

                QV.GFrameY = 50;



                if (url !== '') {

                    document.body.appendChild(iframe);

                    iframe.id = fname;

                    iframe.name = fname;

                    iframe.style.zIndex = '2';

                    iframe.style.position = 'fixed';

                    iframe.style.left = QV.GFrameX + 'px';

                    iframe.style.top = QV.GFrameY + 'px';

                    iframe.style.width = '300px'; // iframe 一開啓的預設寬高尺寸

                    iframe.style.height = '200px';



                    iframe.style.backgroundColor = '#ccffff'; // 目標網頁 的背景色//'#cc22ff';//

                    iframe.style.border = 'medium solid #000000';

                    iframe.style.borderRightWidth = '8px';

                    iframe.style.borderBottomWidth = '8px';

                    iframe.style.overflow = 'hidden'; // 這一行無法阻止 捲軸區的出現



                    //iframe.style.resize = 'both'; // 讓 iframe 可以用右下角拖曳而縮放大小



                    iframe.src = url;

                    // src 目標網頁裡有要配合拖曳的程式(dragframe)動作，不是隨便的網頁都可以拖曳喔 ~~~



                    // 在綠豆視窗的 iframe 中，事先增加一個提示載入等待的文字 div

                    var iframeDocu = iframe.contentDocument || iframe.contentWindow.document;



                    var div = document.createElement('div');

                    div.id = 'sysWaitMsg';

                    div.style.fontSize = '30px';

                    div.align = 'center'; // 左右置中

                    div.style.marginTop = '80px'; // 上邊界

                    div.innerHTML = '內容載入中 ．．．';

                    if (iframeDocu) {

                        var iframeContent = iframeDocu.getElementsByTagName('body');

                        iframeContent[0].appendChild(div);



                    }

                    QV.GFrame = document.getElementById('myiframe');

                    

                    GV.GreenBeanLink = url; // 綠豆連結的記憶

                }

            }

        }

    } // 打開綠豆視窗................(舊的...待刪除)

    //==================================================================================== [刪除綠豆視窗]=========

    function s_iframeKill () { // 刪除綠豆視窗

        
		QV.GFrame = '';
		
        GV.GreenBeanLoadOk = false; // 清除載入 Ok 的狀態

        GV.GreenBeanExist = false; // 清除綠豆的出現狀態

        QV.GFrameX = 0;

        QV.GFrameY = 0;

        GV.GreenBeanLink = ''; // 刪除綠豆連結的記憶

        

        var frame = document.getElementById('myiframe');

        if(frame){

            //window.console.log('Kill GFrame !');

            GV.GreenBeanJustOpened = false;

            frame.parentNode.removeChild(frame);

        }

    } // 刪除綠豆視窗

    //==========================================================================================================

    function s_QFrameW () { // 計算目前的題目區寬度

        

        var w = GV.game_W; // 啓始值

        

        if(LV.SQr){

            if (LV.SQr.visible){ // 題目選單感應區存在

                w -= LV.quizStatusWidth;

            }

            if(LV.SKr){

                if (LV.SKr.visible) { // 虛擬鍵盤存在

                    if (LV.SKBod[1]) {

                        w -= LV.keyBoardWidth;

                    }

                    //w -= LV.keyBoardWidth;

                    if (LV.SKBod[2]) {

                        w -= LV.keyBoardWidth;

                    }

                }

            }

        }

        

        return w;

        

        

    } // 計算目前的題目區寬度

    //==========================================================================================================

    function s_QFrameH () { // 計算目前的題目區高度

        

        var h = GV.game_H; // 啓始值

        

        if (LV.STBox) { // 工具列存在

            h -= LV.menuBarHeight;

        }

        

        if (GV.SysMode < 0 && GV.PlayerTotal > 2) { // 比賽模式

            h -= LV.keyBoardHeight;

        }

        

        return h;

        

        

    } // 計算目前的題目區高度

    //=============================================================================== [題目區 qFrame 的位置大小調整]

    function s_iFrameScale () { // 讓 iFrame 的大小隨時符合縮放效果

        // para 代表介面中各重要部分的位置狀況

        

        var scalex, scaley, scale, vx,

            deltax = 2, // 些微誤差

            deltay = 2; // 些微誤差

        

        // 這邊的坐標或是尺寸，都要還原回真正的瀏覽器尺寸，而不是被縮放過的尺寸

        

        scalex = GV.WindowScaleX; // 遊戲內的縮放比例

        scaley = GV.WindowScaleY; // 遊戲內的縮放比例

        

        if (GV.ScaleMode === 0){

            scale = GV.WindowScaleX; // !!!!!!!!!!!!!!!!!! 縮放以水平倍數為主 !!!!!!!!!!!!!!!!!!!!!

                

        } else if (GV.ScaleMode === 1){

            scale = GV.WindowScaleY; // !!!!!!!!!!!!!!!!!! 縮放以垂直倍數為主 !!!!!!!!!!!!!!!!!!!!!

        }        

        vx = (window.innerWidth - GV.game_W * scale)/2; //取得 Phaser canvas 離瀏覽器視窗邊界的左側空白區域(寬度)

        GV.BrowserLeft = vx;

        

        // 設定 iframe 新坐標 (根據介面上的排列狀況) 同時也調整 題目區 iframe 的背景(等待載入畫面)

        //if (LV.SQBox) { // 如果題目區存在 (代表這個函數不是第一次執行)(以後再改掉，不要用這種判斷方式，還是直接用旗標來管理)

        if (LV.SQr) { // 如果左側題目感應區存在

            if (LV.SQr.visible) { // 如果左側題目感應區存在

                if (QV.SQrIndex === 0){ // 題目感應區在左邊 (以後再改掉，不要用這種判斷方式，還是直接用旗標來管理)

                    if (LV.SKBod[2] && LV.SKr.visible) {

                        GV.QFrameX = vx + (LV.quizStatusWidth + LV.keyBoardWidth + deltax) * scale;

                    } else {

                        GV.QFrameX = vx + (LV.quizStatusWidth + deltax) * scale;

                    }



                } else { // 題目感應區在右邊

                    if (LV.SKBod[2] && LV.SKr.visible) {

                        GV.QFrameX = vx + (LV.keyBoardWidth + deltax) * scale;

                    } else {

                        GV.QFrameX = vx + deltax * scale;

                    }



                }



                LV.SBBox.x = 0; //LV.quizStatusWidth; // (Phaser) 題目區 iframe 背景 (等待載入畫面)

            }

        } else { // 左側題目感應區 不存在

            if (GV.QFrame) {

                if (LV.SKBod[2] && LV.SKr.visible) {

                    GV.QFrameX = vx + (LV.keyBoardWidth + deltax) * scale;

                } else {

                    GV.QFrameX = vx + deltax * scale;;//vx;

                }

            }

        }

        

        if (QV.STrY < (GV.game_H / 2)) { // 工具列在上方

            GV.QFrameY = (LV.menuBarHeight - deltay) * scale;

        } else { // 工具列在下方

            GV.QFrameY = (0 - deltay) * scale; //(LV.menuBarHeight - deltay) * scale;

        }

        

        // 動態調整 iframe 的寬度

        GV.QFrameW = (s_QFrameW() - 2 * deltax) * scale;

        GV.QFrameW = Math.ceil(GV.QFrameW);

        document.getElementById('qframe').style.width = GV.QFrameW + 'px';

        

        //if (GV.QFrameOldW != 0) {

        //    GV.QFrameScaleW = GV.QFrameW / GV.QFrameOldW;

        //}

        //GV.QFrameOldW = GV.QFrameW;

        

        //var t = Date.now(); // 顯示執行時間的除錯監視

        //GV.DebugMsg = '[' + (t - Math.floor(t/10000)*10000) + 'fS' + GV.QFrameW + ']' + GV.DebugMsg;



        // 動態調整 iframe 的高度

        GV.QFrameH = (s_QFrameH() - 2*deltay) * scale;

        GV.QFrameH = Math.ceil(GV.QFrameH);

        document.getElementById('qframe').style.height = GV.QFrameH + 'px';

        

        //if (GV.QFrameOldH != 0) {

        //    GV.QFrameScaleH = GV.QFrameH / GV.QFrameOldH;

        //}

        //GV.QFrameOldH = GV.QFrameH;

        

        document.getElementById('qframe').style.left = GV.QFrameX + "px";        

        document.getElementById('qframe').style.top = GV.QFrameY + "px";

        

        // window.console.log('x = ' + document.getElementById("qframe").style.left + ' / ' + LV.quizStatusWidth * scale);

        //GV.DebugMsg = "[視窗模式]" + scalex + " / " + scaley + " vx=" + vx;

        //GV.DebugMsg = "["+LV.STime.text + "](" + GV.QFrameW + "," + GV.QFrameH+")";

        

        

        

        // 即時調整 (測試用表層) canvas 的大小 (也會清除 canvas 的內容)

        //GV.MyCanvas.width = window.innerWidth;

        //GV.MyCanvas.height = window.innerHeight;

        

        // 即時調整 題目區 iframe 的背景載入等待圖案 的大小與位置

        if (LV.SBBox) {

            LV.SBBox.x = 0;

            LV.SBBox.y = 0;

            LV.SBBox.width = GV.game_W;//全部螢幕為大小

            LV.SBBox.height = GV.game_H;

        }



        //s_IOfunc(4, 0); // 第二層 canvas 測試

        

        

        if (QV.QFrame != null && QV.QBLoadOkFlag && QV.QBStatus === 0) { // .................... 維持題目寬度

            

            // window.console.log('[ifScale]' + (QV.QFrame == null) + " / " + QV.QBLoadOkFlag + " / " + QV.QBStatus);

            //GV.DebugMsg = GV.DebugMsg + "!!!" + GV.SyArg_QScale;

            iframeZoom(GV.SyArg_QScale);

        }

        

    } // 讓 iFrame 的大小隨時符合縮放效果

    //==========================================================================================================

    function s_dialogWin () { // 調整 div 對話顯示區的大小與位置



        var scale,scalex,scaley,vx,vy;

        

        //GV.DebugMsg = '%' + GV.DebugMsg;



        //var t = Date.now(); // 顯示執行時間的除錯監視

        //GV.DebugMsg = '[' + (t - Math.floor(t/10000)*10000) + 'sd]' + GV.DebugMsg;



        if(GV.ScrRotating){ // 螢幕還在旋轉

            return; // 離開

        }



        // 在此計算螢幕視窗的比例

        GV.WindowScaleX = window.innerWidth / GV.game_W;

        GV.WindowScaleY = window.innerHeight / GV.game_H;



        if (GV.WindowScaleX < GV.WindowScaleY){ // 根據兩種倍數大小，判斷是以水平或垂直填滿為主

            GV.WindowScale = GV.WindowScaleX; // 但仍然取最小值

            GV.ScaleMode = 0;

        } else {

            GV.WindowScale = GV.WindowScaleY; // 但仍然取最小值

            GV.ScaleMode = 1;

        }



        //if (window.innerWidth === screen.width && window.innerHeight === screen.height) {

        //    fullscreenflag = true;

        //}

                

                

        if (G4C.game.scale.isFullScreen) {

        //if (fullscreenflag) {

                    

            // ------------------------ 全螢幕的全螢幕的處理 -------------------------------------------

                                        

            // 全螢幕的放大並不是等比例放大， X 和 Y 方向必須個別考量，所以各自計算

            scalex = window.innerWidth / GV.game_W;

            scaley = window.innerHeight / GV.game_H;

            

            if (GV.ScaleMode === 0){

                scaley = scalex; // !!!!!!!!!!!!!!!!!! 縮放以水平倍數為主 !!!!!!!!!!!!!!!!!!!!!

                

            } else if (GV.ScaleMode === 1){

                scalex = scaley; // !!!!!!!!!!!!!!!!!! 縮放以垂直倍數為主 !!!!!!!!!!!!!!!!!!!!!

            }            

            scale = Math.min(scalex, scaley); // 取最小值，用來放大字體

            

            scalex = scale; // 讓兩個方向的比例都一樣 (等比例放大)

            scaley = scale; // 讓兩個方向的比例都一樣 (等比例放大)

                    

            s_consoleLog("FullScreen Mode" + G4C.game.scale.fullScreenScaleMode, "");

                    

            vx = (window.innerWidth - G4C.game.width * scalex)/2; // 水平置中狀況的遊戲視窗 x 坐標

 

            // 將內容固定大小，不要隨著瀏覽器大小改變而改變內容比例

            G4C.game.scale.fullScreenScaleMode = Phaser.ScaleManager.USER_SCALE;

            G4C.game.scale.setUserScale(scalex, scaley, 0, 0);

                    

            //GV.DebugMsg = "[全螢幕]" + window.innerHeight;

                    

                    

            //G4C.game.height = window.innerHeight;

                    

        } else {   

                    

            // [視窗模式] 的放大是設定為等比例放大，所以 X 和 Y 方向的比例雖然各自計算

            scalex = window.innerWidth / GV.game_W;

            scaley = window.innerHeight / GV.game_H;

            

            if (GV.ScaleMode === 0){

                scaley = scalex; // !!!!!!!!!!!!!!!!!! 縮放以水平倍數為主 !!!!!!!!!!!!!!!!!!!!!

                

            } else if (GV.ScaleMode === 1){

                scalex = scaley; // !!!!!!!!!!!!!!!!!! 縮放以垂直倍數為主 !!!!!!!!!!!!!!!!!!!!!

            }

                    

            scale = Math.min(scalex, scaley); // 但仍然取最小值

            scalex = scale; // 再把最小值設定給兩個方向的比例，以滿足等比例放大的效果

            scaley = scale;



            // 空白區域的計算，需要使用 G4C.game.width (當前的 canvas 大小)

            vx = (window.innerWidth - GV.game_W * GV.WindowScale)/2; //取得 Phaser canvas 離瀏覽器視窗邊界的左側空白區域(寬度)

                    

            if (vx < 0) { vx = 0;}

                    

            //s_showDialog("NotFS:" + vx + "/" + GV.WindowScale + " " + fullscreenflag, 1);

                    

            // 將內容固定大小，不要隨著瀏覽器大小改變而改變內容比例

            G4C.game.scale.scaleMode = Phaser.ScaleManager.USER_SCALE;

            G4C.game.scale.setUserScale(scalex, scaley, 0, 0);



        }

        

        //if (QV.QBLoadOkFlag) { // 這個限制會讓第一次的 s_iFrameScale() 沒執行到，導致後續題目顯示會出問題

            s_iFrameScale(); // 裡面已有 維持題目寬度 的函數呼叫

        //}

        



    }; // 調整 div 對話顯示區的大小與位置

    //==========================================================================================================

    function s_consoleLog (msg, msg2) { // 讓 console 視窗顯示資訊，輔助除錯之用途

        window.console.log(msg, msg2);

    }

	
    //==========================================================================================================

    function s_windowOpen (msg, msg2) { // 打開瀏覽器開網頁

        if(msg2 != ""){

            window.open(msg, msg2);

        } else {

           window.location.replace(msg);

        }

        

    }

    //======================================================================== [系統介面有關的綜合功能函數]=========

    function s_IOfunc (para1, para2) { // 系統介面有關的綜合功能函數

        

        //var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        

        //var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

        var x, y, w, h, str;

        

        //if (QFdocument.document) QFdocument = QFdocument.document; // 這一行的作用有待釐清(會干擾答案的傳送)

        

        if (para1 === 1){//---------------------------------------------------(1):控制 題目區 iframe 的深度

            if (para2 === 0) { // iframe 下降

                //document.getElementById("qframe").style.zIndex = "0";

                

                QV.QFrame.style.zIndex = "-1"; // 降低題目區 (QFrame) 深度

                

            } else { // iframe 上升

                

                //document.getElementById("qframe").style.zIndex = "1";

                QV.QFrame.style.zIndex = "0"; // 恢復 題目區 (QFrame) 深度

                

                //s_iFrameScale(); // 讓 iframe 題目區 回到正常大小

            }

        } else if (para1 === 2){// 傳送按鈕資料到題目區

            

            // 這裡的 QFdocument 確定不能用 if (QFdocument.document) ... 這一行，不然會出錯

            //QFdocument.mc.stopnow(para2); // 成功呼叫 iframe 裡的 animate javascript 函數

            //QFdocument.KW_Root.stopnow(para2); // 成功呼叫 iframe 裡的 animate javascript 函數

            QFdocument.KW_Check(para2); // 成功呼叫 iframe 裡的 animate javascript 函數

            

        } else if (para1 === 3) {// 改變題目區底色

            

            // 改變底色

            QFdocument.document.body.style.backgroundColor = "#ffffcc"; // 正常的淡黃色

            //QFdocument.document.body.style.backgroundColor = "#ffccff"; // 測試用的粉紅色

            

            //document.getElementById('qframe').style.width = GV.QFrameW + 'px';

            //document.getElementById('qframe').style.height = GV.QFrameH + 'px';

            

            //GV.DebugMsg = document.getElementById('qframe').style.width + ' / ' + document.getElementById('qframe').style.minWidth;

            

            //iframeZoom(-1);

            

            // 動態改變 iframe 寬度的測試

            //LV.keyBoardWidth = 400; // 虛擬鍵盤區的新寬度

            // [缺]-能將虛擬鍵盤重新畫過的步驟

            //s_iFrameScale();

            

            // 測試讓 phaser 的 div 不能接收滑鼠感應

            //document.getElementById('G4CCanvas').style.pointerEvents = "none";

            

            // 關閉左側題目感應區的測試

            LV.SQr.visible = !LV.SQr.visible; // 切換圖層的隱現

            

            // 關閉虛擬鍵盤區的測試

            LV.SKr.visible = !LV.SKr.visible; // 切換圖層的隱現

            

            

            // 馬上重新計算與顯示題目區

            s_iFrameScale();



        } else if (para1 === 4){// 第二層 Canvas 測試



            //var myCan = document.getElementById('myCanvas'); // 取得 myCanvas 的對應

            var myCan = GV.MyCanvas; // 取得 myCanvas 的對應

            

            // 第二層 Canvas 的測試

            myCan.style.zIndex = "1";

            ////myCan.style.position="fixed";

            

            //myCan.style.top = "55px"; // 可以移動整個 Canvas

            //myCan.style.left = "195px";

            

            //myCan.style.left = document.getElementById('qframe').style.left; // 取得 QFrame 的坐標 當自己的坐標

            //myCan.style.top = document.getElementById('qframe').style.top;



            //myCan.style.width = "775px"; // 但是再改這些屬性會變成 縮放 效果，連裡面的字都變形了

            //myCan.style.height = "490px";

            

            //myCan.style.pointerEvents = "none";

            //myCan.style.opacity = "0.1";

            

            // 瀏覽器改變畫面大小時，Canvas 並不會跟著改變大小或坐標 !!!!!!!!!!!!!!!!!!!

            

            /*

            用 GV.MyCanvas.width , GV.MyCanvas.height 調整 canvas 大小時，內容會消失，

            需要再重畫一次，但如果要配合畫面的縮放，坐標尺寸等要重新計算過

            */

            

            if (!GV.myCanvasFlag){

                GV.myCanvasFlag = true;

                var ctx = myCan.getContext('2d');



                //ctx.fillStyle = 'rgba(255, 0, 0, 0.2)'; // 透明

                //ctx.fillRect(GV.QFrameX, GV.QFrameY + 2, GV.QFrameW, GV.QFrameH); //左上 右下 坐標

                

                //x = 650;

                //y = 300;

                

                x = GV.QFrameX;

                y = GV.QFrameY;

                w = 240;

                h = 32;

                

                GV.MyCanvas.width = w; // 只是 reset size 也會清除內容

                GV.MyCanvas.height = h;

                

                ctx.fillStyle = 'rgba(255, 255, 255, 1)'; // 底框

                //ctx.fillRect(x, y, 260, 32);

                //ctx.rect(x, y, 260, 32);

                ctx.fillRect(0, 0, w, h); // 底色

                ctx.rect(0, 0, w, h); // 外框

                ctx.stroke();

                

                ctx.fillStyle = 'rgba(0, 0, 0, 1)';

                ctx.font = '20px 微軟正黑體';

                //ctx.fillText('表面圖層 Canvas 的測試~', x + 5, y + 23);

                ctx.fillText('表面圖層 Canvas 的測試~', 5, 23);

                

                GV.MyCanvas.style.left = x +"px";

                GV.MyCanvas.style.top = y +"px";



            } else {

                GV.myCanvasFlag = false;

                

                //var ctx = myCan.getContext('2d');

                //ctx.clearRect(0, 0, GV.game_W, GV.game_H); // 用最大範圍來清除

                

                GV.MyCanvas.width = window.innerWidth; // 只是 reset size 也會清除內容

                GV.MyCanvas.height = window.innerHeight;

                

            }



            

        } else if (para1 === 5){ //

            

            //QFdocument.myFunc(); // 呼叫 iframe 裡的 javascript 函數

            

            s_popWinZoom (0.5);

            

            var iframe = document.getElementById('myiframe');

            

            iframe.style.width = '290px';//'580px'; // iframe 的初始寬高尺寸....width 會改變普通網頁的視野

            iframe.style.height = '135px';

            

            

            /* //用 transform 的方法改變 iframe 的大小 (iPad OK)(但拖曳部分的座標要另外根據倍數再重新處理)

            iframe.style.transformOrigin = "0 0"; // 設定變形的原點

            iframe.style.transform = 'scale(0.5, 0.5)';

            */

            

            

            GV.DebugMsg = iframe.contentWindow.document.body.offsetHeight;

            

            

        } else if (para1 === 6){ // 測試

            

            //QFdocument.myFunc(); // 呼叫 iframe 裡的 javascript 函數

            

            // 直接處理 題目區 iframe 裡的 Animate CC 元件

            //QFdocument.mc.B2.visible = false; //..............OK

            // 直接改變 題目區 iframe 裡的 Animate CC 文字欄位內容

            QFdocument.KW_Root.B2.txt.text = "哈囉!"; //..............OK

            

            

            //GV.DebugMsg = iframe.contentWindow.document.body.offsetHeight;

            

            

        } else if (para1 === 7){ // 測試

            

            

            QFdocument.document.body.style.backgroundColor = para2; // 正常的淡黃色





            

        } else if (para1 === 8){ // 比賽模式中，選擇題選項處理測試

            

            h = Math.floor(Math.random() * 3) + 1; // 記號編號

            x = Math.floor(Math.random() * 4) + 1; // 選項編號

            w = Math.floor(Math.random() * 2) + 1; // 玩家編號

            

            s_gameItem (h, x, w);





            

        } else if (para1 === 9){ // 調整綠豆標題 / 調整(綠豆標題與)縮放按鈕的位置與大小

            

            y = GV.GreenBeanZoomH / 2;//24;

            

            // 調整 縮放按鈕大小

            QV.GFrame.contentWindow.document.getElementById('zoomBtn').style.width = (GV.GreenBeanZoomH*1.5) +"px";

            QV.GFrame.contentWindow.document.getElementById('zoomBtn').style.height = (GV.GreenBeanZoomH+5) +"px";

            QV.GFrame.contentWindow.document.getElementById('zoomBtn').style.fontSize= (y/0.7) +"px";

            

            QV.GFrame.contentWindow.document.getElementById('dragBar').style.height = (y * 1.9) +"px";

            

            QV.GFrame.contentWindow.document.getElementById('killBtn').style.width = (y * 1.6) +"px";

            QV.GFrame.contentWindow.document.getElementById('killBtn').style.height = (y * 1.8) +"px";

            QV.GFrame.contentWindow.document.getElementById('killBtn').style.fontSize= (y + 0) +"px";

            

            QV.GFrame.contentWindow.document.getElementById('dragTitle').style.height = (y * 1.8) +"px";

            QV.GFrame.contentWindow.document.getElementById('dragTitle').style.fontSize= (y/0.8) +"px";

            QV.GFrame.contentWindow.document.getElementById('dragTitle').style.left = (y * 2) + "px";

            

            if(GV.SyArg_BrwSize == 2 || GV.SyArg_ToolBarLoc == 0) { // 超大螢幕(電視) 或工具列下方顯示



                QV.GFrame.contentWindow.document.getElementById('empty').style.height = "0px";

                

            }

            

        } else if (para1 === 10){ // 填充題欄位往後切換

            

            //if (GV.SysMode === 0){ // 作答模式中

            if (QV.QBStatus === 0 && GV.InputFieldFlag){ // 正在填充輸入狀態中

                

                QFdocument.KW_inputFieldClick(para2);



            }

            



        } else if (para1 === 11){ // 讓題目端 切換解法

            

            QFdocument.KW_JumpAnswerPage();

            

        } else {

            

            // 縮小 iframe 題目區，讓出螢幕右邊的區域，用來顯示虛擬鍵盤 (但似乎有點沒必要???)

            //document.getElementById("qframe").style.width = "80%";

            //QV.QFrame.style.width = "80%";

                     

            //QFrame.contentWindow.testFunc(); // 成功呼叫 iframe 裡的 javascript 函數

                

            // 因為上面把 if (QFdocument.document) 這一行取消，不知會不會對後面使用 QFdocument 有何影響

            //QFdocument.testFunc(); // 成功呼叫 iframe 裡的 javascript 函數

            

        }

        

        

        //window.console.log('scroll = ' + html.scrollTop);



    } // 系統介面有關的綜合功能函數

	
	function strlength(timeleng)
	{
		
		if(timeleng.toString().length == 1)
			return "0"+timeleng;
		else
			return timeleng;
	}
	
	
	
    //==========================================================================================================

    function s_TimeStamp() { // 時間戳記

        return Math.floor(Date.now()/3600000); // 每小時變動

    } // (附加在載入網頁網址之後) 時間戳記

    //==========================================================================================================

    function s_QuizUnLoad (no) { // 負責在題目 卸載前 的處理步驟

        

        var i;
		
        

        // 在載入別題之前，將當題的資訊進行處理

        

        // 填充題的離開處理

        if (GV.InputFieldFlag) { // 填充題輸入中

            s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, -1); // 關閉這個欄位輸入狀態

        }

        

        //GV.DebugMsg = "NowAns:";

        //for (i = 1; i <= 4; i += 1) { // [預設] 每題最多有 50 小題

            //GV.DebugMsg += "[" + QV.NowAns[i] +"]";

        //}

        //GV.DebugMsg = "卸載第" + no + "題";

        

        

        // 批改當題(每小題比對)，紀錄整題的對錯結果在 

        //                        QV.QData[1][當題][2] = 0; // 該題的作答結果 (-1:未答 / 0:答錯 / 1:答對)

        

        if (GV.SysMode === 0){ // 作答模式中

         
	          // 將 學生答案(QV.NowAns) 編碼，紀錄在 QV.MyAns
			QV.MyAns[no] = s_AnsEncoder ();


         

        } else if (GV.SysMode < 0){ // // 比賽模式

            

            GV.DebugMsg = "" + GV.DebugMsg;

            

        } else if (GV.SysMode === 1){ // // 解答模式
		
			// 關閉流程控制按鈕區

            if (LV.QBCtPnlBackBtn) {

                LV.QBCtPnlBackBtn.visible = false;

                LV.QBCtPnlPlayBtn.visible = false;

                LV.QBCtPnlShowAllBtn.visible = false;

            }

            

            GV.PicWinPointer = ''; // 清除：題目中 PicWin 的完整路徑

        

        }

        

        // 清除 記錄當題答案陣列 QV.NowAns 的內容

        for (i = 1; i <= 50; i += 1) { // [預設] 每題最多有 50 小題

            QV.NowAns[i] = ""; // 清除小題答案值

            QV.OkNowAns[i] = ""; // 清除小題正確答案值

            

            QV.NPNowAns[1][i] = ""; // 第 1 位學生的第 i 題答案預設值(空白 = 未作答)(比賽模式用)

            QV.NPNowAns[2][i] = ""; // 第 2 位學生的第 i 題答案預設值(空白 = 未作答)(比賽模式用)

        }

        

        if(GV.Dr_Status >= 0) {

            Dr_Draw (2); // 清除繪圖

           /*  if (GV.Dr_Status == 1) { // 繪圖狀態中

                G4C.game.state.states['Main'].showPanel(1); // 切回一般狀態

            } */

        }

        

    }    

   //==========================================================================================================

    function s_QuizLoader (no, step) { // 負責題目的載入準備與啟動的各個階段動作

        

        //var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        //var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        var i, txt, 

            QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document
			
		
        if (step === 1 || step == null) { //------------------------------------------------ [題目載入的第一階段]

            

            //window.console.log('題目載入 第一階段 !');

            

            if (QFdocument.document) QFdocument = QFdocument.document;

        

            if (QV.QBStatus <= 0 || no < 0){ // 可以載入題目，以及預設的負值 (no < 0 是題目列表)



                s_iframeKill(); // 刪除目前的綠豆視窗(不管有沒有)



                QV.QBLoadOkFlag = false;

                QV.LoadingCount = 0; // 計數器歸零

                QV.QBStatus = 10; // 將題目載入狀態設定為載入中，並指定一個倒數數值

                

                GV.PicWinLockFlag = false; // 清除 PicWin 的鎖定狀態旗標

                GV.PicWinThumbNailMark = ''; // 清除 PicWin 圖釘指標

                

                //GV.GreenBeanLoadOk = false; // 清除綠豆載入旗標



                QV.QSub = 1; // 目前的題目的小題編號 (選擇題此變數固定為 1)(填充題則指向作答中的欄位編號)

                QV.QSubTotal = -1; // 目前的題目的小題總數 (預設值 -1 代表題目剛來，還沒被設定)



                // 讓 iframe 載入第 no 題的題目

                s_IOfunc(1, 0); // 讓 QFrame 降低深度(不要遮到 phaser)

                QV.QFrame.style.visibility = 'hidden'; //隱藏題目 qframe



                LV.SBBox.visible = true; // 讓 等待訊息 出現

                

                if (no > 0) { // 正常題目
					
				
					
								
                    txt = QV.QData[1][no][1]; // 路徑+題目檔名
					//QV.QFrame.src = QV.QBLink[no] + '.html';
                    //GV.DebugMsg = GV.DebugMsg + '[L:'+txt+']'; //??????????????????????????????????
					
                    if (GV.SysMode === 1) { // 解答模式
					
						if (!QV.QuizShowMode[7]) {
                            txt = txt + 's';
						}
                    }

                } else { // 載入題目列表檔

                    txt = 'QBase/[]_System/TypeList'; // 路徑+題目列表檔名

                    //GV.DebugMsg = GV.DebugMsg + '[L:'+txt+']';

                }
               

                //QV.QFrame.src = txt + '.html'  + '?name=' + Date.now(); // 原本的網址指定方式

                QV.QFrame.contentWindow.location.replace(txt + '.html'  + '?name=' + s_TimeStamp()); // 不留 history 的跳頁方式

                

                GV.QuizFileName = txt.substr(txt.lastIndexOf('/') + 1); // 取得路徑裡的題目檔名

                //GV.QBTimerPeriod = -1; // 讓系統端立即顯示新題號

                if(LV.VersionTxt) {

                    //LV.VersionTxt.text = '?冊?章?節' + String.fromCharCode(13) + GV.QuizFileName;// 讓系統端顯示新題號

                    LV.VersionTxt.text = GV.QuizFileName;// 讓系統端顯示新題號
					

                }

                

                if (GV.SysMode === 1){ // // 解答模式

                    if (LV.STPanelBtn[1][9]) {

                        LV.BigBtnStatus[9] = false;

                        LV.STPanelBtn[1][9].visible = false; // 隱藏 切換解法 的按鈕

                    }



                }

                //s_consoleLog('載入：' + QV.QData[1][no][1] + '.html'); // 提示訊息



            }

            

        //----------------------------------------------------------------------------------------------------

        // 第二階段在 函數 frameLoadOK ()：題目檔案本身載入完成 (但不知道題目自己所進行的載入動作是否完成) -------[第二階段]

        //----------------------------------------------------------------------------------------------------

            

        } else if (step === 2){ //----------------------------------------------------------- [題目載入的第二階段]

            

            // 來自 s_QBTimer ()在判斷 iFrame onload OK 之後的呼叫

            

            // 在這個階段，題目的 題型資訊(選擇/填充) 就會從 題目端 傳過來 系統端

            

            

            //window.console.log('題目載入 第二階段 !');

            

            QV.QBStatus = 2;

            

            

            

        } else if (step === 3){ //----------------------------------------------------------- [題目載入的第三階段]

            

            // 來自 s_QBTimer ()在判斷 QV.QBStatus = 2 之後的呼叫

            

           // window.console.log('題目載入 第三階段 !');

            

            // 在此進一步判斷 題目(檔) 的 題目顯示是否完成 (旗標)

            

            //GV.DebugMsg = GV.DebugMsg + '';

            

            if (QFdocument.KW_ShowOkFlag) {

                QV.QBStatus = 1; // 如果檢查到旗標變數已經 OK，將 QV.QBStatus 設定為 1，讓題目載入步驟繼續下去

            }

            

            

            //QV.QBStatus = 1; // 如果檢查到旗標變數已經 OK，將 QV.QBStatus 設定為 1，讓題目載入步驟繼續下去

            

            

        } else if (step === 4){ //----------------------------------------------------------- [題目載入的第四階段]

            

            // 來自 s_QBTimer ()在判斷 QV.QBStatus = 1 和 QV.QBLoadOkFlag = true 之後的呼叫

            //                            (不過 QV.QBLoadOkFlag = true 似乎不用在這裡又判斷一次)

            

            //window.console.log('題目載入 第四階段 !');

            

            // 到這裡，題目檔已經載入到 iframe，已經能正常運作，但是還在隱藏中

            

            // 顯現題目之前的流程：

            

            // 傳送目前題號給題目端 [題目端會自己拿] 

            

            // 根據現在系統的運作模式以及題型，取得題檔的資訊

            

            if (GV.SysMode <= 0){ // 作答模式中 與 比賽模式中

                

                // 取得正確答案(編碼字串) [題目端會傳過來到 QV.OkAns]

                

                // 取得題型資訊(選擇或填充) [題目端會傳過來到 QV.Type]

                

                // 將題目端傳來的正確答案的編碼字串，解碼到 QV.OkNowAns (批改答案用途)

                s_AnsDecoder (QV.OkAns[QV.QNow], true);

              

                // 傳送學生答案(編碼字串) [題目端會自己拿] (讓題目端顯示已作答的答案)

                

                if (QV.MyAns[QV.QNow].substr(0, 1) !== "@"){ // 該題已有作答 (系統端有該題的作答紀錄)



                    //GV.DebugMsg += " MyAns :" + QV.MyAns[QV.QNow];



                    s_AnsDecoder (QV.MyAns[QV.QNow], false); // 將所記錄的學生答案，解析來用在批改答案



                    ////s_IOfunc(2, QV.MyAns[QV.QNow]); // 傳送資料到題目區的題目檔

                    ////setTimeout(s_IOfunc(2, QV.MyAns[QV.QNow]), 3000);

                }

                

            } else if (GV.SysMode === 1){ // 解答模式

  

                //GV.DebugMsg = GV.DebugMsg + "";

                

                // 傳送學生答案(編碼字串) [題目端會自己拿] (讓題目端顯示已作答的答案)

      
                

                if (QV.QType[QV.QNow] === 6){ // 如果是填充題，在此送出 顯示正確答案 的動作

                    //GV.DebugMsg = "QL4" +"sdfsdf";

                    

                    // 將題目端傳來的正確答案的編碼字串，解碼到 QV.OkNowAns (批改答案用途)

                    s_AnsDecoder (QV.OkAns[QV.QNow], true);

                    

                    if (QV.MyAns[QV.QNow].substr(0, 1) !== "@"){ // 該題已有作答 (系統端有該題的作答紀錄)



                        //GV.DebugMsg += " MyAns :" + QV.MyAns[QV.QNow];



                        s_AnsDecoder (QV.MyAns[QV.QNow], false); // 將所記錄的學生答案，解析來用在批改答案



                        ////s_IOfunc(2, QV.MyAns[QV.QNow]); // 傳送資料到題目區的題目檔

                        ////setTimeout(s_IOfunc(2, QV.MyAns[QV.QNow]), 3000);

                    }

                    

                    // 顯示填充題的正解(改在別的地方呼叫)

                    s_fillAns ("B01", 0, 0, 0, 0, 5); // 參數:B1 只是測試用



                }

            }

            

            

            // 取得並確認題目端的 系統版本辨識變數(功能版本) 是否與系統端符合

           /* if (QFdocument.KW_SysKey !== GV.SysKey) {

                 GV.DebugMsg = '題目的版本 (' + QFdocument.KW_SysKey + ') 與系統端 (' + GV.SysKey + ') 不符合 !!!' ;

                

            } else if (QFdocument.KW_PbFnKey !== GV.SysKey) {

                // 繼續判斷 題目外部函式庫的 系統版本辨識變數(功能版本) 是否與系統端符合

                GV.DebugMsg = '外部函式庫的版本 (' + QFdocument.KW_PbFnKey + ') 與系統端 (' + GV.SysKey + ') 不符合 !!!' ;

                

            } else if (QFdocument.KW_TailFnKey !== GV.SysKey) {

                // 繼續判斷 題目外部函式庫的 系統版本辨識變數(功能版本) 是否與系統端符合

                GV.DebugMsg = '尾端函式庫的版本 (' + QFdocument.KW_TailFnKey + ') 與系統端 (' + GV.SysKey + ') 不符合 !!!' ;

                

            }*/





            

            // 對應就要出現的本題的虛擬鍵盤的切換 ........................................ ?????????????????????????

            

            //GV.DebugMsg = QV.QType[QV.QNow];

            

            if (GV.SysMode === 0){ // 作答模式中，才會出現虛擬鍵盤

                

                if (QV.QType[QV.QNow] === 1){ // 切換為選擇鍵盤

                    //QV.SKType = 1; // 選擇鍵盤

                    //G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                    //G4C.game.state.states['Main'].keyBoardFunc(1, -100, 0); // 重繪 感應區的左右切換按鈕



                    if (QV.SKType !== 1) {

                        QV.SKType = 1; // 選擇鍵盤

                        G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                        G4C.game.state.states['Main'].keyBoardFunc(1, -100, 0); // 重繪 感應區的左右切換按鈕

                    }

                

                } else { // 其他填充題的預設狀態是先 關閉鍵盤 (點選欄位才打開)

                

                    QV.SKType = -1; // 關閉鍵盤

                    G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                    G4C.game.state.states['Main'].keyBoardFunc(1, -100, 0); // 重繪 感應區的左右切換按鈕

                }

                

                // 改變(題目區網頁的)底色

                QFdocument.document.body.style.backgroundColor = GV.QBkGrdClr; // 正常的底色

                

                // 改變(題目區 Canvas的)底色

                QFdocument.document.getElementById("canvas").style.backgroundColor = GV.QBkGrdClr; // 正常的底色

            

            } else if (GV.SysMode < 0) { // 比賽模式

                // 一律為 選擇鍵盤

                if (QV.QType[QV.QNow] === 1){ // 切換為選擇鍵盤

                    if (QV.SKType !== 1) {

                        QV.SKType = 1; // 選擇鍵盤

                        G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                        G4C.game.state.states['Main'].keyBoardFunc(2, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                    }

                }

                

            } else if (GV.SysMode === 1) { // 解答模式

                

                //s_ctrlPanelFunc (0); // 管理 流程控制按鈕的顯示與否

                



                    

                if (QV.QData[1][QV.QNow][2] == 1) { // 答對

                    

                    // 改變(題目區網頁的)底色

                    QFdocument.document.body.style.backgroundColor = GV.QBkGrdOClr; // 答對的底色



                    // 改變(題目區 Canvas的)底色

                    QFdocument.document.getElementById("canvas").style.backgroundColor = GV.QBkGrdOClr; // 答對的底色

                    

                    

                } else { // 未作答 或 答錯

                    

                    // 改變(題目區網頁的)底色

                    QFdocument.document.body.style.backgroundColor = GV.QBkGrdXClr; // 答錯的底色



                    // 改變(題目區 Canvas的)底色

                    QFdocument.document.getElementById("canvas").style.backgroundColor = GV.QBkGrdXClr; // 答錯的底色

                        

                }



            }

            

             GV.Dr_ctx = GV.Dr_QF = Dr_makeCanvas (QFdocument.document); // 繪圖區準備

            

            



            /* 測試

            document.getElementById("qframe").style.backgroundImage = 'none'; //關閉背景的 loading

            QV.QFrame.style.backgroundImage = 'none'; //關閉背景的 loading

            */

            

            

            LV.SBBox.visible = false; //------------------------------------- [隱藏背後等待提示訊息的 loading 的圖案]

            

            



            if (LV.QNowBox) { // (左上角)現在題號的物件



                if (GV.SysMode >= 0){ // 作答模式和解答模式

                    LV.QNowBox.text = QV.QNow; //---------------------------- [顯示新的題目編號資訊]

                    LV.QTotalBox.text = QV.Qtotal; //------------------------ [顯示新的題目編號資訊]

                } else if (GV.SysMode < 0) { // 比賽模式

                    LV.QNowBox.text = GV.GameQBNow; //----------------------- [顯示比賽題目編號資訊]

                    LV.QTotalBox.text = GV.GameQBTotal; //------------------- [顯示新的題目編號資訊]

                }

            }

            

            QV.QBStatus = 0; // ------------------------------------------------ [到這裡，一個題目的載入與顯示完全結束]

            

            

            if (QV.QFrame != null && QV.QBLoadOkFlag) { //----------------------------------------- [維持題目寬度]

                

                //GV.DebugMsg += "@@@" + GV.SyArg_QScale;

                

                iframeZoom(GV.SyArg_QScale);

 

            }

            

            /*

            //---------------------------

            //-   改變(題目區網頁的)底色   -(改至他處)

            //---------------------------

            QFdocument.document.body.style.backgroundColor = "#ffffcc"; // 正常的淡黃色

            //QFdocument.document.body.style.backgroundColor = "#ffccff"; // 測試用的淡紅色

            //-------------------------------

            //-   改變(題目區 Canvas的)底色   -(改至他處)

            //-------------------------------

            //QFdocument.document.getElementById("canvas").style.backgroundColor = "#ffffcc"; // 正常的淡黃色

            //QFdocument.document.getElementById("canvas").style.backgroundColor = "#ffccff"; // 測試用的淡紅色

            QFdocument.document.getElementById("canvas").style.backgroundColor = "#ddffdd"; // 測試用的淡綠色

            */



            s_IOfunc(1, 1); // 讓 QFrame 提高深度(恢復正常)

            QV.QFrame.style.visibility = "visible"; //-------------------------------------- [顯現題目區的 iframe]

            // 這個數值不能影響題目的顯示，但是會不能觸控

            

            

            if (GV.SysMode < 0) { // 比賽模式

                if (GV.GameState < 0) {

                    GV.DebugMsg = "比賽中...開始作答";

                    GV.GameState = 0; // 開始比賽(作答)

                }

                

                for (i=1; i <= GV.PlayerTotal ; i += 1) { // 讓玩家開始作答

                    //(0:不能作答/-1:可以作答/-2:本次答錯[包含未作答而失敗]/大於0:本次答對[答對時間點])

                    GV.PlayerState[i] = -1;

                    //GV.PlayerState[2] = -1;

                }

            }

            

            //s_iFrameScale(); // 裡面已有 維持題目寬度 的函數呼叫

        }

        

    } // 負責題目的載入準備與啟動

    //==========================================================================================================

    function s_QBTimer () { // 定時執行函數

        //var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        //var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        var showtime, h, m, mm ,s, ss, zero = "00", i, flag, ratio;

        

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

        

        if (QFdocument.document) QFdocument = QFdocument.document;

        

        //GV.DebugMsg = GV.DebugMsg + QV.QBStatus;

        

        //----------------------------------------------

        // 定時動作：[計時顯示] ---------------------------

        //----------------------------------------------

        

        if(!GV.SysPracticeFlag) { // 不是練習模式

            LV.TimePass = Math.floor((Date.now() - LV.QNowTime) / 1000); // 經過的總秒數 (單位:秒)

            LV.TimeLeft = QV.TimeTotal - LV.TimePass; // 剩餘時間(總秒數)



            showtime = LV.TimeLeft;//LV.TimePass; // 要顯示的時間



            h = Math.floor(showtime / 3600);

            m = Math.floor((showtime - h * 3600) / 60);

            s = showtime - h * 3600 - m * 60;

            mm = zero.substr(String(m).length) + m;

            ss = zero.substr(String(s).length) + s;

        } else { // 練習模式

            LV.TimePass = 0; // 經過的總秒數 (單位:秒)

            LV.TimeLeft = QV.TimeTotal; // 剩餘時間(總秒數)

        }

        

        

        //--- 配合 每幾秒 執行一次：切換題號與系統版本訊息 (當 GV.QBTimerSecond = -1 會立即進入執行) -------

        //if ( (Math.floor(s % 5) == 0 && GV.QBTimerPeriod != s) || GV.QBTimerPeriod == -1){

        //    //GV.DebugMsg += '.';

        //    if ( (Math.floor(s / 5) % 3) == 2 && (GV.QBTimerPeriod != -1)){

        //        LV.VersionTxt.text = GV.version;

        //    } else { // 當 GV.QBTimerSecond = -1 會進入這裡

                //LV.VersionTxt.text = 'File : ' + GV.QuizFileName;

        //    }

        //    GV.QBTimerPeriod = s;

        //} //--------------------------------------------------------------------------------------



        if (GV.SysMode === 0){ // 作答模式中

            if(LV.STime) { // 如果計時器顯示元件存在

                if(GV.SysPracticeFlag) { // 練習模式

                    LV.STime.text = "練習模式";

                } else {

                    // 作答時間完畢的判斷

                    if(LV.TimeLeft > 0) { // 繼續顯示時間

                        if(h === 0){

                           LV.STime.text = mm + " : " + ss;

                        } else {

                           LV.STime.text = h + " : " + mm + " : " + ss;

                        }

                    } else { //----------------------- 倒數時間到 --------------------------

                        if(!GV.SysStopAnsFlag) {

                            LV.STime.text = "測驗結束";

                            GV.SysStopAnsFlag = true;

                            

                            G4C.game.state.states['Main'].sysPopIOWin(3); // 打開 問答視窗

                            

                            

                            //----------------------- 倒數時間到 --------------------------

                        }

                        

                    }

                    

                } 

                

                

            }

            

        } else if (GV.SysMode < 0) { // 比賽模式

            

            if (GV.GameTimer > 0) { // 在比賽緩衝時間之中

                GV.GameTimer -= 1;

                

                GV.DebugMsg = "比賽中..." + GV.GameTimer;

                

            } else {

                if (GV.GameState > 0) { // 前一回合結束

                    

                    GV.DebugMsg = "比賽中...繼續載入下一題";

                    

                    GV.GameState = -1;

                    

                    s_QuizUnLoad(QV.QNow); // 卸載題目

                    

                    if (GV.GameQBTotal === 0) { // 用原題庫的題數作為比賽用題數

                        

                        if (QV.QNow < QV.QBTotal) {

                            

                            QV.QNow += 1; // 繼續載入下一題

                            GV.GameQBNow += 1;

                            //if (QV.QNow > QV.QBTotal) {

                                //QV.QNow = 1;

                            //}

                            s_QuizLoader(QV.QNow, 1); // 載入目前題目

                        } else { // 最後一題了，比賽結束

                            GV.GameState = -3;

                        }

                    

                    } else if (GV.GameQBTotal > 0) { // 有指定的比賽題數 .....還需要一個變數：比賽目前題數指標

                        

                        if (GV.GameQBNow < GV.GameQBTotal) { // 目標總題數還沒出完

                            QV.QNow += 1; // 繼續載入下一題

                            GV.GameQBNow += 1;

                            if (QV.QNow > QV.QBTotal) { // 題目用完

                                QV.QNow = 1; // 從第一題重新出題

                            }

                            s_QuizLoader(QV.QNow, 1); // 載入目前題目

                        

                        } else { // 最後一題了，比賽結束

                            GV.GameState = -3;

                        }

                        

                    }

                    

                    

                    

                } else if (GV.GameState === 0) { // 比賽答題中

                    

                    // 在此檢查所有玩家，若是都答錯，則準備進入下一回合

                    flag = true; // 全部人都答錯旗標

                    for (i = 1 ; i <= GV.PlayerTotal ; i += 1){ // 目前所有玩家

                        if (GV.PlayerState[i] !== -2) { // 有人不是答錯

                            flag = false;

                            break;

                        }

                    }

                    if (flag) { // 全部人都答錯

                        

                        GV.GameState = 1000; // 用 1000 代表 全部人都答錯

                        

                        // 顯示正確答案

                        for (i = 1 ; i <= QV.QSubTotal ; i += 1){ // 找出選擇題的正確選項

                            if (QV.OkNowAns[i] === "1") { // 第 i 選項是答案

                                    s_gameItem (2, i, 0);

                            }

                        }

                        

                        GV.GameTimer = GV.GameTimerMax * 2; // 全答錯的話，緩衝時間兩倍

                    }

                    

                } else if (GV.GameState === -3) { // GV.GameState < 0 : 比賽結束

                    

                    GV.DebugMsg = "比賽結束...所有題目已經作答完畢";

                    

                    GV.GameQBTotal = -1; // 更新題目總數資訊

                    

                }

            }

            

        } else if (GV.SysMode === 1){ // 解答模式中

            

            if(QV.QBStatus == 0) {

                

                // 放在這裡是因為要抓滑鼠滾輪的不定時狀況 (iOS 在 sub_AniDraw 拖曳函數裡)

                if (GV.PicWinPointer != ''){

                    if (GV.BrowserType !== 'iOS') {

                        GV.iFrameScrollX = QFdocument.documentElement.scrollLeft; // 記憶水平捲動的坐標

                        GV.iFrameScrollY = QFdocument.documentElement.scrollTop; // 記憶上下捲動的坐標

                    }

                    

                    if (GV.PicWinLockFlag){ // PicWin 在鎖定狀態

                        s_fillAns (GV.PicWinPointer, 0, 0, 0, 0, 50); // PicWin 定位

                    }

                    

                }



                //=================== [在綠豆剛出現時，判斷綠豆會不會太大(for 手機設備)] =========================

                if (GV.GreenBeanLoadOk && GV.GreenBeanExist && GV.GreenBeanCheckStatus == 0) {

                    

                    GV.GreenBeanMinRatio = 0.5; // 綠豆視窗的最小比例

                    

                    if (QV.GFrameW > 0 && QV.GFrameH > 0) {

                        

                        //window.console.log('綠豆尺寸:' + QV.GFrameW + '/' + QV.GFrameH);

                        

                        GV.GreenBeanCheckStatus = 1; // 阻止重複檢查(綠豆出現後，只檢查一次)



                        if (QV.GFrameH > 0.7 * window.innerHeight || QV.GFrameW > 0.7 * window.innerWidth){ // 尺寸有問題



                            // 先取長寬比例的小值

                            ratio = 0.8 * Math.min((window.innerWidth / QV.GFrameW), (window.innerHeight / QV.GFrameH));



                            // 再跟原尺寸比較(取小值)

                            ratio = Math.min(ratio, 1);



                            // 取小數一位

                            ratio = 1 * Math.floor(ratio * 10) / 10;

                            GV.GreenBeanMinRatio = Math.min(0.5, ratio); // 綠豆視窗的最小比例

                            //GV.DebugMsg = 'Min='+GV.GreenBeanMinRatio;



                            s_popWinZoom (ratio, true);

                            //GV.DebugMsg = ratio;



                        } else { // 尺寸沒問題

                            //GV.DebugMsg = '.' + GV.DebugMsg;

                            if (QV.GFrame != null){

                                QV.GFrameX = window.innerWidth - QV.GFrameW;

                                QV.GFrame.style.left = QV.GFrameX + 'px'; // 讓綠豆視窗靠右

                            }

                        }

                    }



                }

            }

        }

        

        

        //----------------------------------------------------------------------------------------------------

        //--------------------- 定時動作：[重點載入] ------------------------------------------------------------

        //----------------------------------------------------------------------------------------------------

        /*

        []-檢查題目的載入狀態，不單靠 frameLoadOk 函數 的判斷效果

            []-題目裡面要安排一個全域變數，記錄題目的載入狀態，供系統隨時檢查

            QV.QBStatus > 2 : 代表 frameLoaderOk 尚未成功，還不用定時函數判斷題目的狀態

            QV.QBStatus = 2 : 代表 frameLoaderOk 已經判斷成功，接著讓定時函數接手判斷題目是否已經待命

            QV.QBStatus = 1 : 代表定時函數已經檢查到題目的全域變數，得知題目已經準備完成

            QV.QBStatus = 0 : 題目已經可以開始操作

        */

        //window.console.log('QV.QBStatus:' + QV.QBStatus);

        if (QV.QBStatus > 0) {

            if(QV.QBStatus === 1 && QV.QBLoadOkFlag) { // 題目(檔) 的內在旗標變數 已經是 準備完成 的狀態

                s_QuizLoader (QV.QNow, 4); // 題目載入的第四階段

            } else { // 載入等待的動態效果            

                QV.LoadingCount += 0.1; // 載入尚未完成，持續累計計數器

                LV.SBloadingMsg.text = "題 目 載 入 中 . . . " + Math.floor(QV.LoadingCount); // 載入等待的動態效果

            }

            if (QV.QBStatus === 2) { // qframe 已經用內建函數判斷為 load Ok

                // 如果檢查到旗標變數已經 OK，將 QV.QBStatus 設定為 1

                s_QuizLoader (QV.QNow, 3); // 題目載入的第三階段

            }

            if (QV.QBStatus >= 3) { // qframe 已經用內建函數判斷為 load Ok

                // 繼續判斷 題目(檔) 的內在旗標變數 是否準備完成

                QV.QBStatus -= 1;

                if (QV.QBLoadOkFlag){ // 如果 iFrame 的 onLoad 事件成功，此旗標會 true

                    s_QuizLoader (QV.QNow, 2); // 題目載入的第二階段

                    QV.QBStatus = 2;

                }

            }

        }

        //----------------------------------------------------------------------------------------------------

        

        

        // 正在填充輸入狀態中，控制該欄位外框的閃爍效果 (跟游標同步)

        if (QV.QBStatus === 0 && GV.InputFieldFlag){ 

            

            GV.InputFieldCount += 1;



            if (GV.InputFieldCount >= 0) {

                GV.InputFieldCount = -10;

                

                // 點亮

                //s_fillAns (GV.InputFieldNow, GV.InputFieldX, GV.InputFieldY, GV.InputFieldW, GV.InputFieldH, 2);

                s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, 2);

                

            } else if (GV.InputFieldCount === -5) {

                

                // 熄滅

                //s_fillAns (GV.InputFieldNow, GV.InputFieldX, GV.InputFieldY, GV.InputFieldW, GV.InputFieldH, 3);

                s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, 3);

            }

            

            //GV.DebugMsg = GV.InputFieldFlag + "/" + GV.InputFieldCount;

        }

        

        // 螢幕還在旋轉，監視長寬變化 (iPad 相關)

        if(GV.ScrRotating){

            

            GV.ScrRotatCount -= 1; // 次數遞減

            

            if (window.innerWidth != GV.ScrInOldW || GV.ScrRotatCount < 0){ // 寬度有變化

                

                if (window.innerHeight != GV.ScrInOldH || GV.ScrRotatCount < 0){ // 高度有變化

                    

                    GV.ScrInOldW = window.innerWidth; //瀏覽器內寬度(舊) 更新資料

                    GV.ScrInOldH = window.innerHeight; //瀏覽器內高度(舊)

                    

                    GV.ScrRotating = false;

                    

                    s_dialogWin ();

                    

                }

                

            }

            

            

        }

        

        if (GV.SysMode >= 0 && GV.Dr_WheelCount > 0){ // 作答或解答模式

            

            GV.Dr_WheelCount -= 1; // 配合滾輪不時會出現位置誤差的補救動作

            

            if (GV.Dr_Status >= 0) { // ========================================================================= 繪圖區

                

                if (GV.Dr_ctx){

                    GV.drawQ.style.left = - (GV.Dr_scrollX + 2*GV.Dr_WheelCount) + 'px';

                    GV.drawQ.style.top = - (GV.Dr_scrollY + 2*GV.Dr_WheelCount) + 'px';



                    // 配合滾輪不時會出現位置誤差的補救動作

                    if (GV.Dr_WheelCount <= 0) { GV.drawQ.style.visibility = 'visible'; }

                }

                

            } // ================================================================================================ 繪圖區

            

                

        }

        

        //GV.DebugMsg = QV.QState[QV.QNow];

        //GV.DebugMsg = QV.QType[QV.QNow];

        //GV.DebugMsg = '[scrInn](' + window.innerWidth + "," 

        //    + window.innerHeight + ")";

        //GV.DebugMsg = '['+ QV.GFrameX + '/' + QV.GFrameY + ']';

        





    } // 定時執行函數

    //==========================================================================================================

    function frameLoadOK () { //-------------------------------------------------------------- [題目載入的第二階段]

        //window.console.log('iFrame 載入成功 !!!');

        if (QV.QFrame == null){

            QV.QFrame = document.getElementById('qframe'); // 取得 iframe 的對應(一次執行，供其他使用)

        }

        QV.QBLoadOkFlag = true; // 讓定時事件 s_QBTimer () 進行判斷用

        

        //GV.DebugMsg += "fLOK]";

        

    }

    //==========================================================================================================

    function iframeScroll (para, dx, dy) { // iframe 內容的上下捲動(用拖曳才會發生)(iPad 不走這)(滑鼠滾輪不走這)

        //var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        //var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

        

        //if (!GV.MobileBrowserFlag) { // 不是手機瀏覽器

        

            if (QFdocument.document) QFdocument = QFdocument.document;



            var html = QFdocument.documentElement, // Chrome, Firefox, IE and Opera 

                tleft = html.scrollLeft,

                ttop = html.scrollTop;



            if (para != null){ // 似乎沒有用到

                if (!para){ // // 讓 iframe 向上捲動

                    html.scrollTop += 10;

                } else {

                    html.scrollTop -= 10;

                }

            } else { // 用 dx dy 控制



                tleft -= dx;

                if (tleft < 0){

                    tleft = 0;

                }



                ttop -= dy;

                if (ttop < 0){

                    ttop = 0;

                }



                html.scrollLeft = tleft;

                html.scrollTop = ttop;

            }

        /*

        if (GV.BrowserType === 'Android') { // 在 Android 需要處理的繪圖區移動

            if (GV.Dr_Status >= 0) { // ===================================================================== 繪圖區

                if (GV.Dr_ctx){

                    //GV.DebugMsg = "[tleft=" +  tleft + "]";

                    GV.drawCanvas.style.left = - html.scrollLeft + 'px';

                    GV.drawCanvas.style.top = - html.scrollTop + 'px';

                }

            } // ============================================================================================ 繪圖區

        }

        */

        

        //} else { // 是手機瀏覽器

            

            //var comp = QFdocument.KiWi_comp; // 取得題檔的相關元件

            

            //sub_AniDraw (GV.SyArg_QScale, dx, dy, QFdocument, comp);

            

        //}

        

        //window.console.log('scroll = ' + html.scrollTop);

        //GV.DebugMsg = 'iframeScroll-scroll = ' + html.scrollTop;

        

        //GV.iFrameScrollY = html.scrollTop; // 記憶上下捲動的坐標

    }

    //==========================================================================================================

    function sub_AniDraw (ratio, dx, dy, QFdoc, comp) { // 測試用 新的方法 移動 iPad 題目內容

        //...................................... 負責 Animate CC內容的繪製 (被 iframeZoom , s_resizeCanvas 呼叫使用)

        // ratio:縮放比例

        // dx,dy:位移 (1000 代表位置歸零)

        // QFdoc:對象所在的 document

        // comp 對象的 comp 元件

        if (QV.QFrame == null){ // for firefox 奇怪的 bug

            QV.QFrame = document.getElementById('qframe');

        }

        

        var lib = comp.getLibrary(), // 取得題檔的相關元件

            w = lib.properties.width, // 原題檔影片寬度

            h = lib.properties.height, // 原題檔影片高度

            qfw = QV.QFrame.style.width,

            qfh = QV.QFrame.style.height,

            iw = Math.floor(qfw.substring(0,qfw.indexOf('px'))), // 顯示區域的寬度

            //iw = GV.QFrameW, // 顯示區域的寬度

            ih = Math.floor(qfh.substring(0,qfh.indexOf('px'))), // 顯示區域的高度

            //ih = QFdoc.document.body.clientHeight, // 顯示區域的高度

            //ih = GV.QFrameH, // 顯示區域的高度

            pRatio = window.devicePixelRatio || 1,

            xRatio = iw / w,

            yRatio = ih / h,

            spaceDn, spaceLf, spaceRt,

            xScale, yScale, tw, th, tmp, ox, oy, dragFlag = false, iOSdrawMoveFlag = false;

        

        //GV.DebugMsg += "sub_[" + w + "/"  + iw + "/"  + h + "/" + ih + "/" + GV.QFrameH + "]";

        //GV.DebugMsg += "(" + qfw + "/" + qfh +")";

        //GV.DebugMsg += "(" + GV.QFrameW + "/" + GV.QFrameH + ")";

        

        GV.QFrameDrag = false; // 題目區是否可以拖曳的旗標，通知題目做判斷

        

        if (ratio < 0) { // 題目以 寬度全滿 呈現

            

            //GV.DebugMsg += '<'+ xRatio +'>';

            

            ratio = xRatio * 0.98; // 取寬比例為共同比例 (有 [以寬為準] 的效果)(乘上小數是避免水平卷軸有時出現)

            GV.SyArg_QScale = -1  * ratio; // 以負值代表全寬

            

            // 題目真正的高度應該是縮放之後的結果:Math.floor(h * ratio)

            // 後續與高度有關的動作，還是要再檢查一遍

            

            //GV.DebugMsg = GV.DebugMsg + "sub_[" + Math.floor(h * ratio) + "/" + ih + "/" + GV.QFrameH + "]";

            

        } else {

            ratio = GV.SyArg_QScale;

            

            if (ratio > xRatio) { // 開啓拖曳移動旗標 (因為 放大超過題寬的 才可以移動)

                dragFlag = true;

                GV.QFrameDrag = true;

            }

        }

        

        tw = w * ratio; // 題目的真正(縮放後)寬高

        th = h * ratio;

        

        if (Math.floor(th) > ih) { // 題目的真正(縮放後)高度，若超出顯示區就打開拖曳權限

            dragFlag = true;

            GV.QFrameDrag = true;

        }

        

        if (GV.Dr_Status >= 0) { // ========================================================================= 繪圖區

            

            if (GV.Dr_ctx){

                

                //GV.DebugMsg = "[ratio=" +  ratio + "]" + GV.QFrameW +'/'+ GV.QFrameScaleW;

                

                GV.drawQ.style.width = GV.Dr_Width * ratio + 'px'; // 能縮放

                GV.drawQ.style.height = GV.Dr_Height * ratio + 'px'; // 能縮放

                

                if (GV.BrowserType === 'iOS') { // 在 iOS 需要處理的繪圖區移動，先設定判斷旗標

                    iOSdrawMoveFlag = true;

                }



                //GV.drawCanvas.style.width = GV.QFrameW * ratio / GV.QFrameScaleW + 'px'; // 能縮放，但後來畫圖坐標會跟著(縮放)跑掉

                //GV.drawCanvas.style.height = GV.QFrameH * ratio / GV.QFrameScaleH + 'px'; // 能縮放



                // 繪圖空間尺寸

                //GV.drawCanvas.width = GV.QFrameW/ratio/ratio * window.devicePixelRatio + 100;

                //GV.drawCanvas.height = GV.QFrameH/ratio/ratio * window.devicePixelRatio + 100;

                

                //GV.Dr_ctx.lineWidth = 20 * window.devicePixelRatio;

                //GV.Dr_ctx.rect(0, 0, (GV.QFrameW-40)/ratio/ratio * window.devicePixelRatio - 100,

                //               (GV.QFrameH-40)/ratio/ratio * window.devicePixelRatio - 100); // 外框

                //GV.Dr_ctx.stroke();



            }

        } // ================================================================================================ 繪圖區

        

        if (GV.BrowserType === 'iOS'){ //'iOS'){

            

            

            

            tmp = QFdoc.document.body.style.left;

            ox = Math.floor(tmp.substring(0,tmp.indexOf('px'))); // 顯示區的目前坐標

            

            tmp = QFdoc.document.body.style.top;

            oy = Math.floor(tmp.substring(0,tmp.indexOf('px'))); // 顯示區的目前坐標

            

            //GV.DebugMsg = "[oy=" +  oy + "]";

            GV.iFrameScrollX = -ox; // 記憶水平捲動的坐標

            GV.iFrameScrollY = -oy; // 記憶上下捲動的坐標

            

            if (dx < 1000 && dy < 1000){ // 是拖曳

                

                QFdoc.document.body.style.position = 'fixed';



                if (dragFlag && dx !== 0){



                    dx += ox;



                    if (dx > 0){

                        dx = 0; // 向右方向的界限 (左邊不露白)

                    } else if (dx < (GV.QFrameW - tw)){ // 右邊有空白

                        dx = GV.QFrameW - tw; // 修正為右邊沒有空白

                    }



                } else {

                    if (dx === 1000) { // 傳來 1000，代表要位置歸零

                        dx = 0;

                    } else if (dx === 0) { // 原地

                        dx = ox;

                    }



                }

                // 改變 Animate CC 內容顯示的坐標 (for iOS)

                QFdoc.document.body.style.left = dx + 'px'; // 控制水平捲動位移 (也會影響顯示的可視範圍)

                

                if(iOSdrawMoveFlag){

                    GV.drawCanvas.style.left = dx + 'px';

                    GV.Dr_scrollX = -dx;

                }

                

                //------------------------------------------------------------------------------------------



                if (dragFlag && dy !== 0){



                    dy += oy;



                    if (dy > 0){

                        dy = 0; // 向下方向的界限 (上面不露白)

                    } else if (dy < (GV.QFrameH - th)){ // 下面有空白

                        dy = GV.QFrameH - th; // 修正為下面沒有空白

                    }



                } else {

                    if (dy === 1000) { // 歸零

                        dy = 0;

                    } else if (dy === 0) {

                        dy = oy;

                    }



                }



                // 改變 Animate CC 內容顯示的坐標 (for iOS)

                QFdoc.document.body.style.top = dy + 'px'; // 控制垂直捲動位移 (也會影響顯示的可視範圍)

                if(iOSdrawMoveFlag){

                    GV.drawCanvas.style.top = dy + 'px';

                    GV.Dr_scrollY = -dy;

                }

                //------------------------------------------------------------------------------------------

                



                return; // 只是拖曳，在此停止執行(不再往下執行而重新繪製題目)

                

                //GV.DebugMsg = "[]" + GV.DebugMsg;

                

            } else { // 原地縮放 (dx = 1000)

                

                

                

                //GV.DebugMsg = "[原地縮放]" + pRatio +'/th=' + th +'/oy=' + oy + '/' + GV.DebugMsg;

                

                spaceDn = ih - (oy + th); // 下面的空白 (= 0 代表沒空白)(< 0 代表下面還有內容沒顯示)

                

                spaceRt = iw - (ox + tw); // 右邊的空白 (= 0 代表沒空白)(< 0 代表右邊還有內容沒顯示)



                // (原地縮放的狀況處理) 調整位置 (避免縮放之後，任一側有不必要的空白)

                if (tw <= iw){ // 如果 tw 小於等於 iw：

                    if (ox < 0){ // 左邊超出

                        //GV.DebugMsg = '[左邊超出]=' + GV.DebugMsg;

                        ox = 0; //讓左邊沒空白

                    } else if(ox + tw > iw) { // 右邊超出(目前不會發生)

                        //GV.DebugMsg = '[右邊超出]=' + GV.DebugMsg;

                        ox = iw - tw; // 讓右邊沒空白

                    } else {

                        ox = 0; //左右位置歸零

                    }

                } else { // 題目太胖

                    if (ox > 0) {// 左邊有空白

                        ox = 0; //讓左邊沒空白

                    } else if(spaceRt > 0) { // 右邊有空白

                        ox = iw - tw; // 讓右邊沒空白

                    }

                }

                

                if (th <= ih){ // 如果 th 小於等於 ih

                    if (oy + th > ih){ // 下面超出

                        //GV.DebugMsg = '[下面超出]=' + GV.DebugMsg;

                        oy = ih - th; //讓下面沒空白

                    } else if(oy < 0){ // 上面超出

                        //GV.DebugMsg = '[上面超出]=' + GV.DebugMsg;

                        oy = 0; //讓上面沒空白

                    } else {

                        oy = 0; //上下位置歸零

                    }

                } else { // 題目太高

                    if (oy > 0) {// 上面有空白

                        //GV.DebugMsg = '[上面有空白]=' + GV.DebugMsg;

                        oy = 0; //讓上面沒空白

                    } else if(spaceDn > 0) { // 下面有空白

                        //GV.DebugMsg = '[下面有空白]=' + GV.DebugMsg;

                        oy = ih - th; // 讓下面沒空白

                    }

                }

                

                dx = ox; // 位移等於原地位置(其實就是目前位置不動)

                dy = oy;

                

                

            }

            QFdoc.document.body.style.left = dx + 'px'; // 控制水平捲動位移 (也會影響顯示的可視範圍)

            QFdoc.document.body.style.top = dy + 'px'; // 控制垂直捲動位移 (也會影響顯示的可視範圍)

            

            // 步驟 1/3 // 這裡不影響畫面大小，只影響內容的縮放程度

            // 步驟 2/3   // 直接放大 canvas (但內容會模糊) // 有 縮小/放大 整個區域的效果



            if (tw < GV.QFrameW) { // 水平不用調整

                //GV.DebugMsg = "[W" + tw + "/" + dx + "/" + GV.QFrameW + "]" + GV.DebugMsg;

                

                //var t = Date.now(); // 顯示執行時間的除錯監視

                //GV.DebugMsg = '[' + (t - Math.floor(t/10000)*10000) + 'W]' + GV.DebugMsg;

                

                //QFdoc.canvas.width = tw * pRatio; // 步驟 1/3 // 這裡不影響畫面大小，只影響內容的縮放程度

                //QFdoc.canvas.style.width = tw + 'px'; // 步驟 2/3 // 黃底

                

                //QFdoc.anim_container.style.width = tw + 'px'; // 綠底容器 變大 (跟 超出 有關)(也有超出的問題，需要判斷)

                

                

                /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

                if (tw > (GV.QFrameW -10)){

                    QFdoc.canvas.width = tw * pRatio;

                    QFdoc.canvas.style.width = tw + 'px'; // 內容 放大

                } else {

                    QFdoc.canvas.width = w * pRatio * ((GV.QFrameW -10) / w);

                    QFdoc.canvas.style.width = (GV.QFrameW -10) + 'px'; // 內容 放大

                }

                QFdoc.anim_container.style.width = (GV.QFrameW -10) + 'px'; // 綠底容器 變大

                //...............................................................................................

                

            } else {

                

                xScale = (GV.QFrameW - dx) / tw;

                

                //QFdoc.canvas.style.width = (GV.QFrameW - dx) + 'px'; // 步驟 2/3 // 顯示的可視範圍(黃底)受限

                ////QFdoc.anim_container.style.width = GV.QFrameW + 'px'; // 綠底容器 變大 (要跟 iframe 一樣大)

                QFdoc.anim_container.style.width = 0 + 'px'; // 綠底容器 變大 (要跟 iframe 一樣大)

                //QFdoc.canvas.width = tw * xScale * pRatio;// / xScale; // 步驟 1/3 // 因為受限，要把受限的比例調整回來

                /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

                if ((GV.QFrameW - dx) > (GV.QFrameW -10)){

                    //GV.DebugMsg = "q" + GV.DebugMsg;

                    QFdoc.canvas.width = tw * xScale * pRatio;

                    QFdoc.canvas.style.width = (GV.QFrameW - dx) + 'px'; // 內容 放大

                } else {

                    QFdoc.canvas.width = w * pRatio * ((GV.QFrameW -10) / w);

                    QFdoc.canvas.style.width = (GV.QFrameW -10) + 'px'; // 內容 放大

                }

                //...............................................................................................

            }



            if (th < GV.QFrameH){ // 垂直不用調整

                //GV.DebugMsg = "[H" + th + "/" + dy + "/" + GV.QFrameH + "]" + GV.DebugMsg;

                

                //QFdoc.canvas.height = th * pRatio; // 步驟 1/3 // 這裡不影響畫面大小，只影響內容的縮放程度

                //QFdoc.canvas.style.height = th + 'px'; // 步驟 2/3 // 黃底

                ////QFdoc.anim_container.style.height = th + 'px';



                /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

                if (th > GV.QFrameH){

                    QFdoc.canvas.height = th * pRatio;

                    QFdoc.canvas.style.height = th + 'px'; // 內容 放大

                } else {

                    QFdoc.canvas.height = h * pRatio * (GV.QFrameH / h);

                    QFdoc.canvas.style.height = GV.QFrameH + 'px'; // 內容 放大

                }

                QFdoc.anim_container.style.height = GV.QFrameH + 'px';

                //...............................................................................................

                

            } else {

                

                yScale = (GV.QFrameH - dy) / th; // ........... OK



                QFdoc.canvas.style.height = (GV.QFrameH - dy) + 'px'; // 步驟 2/3 // 顯示的可視範圍(黃底)受限

                //QFdoc.anim_container.style.height = GV.QFrameH + 'px';

                QFdoc.anim_container.style.height = 0 + 'px';

                QFdoc.canvas.height = th * yScale * pRatio; // 步驟 1/3 // 這裡不影響畫面大小，只影響內容的縮放程度

                

            }



            ////QFdoc.dom_overlay_container.style.width = GV.QFrameW + 'px'; // 內容的容器(綠底範圍) 變大

            //QFdoc.dom_overlay_container.style.width = 100 + 'px'; // 內容的容器(綠底範圍) 變大

            ////QFdoc.dom_overlay_container.style.height = GV.QFrameH + 'px'; //也是要配合 frame 的動態變化，不然會超出

            //QFdoc.dom_overlay_container.style.height = 100 + 'px'; //也是要配合 frame 的動態變化，不然會超出

            

            /* 把 Animate 運作範圍放大............................................................(取代上面//兩行)*/

            QFdoc.dom_overlay_container.style.width = (GV.QFrameW -10) + 'px'; // 內容的容器 變大

            QFdoc.dom_overlay_container.style.height = GV.QFrameH + 'px';

            //...............................................................................................

            

            

            

            

            

            // 步驟 3/3   // 純內容的縮放 (Canvas尺寸不會改變)

            QFdoc.stage.scaleX = pRatio * ratio;

            QFdoc.stage.scaleY = pRatio * ratio;

            

            

            

            



            



        } else {  // 非 iOS 的設備

            

            // 步驟 1/3    // 這裡不影響畫面大小，只影響內容的縮放程度

            //QFdoc.canvas.width = w * pRatio * ratio;

            //QFdoc.canvas.height = h * pRatio * ratio;

            /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

            if (w * ratio > (GV.QFrameW -10)){

                QFdoc.canvas.width = w * pRatio * ratio;

            } else {

                QFdoc.canvas.width = w * pRatio * ((GV.QFrameW -10) / w);

            }

            if (h * ratio > (GV.QFrameH - 0)){

                QFdoc.canvas.height = h * pRatio * ratio; // 內容的容器 變大

            } else {

                QFdoc.canvas.height = h * pRatio * ((GV.QFrameH -0) / h); // 內容的容器 變大

            }

            //QFdoc.canvas.height = h * pRatio * ratio;

            

            //...............................................................................................

            

            

            // 步驟 2/3   // 直接放大 canvas (但內容會模糊)

            //QFdoc.canvas.style.width = w * ratio + 'px'; // 內容 放大

            //QFdoc.canvas.style.height = h * ratio + 'px';

            /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

            if (w * ratio > (GV.QFrameW -10)){

                QFdoc.canvas.style.width = w * ratio + 'px'; // 內容 放大

            } else {

                QFdoc.canvas.style.width = (GV.QFrameW -10) + 'px'; // 內容 放大

            }

            if (h * ratio > (GV.QFrameH - 0)){

                QFdoc.canvas.style.height = (h * ratio) + 'px'; // 內容的容器 變大

            } else {

                QFdoc.canvas.style.height = (GV.QFrameH - 0) + 'px'; // 內容的容器 變大

            }

            //...............................................................................................

            

            

            //QFdoc.dom_overlay_container.style.width = w * ratio + 'px'; // 內容的容器 變大

            //QFdoc.dom_overlay_container.style.height = h * ratio + 'px';

            /* 把 Animate 運作範圍放大............................................................(取代上面兩行)*/

            if (w * ratio > (GV.QFrameW -10)){

                QFdoc.dom_overlay_container.style.width = w * ratio + 'px'; // 內容的容器 變大

            } else {

                QFdoc.dom_overlay_container.style.width = (GV.QFrameW -10) + 'px'; // 內容的容器 變大

            }            

            if (h * ratio > (GV.QFrameH - 0)){

                QFdoc.dom_overlay_container.style.height = (h * ratio) + 'px'; // 內容的容器 變大

            } else {

                QFdoc.dom_overlay_container.style.height = (GV.QFrameH - 0) + 'px'; // 內容的容器 變大

            }

            //...............................................................................................

            

            

            QFdoc.anim_container.style.width = w * ratio + 'px'; // 容器 變大 (跟 超出 有關)

            QFdoc.anim_container.style.height = h * ratio + 'px';

            

            // 步驟 3/3   // 純內容的縮放 (Canvas尺寸不會改變)

            QFdoc.stage.scaleX = pRatio * ratio;

            QFdoc.stage.scaleY = pRatio * ratio;

            

            /*

                即使是非 iOS 的設備，還是會需要一些拖曳狀況的控制 (放在顯示完畢的後面)

                狀況：[縮小或題寬] - 

                    捲回最上面：題目縮放後尺寸 < 顯示區域

                    原地：題目縮放後尺寸 > 顯示區域 (同時注意->題目邊界不要留白)

            */

            

            

            /*

            if(GV.SyArg_QScale <= 1 && th < ih) { // (縮小或題寬) 且 (題目縮放後尺寸 < 顯示區域)

                //GV.DebugMsg = "水平拖曳歸零";

                dx = 0; // 水平拖曳量

                dy = h * ratio; // 捲回最上面(上面無空白)

                this.iframeScroll (null, dx, dy);

            }

            */

            

        }

        

    } // 負責 Animate CC內容的繪製

    //==========================================================================================================

    function iframeZoom (ratio, dx, dy) { // 從系統端控制題目縮放的函數

        // ratio 是指定的縮放倍數

        // dx = 1000 代表位置歸零

        //GV.DebugMsg = "[ratio=" +  ratio + "]" + GV.DebugMsg;

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument), // 指向 iframe 裡的 document

            comp = QFdocument.KiWi_comp; // 取得題檔的相關元件

        

        if (ratio == null) {ratio = GV.SyArg_QScale;}

        //if (dx == null) {dx = 0;}

        //if (dy == null) {dy = 0;}

        

        if(GV.SyArg_QScale <= 1) { // 縮小或題寬，不能左右方向拖曳................( dx 的 1000 在這裡被過濾掉了)

            //GV.DebugMsg = "水平拖曳歸零";

            dx = 0; // 水平拖曳量

        }

        

        if (dx == null) {dx = 1000;}

        if (dy == null) {dy = 1000;}

        sub_AniDraw (ratio, dx, dy, QFdocument, comp);

        

        if (GV.PicWinPointer != ''){ // 管理 PicWin 的位置(不要超出可視範圍)

            //if (GV.BrowserType !== 'iOS') {

            //    GV.iFrameScrollX = QFdocument.documentElement.scrollLeft; // 記憶水平捲動的坐標

            //    GV.iFrameScrollY = QFdocument.documentElement.scrollTop; // 記憶上下捲動的坐標

            //}

                    

            //if (GV.PicWinLockFlag){ // PicWin 在鎖定狀態

                s_fillAns (GV.PicWinPointer, 0, 0, 0, 0, 52); // 測試

            //}

                    

        }

        

    } // 從系統端控制題目縮放的函數

    //==========================================================================================================

    function s_resizeCanvas (compKey, paracomp, gbflag) { // 被題目檔呼叫的畫面縮放控制函數

        //var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument), // 指向 iframe 裡的 document

            //comp = QFdocument.AdobeAn.getComposition(compKey); // 取得題檔的相關元件

        var qFrame,

            QFdocument, // 指向 iframe 裡的 document

            comp; // 取得題檔的相關元件

        

        if (!gbflag){ // 不是綠豆視窗模式(一般模式)

            qFrame = document.getElementById('qframe');

            QFdocument = (qFrame.contentWindow || qFrame.contentDocument); // 指向 題目 iframe 區

            comp = paracomp; // 取得題檔的相關元件

        } else {

            QFdocument = (QV.GFrame.contentWindow || QV.GFrame.contentDocument); // 指向 綠豆視窗 iframe 區

            comp = paracomp; // 取得題檔的相關元件

        }

        

        

        

        //sub_AniDraw (GV.SyArg_QScale, 0, 0, QFdocument, comp);

        sub_AniDraw (GV.SyArg_QScale, 1000, 0, QFdocument, comp);

        

        QFdocument.stage.tickOnUpdate = false;            

		QFdocument.stage.update();            

		QFdocument.stage.tickOnUpdate = true;

        

        

    }

    // 綠豆視窗也可以共用上面這個函數，但是要注意數值(ih)的計算差異....

    // 試過了，失敗 !!

    //==========================================================================================================

    function s_itemClicked (no) { // (選擇題)第 no 個答案選項 被按下

        

        var j = no;

        

        if (QV.NowAns[j] === "1") { // 該位置已經作答，再按一次代表取消作答

            //GV.DebugMsg = "取消：第 " + QV.QNow + " 題的第 " + j + " 個選項答案 ~";

            QV.NowAns[j] = ""; // 取消作答



        } else {

            //GV.DebugMsg = "清除：第 " + QV.QNow + " 題的" + QV.QSubTotal + "個答案 ~";
		
			if(QV.QType[QV.QNow] != 2)
			{
				for (i = 1; i <= QV.QSubTotal; i += 1) { // 先取消本題所有作答 (單選特性)

					QV.NowAns[i] = ""; // 取消作答

				}
			}

            //GV.DebugMsg = "第 " + player + " 支鍵盤：選擇答案鈕[" + ctxt + "]被按下 ~";



            QV.NowAns[j] = "1"; // 在答案陣列中，紀錄該題答案



        }

                    

        s_soundFX(1, 0); // 音效

                    

        // 馬上對 [學生答案] 進行編碼，並記錄到 QV.MyAns[QV.QNow]

        QV.MyAns[QV.QNow] = s_AnsEncoder();

        //GV.DebugMsg = "第" + QV.QNow + "題答案紀錄:" + QV.MyAns[QV.QNow];



        G4C.game.state.states['Main'].QBtnShow(QV.QNow, 2); // 改變 當題感應區 的資訊狀態 (已作答)

                    

        s_IOfunc(2, j); // 傳送資料到題目區的題目檔

        

    

    } // 選擇題選項按下-函數

    //==========================================================================================================

    function s_ctrlPanelFunc (para) { // 管理-流程控制按鈕隱現的函數 (被題目呼叫)

        // 執行時機：[題目剛載入][題目自己切換解法][流程按鈕被按過]

        // 負責根據相關的全域變數，管理這些按鈕該出現或消失

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

       

        if (para === 0){ // 預備 (讓題目在切換解法後，呼叫這裡)



            // 判斷題目是否需要管理流程 (根據某全域變數 KW_StepCtrlMv 是否未定義)

            if (QFdocument.KW_StepCtrlMv == null){ // 未定義 (不需要控制)

                

                LV.QBCtPnlBackBtn.visible = false;

                LV.QBCtPnlPlayBtn.visible = false;

                LV.QBCtPnlShowAllBtn.visible = false;

                

            } else {

                // 記憶一些相關變數

                QV.StepCtrlMv = QFdocument.KW_StepCtrlMv; // 控制對象 MovieClip

                QV.StepCtrlNow = QFdocument.KW_StepCtrlNow; // 目前步驟(從 1 開始)

                QV.StepCtrlTotal = QFdocument.KW_StepCtrlTotal; // 全部步驟(預設值為 1 )

			
                //GV.DebugMsg = "[" + QV.StepCtrlMv + "]:(" + QV.StepCtrlNow + "/" + QV.StepCtrlTotal + ")";

               

                s_ctrlPanelFunc (-2); // 更新顯示狀態

                

            }

            

        } else if (para === -2){ // 顯示管理

            
		
			
			
			
            if (QV.StepCtrlNow > 1){
				
				LV.QBCtPnlBackBtn.visible = true;

                if (QV.StepCtrlNow < QV.StepCtrlTotal){

                    LV.QBCtPnlPlayBtn.visible = true;

                    LV.QBCtPnlShowAllBtn.visible = true;

                } else {

                    LV.QBCtPnlPlayBtn.visible = false;

                    LV.QBCtPnlShowAllBtn.visible = false;

                }

                    

            } else {

                LV.QBCtPnlBackBtn.visible = false;

                if (QV.StepCtrlTotal == 1){ // 全部只有一個步驟(不需要控制)

                    LV.QBCtPnlPlayBtn.visible = false;

                    LV.QBCtPnlShowAllBtn.visible = false;

                } else {

                    LV.QBCtPnlPlayBtn.visible = true;

                    LV.QBCtPnlShowAllBtn.visible = true;

                }

            }
		           

        

        } else if (para === -1){ // 控制-倒退

            

            QV.StepCtrlNow -= 1;

           		
			if(QV.StepCtrlMv.length)
				QFdocument.mc[QV.StepCtrlMv].gotoAndStop("Step" + QV.StepCtrlNow);
			else
				QFdocument.mc.gotoAndStop("Step" + QV.StepCtrlNow);

            

            s_ctrlPanelFunc (-2); // 更新顯示狀態

            

        } else if (para === 1){ // 控制-前進

            

            QV.StepCtrlNow += 1;

            
			if(QV.StepCtrlMv.length)
				QFdocument.mc[QV.StepCtrlMv].gotoAndStop("Step" + QV.StepCtrlNow);
			else
				QFdocument.mc.gotoAndStop("Step" + QV.StepCtrlNow);

            

            s_ctrlPanelFunc (-2); // 更新顯示狀態

            

        } else if (para === 2){ // 控制-顯示全部

            

            QV.StepCtrlNow = QV.StepCtrlTotal;

            
			if(QV.StepCtrlMv.length)
				QFdocument.mc[QV.StepCtrlMv].gotoAndStop("Step" + QV.StepCtrlNow);
			else
				QFdocument.mc.gotoAndStop("Step" + QV.StepCtrlNow);

            

            s_ctrlPanelFunc (-2); // 更新顯示狀態

            

        } else if (para === 50){ // 被 切換解法的功能 借用此函數 (顯示： 系統端的切換解法 公用按鈕)

            

            //QFdocument.KW_JumpAnswerPage();

            //LV.SKQScorBox.visible = true;

            LV.BigBtnStatus[9] = true;

            LV.STPanelBtn[1][9].visible = true;

            

        } else if (para === 51){ // 被 切換解法的功能 借用此函數 (隱藏： 系統端的切換解法 公用按鈕)

            

            //QFdocument.KW_JumpAnswerPage();

            

        }

        

    } // 管理-流程控制按鈕隱現的函數 (被題目呼叫)

    //==========================================================================================================

    

    

    

    //==========================================================================================================

    function s_KeyInput (kcode) { // 處理(電腦端)實體鍵盤輸入(事件在系統網頁尾端)

        

        var fid, maxLen, ansLen, str = '';

        

        //window.console.log('key(' + kcode + ')');

        

        //if (QV.QType[QV.QNow] === 6) { // 填充題

        

        if (GV.InputFieldFlag && !GV.SysStopAnsFlag){ // 某填充欄位正在輸入狀態之中，而且在 可以作答 的系統狀態中

            

            fid = GV.InputFieldNow; // 輸入欄位的代號

            maxLen = QV.NowFiLen[QV.QSub]; // 正確答案長度

            str = QV.NowAns[QV.QSub]; // 目前答案

            ansLen = str.length; // 目前答案長度

            



            //var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

            

            //GV.InputFieldNow = ""; // 目前正在輸入的欄位名稱 (fid)

            

            //window.console.log('key(' + kcode + ')');

            

            if (kcode == -1){ // 特殊鍵 (Delete 鍵)

                

                QV.NowAns[QV.QSub] = ""; // 清除答案陣列的該小題

                

                s_fillAns (fid, 0, 0, 0, 0, 4); // 清光答案的效果

                

                // 馬上對 [學生答案] 進行編碼，並記錄到 QV.MyAns[QV.QNow]

                QV.MyAns[QV.QNow] = s_AnsEncoder();

                

            } else if (kcode == -2){ // 特殊鍵 (BackSpace 鍵)



                QV.NowAns[QV.QSub] = str.substr(0,ansLen - 1); // 在答案陣列中，紀錄最新的答案



                s_fillAns(fid, 0, 0, 0, 0, 1, QV.NowAns[QV.QSub]); // 顯示答案

                

                // 馬上對 [學生答案] 進行編碼，並記錄到 QV.MyAns[QV.QNow]

                QV.MyAns[QV.QNow] = s_AnsEncoder();

                

            } else if (kcode == 32){ // 一般鍵 (空白鍵)



                return; // 排除使用

                

            } else {

                

                if (ansLen === 0) { // 沒資料，直接填入

                    str = String.fromCharCode(kcode);



                } else if (ansLen < maxLen) { // 長度還夠輸入，附加在目前答案之後

                    str += String.fromCharCode(kcode);



                } else { // 長度已經滿了 // 用原本答案繼續進行(不改變)



                    //str = QV.NowAns[QV.QSub]; // 用原本答案繼續進行(不改變)



                }

                

                QV.NowAns[QV.QSub] = str; // 在答案陣列中，紀錄最新的答案

                

                s_fillAns(fid, 0, 0, 0, 0, 1, str); // 顯示答案

                

                // 馬上對 [學生答案] 進行編碼，並記錄到 QV.MyAns[QV.QNow]

                QV.MyAns[QV.QNow] = s_AnsEncoder();

                

            }

            

            //-------------------------------------------------------------------

            //this.QBtnShow(QV.QNow, 2); // 將 當題感應區 呈現 被按下 的狀態

            //-------------------------------------------------------------------





        }

        

        

		/*

        if (KW_InputFieldNow !== ""){ // 有欄位被選擇(輸入狀態中)



            // 從 KW_InputFieldNow 取得編號 (取末兩字母)

            var sno = Number(KW_InputFieldNow.slice(-2));



            //window.console.log('Field[' + sno + ']');



            var maxLen = KW_OkAns[sno].length; // 正確答案長度



            // 取得該欄位目前的內容 KW_MyAns

            var txt = KW_MyAns[sno];//, uKey = evt.charCode; // 取得按鍵的 UniCode



            //window.console.log('Field[' + sno + '/' + maxLen + '] get a key(' + kcode + ')');



            if (kcode < 0){ // 特殊鍵

                //KW_Root[KW_InputFieldNow].Flash.shape.graphics._fill.style = "#009900"; // 綠色框

                eval("KW_Root." + KW_InputFieldNow).Flash.shape.graphics._fill.style = "#009900"; // 綠色框

                txt = ""; // Delete 鍵

            } else { // 普通鍵





                // 根據狀況與條件，將 輸入的字元 附加在答案內容之後



                // (1) 欄位長度

                if (txt.length < maxLen){

                    //KW_Root[KW_InputFieldNow].Flash.shape.graphics._fill.style = "#009900"; // 綠色框

                    eval("KW_Root." + KW_InputFieldNow).Flash.shape.graphics._fill.style = "#009900"; // 綠色框

                    txt += String.fromCharCode(kcode);

                } else {

                    //KW_Root[KW_InputFieldNow].Flash.shape.graphics._fill.style = "#FF0000"; // 紅色框

                    eval("KW_Root." + KW_InputFieldNow).Flash.shape.graphics._fill.style = "#FF0000"; // 紅色框

                }



            }



            KW_MyAns[sno] = txt; // 存回 學生答案變數



            //KW_Root[KW_InputFieldNow].txt.text = txt; // 顯示欄位內容

            eval("KW_Root." + KW_InputFieldNow).txt.text = txt; // 顯示欄位內容

        }

        */

        

    } // 處理(電腦端)實體鍵盤輸入(事件在系統網頁尾端)

    //==========================================================================================================



    //-------------------------------[ 網頁網址參數 ]-----------------------------------------

    function s_GetSQLString (para) { // 處理 網頁網址參數 (QueryString) 讀取



        var linkurl = location.href,

            strall,

            strary,

            i, result = null;



        //var KW_RunAsGreenBeanFlag = false; // 旗標：是否在綠豆視窗中運作 (預設值是 false)(移動到外部函式庫)



        //window.console.log(linkurl);



        if(linkurl.indexOf('?') != -1){

            strall = linkurl.substr(linkurl.lastIndexOf('?') + 1);

            //window.console.log('str:['+strall+']');



            strary = strall.split('&');



            for(i = 0; i <= strary.length - 1 ; i += 1){



                if(strary[i].split('=')[0] == para){ // 找到指定的參數

                    result = strary[i].split('=')[1];



                    //window.console.log('取得 gbflag 的值 = ' + result);



                    //KW_RunAsGreenBeanFlag = true; //是在綠豆視窗中運作



                    break;

                }

            }

            return result;

        } else {

            return null;

        }

    } // 處理 網頁網址參數 (QueryString) 讀取

    

    //-------------------------------[ 重練問答視窗 ]-----------------------------------------

    function s_userIOBtnPos (row, sx, sy) { //====================================== 問答視窗按鈕 位置

        

        var i, w = 0, dx = 20;

        

        if (sx == null) { sx = GV.userIOKey[row][1].x; }

        if (sy == null) { sy = GV.userIOKey[row][1].y; }



        for (i = 1; i <= GV.userIOKey[row][0]; i += 1) {

            if (GV.userQType < 0) { // 會考模擬等測驗

                GV.userIOKey[row][i].visible = false;

            }

            if (GV.userIOKey[row][i].visible) {

                GV.userIOKey[row][i].x = sx + w;

                GV.userIOKey[row][i].y = sy;

                w += GV.userIOKey[row][i].width + dx;

                if (i == GV.userIOKey[0][row]) { // 這個按鈕有被按

                    GV.userIOKey[row][i].tint = GV.userIOKey[row][i].clr;

                } else { // 變暗

                    GV.userIOKey[row][i].tint = 0.4 * GV.userIOKey[row][i].clr;

                }

            }

            

        }

        

    } //================================ 重練問答視窗：按鈕位置排列

    function s_userIOKey (para) { //================================================ 問答視窗按鈕 函數

        

        var i, t, no, bno, link = '', folder = '',

            type = para.name.substr(0, 1),

            ctxt = para.name.indexOf('@'),

            keyno = Number(para.keyno); // 每個問答按鈕的獨立編號

        

        if (type === "%") { //------------------------------------- 問答視窗背景感應區 (按鈕)

            

            // 其實應該要 do nothing

            

            s_userIOWin(0); // 關閉 問答視窗

            return;

            

        } else if (type === "k") { //------------------------------------- kiki (按鈕)

            

            //window.console.log("題目列表");

            

            s_QuizUnLoad(QV.QNow); // 卸載題目

            s_QuizLoader(-1, 1); // 載入第 -1 號的題目 (題目列表)

            

            s_userIOWin(0); // 關閉 問答視窗            

            return;

            

        } else if (ctxt >= 0) {

            

            type = Number(para.name.substr(0, ctxt)); // 取得按鈕編號

            ctxt = Number(para.name.substr(ctxt + 1)); // 取得按鈕所在層次數

            

            //window.console.log(type + "@" + ctxt);

            

            for (i = 1; i <= GV.userIOKey[ctxt][0]; i += 1) { // 外觀變化

                if (i == type) { // 這個按鈕剛被按

                    para.tint = para.clr;

                    GV.userIOKey[0][ctxt] = type; // 該層已按下的按鈕編號

                } else { // 變暗

                    GV.userIOKey[ctxt][i].tint = 0.4 * GV.userIOKey[ctxt][i].clr;

                }

            }

        }

        
   
        

        if (keyno == 1) { // ------------------------------------------ 問答視窗：易

            GV.userIO_QzAllCount += 1;

            if (GV.userIO_QzAllCount >= 0) {

                GV.userIO_QzAllCount = 0;

                //GV.userIOKey[2][4].visible = true; // 顯示 [全部] 按鈕

                s_userIOBtnPos (2); // 重排水平位置

            }

        } else if (keyno === 2) { //----------------------------------- 問答視窗：中

            GV.userIO_QzAllCount -= 1;

			//GV.userIOKey[2][4].visible = false; // 隱藏 [全部] 按鈕

            if (GV.userIOKey[0][2] == 4) { GV.userIOKey[0][2] = 1; } // 該層(預設)已按下的按鈕編號

            s_userIOBtnPos (2); // 重排水平位置

        } else if (keyno === 3) { //----------------------------------- 問答視窗：難

            GV.userIO_QzAllCount += 1;

            if (GV.userIO_QzAllCount >= 0) {

                GV.userIO_QzAllCount = 0;

                //GV.userIOKey[2][4].visible = true; // 顯示 [全部] 按鈕

                s_userIOBtnPos (2); // 重排水平位置

            }

        } else if (keyno === 4) { //----------------------------------- 問答視窗：5 題

            

        } else if (keyno === 5) { //----------------------------------- 問答視窗：10 題

            

        } else if (keyno === 6) { //----------------------------------- 問答視窗：20 題

            

        } else if (keyno === 11) { //----------------------------------- 問答視窗：開始作答
		
			if(GV.SysMode == 1) //有按下批改按鈕才記錄離開時間
			{
				var today = new Date(); 
				var anstime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(today.getMinutes())+':'+strlength(today.getSeconds());
				send_php("writescore/writeans.php","anstime="+anstime+"&classnum="+s_GetSQLString('M')+"&Q="+s_GetSQLString('Q')); 
			}

            s_ReTest ();
            

        } else if (keyno === 12) { //----------------------------------- 問答視窗：回太空艙
		
			if(GV.SysMode == 1) //有按下批改按鈕才記錄離開時間
			{
				var today = new Date(); 
				var anstime = strlength(today.getDate())+'/'+strlength((today.getMonth()+1))+'/'+today.getFullYear()+'  '+strlength(today.getHours())+':'+strlength(today.getMinutes())+':'+strlength(today.getSeconds());
				send_php("writescore/writeans.php","anstime="+anstime+"&classnum="+s_GetSQLString('M')+"&Q="+s_GetSQLString('Q')); 
			}

            s_exitFunc(); // 回主選單(太空艙)

        }

        

    } //========================================== 重練問答視窗：按鈕函數

    function s_userIOBtn (keyno, cord, scx, scy, str, fclr, clr) { // -- 問答視窗：繪製按鈕

        

        var btn, btntxt,no,

            level = cord.indexOf('@');

        

        no = Number(cord.substr(0, level)); // 取得按鈕編號

        level = Number(cord.substr(level + 1)); // 取得按鈕所在層次數

        

        if (clr == null) { clr = 0xffffff; }

        

        btn = G4C.game.add.button(0, 0, 'kiwibtn', s_userIOKey,

                                                            this, "out", "out", "down", "out");

        btn.name = cord;

        btn.keyno = keyno; // 每個問答按鈕的獨立編號

        btn.scale.x = scx;

        btn.scale.y = scy;

        btn.anchor.set(0.5);



        //window.console.log(no + "@" + level + "/" + LV.pKey[0][level]);

        

        if (no !== GV.userIOKey[0][level] && no > 0) { // 不是被指定的按鈕

            btn.tint = 0.4 * clr;

        } else {

            btn.tint = clr; // 直接變亮

        }

        btn.clr = clr;



        // 顯示按鈕的文字

        btntxt = G4C.game.add.text(0, 4, str,

                                    { font: (8 + 20 * scy)  + "px " + QV.ChFont, fill: "#000000"});

        btntxt.anchor.set(0.5, 0.5);

        btntxt.fill = fclr;

        btntxt.scale.x = 1 / scx;

        btn.addChild(btntxt);



        //LV.SKBox.addChild(btn);

        return btn;

        

    }  //======== 重練問答視窗：繪製按鈕

    function s_userIOWin (type) { // 先用來製作：重複測驗的問答視窗

        

        var w, h, x , y , ty, sx = 200, box, txt, pic, btn1, btn2, t;

        

        //console.log('試卷模式:' + GV.userIOKey[0][1] + '/' + GV.userIOKey[0][2]);

        

        if (type === 0) { //............................................ 關閉目前的問答視窗

            

            if (GV.userIOWinBox) { GV.userIOWinBox.kill(); } // 如果有舊的，先刪除

            

        } else if (type > 0) { //....................................... 問答視窗 準備開始

        

            if (GV.userIOWinBox) { GV.userIOWinBox.kill(); } // 如果有舊的，先刪除

            

            s_IOfunc(1, 0); // 讓 QFrame 降低深度(不要遮到 phaser)

            

            if (GV.userIOKey[0][2] == 4) { GV.userIOKey[0][2] = 1; } // 讓 [全部] 題數的選項不要被記憶

            

            // 共同的準備動作 感應背景

            GV.userIOWinBox = G4C.game.add.graphics(0, 0);

            GV.userIOWinBox.beginFill(0x000000, 1); //底色

            GV.userIOWinBox.lineStyle(2, 0xffffff, 1); // draw a rectangle

            GV.userIOWinBox.fillAlpha = 0.5; // 感應區的透明度

            GV.userIOWinBox.drawRect(0, 0, GV.game_W, GV.game_W); // 區域

            GV.userIOWinBox.inputEnabled = true; // 加上滑鼠互動函數

            GV.userIOWinBox.events.onInputDown.add(s_userIOKey, this);

            GV.userIOWinBox.name = "%";

            

            if (type === 1) { //................................................. 問答視窗：[]

                
				if(s_GetSQLString('M').substr(2,1) == 0)
				{
					w = 800; // 寬度
					h = 450; // 高度
					GV.userIO_QzAllFlag = false;
					GV.userIOKey[0][2] = 4;
				}
				else
				{
					w = 1000; // 寬度
					h = 600; // 高度
					GV.userIO_QzAllFlag = true;
				}
           
				

                ty = h / 8;

                if (GV.userQType < 0) { // 會考模擬等測驗

                    h = 400; // 高度

                    ty = h / 3;

                }

                

                x = (GV.game_W - w) / 2;

                y = (GV.game_H - h) / 2;

                

                box = G4C.game.add.graphics(x + 20, y + 20); // ------- 陰影

                box.beginFill(0x000000, 1); //底色

                //box.lineStyle(4, 0xffffff, 1); // draw a rectangle

                box.drawRect(0, 0, w, h); // 區域

                

                GV.userIOWinBox.addChild(box);

                

                box = G4C.game.add.graphics(x, y);

                box.beginFill(QV.IOBkGrdClr, 1); //底色(用系統底色)

                box.lineStyle(4, 0xffffff, 1); // draw a rectangle

                box.drawRect(0, 0, w, h); // 區域

                
				if(s_GetSQLString('M').substr(2,1) == 0)
					txt = G4C.game.add.text(box.width / 2.5, ty+20, "再測驗一次？",
											{ font: 60  + "px " + QV.ChFont, fill: "#ffff00"});
				else
					   txt = G4C.game.add.text(box.width / 2, ty, "再測驗一次？",
                                            { font: 60  + "px " + QV.ChFont, fill: "#ffff00"});

                txt.anchor.set(0.5, 0.5); // 垂直置中

                box.addChild(txt);

                

                pic = G4C.game.add.sprite(txt.x - txt.width / 2 - 30, txt.y - 10, "kiki"); // 頭像

                pic.anchor.set(1, 0.5);

                pic.scale.x = 1.2;

                pic.scale.y = pic.scale.x;

                pic.inputEnabled = true; // 加上滑鼠互動函數

                pic.events.onInputDown.add(s_userIOKey, this);

                pic.name = "k";

                box.addChild(pic);

                

               
					
					// [易] [中] [難]
                    // 第一層：難易度 顯示三個按鈕

					GV.userIOKey[1][0] = 3; // 該層按鈕總數
					if (GV.userIOKey[0][1] < 1) { // 是預設值
						GV.userIOKey[0][1] = 1; // 該層(預設)已按下的按鈕編號
					}
					
					GV.userIOKey[1][1] = s_userIOBtn(1, '1@1', 1.2, 1.5, '易', '#000000', 0x00ff00);
					GV.userIOKey[1][1].x = sx + GV.userIOKey[1][1].width / 2;
					if(s_GetSQLString('M').substr(2,1) == 0)
						GV.userIOKey[1][1].y = h * 1.2 / 2.5;            
					else
						GV.userIOKey[1][1].y = h * 1.2 / 4;            
					box.addChild(GV.userIOKey[1][1]);
					
					GV.userIOKey[1][2] = s_userIOBtn(2, '2@1', 1.2, 1.5, '中', '#000000', 0xffff00);
					GV.userIOKey[1][2].x = w * 2 / 4;
					GV.userIOKey[1][2].y = GV.userIOKey[1][1].y; 
					GV.userIOKey[1][2].visible = GV.userIO_QzAllFlag;					
					box.addChild(GV.userIOKey[1][2]);
					
					GV.userIOKey[1][3] = s_userIOBtn(3, '3@1', 1.2, 1.5, '難', '#000000', 0xff0000);
					GV.userIOKey[1][3].x = w * 3 / 4;           
					GV.userIOKey[1][3].y = GV.userIOKey[1][1].y;            
					box.addChild(GV.userIOKey[1][3]);
					

                	s_userIOBtnPos (1); // 重排水平位置

                	// [5 題] [10 題] [20 題] / [全部] .......(單選 [難] 或 [易] ，才出現 [全部] ，選 [中] 沒有 [全部] )
					// [全部] 不會直接出現，要設計一個密技來打開開關
					// 第二層：題數 顯示三個按鈕
	
					GV.userIOKey[2][0] = 3; // 該層按鈕總數
					if (GV.userIOKey[0][2] < 1) { // 是預設值
						GV.userIOKey[0][2] = 1; // 該層(預設)已按下的按鈕編號
					}

					GV.userIOKey[2][1] = s_userIOBtn(4, '1@2', 1.2, 1.5, '5 題', '#000000', 0xffffff);
					GV.userIOKey[2][1].x = sx + GV.userIOKey[2][1].width / 2;
					GV.userIOKey[2][1].y = h * 1.9 / 4;
					GV.userIOKey[2][1].visible = GV.userIO_QzAllFlag;
					box.addChild(GV.userIOKey[2][1]);

					GV.userIOKey[2][2] = s_userIOBtn(5, '2@2', 1.2, 1.5, '10 題', '#000000', 0xffffff);
					GV.userIOKey[2][2].x = w * 2 / 4;
					GV.userIOKey[2][2].y = GV.userIOKey[2][1].y;
					GV.userIOKey[2][2].visible = GV.userIO_QzAllFlag;
					box.addChild(GV.userIOKey[2][2]);

					GV.userIOKey[2][3] = s_userIOBtn(6, '3@2', 1.2, 1.5, '20 題', '#000000', 0xffffff);
					GV.userIOKey[2][3].x = w * 3 / 4;
					GV.userIOKey[2][3].y = GV.userIOKey[2][1].y;
					GV.userIOKey[2][3].visible = GV.userIO_QzAllFlag;
					box.addChild(GV.userIOKey[2][3]);

                	/*GV.userIOKey[2][4] = s_userIOBtn(7, '4@2', 1.2, 1.5, '全部', '#000000', 0xffffff);
					GV.userIOKey[2][4].x = w * 4 / 4;
					GV.userIOKey[2][4].y = GV.userIOKey[2][1].y;
					//GV.userIOKey[2][4].tint = 0x222222;
					GV.userIOKey[2][4].visible = GV.userIO_QzAllFlag;
					//GV.userIO_QzAllCount = -5; // 問答視窗的[全部] 按鈕的啟用計數器 起始值
					box.addChild(GV.userIOKey[2][4]);*/
					

					s_userIOBtnPos (2); // 重排水平位置

                	// [普通出題(不重複)] 、[弱題優先] 與 [錯題重練] (預設值=普通出題) (選只能選其中一個)
            		// 第三層：難易度 顯示三個按鈕
					
					GV.userIOKey[3][0] = 3; // 該層按鈕總數
					GV.userIOKey[0][3] = 1; // 該層(預設)已按下的按鈕編號
					GV.userIOKey[3][1] = s_userIOBtn(8, '1@3', 1.5, 1.5, '普通出題', '#000000', 0xaaffff);
					GV.userIOKey[3][1].x = sx + GV.userIOKey[3][1].width / 2;
					GV.userIOKey[3][1].y = h * 2.6 / 4; 
					GV.userIOKey[3][1].visible = GV.userIO_QzAllFlag;					
					box.addChild(GV.userIOKey[3][1]);

					GV.userIOKey[3][2] = s_userIOBtn(9, '2@3', 1.5, 1.5, '弱題優先', '#000000', 0xaaffff);
					GV.userIOKey[3][2].x = w * 2 / 4;
					GV.userIOKey[3][2].y = GV.userIOKey[3][1].y;  
					GV.userIOKey[3][2].visible = GV.userIO_QzAllFlag;					
					box.addChild(GV.userIOKey[3][2]);

					GV.userIOKey[3][3] = s_userIOBtn(10, '3@3', 1.5, 1.5, '錯題重練', '#000000', 0xaaffff);
					GV.userIOKey[3][3].x = w * 3 / 4;
					GV.userIOKey[3][3].y = GV.userIOKey[3][1].y; 
					box.addChild(GV.userIOKey[3][3]);

                	if (QV.Exam[1][2] < QV.Qtotal && s_GetSQLString('M').substr(2,1) != 0) { // 第 1 次測驗的答對題數 < 總題數
						GV.userIOKey[3][3].visible = true; // 顯示 錯題重練
					} else {
						GV.userIOKey[3][3].visible = false;
					}

                	s_userIOBtnPos (3); // 重排水平位置
				

                // [開始測驗] [回主選單/回太空艙]

                if (GV.userQType < 0) { // 是會考模擬等測驗

                    ty = h * 3 / 4;

                    t = '再試一次';

                } else {

                    ty = h * 3.4 / 4;

                    t = '開始測驗';

                }

                btn1 = s_userIOBtn(11, '', 2.3, 1.8, t, '#000000', 0xcccccc);

                btn1.x = w / 2;

                btn1.y = ty;

                box.addChild(btn1);

                

                btn2 = s_userIOBtn(12, '', 1.8, 1.5, '回太空艙', '#000000', 0xcccccc);

                btn2.x = w - w / 6;

                btn2.y = txt.y - 5;            

                box.addChild(btn2);

                

                

                GV.userIOWinBox.addChild(box); // 要放在最後

                

            }

            

            

        }

    } //========================================== 重練問答視窗：製作視窗

    

    function s_ReTest () {

        

        var i;

        

        s_QuizUnLoad(); // 卸載題目(不能省略)

        

        GV.SysMode = 0; // 系統運作模式旗標

        

        QV.SKType = 1; // 虛擬鍵盤的狀態旗標 (-1: 無鍵盤[題目區的捲軸] / 1:選擇題 ABCD / 3:填充題)

        

        QV.IOBkGrdClr = 0x225566; // 作答模式

		

        // 接著進入的場景

        G4C.game.state.start("Ready");

        

    } //================================================= 重新進行測驗



    //==========================================================================================================

    function s_AnsEncoder () { // 將目前題目的學生答案，編碼儲存成一個字串

        // QV.QNow : 目前題號



        var i, j, tmp = "", qtotal = QV.QSubTotal, qcounter = 0;

     

        if (qtotal < 1) {return String.fromCharCode(64 + 0); } // 直接回覆 0 個答案



        for (i = 1 ; i <= qtotal ; i += 1){



            if (QV.NowAns[i] !== ""){ // 有回答
		
                j = QV.NowAns[i].toString().length; // 答案字元長度

                qcounter += 1;
		

            } else {

                j = 0; // 答案字元長度

            }



            tmp += "["+ String.fromCharCode(64 + j);// 將 答案字元長度 編碼

            tmp += QV.NowAns[i] + "]";// 將 答案 編碼

            

            //GV.DebugMsg += "[" + QV.NowAns[i] + "]";

         }

        

        tmp = String.fromCharCode(64 + qcounter) + tmp; // 將 有回答的答案總數 編碼，若都沒回答，此處編碼結果會是 @ (小老鼠)

        

        //GV.DebugMsg = " (" + QV.QNow+ ")編碼：" + QV.QSubTotal + "個答案:" + tmp + GV.DebugMsg;

        

        return tmp;

        

    } // 答案傳遞前的編碼函數

    //==========================================================================================================

    function s_AnsDecoder (str, flag) { // 將答案的編碼字串，解碼到適當陣列

        // flag = true : 將 正確答案 的編碼字串，解碼到 QV.OkNowAns

        // flag = false : 將 學生答案 的編碼字串，解碼到 QV.NowAns

        // QV.QNow : 目前題號



        var qtotal = 0, i, j, tmp = "", qcounter = 0,

            str, tail, count, head, samflag, t, maxlen = 0;


		
        qtotal = str.substr(0,1).charCodeAt(0) - 64;


        if (flag) {

            QV.QSubTotal = qtotal; // 紀錄小題數目 (從正確答案字串)

            //GV.DebugMsg = "解析" + QV.QNow + "正確答案:" + str;

        } else {

            qtotal = QV.QSubTotal;

            //GV.DebugMsg += "解析" + QV.QNow + "學生答案:" + str;// + "("+qtotal+")";

        }

        

        tmp = str.substr(2);



        //tmp3 = qtotal + "個答案:";



        for (i = 1 ; i <= qtotal ; i += 1){

            j = tmp.substr(0,1).charCodeAt(0) - 64; // 第 i 個答案的長度

            tmp = tmp.substr(1); // 跳過間隔符號

            if (flag) { // 解析正確答案

                QV.OkNowAns[i] = tmp.substr(0,j); // 取得第 i 個正確答案

                

                // 在此判斷是否為填充題，並計算填充正確答案的長度

                maxlen = 0; //預設正確答案的長度 0

                if (QV.QType[QV.QNow] === 6){

                    //GV.DebugMsg += "  填充[" + i + "]長度 = " + QV.OkNowAns[i].length;

                    

                    //window.console.log('填充'+i+':'+QV.OkNowAns[i]);

                    

                    // 在此繼續解析 [同質答案]

                    str = QV.OkNowAns[i];

                    QV.OkNowSame[i][1] = 0; // 第 i 小題的(同質)答案數量

                    QV.OkNowSame[i][2] = 0; // 第 i 小題的答案比對指標



                    count = 0;

                    head = 0;

                    samflag = false;

                    do {



                        str = str.substr(head);

                        tail = str.indexOf('|');

                        

                        if (tail > 0) { // (一個小題裡)有多個同質答案

                            samflag = true;

                            count += 1;

                            t = str.substr(0, tail);



                            //window.console.log('t' + "_" + count + '=' + t);// + ' /[' + tmp + ']');



                        } else {

                            count += 1;

                            t = str;



                            //window.console.log('t=' + tmp);

                        }

                        

                        if (samflag == true && count == 1) { // t 是同質組別編號

                        

                            //window.console.log("Grp[" + t + "]");

                            

                            QV.OkNowSame[i][0] = Number(t); // 同質組別編號



                        } else { // 是答案

                            

                            QV.OkNowSame[i][1] += 1; // 累積答案數量

                            

                            if (t.length > maxlen) {

                                maxlen = t.length; // 取較長的答案長度

                            }

                            

                            if (count == 1) { // 單一答案 (非同質)

                                

                                QV.OkNowAns[i] = t; // 作為預設答案

                                

                                //window.console.log("Ans[" + count + "]=" + t);

                                

                            } else { // 是同質答案

                                

                                if (count == 2) { // 第一個同質答案(放兩個地方)

                                    QV.OkNowAns[i] = t; // 作為預設答案

                                }

                                

                                QV.OkNowSame[i][count + 1] = t; // 儲存同質答案



                                //window.console.log("SamAns[" + (count - 1) + "]=" + t);

                                

                            }

                            

                        }



                        head = tail + 1;



                    } while (tail > 0)

                    

                    if (maxlen < 9) { // 調整欄位長度

                        QV.NowFiLen[i] = maxlen + 2;

                    } else {

                        QV.NowFiLen[i] = maxlen;

                    }

                    

                    /*

                    [OK]_正確答案的同質分解已經完成

                    [OK]_修改 批改 的部分

                    

                    [ToDo]_修改 填充題顯示正確答案 的部分

                    */

                    

                    

                    

                    

                    

                    

                }

                

            } else {

                QV.NowAns[i] = tmp.substr(0,j); // 取得第 i 個學生答案

            }

            

            

            //if (!flag){ GV.DebugMsg += "["+QV.NowAns[i]+"]"; }

            

            tmp = tmp.substr(j+2); // 跳過間隔符號

            

            if (j > 0){

                qcounter += 1; // 判斷單複選

            }

        }

        

        if (flag && QV.QType[QV.QNow] === 1){ // 選擇題型 (在解析正確答案時才進行)



            if (qcounter === 1){ //(1:單選 / 2:複選)

                // 單選題

                QV.QType[QV.QNow] = 1;

            } else {

                // 複選題

                QV.QType[QV.QNow] = 2;

            }

        

        }

        

    } // 答案傳遞後的解碼函數

    //==========================================================================================================

    function s_popWinZoom (ratio, resizeFlag) {

        // ratio 是指定的縮放倍數

        

        //ratio = 0.5 + 1.8 * Math.random(); // 隨機測試用

        //ratio = Math.floor(ratio * 10) / 10; // 隨機測試用

        

        if(QV.GFrame == null){ // 偵測 綠豆視窗是否存在

            GV.DebugMsg = "綠豆視窗不存在 !!";

            return;

        } 

        

        //window.console.log('GFrame:' + QV.GFrame.contentWindow.KiWi_comp);

        

        if(!QV.GFrame.contentWindow.KiWi_comp){ // 對象不存在

           return;

        } else {

        

            var QFdocument = (QV.GFrame.contentWindow || QV.GFrame.contentDocument), // 指向 iframe 裡的 document

                lib = QFdocument.KiWi_comp.getLibrary(), // 取得題檔的相關元件

                w = lib.properties.width, // 原題檔影片寬度

                h = lib.properties.height, // 原題檔影片高度

                iw = QFdocument.document.body.clientWidth, // 顯示區域(扣掉捲軸)的寬高度

                xRatio = iw / w,

                pRatio = window.devicePixelRatio || 1;



            if (ratio < 0) { // 寬度佔滿螢幕

                ratio = xRatio;// * 0.999; // 寬度為主(乘上小數是避免水平卷軸有時出現)

                GV.SyArg_GScale = -1  * ratio; // 以負值代表全寬

            }



            // 步驟 1/3    // 這裡不影響畫面大小，只影響內容的縮放程度

            QFdocument.canvas.width = w * pRatio * ratio;

            QFdocument.canvas.height = h * pRatio * ratio;



            // 步驟 2/3   // 直接放大 canvas (但內容會模糊)

            QFdocument.canvas.style.width = w * ratio + 'px';

            QFdocument.canvas.style.height = h * ratio + 'px';

            QFdocument.dom_overlay_container.style.width = w * ratio + 'px';

            QFdocument.dom_overlay_container.style.height = h * ratio + 'px';

            QFdocument.anim_container.style.width = w * ratio + 'px';

            QFdocument.anim_container.style.height = h * ratio + 'px';



            // 步驟 3/3   // 純內容的縮放 (Canvas尺寸不會改變)

            QFdocument.stage.scaleX = pRatio * ratio;

            QFdocument.stage.scaleY = pRatio * ratio;



            //GV.DebugMsg = "綠豆視窗縮放 = " + ratio + " 倍";

            

            QV.GFrameW = w * ratio;

            QV.GFrameH = h * ratio + 60;

            

            if (resizeFlag){ // 配合綠豆起始大小的檢查 (在 Timer 函數裡)

               // QV.GFrameW = w * ratio;

                //QV.GFrameH = h * ratio + 60;

                QV.GFrame.style.width = QV.GFrameW + 'px';

                QV.GFrame.style.height = QV.GFrameH + 'px';



                // 讓綠豆視窗一出現時就靠右

                QV.GFrameX = (window.innerWidth - (w * ratio));

                QV.GFrame.style.left =  QV.GFrameX + 'px';

                QV.GFrameY = 0;

                QV.GFrame.style.top = QV.GFrameY + 'px';



                //GV.DebugMsg += ']';

            }

            

            if(GV.SyArg_BrwSize == 2 || GV.SyArg_ToolBarLoc == 0) { // 超大螢幕(電視) 或工具列下方顯示

                if ((QV.GFrameY + QV.GFrameH) > window.innerHeight ){ // 檢查綠豆位置是否太低(讓標題跑出螢幕)

                    QV.GFrameY = window.innerHeight - QV.GFrameH; // 讓綠豆下緣切齊螢幕下方

                    QV.GFrame.style.top = QV.GFrameY + 'px';

                }

            }



        }

        

    } // 從系統端控制綠豆視窗內容縮放的函數

    //==========================================================================================================

    function s_fillAns (fid, x, y, w, h, para, txt) { // 會被填充題呼叫

        // fid : 填充欄位 id (在 Animate CC 裡的 MovieClip Name)

        // x,y : 填充欄位坐標

        // w,h : 填充欄位寬高



        //var myCan = GV.MyCanvas, // 取得 myCanvas (事先就宣告好的) 的對應

        var bodr = 5,

            fidObj, i, j, samflag, samno, k,

            ansShowFlag,

            tmp, sno, rootLink = 'QFdocument.KW_Root';

            //nx = (GV.QFrameX + 1) + x - bodr, // (與題目區的)相對位置

            //ny = (GV.QFrameY + 2) + y - bodr,

            

            

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 題目區 iframe 裡的 document

        

        //myCan.style.zIndex = "1"; // 深度設定

        

        if (fid != '') { rootLink = rootLink + '.'; }

        

        fidObj = eval(rootLink + fid); // 記憶 fid 元件的完整路徑

        

        //window.console.log('Field(' + sno + ')=' + QV.OkNowAns[sno]);

        //window.console.log('Field(' + fid +')');

        

        //if(eval(rootLink + fid)){ // 該欄位存在

        if(fidObj){ // 該欄位存在

        //if(QFdocument.KW_Root[fid]){ // 該欄位存在 (但這樣的寫法，只能處理填充欄位在最上層的狀況)

            

            if (para == 0 || para == null){ //----------------------------------------------- [進入輸入狀態]

                

                if (GV.SysMode === 1){ // 解答模式中，不出現虛擬鍵盤

                    return; // 直接離開函數

                }

                

                //GV.DebugMsg = "s_fil"+ GV.DebugMsg;

                

                if (GV.InputFieldFlag) { // 有欄位正在輸入狀態

                    if (GV.InputFieldNow == fid) { // 是同一個欄位(所以是重複按同一個欄位)

                        s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, -1); // 關閉這個欄位輸入狀態(因為重複按同一個欄位)

                        return; //直接離開 (因為重複按同一個欄位)

                    } else {

                        s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, -1); // 關閉之前正在輸入的欄位

                    }

                }

                

                //QV.QSub = Number(fid.substr(1)); // 從欄位(影片)名稱取得小題題號

                QV.QSub = Number(fid.slice(-2)); // 從欄位(影片)名稱取得小題題號

                

                //GV.DebugMsg = "欄位[" + fid + "] [" + QV.QSub + "]輸入的狀態";

                

                

                GV.InputFieldX = x;

                GV.InputFieldY = y;

                GV.InputFieldW = w;

                GV.InputFieldH = h;

                

                GV.InputFieldCount = 0; // 讓閃爍先(暫時)點亮

                GV.InputFieldFlag = true;

                

                // 登記的動作 (作為後續虛擬鍵盤的操作對象)

                GV.InputFieldNow = fid;

                

                // 虛擬鍵盤的處理

                QV.SKType = 3; // 填充

                G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                G4C.game.state.states['Main'].keyBoardFunc(1, -100, 0); // 重繪 感應區的左右切換按鈕

                

                

            } else if (para == -1) { //---------------------------------------------------- [取消輸入的狀態]

                // 跟 para = 0 意義相反

                

                //GV.DebugMsg = "欄位[" + GV.InputFieldNow + "] 取消輸入的狀態";

                

                if(GV.InputFieldNow != "") {

                    

                    GV.InputFieldCount = -5; // 讓閃爍先(暫時)停止

                    GV.InputFieldFlag = false;



                    s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, 3); // 停止閃爍效果





                    // 取消登記 (不再接受虛擬鍵盤的輸入)

                    GV.InputFieldNow = "";



                    // 虛擬鍵盤的處理

                    QV.SKType = -1; // 關閉虛擬鍵盤(-1)

                    G4C.game.state.states['Main'].keyBoardFunc(1, QV.SKType, 0); // 重繪虛擬鍵盤 (根據 QV.SKType )

                    G4C.game.state.states['Main'].keyBoardFunc(1, -100, 0); // 重繪 感應區的左右切換按鈕

                    

                }

                

                //if (a) { // 如果這個欄位的答案是空的，就顯示問號

                    

                //}

                

                

                // 直接改變 題目區 iframe 裡的 Animate CC 文字欄位內容

                //QFdocument.mc[fid].txt.text = "哈囉!";

                

                

                

            } else if (para == 1) { //------------------------------------------------------- [直接變更答案]

                // 直接改變 題目區 iframe 裡的 Animate CC 文字欄位內容

                

                /*

                // 調整為 置中對齊 (欄位坐標也要跟著調整，不然會偏掉)

                QFdocument.mc[fid].txt.textAlign = "center";

                // 將 欄位坐標 設定在新的位置(欄位寬度的一半)(所以是正中間)

                QFdocument.mc[fid].txt.setTransform(QFdocument.mc[fid].txt.lineWidth / 2, 2.9);

                

                

                // 調整為 靠右對齊 (欄位坐標也要跟著調整，不然會偏掉)

                QFdocument.mc[fid].txt.textAlign = "right";

                // 將 欄位坐標 設定在新的位置(欄位寬度)(所以是右邊)

                QFdocument.mc[fid].txt.setTransform(QFdocument.mc[fid].txt.lineWidth, 2.9);

                

                // 調整為 靠左對齊 (欄位坐標也要跟著調整，不然會偏掉)

                QFdocument.mc[fid].txt.textAlign = "left";

                // 將 欄位坐標 設定在新的位置(0 或很小的數字 3.3)(所以是左邊)

                QFdocument.mc[fid].txt.setTransform(3.3, 2.9);

                */

                

                //GV.DebugMsg = QFdocument.mc[fid].txt.font;

                

                //QFdocument.KW_Root[fid].txt.color = "#000000";//"#0000FF"; // 改變文字顏色

                fidObj.txt.color = "#000000";//"#0000FF"; // 改變文字顏色

                

                //QFdocument.mc[fid].txt.font = "bold 36px Arial"; // 改變文字字型資訊

                

                //QFdocument.KW_Root[fid].txt.text = txt;//"哈囉!";

                fidObj.txt.text = txt;

                

                

                

                

            } else if (para == 2) { //-------------------------------------------------------------- [點亮]

                // 欄位外觀變更

                /*

                var ctx = myCan.getContext('2d');



                GV.MyCanvas.width = w + 2 * (bodr + 2); // 只是 reset size 也會清除 canvas 2d 內容

                GV.MyCanvas.height = h + 2 * (bodr + 2);



                //ctx.fillStyle = 'rgba(190, 190, 255, 1)'; // 底框

                //ctx.fillRect(0, 0, w, h); // 底色

                ctx.strokeStyle = "#0099ff";

                ctx.lineWidth = bodr;

                ctx.rect(0.5 * bodr, 0.5 * bodr + 1, (w-2) + 0.9 * bodr, (h-4) + 0.9 * bodr); // 外框

                ctx.stroke();



                GV.MyCanvas.style.left = nx +"px";

                GV.MyCanvas.style.top = ny +"px";

                */

                

                if (QV.NowAns[QV.QSub].length < QV.NowFiLen[QV.QSub]){

                    s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, 6, "#009900"); // 改變閃爍外框顏色為 綠色

                } else {

                    s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, 6, "#FF0000"); // 改變閃爍外框顏色為 紅色

                }

                

                //QFdocument.KW_Root[fid].Flash.visible = true;

                fidObj.Flash.visible = true;

                

            } else if (para == 3) { //-------------------------------------------------------------- [熄滅]

                /*

                var ctx = myCan.getContext('2d');

                ctx.clearRect(0, 0, myCan.width, myCan.height); // 清除 canvas 範圍的內容

                */



                //GV.MyCanvas.width = window.innerWidth; // 或是，只是 reset size 也會清除內容

                //GV.MyCanvas.height = window.innerHeight;

                

                //QFdocument.KW_Root[fid].Flash.visible = false;

                fidObj.Flash.visible = false;

                

                

            } else if (para == 4) { //---------------------------------------------------------- [清光答案]

                // 直接改變 題目區 iframe 裡的 Animate CC 文字欄位內容

                //QFdocument.KW_Root[fid].txt.text = '';

                fidObj.txt.text = '';

                

                

            } else if (para == 5) { //---------------------------------------------------------- [顯示正解]

                

                // 在填充欄位旁新增一個填充欄位

                

                //GV.DebugMsg += "[" + QV.QSubTotal +"]";

                

                // 先處理同質答案的比對，根據優先成功的結果

                for (i = 1; i <= QV.QSubTotal; i += 1) {

                    

                    //window.console.log('第 ' + i + ' 小題的組號:' + QV.OkNowSame[i][0]);

                    

                    if (QV.OkNowSame[i][0] > 0) { // 有同質答案

                        

                        for (j = 1; j <= QV.OkNowSame[i][1]; j += 1) { // 檢查每一個同質答案

                            

                            if (QV.NowAns[i] == QV.OkNowSame[i][2 + j]) { // 目前(第i)小題答案比對

                                QV.OkNowSame[i][2] = j; // 比對成功的編號

                            }



                        }

                        

                        if (QV.OkNowSame[i][2] > 0) { // 比對成功

                            

                            // 回頭檢查之前的小題，是否一致

                            

                            samflag = false; // 預設失敗

                            samno = 1;

                            for (j = 1; j <= QV.QSubTotal; j += 1) { // 檢查設定每一小題

                                

                                if (QV.OkNowSame[i][0] == QV.OkNowSame[j][0]) {

                                    // 第j小題跟本小題是同一組

                                    

                                    if (!samflag && QV.OkNowSame[j][2] > 0) { // 是第一個出現 且 有比對成功的同組

                                        

                                        samflag = true;

                                        

                                        samno = QV.OkNowSame[j][2];

                                         

                                        QV.OkNowAns[j] = QV.OkNowSame[j][2 + samno]; // 修正預設正確答案

                                            

                                        //window.console.log('同組第一答案:' + QV.OkNowAns[j]);

                                        

                                        if (j > 1) { // 前面可能有同組的漏網之魚

                                            

                                            for (k=1; k < j; k += 1) {

                                                

                                                if (QV.OkNowSame[k][0] == QV.OkNowSame[j][0]) {

                                                    QV.OkNowAns[k] = QV.OkNowSame[k][2 + samno]; // 修正預設正確答案

                                                }

                                                

                                            }

                                            

                                            

                                        }

                                        

                                        

                                    } else { // 不是第一個的同組

                                        

                                        QV.OkNowAns[j] = QV.OkNowSame[j][2 + samno]; // 修正預設正確答案

                                        

                                        //window.console.log('同組修正答案:' + QV.OkNowAns[j]);

                                        

                                    }

                                    

                                }

                            

                            

                            }

                        }

                    }

                    

                }





                for (i = 1; i <= QV.QSubTotal; i += 1) {



                    fid = "B" + ("0" + i).slice(-2);

                

                    rootLink = 'QFdocument.KW_Root.' + QFdocument.KW_NoMap[i]; // 從題目取得欄位的路徑前置

                    

                    fidObj = eval(rootLink + fid); // 記憶 fid 元件的完整路徑

                

                    if (QV.NowAns[i] != QV.OkNowAns[i]) { // 目前小題答案比對(答錯的狀況)

                        

                        //tmp = QFdocument.KW_Root[fid].txt.clone (true); // 複製，會沿用原本的文字欄位屬性

                        tmp = fidObj.txt.clone (true); // 複製，會沿用原本的文字欄位屬性

                        ansShowFlag = true;

                        if (QV.NowAns[i] != ''){ // 答錯且已經有學生答案

                            //tmp.y = QFdocument.KW_Root[fid].txt.y - 20; // 正確答案顯示在上面一點的地方

                            tmp.y = fidObj.txt.y - 20; // 正確答案顯示在上面一點的地方

                            

                            if(parent.QV.QuizShowMode[3]) { ansShowFlag = false; } // 答錯但不顯示正確答案

                            

                            //QFdocument.KW_Root[fid].Mode.gotoAndStop(1); // 顯示叉叉

                            //QFdocument.KW_Root[fid].Mode.visible = true;

                            if(!QV.QuizShowMode[1]) {

                                fidObj.Mode.gotoAndStop(1); // 在錯誤答案旁顯示叉叉

                                fidObj.Mode.visible = true;

                            }

                            

                        } else {// 答錯但學生未作答

                            //tmp.y = QFdocument.KW_Root[fid].txt.y - 2; // 顯示在欄位裡 (-2 : 使用不同字體的誤差)

                            tmp.y = fidObj.txt.y - 2; // 顯示在欄位裡 (-2 : 使用不同字體的誤差)

                            if(parent.QV.QuizShowMode[2]) { ansShowFlag = false; } // 未作答就不顯示正確答案

                        }

                        

                        tmp.color = "#FF0000";

                        tmp.font = "bold 26px 'MS UI Gothic'";// + QV.ChFont;

                        tmp.text = QV.OkNowAns[i]; // 小題題號對應的正確答案



                        //QFdocument.KW_Root[fid].addChild(tmp); // 將新元件加到填充欄位所在的影片片段中，才會用到原本欄位的相對坐標

                        if (ansShowFlag) { // 來自各種狀況，決定是否顯示正確答案

                            fidObj.addChild(tmp); // 將新元件加到填充欄位所在的影片片段中，才會用到原本欄位的相對坐標

                        }

                        

                        

                    } else { // 本題答對

                        //QFdocument.KW_Root[fid].Mode.gotoAndStop(0); // 顯示圈圈

                        //QFdocument.KW_Root[fid].Mode.visible = true;

                        if(!parent.QV.QuizShowMode[4]) { // 答對且給圈圈

                            fidObj.Mode.gotoAndStop(0); // 顯示圈圈

                            fidObj.Mode.visible = true;

                        }

                    }



                }

                

                s_fillAns (GV.InputFieldNow, 0, 0, 0, 0, -1); // 關閉之前正在輸入的欄位

                

            } else if (para == 6) { //--------------------------------------------------- [改變閃爍外框顏色]

                

                //QFdocument.KW_Root[fid].Flash.shape.graphics._fill.style = txt; // 改變影片顏色

                fidObj.Flash.shape.graphics._fill.style = txt; // 改變影片顏色

                

            } else if (para == 50) { //-------------------------------------------------- [控制 PicW]in]

                

               // var pw = eval('QFdocument.KW_Root.' + fid),

                    //pwx = Math.floor(pw.x), // 區域坐標(對上一層影片 Answer1)

                    //pwy = Math.floor(pw.y), // 區域坐標(對上一層影片 Answer1)

                    //pwg = QFdocument.KW_Root.localToGlobal(pwx, pwy),

                    //pwb = pw.nominalBounds,

                    //pww = Math.floor(pwb.width * pw.scaleX),

                    //pwh = Math.floor(pwb.height * pw.scaleY);

                

                fidObj.x = GV.PicWinX + (GV.iFrameScrollX/ Math.abs(GV.SyArg_QScale));

                fidObj.y = GV.PicWinY + (GV.iFrameScrollY/ Math.abs(GV.SyArg_QScale));

                

                //GV.DebugMsg = 'Loc:' + GV.iFrameScrollX + '/' + GV.iFrameScrollY +' ['+GV.SyArg_QScale+']';

                

            } else if (para == 51) { //--------------------------------------------------- [切換 PicWin 的鎖定狀態]

                

                if (!GV.PicWinLockFlag) { // 目前未鎖定

                    

                    var pwx = Math.floor(fidObj.x), // (會包含網頁上捲的坐標位移)

                        pwy = Math.floor(fidObj.y),

                        pwb = fidObj.nominalBounds;// 取得 PicWin 原始寬高

                

                    GV.PicWinThumbNailMark = new createjs.Text("📌", "20px 'MS UI Gothic'"); // 產生 Text 元件

                    GV.PicWinThumbNailMark.x = pwb.width - 18;

                    GV.PicWinThumbNailMark.y = -10;

                    fidObj.addChild(GV.PicWinThumbNailMark);

                    

                    GV.PicWinX = pwx - (GV.iFrameScrollX/ Math.abs(GV.SyArg_QScale)); // 要扣掉坐標裡包含的上下捲

                    GV.PicWinY = pwy - (GV.iFrameScrollY/ Math.abs(GV.SyArg_QScale)); // 要扣掉坐標裡包含的上下捲



                    GV.PicWinLockFlag = true;

                    

                } else { // 目前已經鎖定

                    

                    fidObj.removeChild(GV.PicWinThumbNailMark);

                    

                    GV.PicWinLockFlag = false;

                    

                }

                

            } else if (para == 52) { //-------------------------------------------------- [保持 PicW]in 的可視狀態]

                var ratio = Math.abs(GV.SyArg_QScale),

                    qfw = GV.QFrameW / ratio,

                    delta = 50 / ratio;

                

                if (fidObj.x > qfw - delta){

                    //GV.DebugMsg = '['+ratio+']' + fidObj.x +'/'+qfw;

                    fidObj.x = qfw - 2 * delta;

                }

            } else if (para == 61) { //-------------------------------------------------- [綠豆測試]



                //GV.DebugMsg += '['+fid+']';

                fidObj.visible = false;



            }

        

        

        }

        

        

        

        

    } // 負責填充題的答案處理動作(批改的答案顯示也在)

    //==========================================================================================================

    function s_soundFX (para, type) { // 負責系統音效

        //var i, j;

        

        if (para == 1){ // 播放 type 所指定的音效

            if(GV.SyArg_PlaySound) {

                if (type == 0) {

                    LV.dot.play(); // 音效

                } else if (type == 1) {

                    LV.ping.play(); // 音效

                }

            }

        

        } else if (para == 0){ // 停止目前播放中的音效 (測試 OK)

            G4C.game.sound.stopAll();

            

        } else if (para == -1){ // 關閉系統音效(切換旗標)

            G4C.game.sound.stopAll();

            GV.SyArg_PlaySound = false;

            

        } else if (para == 100){ // 打開系統音效(切換旗標)

            GV.SyArg_PlaySound = true;

            

        }

        

        

    }

    //==========================================================================================================

    function s_gameItem (para, itno, player) { // 負責在選擇題的選項作出是誰作答的記號

        

        // para = 0:刪除記號 / 1:打勾記號 / 2:圈圈記號 / 3:叉叉記號

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument), // 指向 題目區 iframe 裡的 document

            item, tmp, nx, ny, winFlag, i, j, dx;



        if (QFdocument.KW_Root === null) { // 題目不存在就離開

            return;

        }

        

        if (para === 0) { // ............................................................... 消除記號

            

            if (GV.ItemMark[player] !== null) { // 已經存在

                

                // 刪除原有的

                QFdocument.KW_Root.removeChild(GV.ItemMark[player]);

                

                return;

                

            }

            

        } else { // ........................................................................ 其他記號

            

            //player = Math.floor(Math.random() * 4) + 1;

            

            /*

            for (i = 1 ; i <= QV.QSubTotal ; i += 1){ // 找出選擇題的正確選項

                if (QV.OkNowAns[i] == '1') {

                    itno = i;

                    break;

                }

            }

            */

            

            if (para === 1) { // 原本是打勾，順便檢查對錯

                //QV.NPNowAns[player][j]

                

                winFlag = true; // 一開始先預設答對

                para = 2; // 圈圈記號

                

                for (i = 1 ; i <= QV.QSubTotal ; i += 1){ // 找出選擇題的正確選項

                    if (QV.NPNowAns[player][i] !== QV.OkNowAns[i]) { // 第 i 小題答案不對

                        winFlag = false; // 整題判定為答錯

                        para = 3; // 叉叉記號

                        

                        //(0:不能作答 -1:可以作答/-2:本次答錯[包含未作答而失敗]/大於0:本次答對[答對時間點])

                        GV.PlayerState[player] = -2;

                        

                        break;

                    }

                }

                

                if (winFlag && GV.GameState === 0) { // 答題中，且答案檢查完畢，真的是答對

                    

                    GV.DebugMsg = "恭喜 ~ 第 " + player + " 位玩家答對 ~ !";                    

                    

                    //(0:不能作答 -1:可以作答/-2:本次答錯[包含未作答而失敗]/大於0:本次答對[答對時間點])

                    GV.PlayerState[player] = Date.now();

                    

                    GV.GameState = player; // 用比賽模式狀態值記錄 本題勝者編號

                    

                    QV.NPScore[player] += 1; // 成績

                    

                    j = GV.PlayerScoreMap[player]; // 取得記分板上的順序對應

                    LV.STScoreBox[j].text = QV.NPScore[player]; // 更新成績

                    

                    // 在此檢查其他玩家的狀態，如果尚未作答(狀態值=-1)就直接認定為失敗

                    

                    GV.GameTimer = GV.GameTimerMax; // 比賽回合之間的緩衝時間 (單位：十分之秒)

                    // 讓比賽進入單題回合的收尾階段 : (這些事情放在 QTimer 函數裡面好了)

                        // 更新顯示目前比數成績

                        // 準備載入下一題

                    

                }

                

                

            }

            

            //if (itno === null) { itno = Math.floor(Math.random() * 4) + 1; }

            

            item = 'Btn' + itno ; // 選擇題的選項 A B C D 影片片段名稱

        

            nx = QFdocument.KW_Root[item].x;

            ny = QFdocument.KW_Root[item].y;





            // 測試對選項和內容影片片段的控制...Ok

            //QFdocument.mc[item].x += 100;

            //QFdocument.mc['C1'].x += 100;

            //QFdocument.mc[item].visible = false;

            //QFdocument.mc['C1'].visible = false;

            //QFdocument.mc[item].alpha = 0.5;

            

            //GV.ItemMark = "";

            

            if (GV.ItemMark[player] !== null) { // 已經存在

                

                //itno = Math.floor(Math.random() * 4) + 1;

                //item = 'Btn' + itno ; // 選擇題的選項 A B C D 影片片段名稱

                

                nx = QFdocument.KW_Root[item].x;

                ny = QFdocument.KW_Root[item].y;

                

                // 刪除原有的

                QFdocument.KW_Root.removeChild(GV.ItemMark[player]);

                

            }

            

            // 測試在題目區增加一個影片片段

            GV.ItemMark[player] = new QFdocument.createjs.MovieClip();

            GV.ItemMark[player].x = nx;

            GV.ItemMark[player].y = ny - 30;

            QFdocument.KW_Root.addChild(GV.ItemMark[player]);

            

            

            if (player > 0) { // 是玩家

                

                dx =  -(12 + 12.5 * GV.PlayerTotal) + player * 25;



                // 在題目區畫圖

                var shape = new QFdocument.createjs.Shape();



                shape.graphics.beginFill('#ffffff').drawCircle(0 + dx,1,12).endFill(); // 畫底圓

                shape.graphics.setStrokeStyle(3,'').beginStroke('#ff0000').drawCircle(0 + dx,1,12).endStroke(); // 畫圓框

                GV.ItemMark[player].addChild(shape); // 將新元件加到新的影片片段中

                

                // 從 系統端 到 題目區裡 產生新的文字欄位

                tmp = new QFdocument.createjs.Text("", "bold 24px 'MS UI Gothic'");

                tmp.name = "mytxt";

                tmp.color = "#0000FF";

                tmp.x = - 6.5 + dx; // 取用答案選項的坐標

                tmp.y = - 12;

                GV.ItemMark[player].addChild(tmp); // 將新元件加到新的影片片段中

                tmp.text = player;// + "✔✌"; // 玩家編號：➀ ➁ ➂ ➃ ➊ ➋ ➌ ➍ 叉叉:✗✘ 勾勾:✓✔ ✌

                //QFdocument.mc.txt2.text = "哈囉!";



            }

            

            // 在題目區貼上圖檔

            //var bitmap = new QFdocument.createjs.Bitmap("../../assets/kiwi.png"); // 從題目資料夾回到主資料夾

            var bitmap = new QFdocument.createjs.Bitmap(GV.MarkImg[para]); // 從題目資料夾回到主資料夾

            bitmap.x = -20;//- 55; // 取用答案選項的坐標

            bitmap.y = 8;//- 15;

            //QFdocument.mc.addChild(bitmap); // 將新元件加到填充欄位所在的影片片段中，才會用到原本欄位的相對坐標

            //GV.ItemMark.addChild(bitmap); // 將新元件加到新的影片片段中

            GV.ItemMark[player].addChildAt(bitmap, 0); // 將新元件加到新的影片片段中 的 底層

            bitmap.scaleX = 0.5;

            bitmap.scaleY = 0.5;

            

            

            

        }

        

        

        

    }

	//==========================================傳送至PHP處理==============================================
	
	function send_php (phpname,sendvalue) { 
	
		var xmlhttp = new XMLHttpRequest();				
		xmlhttp.open("POST", phpname,true);
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4) {
				window.console.log(xmlhttp.responseText);
			}
		}
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send(sendvalue);

    } 

	
	
    //==========================================================================================================

    function s_exitFunc () { // 系統的離開

        GV.DebugMsg = "[離開按鈕] 被按下 ~";
	
		s_windowOpen("menu.php" + "?name=" + s_TimeStamp() + "&V=" + QV.ClassVerNo + "&C=" + s_GetSQLString('M').substr(2,1), "");

    } // 系統的離開

    //==========================================================================================================

    function s_newCanvas (fid, w, h) { // (暫時沒用到) 改用來呈現 比賽模式 的題目選項狀態

        

        // fid : 填充欄位 id (在 Animate CC 裡的 MovieClip Name)

        // x,y : 填充欄位坐標

        // w,h : 填充欄位寬高

        

        /*

            這個表層的 Canvas 如果建立在 題目 iframe 裡，會不會比較好呢？

        

        

        */

        

        fid = 'Btn' + (Math.floor(Math.random() * 4) + 1) ; // 選擇題的選項 A B C D 影片片段名稱

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument), // 指向 題目區 iframe 裡的 document

            myCan = GV.MyCanvas, // 取得 myCanvas 的對應

            nx, ny;

        

        //window.console.log(window.devicePixelRatio);

        

        if (QFdocument.KW_Root[fid]) { // 該欄位存在



            nx = (GV.QFrameX - 1) + QFdocument.KW_Root[fid].x; // 選項的相對位置(取得的坐標是未經縮放的坐標)(自己要再處理縮放)

            ny = (GV.QFrameY + 1) + QFdocument.KW_Root[fid].y;



            // 第二層 Canvas 的測試

            myCan.style.zIndex = "1";

                

            if (!GV.myCanvasFlag){

                

                GV.myCanvasFlag = true;

                

                var ctx = myCan.getContext('2d');

                var scale = window.devicePixelRatio;//4; // 解析度倍率 ( 取用系統螢幕的[裝置像素比] )



                // 尺寸的限制同時也要改 s_makeCanvas() 函數裡的 style.width 和 style.height

                

                GV.MyCanvas.width = 100 * scale; //GV.game_W * scale; //w; // (只是 reset size 也會清除內容)

                GV.MyCanvas.height = 100 * scale; //GV.game_H * scale; //h; // 也可以不執行這兩行(執行之後有限制 Canvas 尺寸的效果)



                ctx.fillStyle = 'rgba(255, 255, 255, 1)'; // 底框

                ctx.fillRect(0, 0, w * scale, h * scale); // 底色

                ctx.lineWidth = 1 * scale;

                ctx.rect(0, 0, w * scale, h * scale); // 外框

                ctx.stroke();

                

                ctx.scale(1, 1); // 無法改變解析度



                ctx.fillStyle = 'rgba(0, 0, 0, 1)'; // 文字顏色

                ctx.font = (18*scale) + 'px 微軟正黑體';

                ctx.fillText('表面圖層 Canvas 的測試~', 5 * scale, 23 * scale);



                GV.MyCanvas.style.left = nx + "px";

                GV.MyCanvas.style.top = ny + "px";

                    

            } else {

                GV.myCanvasFlag = false;



                var ctx = myCan.getContext('2d');

                //ctx.clearRect(0, 0, GV.game_W, GV.game_H); // 用最大範圍來清除

                ctx.clearRect(0, 0, myCan.width, myCan.height); // 清除 canvas 範圍的內容



                //GV.MyCanvas.width = window.innerWidth; // 只是 reset size 也會清除內容

                //GV.MyCanvas.height = window.innerHeight;



            }

            

        }



        // 第二層 Canvas 的測試

        //myCan.style.zIndex = "1";

        ////myCan.style.position="fixed";

            

        //myCan.style.top = "55px"; // 可以移動整個 Canvas

        //myCan.style.left = "195px";

            

        //myCan.style.left = document.getElementById('qframe').style.left; // 取得 QFrame 的坐標 當自己的坐標

        //myCan.style.top = document.getElementById('qframe').style.top;



        //myCan.style.width = "775px"; // 但是再改這些屬性會變成 縮放 效果，連裡面的字都變形了

        //myCan.style.height = "490px";

            

        //myCan.style.pointerEvents = "none";

        //myCan.style.opacity = "0.1";

            

        // 瀏覽器改變畫面大小時，Canvas 並不會跟著改變大小或坐標 !!!!!!!!!!!!!!!!!!!

            

        /*

        用 GV.MyCanvas.width , GV.MyCanvas.height 調整 canvas 大小時，內容會消失，

        需要再重畫一次，但如果要配合畫面的縮放，坐標尺寸等要重新計算過

        */

        

    } // 用新的 Canvas 負責填充內容的顯示 (暫時沒用到)



    /*

        [Why]-本來打算用 canvas 在填充題欄位的上方，自行模擬出填入答案與顯示的動作，但是後來發現可以從系統端直接控制文字欄位的內容，

            所以暫時擱置此方案。

    */

    

    

    

    

    function myFunctionName () { // 測試改變 iframe 裡面的 canvas 的屬性

        var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        //var scale;

        

        if (QFdocument.document) QFdocument = QFdocument.document;

        

        // 改變底色

        //QFdocument.body.style.backgroundColor = "#ffffcc"; // 正常的淡黃色

        QFdocument.body.style.backgroundColor = "#ffccff"; // 測試用的粉紅色

        

        

        /*

        // 改變 canvas 大小

        var QFcanvas = QFdocument.getElementById('canvas'); // 指向 iframe 裡的 document 裡的 canvas

        scale = (parseInt(QFrame.style.width) * 0.9) / parseInt(QFcanvas.style.width); // 計算縮放倍數

        

        QFcanvas.style.width = parseInt(QFrame.style.width) * 0.9 +"px"; // 設定為 iframe 寬度的 0.9 倍

        

        QFcanvas.style.height = parseInt(QFcanvas.style.height) * scale +"px";

        

        //QFcanvas.style.width = 1100 + "px";

        

        //window.console.log(parseInt(QFrame.style.width) * 0.95, scale);

        */

        

        

        // 這一段程式要放在 畫面改變的即時處理程序 中



    }

    

    function myFunctionName2 () { // 測試改變 iframe 裡面的

        var QFrame = document.getElementById('qframe'); // 取得 iframe 的對應

        var QFdocument = (QFrame.contentWindow || QFrame.contentDocument); // 指向 iframe 裡的 document

        

        if (QFdocument.document) QFdocument = QFdocument.document;

        

        

        //QFdocument.test(); // 測試：執行 題庫 html 檔裡的函數...OK

        

        /*

        ※ 在頁框裡的函數要寫成這樣( = function ...)，才可以(在這裡)被正常呼叫：

            test = function() {

                alert("sdfsdfsf");

            }

            

        ※ 在題目檔(1e0001s.html)裡，已經有會自動固定大小(resizeCanvas)的程式，這樣會干擾系統對於大小畫面的管理

        

        ※ 在題目檔(1e0001s.html)裡，內容似乎沒有太多針對單一題目的獨特資料，是否題庫裡的各題可以用單一個 html 代替

        

        ※ 或者說，將題目檔()的 html 內容，直接抽出寫在系統的 html 裡，也就是說，不透過 iframe 而直接整合在一起

            []-這樣要如何切換題目呢？

        

        */

        

        //var QFcanvas = QFdocument.getElementById('canvas'); // 指向 iframe 裡的 document 裡的 canvas

        

        //var iframeVar = QFrame.contentWindow.brenda; // 讀取到 iframe 裡的變數 .... OK

        

        //var iframeVar = QFrame.contentWindow.innerWidth; // 讀取到 iframe 裡的 window.innerWidth ....OK

        

        //document.getElementById('qframe').contentWindow.init(); //測試：執行 題庫 html 檔裡的函數...OK

        

        

        

        

        

        

        /*

        var iframeVar = QFrame.contentWindow.stage.scaleX; // .... OK

        

        window.console.log('lastW = ' + iframeVar);

        */

        

        

        /*

        // 讓 iframe 載入所指定的網頁

        document.getElementById("qframe").style.backgroundImage = 'assets/loading/5.gif'; // 讓 等待動畫 出現

        QFrame.src = 'QBase/test/ts0002.html';

        */

        

    

    }

    

    /*

    function old_mouseDown (x ,y) {

        //document.getElementById('GFrame').style.left = '10px';

        window.console.log('System Mouse down func ~');

        //QV.GFrameDrag = true;

        //QV.GFrameDx = window.event.clientX;// - document.getElementById('GFrame').style.left;

        //QV.GFrameDy = window.event.clientY - document.getElementById('GFrame').style.top;

        

        

        //if(QV.GFrameDrag) { // 綠豆視窗在拖曳

            var iframeX =  document.getElementById('GFrame').offsetLeft;

            var iframeY =  document.getElementById('GFrame').offsetTop;

            document.getElementById('GFrame').style.left = (iframeX + x) + 'px';

            document.getElementById('GFrame').style.top = (iframeY + y) + 'px';

        //}

        

        

        

    } // 無作用

    */

    

    /*

    function s_drag(x,y) {

        //document.getElementById('GFrame').style.left = '10px';

        //window.console.log(event.clientX + '/' + event.clientY); // 在 iframe 上空會抓不到

        //window.console.log(event.clientX + '/' + event.clientY); // 在 iframe 上空會抓不到

        //GV.DebugMsg = event.clientX + '/' + event.clientY + ' [' + G4C.game.input.x + '/' + G4C.game.input.y +']';

        //var iframeX =  document.getElementById('GFrame').offsetLeft;

        //var iframeY =  document.getElementById('GFrame').offsetTop;

        document.getElementById('GFrame').style.left = (GV.QFrameX - x) + 'px';

        document.getElementById('GFrame').style.top = (GV.QFrameY - y) + 'px';

        GV.DebugMsg = GV.QFrameX + '/' + GV.QFrameY + ' [' + x + '/' + y +']';

    }

    */

    

    /*

    //==========================================================================================================

    function Dr_WheelEvent () { // 繪圖套件：滾輪事件(比 Scroll 事件先發動) (配合滾輪不時會出現位置誤差的補救動作)

        //window.console.log('Wheel !');

        GV.Dr_WheelCount = 3;

        if (GV.Dr_Status >= 0) {

            if (GV.Dr_ctx){

                GV.drawCanvas.style.visibility = 'hidden'; // 配合滾輪不時會出現位置誤差的補救動作

            }

        }

    } // 繪圖套件：滾輪事件



    //==========================================================================================================

    function Dr_MoveEvent () { // 繪圖套件：繪圖區移動

        

        //window.console.log('Scroll !');

        

        if (GV.Dr_Status >= 0) { // ========================================================================= 繪圖區

            

            if (GV.Dr_ctx){

                var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument);

                //var html = QFdocument.document.documentElement;

                //var op = GV.drawCanvas.style.top;

                //var top = QFdocument.pageYOffset;

                //var ttop = html.scrollTop; // 不準確

                //var ttop = QV.QFrame.contentWindow.canvas.style.top;

                var tleft = QFdocument.document.documentElement.scrollLeft;

                var tttop = QFdocument.document.documentElement.scrollTop;

                



                //GV.DebugMsg = "[tttop= " + op + "(" + top + "/" + ttop + "/" + tttop + ")]"+count;

                

                

                if (tleft !== GV.Dr_scrollX) { // 水平坐標與記憶的不同

                    GV.drawCanvas.style.left = - tleft + 'px';

                    GV.Dr_scrollX = tleft;

                }

                

                if (tttop !== GV.Dr_scrollY) { // 垂直坐標與記憶的不同

                    GV.drawCanvas.style.top = - tttop + 'px';

                    GV.Dr_scrollY = tttop;

                }



            }

        } // ================================================================================================ 繪圖區

        

            

    } // 繪圖套件：繪圖區移動



    //==========================================================================================================

    function Dr_makeCanvas (doc) { // 繪圖套件：在 doc 製作一個 canvas

        

        var can, ctx,

            scale = window.devicePixelRatio,//4; // 解析度倍率 ( 取用系統螢幕的[裝置像素比] )

            w = 2 * window.screen.availWidth, // 最大寬度(包含不可見、縮放才會出現的部分)

            h = 2 * window.screen.availHeight; // 最大高度

        

        // 指向題目本身的 canvas

        //var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument);

        //doc = QFdocument;

        //can = doc.canvas;





        // 另外新開的 canvas

        can = doc.createElement('canvas');



        can.id = "myCanvas";

        

        //can.style.width = w + 'px'; // GV.game_W + 'px';

        //can.style.height = h + 'px'; // GV.game_H + 'px';

        

        // 繪圖空間尺寸(能抓到繪圖動作的尺寸)

        can.width = w * scale; //GV.game_W * scale; //w; // (只是 reset size 也會清除內容)

        can.height = h * scale; //GV.game_H * scale; //h; // 也可以不執行這兩行(執行之後有限制 Canvas 尺寸的效果)

        

        can.style.top = 0 + "px";

        can.style.left = 0 + "px";

        can.style.zIndex = 1;

        can.style.position = "fixed";

        can.style.pointerEvents = "none"; // 不加這一行，一開始 phaser 就都會被這個 Canvas 遮住( phaser 不能操作)

        

        var body = doc.getElementsByTagName("body")[0];

        //var body = QV.QFrame.contentWindow.canvas;//anim_container;



        

        body.appendChild(can);



        

        GV.drawCanvas = can;

        

        if (GV.BrowserType !== 'iOS') { // iOS 另外在別的函數個別處理

            //doc.onscroll = function() {Dr_MoveEvent()}; // 捲動事件 (電腦端 和 Android)

            doc.addEventListener("scroll", Dr_MoveEvent); // 捲動事件 (電腦端 和 Android)

            doc.addEventListener("wheel", Dr_WheelEvent); // 捲動事件 (電腦端 和 Android)

        }

        



        

        //----------------------------------------------------------------

        

        

        ctx = can.getContext('2d');

        

        //ctx.lineWidth = 10 * scale;

        //ctx.fillStyle = 'rgba(255, 255, 255, 1)'; // 底框

        //ctx.rect(0, 0, w * scale, h * scale); // 外框

        //ctx.stroke();

        

        

        ctx.lineWidth = GV.Dr_LineWidth * scale;

        



        //ctx.fillStyle = 'rgba(255, 255, 255, 1)'; // 底框

        //ctx.fillRect(0, 0, 100 * scale, 100 * scale); // 底色

        //ctx.rect(0, 0, 100 * scale, 100 * scale); // 外框

        //ctx.stroke();

        //ctx.fillStyle = 'rgba(0, 0, 0, 1)'; // 文字顏色

        //ctx.font = (18 * scale) + 'px 微軟正黑體';

        //ctx.fillText('表面圖層 Canvas 的測試~', 5 * scale, 23 * scale);



        

        //GV.DebugMsg = '[Draw] ' + w + '/' + h + '('+scale+')';

        

        GV.Dr_Status = 0; // 繪圖功能狀態

        GV.Dr_Width = w;

        GV.Dr_Height = h;

        GV.Dr_scrollY = 0;

        

        return ctx;

        

    } // 繪圖套件：在 doc 製作一個 canvas

    



    //==========================================================================================================

    function Dr_Draw (para, x, y) { // 繪圖套件：繪圖動作

        //var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument), // 指向 題目區 iframe 裡的 document

        //    myCan = QFdocument.document.getElementById('myCanvas'); // 取得 myCanvas 的對應(指向題目區)

        

        var scale = window.devicePixelRatio,//4; // 解析度倍率 ( 取用系統螢幕的[裝置像素比] )

            ratio = GV.SyArg_QScale;

        //window.console.log("draw (" + x + "," + y + ")~");

        

        // 針對題目區縮放比例的繪圖坐標修正

        if (ratio < 0){

            ratio = - ratio;

        }

        x = (x + GV.Dr_scrollX) / ratio;// * GV.QFrameScaleW;

        y = (y + GV.Dr_scrollY) / ratio;// * GV.QFrameScaleH;

        

        //if (!GV.myCanvasFlag){

            if (para == 0){

                

                GV.Dr_ctx.beginPath(); // 讓線條各自獨立運作(不然刪除時，所有線條會一起被刪除)

                

                // 換顏色(測試用)

                //GV.Dr_ctx.strokeStyle = 'rgba('+Math.random()*255+','+Math.random()*255+','+Math.random()*255+', 1)';

                

                //GV.DebugMsg = "["+x +","+y+"]" + GV.DebugMsg;

                

                

                GV.Dr_ctx.moveTo(x * scale, y * scale);



            } else if (para == 1) {

                

                //GV.DebugMsg = ".....("+x +","+y+")" + GV.DebugMsg;

                //GV.DebugMsg = ".....("+x +","+y+")[&" + window.screen.availWidth + "," + window.screen.availHeight +"](" + GV.QFrameW + "," +GV.QFrameH + ")";

                

                GV.Dr_ctx.lineTo(x * scale, y * scale);



                GV.Dr_ctx.stroke();

                

            } else if (para == 2) { // 刪除全部

                

                GV.drawCanvas.width = GV.Dr_Width * scale; // (只是一個維度的 reset size 也會清除整個內容)

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor; // 恢復顏色

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio; // 恢復寬度

            }

            

            

        //}

            

    } // 繪圖套件：繪圖動作

   



    //==========================================================================================================

    function Dr_IOKey (para) { // 繪圖套件：繪圖介面-動作

        

        var type = para.name.substr(0, 1), // 取得種類

            ctxt = para.name.substr(1); // 取得內容

        

        if (type === "@") { //------------------------------------- 鍵盤功能鈕

            

            if (ctxt === "0") { //----------------------------------- 繪圖介面背景感應區 (按鈕)

                

                //GV.DebugMsg = "動作 取消 ~";

                

                //Dr_IOPanel(0); // 關閉 繪圖介面

                

            }

        

        } else if (type === "B") { //------------------------------------- 工具列繪圖功能鈕

            

            //GV.DebugMsg = "工具繪圖按鈕[" + ctxt + "]被按下 ~";

            

            if (ctxt === "1") { //------------------------------------------ 刪除全部

                

                Dr_Draw (2);

            

            } else if (ctxt === "2") { //----------------------------------- 橡皮擦

                

                GV.Dr_ctx.globalCompositeOperation = 'destination-out'; // 橡皮擦效果

                // 配合題目的縮放比例，調整橡皮擦大小

                GV.Dr_ctx.lineWidth = 10 * window.devicePixelRatio / Math.abs(GV.SyArg_QScale/1.5);

                

            } else if (ctxt === "3") { //----------------------------------- 一般介面

                

                //if (GV.BrowserType === 'iOS'){ // 配合 iOS 的繪圖特性

                //    s_IOfunc(1, 1); // 讓 QFrame 提高深度(恢復正常)

                //}

                

                G4C.game.state.states['Main'].showPanel(1);

                

            } else if (ctxt === "4") { //-----------------------------------

                

            } else if (ctxt === "5") { //----------------------------------- 紅色

                

                GV.Dr_LineColor = 'rgba(255, 0, 0, 1)';

                GV.Dr_ctx.globalCompositeOperation = 'source-over';

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor;

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio;

                

            } else if (ctxt === "6") { //----------------------------------- 藍色

                

                GV.Dr_LineColor = 'rgba( 0, 0, 255, 1)';

                GV.Dr_ctx.globalCompositeOperation = 'source-over';

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor;

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio;

                

            } else if (ctxt === "7") { //----------------------------------- 綠色

                

                GV.Dr_LineColor = 'rgba( 0, 180, 0, 1)';

                GV.Dr_ctx.globalCompositeOperation = 'source-over';

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor;

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio;

                

            } else if (ctxt === "8") { //----------------------------------- 黃色

                

                GV.Dr_LineColor = 'rgba( 255, 255, 0, 1)';

                GV.Dr_ctx.globalCompositeOperation = 'source-over';

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor;

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio;

                

            } else if (ctxt === "9") { //----------------------------------- 黑色

                

                GV.Dr_LineColor = 'rgba( 0, 0, 0, 1)';

                GV.Dr_ctx.globalCompositeOperation = 'source-over';

                GV.Dr_ctx.strokeStyle = GV.Dr_LineColor;

                GV.Dr_ctx.lineWidth = GV.Dr_LineWidth * window.devicePixelRatio;

            }

        

        

        }

        

    } // 繪圖套件：繪圖介面-動作

    //==========================================================================================================

    */ // 原：繪圖套件

    

    

    

    function s_makeCanvas () {

        

        GV.MyCanvas = document.createElement('canvas');



        GV.MyCanvas.id = "myCanvas";

        

        //GV.MyCanvas.style.width = window.innerWidth + 'px'; // 使用(瀏覽器視窗)的內容畫面尺寸

        //GV.MyCanvas.style.height = window.innerHeight + 'px';

        GV.MyCanvas.style.width = 100 + 'px'; // GV.game_W + 'px';

        GV.MyCanvas.style.height = 100 + 'px'; // GV.game_H + 'px';



        GV.MyCanvas.style.top = "0px";

        GV.MyCanvas.style.left = "0px";

        GV.MyCanvas.style.zIndex = 1;

        GV.MyCanvas.style.position = "fixed";

        GV.MyCanvas.style.pointerEvents = "none"; // 不加這一行，一開始 phaser 就都會被這個 Canvas 遮住( phaser 不能操作)

        

        var body = document.getElementsByTagName("body")[0];

        body.appendChild(GV.MyCanvas);

        

        //window.console.log('myCanvas is ok !');

        

        

        //----------------------------------------------------------

        /* canvas 滑鼠感應被取消，所以這一段也沒有效果

        GV.MyCanvas.addEventListener('click', function(evt) {



            var mousePos = getMousePos(canvas, evt);

            debugger;

            if (isInside(mousePos,rect)) {

                alert('clicked inside rect');

            }else{

                alert('clicked outside rect');

            }



            window.console.log('myCanvas is clicked !');

        }, false);

        */

        //----------------------------------------------------------

    } // 表層 Canvas 的啓始動作

    

    

    function a_randomAns () { // 測試直接處理 Animate CC 裡的變數函數

        

        var QFdocument = (QV.QFrame.contentWindow || QV.QFrame.contentDocument); // 指向 iframe 裡的 document

        

        //GV.DebugMsg = QFdocument.CorrectAns[0]; // 讀取 全域區 的陣列 (成功)

        

        //QFdocument.mc.stopnow(para2); // 呼叫 影格區 的 animate javascript 函數 (成功)

    } // [測試] - 直接從 系統端 處理 Animate CC 裡的變數函數 (記憶)

    

    

</script>

</head>

<body  onresize = 's_dialogWin();'>

	<!--<iframe id='qframe' src='' onload = 'frameLoadOK();' scrolling='no'>-->

    <iframe id='qframe' src='' onload = 'frameLoadOK();'>

        你的瀏覽器不支援 iframe ?!

    </iframe>

    <!--<div class="DebugMsg" id = "DebugMsg" >除錯訊息：</div>-->

</body>

<script>

    //QV.QFrame = document.getElementById('qframe');

    s_makeCanvas(); // 表層

    

    /* 暫時關閉這個效果，開發過程有點干擾

    window.onbeforeunload = function() { // 避免突然離開網頁(在網頁 被Focus 或 被滑鼠點擊過 之後開始有效)

        return "確認離開當前頁面嗎？"; // 這些文字訊息會被瀏覽器忽略，直接使用瀏覽器內定文字訊息

    }

    */

    

    //window.console.log('my is ok !');

    

    if (GV.BrowserType === 'iOS'){

        

        window.addEventListener("orientationchange", function() { // 螢幕旋轉事件 (for iPad)



            GV.ScrRotating = true; // 讓計時程式開始監視長寬的變化



            GV.ScrRotatCount = 20;



            //GV.DebugMsg = '[Rot]';



        });

        

        // 暫時將 iOS 的繪圖事件處理 放在這裡 

        /*

        window.document.ontouchstart = function(evt){

            

            var DrgOX = window.event.touches[0].clientX - GV.QFrameX, // 記憶按下的滑鼠坐標 (iPad 觸控坐標取得方式與滑鼠事件不同)

                DrgOY = window.event.touches[0].clientY - GV.QFrameY;

            

            evt.stopPropagation(); // 這一行可以停止將這個滑鼠事件傳給 flash (避免干擾原本 flash 的內容操作)

                

            if (GV.Dr_Status == 1) {

                //GV.DebugMsg = "觸控開始~("+DrgOX +","+DrgOY+")";

                Dr_Draw (0, DrgOX, DrgOY);  // 繪圖起點

            }

            

        }

        

        window.document.ontouchmove = function(evt){

        //window.document.addEventListener("ontouchstart", function(evt) {

            

            var DrgTX = window.event.touches[0].clientX - GV.QFrameX, // 拖曳過程的滑鼠坐標

                DrgTY = window.event.touches[0].clientY - GV.QFrameY;

            //var DrgTX = window.event.clientX, // 滑鼠坐標

            //    DrgTY = window.event.clientY;

            

            evt.stopPropagation(); // 這一行可以停止將這個滑鼠事件傳給 flash (避免干擾原本 flash 的內容操作)

                

            if (GV.Dr_Status == 1){



                //GV.DebugMsg = "("+DrgTX+","+DrgTY+")";// + parent.GV.DebugMsg;

                    

                Dr_Draw (1, DrgTX, DrgTY);  // 繪圖

            }

        }

        */



    } else if (GV.BrowserType === 'DeskTop'){ // 電腦端

        // 加入實體鍵盤讀取的事件

        document.body.onkeypress = function(evt){ // 處理 一般鍵 輸入

            //window.console.log('ssskey(' + evt.charCode + ')');

            s_KeyInput(evt.charCode); // 按鍵的 UniCode

            

        } // 處理 一般鍵 輸入

        

        document.body.onkeydown = function(evt){ // 處理 特殊鍵 輸入

            

            //window.console.log('Special key(' + evt.keyCode + ')');

            // 在此過濾

            if (evt.keyCode == 8){ // BackSpace 鍵

                

                evt.preventDefault(); // 這一行可以阻止 BackSpace 鍵被瀏覽器接收，網頁會往回跳(只能放這)

                

                if(!GV.SysStopAnsFlag) {s_KeyInput(-2); } // 特殊鍵 (BackSpace)

                

            } else if (evt.keyCode == 9){ // Tab 鍵

                

                evt.preventDefault(); // 這一行可以阻止 Tab 鍵被瀏覽器接收，使得輸入焦點有意外的變化(只能放這)

                

                //s_KeyInput(-1); // 特殊鍵

                if(!GV.SysStopAnsFlag) {s_IOfunc(10, -2); } // 會跳回第一個欄位的模式

                

            } else if (evt.keyCode == 13){ // Enter 鍵

                

                evt.preventDefault(); // 這一行可以阻止 Enter 鍵被瀏覽器接收，使得輸入有意外的換行(只能放這)

                //s_KeyInput(-1); // 特殊鍵

                if(!GV.SysStopAnsFlag) {s_IOfunc(10, -1); } // 不會跳回第一個欄位的模式

                

            } else if (evt.keyCode == 46){ // Delete 鍵

                

                if(!GV.SysStopAnsFlag) {s_KeyInput(-1); } // 特殊鍵 (Delete)

                

            }

        } // 處理 特殊鍵 輸入

        

    }

    

</script>

</html>
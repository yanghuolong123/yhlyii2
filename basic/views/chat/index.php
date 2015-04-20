<?php 
//use Yii;
use yii\helpers\Html;
$this->title = '聊天室';
$baseUrl = \Yii::$app->request->baseUrl;
?>
        
    
<div class="container">
    <div class="container">
        <div class="row">
            
             <!--左边栏-->

            <div id="left-column" class="span3 col-lg-3">
                <div class="well c-sidebar-nav">
                    <ul class="nav nav-list">
                        <li class="active"><a href="javascript:void(0)"><b>在线用户</b></a>
                        </li>
                    </ul>
                    <ul id="left-userlist">
                    </ul>
                    <div style="clear: both"></div>
                </div>
            </div>

            <!--主聊天区-->
            <div id="chat-column" class="span8 well col-lg-6">

                <!--
                <div id="chat-tool" style="height:100px;border:0px solid #ccc;">
                    个人资料区
                </div>
                -->

                <!--消息显示区-->
                <div id="chat-messages" style="border:0px solid #ccc;">
                    <div class="message-container">
                    </div>
                </div>


                <!--工具栏区-->
                <div id="chat-tool"
                     style="padding-left:10px;height:30px;border:0px solid #ccc;background-color:#F5F5F5;">
                    <select id="userlist" style="float: left; width: 90px;">
                        <option value=0>所有人</option>
                    </select>

                    <!-- 聊天表情 -->
                    <a onclick="toggleFace()" id="chat_face" class="chat_face">
                        <img src="<?= $baseUrl; ?>/static/img/face/15.gif"/>
                    </a>
                </div>
                <!--工具栏结束-->


                <!--聊天表情弹出层-->
                <div id="show_face" class="show_face">
                </div>
                <!--聊天表情弹出层结束-->


                <!--发送消息区-->
                <div id="input-msg" style="border:0px solid #ccc;">
                    <form id="msgform" class="form-horizontal post-form">
                        <div class="input-append">
                            <textarea id="msg_content" style="float: left; width: 68%;" rows="3" 
                                      contentEditable="true"></textarea>
                            <img style="width:80px;height:90px;float: left;" onclick="sendMsg()" style="float: left;"
                                 src="<?= $baseUrl; ?>/static/img/button.gif"/>
                        </div>
                    </form>
                </div>
            </div>
            <!--主聊天区结束-->
         

        </div>
    </div>
    <!-- /container -->
    <div id="msg-template" style="display: none">
        <div class="message-container">
            <div class="userpic"></div>
            <div class="message">
                <span class="user"></span>

                <div class="cloud cloudText">
                    <div style="" class="cloudPannel">
                        <div class="sendStatus"></div>
                        <div class="cloudBody">
                            <div class="content"></div>
                        </div>
                        <div class="cloudArrow "></div>
                    </div>
                </div>
            </div>
            <div class="msg-time"></div>
        </div>
    </div>
    <!-- / -->
</div>
    
<script>
        function getRequest() {     
            var theRequest = new Object();     
            theRequest['name'] = '<?= \Yii::$app->user->identity->username ?>';
            theRequest['avatar'] = 'http://bbs.feichangjuzu.com/uc_server/avatar.php?uid=<?= \Yii::$app->user->identity->id ?>&size=small';
            theRequest['uid'] = '<?= \Yii::$app->user->identity->id ?>';
   
            return theRequest;
        }
    </script>
    <?php 
        $this->registerCssFile("@web/static/css/chat.css", [
            'depends' => [\yii\bootstrap\BootstrapAsset::className()],
           // 'media' => 'print',
        ]);
        $this->registerJsFile('@web/static/js/jquery.json.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->registerJsFile('@web/static/js/console.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->registerJsFile('@web/static/js/config.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->registerJsFile('@web/static/js/comet.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
        $this->registerJsFile('@web/static/js/chat.js?r='.time(), ['depends' => [\yii\web\JqueryAsset::className()]]);
    ?>

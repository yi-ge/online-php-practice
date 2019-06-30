<?php include('header.php'); ?>
    <div class="myRecord"><button class="pure-button" id="myRecordButton">我的学习统计</button></div>
    <div class="myCredit"></div>
    <div class='tabs tabs_default'>
        <ul class='horizontal'>
            <li><a class="pure-button" href="#stage">闯关模式</a></li>
            <li><a class="pure-button" href="#challenge">挑战赛</a></li>
            <li><a class="pure-button admin" target="_blank" href="/page/user.php">用户管理</a></li>
            <li><a class="pure-button pure-button-primary admin" target="_blank" href="/page/edit.php">添加新题目</a></li>
        </ul>
        <div id='stage'>
            <div class="index-main">
                <table id="stage-table" class="stripe">
                </table>
            </div>
        </div>
        <div id='challenge'>
            <div class="index-main">
                <table id="challenge-table" class="stripe">
                </table>
            </div>
        </div>
    </div>
<?php include('footer.php'); ?>
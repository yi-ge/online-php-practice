<?php include('../header.php'); ?>
    <div class="content" id="content">
        <div id="leftPane">
            <div id="container"></div>
            <div class="control">
                <div class="pure-button pure-button-primary" id="run">执行代码</div>
                <label for="auto" class="pure-checkbox auto">
                    <input id="auto" type="checkbox"> 自动
                </label>
            </div>
        </div>
        <div id="rightPane">
            <div id="out">
                <iframe class="result" id="result"></iframe>
            </div>
        </div>
    </div>
<?php include('../footer.php'); ?>
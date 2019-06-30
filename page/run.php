<?php include('../header.php'); ?>
    <div class="content" id="content">
        <div id="leftPane">
            <div id="container"></div>
            <div class="control">
                <div class="pure-button pure-button-primary" id="run">提交</div>
                <div class="pure-button" id="testRun">执行代码</div>
                <label for="auto" class="pure-checkbox auto">
                    <input id="auto" type="checkbox"> 自动执行代码
                </label>
            </div>
        </div>
        <div id="rightPane">
            <div class='tabs tabs_default' style="height: 100%">
                <ul class='horizontal' style="margin-top: 10px;margin-bottom: 20px">
                    <li><a class="pure-button" href="#describeView">题目描述</a></li>
                    <li><a class="pure-button" href="#answerView" id="answerViewButton">解答</a></li>
                    <li><a class="pure-button" href="#resultView">运行结果</a></li>
                </ul>
                <div id='describeView'>
                    <div class="cont"></div>
                </div>
                <div id='answerView'>
                    <div class="cont"></div>
                </div>

                <div id='resultView'>
                    <div class="cont">
                        <div class="testCase">
                            <h4 style="font-weight: 500;margin: 4px 0;">输入：</h4>
                            <div class="testCaseContent"></div>
                        </div>
                        <div class="expectedResult" style="margin-top: 10px;">
                            <h4 style="font-weight: 500;margin: 4px 0;">预期结果：</h4>
                            <div class="expectedResultContent"></div>
                        </div>
                        <div class="resultArea" style="margin-top: 10px;">
                            <h4 style="font-weight: 500;margin: 4px 0;">输出：</h4>
                            <div class="resultContent">
                                <iframe class="result" id="result"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('../footer.php'); ?>
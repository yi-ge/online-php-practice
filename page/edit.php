<?php include('../header.php'); ?>
    <div class='edit-main'>
        <style type="text/css">
            .edit-main {
                padding: 20px;
            }

            article {
                margin: 0 auto;
                width: 60%;
            }

            article > heading {
                border-bottom: 1px solid #e5e5e5;

                margin: 1.5em 0;
                padding-bottom: 1.5em;

                display: block;
                overflow: auto;
            }

            article > heading h1 {
                margin: 0;
            }

            .contain-floats::after {
                display: block;
                content: " ";
                clear: left;
            }

            .panes {
                margin-left: -30%;
                margin-right: -30%;
            }

            .panes section {
                float: left;
                width: 33.33%;
                height: 30em;
            }

            .result {
                display: block;
                margin-right: 1em;
                height: 100%;
            }

            .result {
                width: 100%;
            }

            #jsContainer, #phpContainer, #testContainer {
                margin: 5px;
                box-sizing: border-box;
                border: 1px solid #eeeeee;
            }

            #phpContainer {
                margin: 5px 4px 5px 4px;
            }

            .edit-form label {
                margin-top: 10px;
            }

            .button-control {
                height: 50px;
            }
        </style>
        <form class="pure-form pure-form-stacked edit-form" id="edit-form">
            <fieldset>
                <legend><h1><?php if (isset($_GET['id'])) {
                            echo '编辑';
                        } else {
                            echo '添加';
                        } ?>题目</h1></legend>

                <div class="pure-g">
                    <div class="pure-u-lg-1-6">
                        <label for="type">分类</label>
                        <select id="type" name="type">
                            <option value="1">闯关模式</option>
                            <option value="2">挑战赛</option>
                        </select>
                    </div>

                    <div class="pure-u-lg-1-6">
                        <label for="isAnswer" class="pure-checkbox">
                            是否有解答
                        </label>
                        <select id="isAnswer" name="isAnswer">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>

                    <div class="pure-u-lg-1-6">
                        <label for="difficulty">难度</label>
                        <select id="difficulty" name="difficulty">
                            <option value="1">简单</option>
                            <option value="2">中等</option>
                            <option value="3">困难</option>
                        </select>
                    </div>

                    <div class="pure-u-lg-1-6">
                        <label for="isCredit">是否有积分</label>
                        <select id="isCredit" name="isCredit">
                            <option value="1">有</option>
                            <option value="0">没有</option>
                        </select>
                    </div>

                    <div class="pure-u-lg-1-6">
                        <label for="language">编程语言</label>
                        <select id="language" name="language">
                            <option value="PHP">PHP</option>
                            <option value="HTML">HTML</option>
                            <option value="JAVA">JAVA</option>
                        </select>
                    </div>
                </div>


                <label for="name">名称</label>
                <input id="name" name="name" class="pure-input-3-4" type="text">

                <div class="pure-g">
                    <div class="pure-u-lg-1-4">
                        <label for="passPercent">通过率</label>
                        <input id="passPercent" name="passPercent" class="pure-input-1" type="text">
                    </div>

                    <div class="pure-u-lg-1-4">
                        <label for="tag">标签</label>
                        <input id="tag" name="tag" class="pure-input-1" type="text">
                    </div>

                    <div class="pure-u-lg-1-4">
                        <label for="credit">积分</label>
                        <input id="credit" name="credit" type="text" value="0" class="pure-input-1">
                    </div>
                </div>

                <label for="mark">备注</label>
                <textarea id="mark" name="mark" class="pure-input-1"></textarea>

                <div class='tabs tabs_default'>
                    <ul class='horizontal' style="margin-top: 10px;margin-bottom: 20px">
                        <li><a class="pure-button" href="#describeEdit">题目描述</a></li>
                        <li><a class="pure-button" href="#answerEdit">解答</a></li>
                        <li><a class="pure-button admin" href="#testCaseEdit">测试用例</a></li>
                        <li><a class="pure-button admin" href="#expectedResultEdit">预期结果</a></li>
                    </ul>
                    <div id='describeEdit'>
                        <textarea id='describe' class="pure-input-1"></textarea>
                    </div>
                    <div id='answerEdit'>
                        <textarea id='answer' class="pure-input-1"></textarea>
                    </div>

                    <div id='testCaseEdit'>
                        <textarea id="testCase" class="pure-input-1"></textarea>
                    </div>

                    <div id="expectedResultEdit">
                        <textarea id="expectedResult" class="pure-input-1"></textarea>
                    </div>
                </div>

                <article id="article">
                    <div class="panes contain-floats">
                        <section>
                            <heading>
                                <h1>JavaScript</h1>
                            </heading>

                            <div id="jsContainer"></div>
                        </section>

                        <section>
                            <heading>
                                <h1>PHP 主程序</h1>
                            </heading>

                            <div id="phpContainer"></div>
                        </section>

                        <section>
                            <heading>
                                <h1>PHP 测试用例</h1>
                            </heading>

                            <div id="testContainer"></div>
                        </section>
                    </div>
                </article>

                <div class="button-control">
                    <div class="pure-button" id="runMain">运行主程序</div>
                    <div class="pure-button" id="runUnit">运行测试用例</div>
                </div>
                <div id="out">
                    <iframe class="result" id="result"></iframe>
                </div>

                <button type="button" id="submitForm" style="margin-top: 20px" class="pure-button pure-button-primary">保存</button>
            </fieldset>
        </form>

    </div>
<?php include('../footer.php'); ?>
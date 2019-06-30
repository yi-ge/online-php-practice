$(function () {
  var converter = new showdown.Converter();

  var phpUnitCode = "";
  var javascriptCode = "";
  var theType = "";
  var no = "";
  var isLoading = false;

  require(["vs/editor/editor.main"], function () {
    window.editor = monaco.editor.create(document.getElementById('container'), {
      value: [
        '<?php',
        '\techo "Hello world!";'
      ].join('\n'),
      language: 'php'
    });

    function run (phpCode, oldCode) {
      var resultIframe = document.getElementById('result');
      var resultDocument = resultIframe.contentWindow.document;
      var resultBody = null;

      var clear = function () {
        resultBody.innerHTML = '';
      };

      var print = function (html) {
        // outExeTime();
        resultBody.insertAdjacentHTML('beforeEnd', html);
      };

      var pass = function () {
        console.log('测试通过');
        $.ajax({
          type: "POST",
          url: "/api.php?action=addRecord",
          dataType: "json",
          cache: !1,
          timeout: 6e4,
          data: {
            user_id: window.localStorage.id,
            problemset_id: GetQueryString('id'),
            type: theType,
            code: oldCode,
            is_pass: '1'
          },
          success: function (r) {
            if (r.status === 1) {
              console.log('测试通过的结果保存成功');
              if (confirm("测试通过，进入下一题？")) {
                $.ajax({
                  type: "GET",
                  url: "/api.php?action=getNextNo&no=" + no + "&type=" + theType,
                  dataType: "json",
                  cache: !1,
                  timeout: 6e4,
                  success: function (r) {
                    if (r.status === 1) {
                      if (r.id > 0) {
                        window.location.href = '/page/run.php?id=' + r.id;
                      } else if (r.id == 0){
                        alert('恭喜你！已经做完最后一题！');
                        window.location.href = '/index.php';
                      } else {
                        window.location.href = '/index.php';
                      }
                    } else {
                      alert('很抱歉，获取下一题数据失败，请重试。')
                    }
                  },
                  error: function () {
                    alert('很抱歉，出错了，请重试！');
                  }
                })
              }
            } else {
              alert('很抱歉，保存数据失败，请重试。')
            }
          },
          error: function () {
            alert('很抱歉，出错了，请重试！');
          }
        })
      }

      var printText = function (text) {
        resultBody.appendChild(document.createTextNode(text));
      };

      // Ensure the document has a body for IE9
      resultDocument.write('<body></body>');
      resultDocument.close();
      resultBody = resultDocument.body;

      clear();

      try {
        /*jshint evil: true */
        new Function('phpCode, print, resultBody, pass', javascriptCode)(phpCode, print, resultBody, pass);
      } catch (error) {
        printText('<JavaScript error> ' + error.toString());
      }
    }

    function exec(test) {
      // var start = (new Date()).getTime()

      var phpCode = window.editor.getValue();
      var oldCode = phpCode;

      if (!test) {
        if (isLoading) return;
        isLoading = true;
        if (phpCode.indexOf('// --------- 测试区域') !== -1) {
          phpCode = phpCode.substring(0, phpCode.lastIndexOf('// --------- 测试区域')) + phpUnitCode.replace('<?php', '');
        } else {
          phpCode = phpCode + phpUnitCode.replace('<?php', '');
        }

        // 每次提交都记录数据, 需要成功返回后再执行，确保数据一致性
        $.ajax({
          type: "POST",
          url: "/api.php?action=addRecord",
          dataType: "json",
          cache: !1,
          timeout: 6e4,
          data: {
            user_id: window.localStorage.id,
            problemset_id: GetQueryString('id'),
            type: theType,
            code: oldCode,
            is_pass: '0'
          },
          success: function () {
            run(phpCode, oldCode);
            isLoading = false;
          },
          error: function () {
            alert('很抱歉，出错了，请重试！');
            isLoading = false;
          }
        })
      } else {
        run(phpCode, oldCode)
      }
    }

    $('#testRun').click(function () {
      $('.tabs').trigger('show', '#resultView');
      exec(true);
    });

    $('#run').click(function () {
      $('.tabs').trigger('show', '#resultView');
      exec(false);
    });

    var contentHeight = window.document.body.clientHeight - 50;

    $('#container').css('height', contentHeight - 70);

    var splitter = $('#content').height(contentHeight).split({
      orientation: 'vertical',
      limit: 10,
      position: '40%', // if there is no percentage it interpret it as pixels
      onDrag: function (event) {
        console.log(splitter.position());
        window.editor.layout();
      }
    });

    if (!localStorage.auto) {
      localStorage.auto = '1';
    }

    window.auto = localStorage.auto;

    window.editor.onDidChangeModelContent(function () {
      if (window.auto === '1') {
        exec(true)
      }
    });

    window.editor.layout();

    if (window.auto === '1') {
      $("#auto").prop("checked", 'true');
      exec(true);
    } else {
      $("#auto").prop("checked", 'false')
    }

    $("#auto").click(function () {
      if ($(this).prop("checked")) {
        window.auto = '1';
        localStorage.auto = '1'
      } else {
        window.auto = '2';
        localStorage.auto = '2'
      }
    });

    if (GetQueryString('id')) {
      $.ajax({
        type: "GET",
        url: "/api.php?action=getProblemset&id=" + GetQueryString('id'),
        dataType: "json",
        cache: !1,
        timeout: 6e4,
        success: function (r) {
          if (r.status === 1) {

            var typeStr = '挑战赛';

            if (r.result.type === '1') {
              typeStr = '闯关模式'
            }

            var difficultyStr = '困难';

            if (r.result.difficulty === '1') {
              difficultyStr = '简单'
            } else if (r.result.difficulty === '2') {
              difficultyStr = '中等'
            }

            $(document).attr('title', r.result.name  + ' - ' + typeStr);

            $('#describeView .cont').html('<h3 style="font-weight: 600">' + r.result.name + '(' + difficultyStr + ')' + ' - ' + typeStr + '</h3>' + converter.makeHtml(r.result.describe));
            $('#answerView .cont').html(converter.makeHtml(r.result.answer));

            if (r.result.isAnswer !== '1') {
              $('#answerViewButton').hide();
            }

            window.editor.setValue(r.result.phpCode);

            phpUnitCode = r.result.phpUnitCode;
            javascriptCode = r.result.javascriptCode;

            var testCase = r.result.testCase;
            var expectedResult = r.result.expectedResult;

            $('.testCaseContent').html(converter.makeHtml(testCase));
            $('.expectedResultContent').html(converter.makeHtml(expectedResult));

            $('#rightPane pre code').each(function(i, block) {
              hljs.highlightBlock(block);
            });

            theType = r.result.type;
            no = r.result.no;

            $.ajax({
              type: "POST",
              url: "/api.php?action=getRecord",
              dataType: "json",
              cache: !1,
              timeout: 6e4,
              data: {
                user_id: window.localStorage.id,
                problemset_id: GetQueryString('id'),
              },
              success: function (r) {
                if (r.status === 1) {
                  window.editor.setValue(r.code);
                }

                $('.tabs').tabslet({
                  controls: {
                    prev: '.prevTab',
                    next: '.nextTab'
                  }
                });
                $('#loading').hide();
              },
              error: function () {
                alert('很抱歉，出错了，请稍后刷新重试！');
                $('#loading').hide();
              }
            });
          } else {
            alert('很抱歉，获取数据失败，请稍后刷新重试。')
          }

          $('#loading').hide();
        },
        error: function () {
          alert('很抱歉，出错了，请稍后刷新重试！');
          $('#loading').hide();
        }
      })
    } else {
      $('#loading').hide();
      $('.tabs').tabslet({
        controls: {
          prev: '.prevTab',
          next: '.nextTab'
        }
      });
    }
  });
});
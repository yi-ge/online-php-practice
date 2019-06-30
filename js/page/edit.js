$(function () {
  var jsCode = `'use strict';

var phpEngine = uniter.createEngine('PHP');

phpEngine.expose({
    pass: function () {
        pass();
        return '测试通过';
    },
}, 'r');

phpEngine.getStdout().on('data', function (data) {
    print(data);
});

phpEngine.getStderr().on('data', function (data) {
    print(data);
});

phpEngine.execute(phpCode).fail(function (error) {
    // print(error.toString());
});
    `;

  var testCode = `<?php
	$pass = 1;
	
	$m = 
	if ($m != ) {
		$pass = 0;
		echo "测试用例：<br>";
		echo "输出结果：" . $m . "<br>";
	}

	if ($pass) {
		echo $r->pass();
	} else {
		echo "<br>测试不通过";
	}`;

  require(["vs/editor/editor.main"], function () {
    window.editor = monaco.editor.create(document.getElementById('phpContainer'), {
      value: [
        '<?php',
        '\tfunction sum($a, $b) {',
        '\t\treturn $a + $b;',
        '\t}',
        '',
        '// --------- 测试区域',
        ''
      ].join('\n'),
      language: 'php'
    });

    window.jsEditor = monaco.editor.create(document.getElementById('jsContainer'), {
      value: jsCode,
      language: 'javascript'
    });

    window.testEditor = monaco.editor.create(document.getElementById('testContainer'), {
      value: testCode,
      language: 'php'
    });

    function execMain() {
      var javascriptCode = window.jsEditor.getValue(),
        phpCode = window.editor.getValue(),
        resultIframe = document.getElementById('result'),
        resultDocument = resultIframe.contentWindow.document,
        resultBody;

      function clear() {
        resultBody.innerHTML = '';
      }

      function print(html) {
        resultBody.insertAdjacentHTML('beforeEnd', html);
      }

      function pass() {
        console.log('pass')
      }

      function printText(text) {
        resultBody.appendChild(document.createTextNode(text));
      }

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

    function runUnit() {
      var javascriptCode = window.jsEditor.getValue(),
        phpCode = window.editor.getValue(),
        testCode = window.testEditor.getValue(),
        resultIframe = document.getElementById('result'),
        resultDocument = resultIframe.contentWindow.document,
        resultBody;

      if (phpCode.indexOf('// --------- 测试区域') !== -1) {
        phpCode = phpCode.substring(0, phpCode.lastIndexOf('// --------- 测试区域')) + testCode.replace('<?php', '');
      } else {
        phpCode = phpCode + testCode.replace('<?php', '');
      }


      function clear() {
        resultBody.innerHTML = '';
      }

      function print(html) {
        resultBody.insertAdjacentHTML('beforeEnd', html);
      }

      function pass() {
        console.log('测试通过')
      }

      function printText(text) {
        resultBody.appendChild(document.createTextNode(text));
      }

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

    $('#runMain').click(function () {
      execMain()
    });

    $('#runUnit').click(function () {
      runUnit()
    });

    var contentHeight = window.document.body.clientHeight - 50;

    $('#phpContainer').css('height', contentHeight - 70);
    $('#jsContainer').css('height', contentHeight - 70);
    $('#testContainer').css('height', contentHeight - 70);

    $('#article').css('height', contentHeight + 30);

    window.editor.layout();
    window.jsEditor.layout();
    window.testEditor.layout();


    window.onresize = function () {
      window.editor.layout();
      window.jsEditor.layout();
      window.testEditor.layout();
    };

    var describe = new SimpleMDE({ element: $("#describe")[0] });
    var answer = new SimpleMDE({ element: $("#answer")[0] });
    var testCase = new SimpleMDE({ element: $("#testCase")[0] });
    var expectedResult = new SimpleMDE({ element: $("#expectedResult")[0] });

    $('#submitForm').click(function () {

      // 李永升：你应该适配一下，大于1时直接加百分号，小于1时乘以100再加百分号
      var passPercent = $('#passPercent').val();
      if (passPercent > 1) {
        passPercent = passPercent / 100
      }
      $('#passPercent').val(passPercent);

      var postData = $('#edit-form').serializeArray();

      var javascriptCode = window.jsEditor.getValue(),
        phpUnitCode = window.testEditor.getValue(),
        phpCode = window.editor.getValue();

      postData.push({
        name: 'javascriptCode',
        value: javascriptCode
      });

      postData.push({
        name: 'phpUnitCode',
        value: phpUnitCode
      });

      postData.push({
        name: 'phpCode',
        value: phpCode
      });

      postData.push({
        name: 'describe',
        value: describe.value()
      });

      postData.push({
        name: 'answer',
        value: answer.value()
      });

      postData.push({
        name: 'testCase',
        value: testCase.value()
      });

      postData.push({
        name: 'expectedResult',
        value: expectedResult.value()
      });

      if (GetQueryString('id')) {
        $.ajax({
          type: "POST",
          url: "/api.php?action=editProblemset&id=" + GetQueryString('id'),
          dataType: "json",
          cache: !1,
          timeout: 6e4,
          data: postData,
          success: function (r) {
            if (r.status === 1) {
              window.location.href = '/index.php'
            } else {
              alert('很抱歉，保存失败，请稍后刷新重试。')
            }
          },
          error: function () {
            alert('很抱歉，出错了，请稍后刷新重试！')
          }
        })
      } else {
        $.ajax({
          type: "POST",
          url: "/api.php?action=addProblemset",
          dataType: "json",
          cache: !1,
          timeout: 6e4,
          data: postData,
          success: function (r) {
            if (r.status === 1) {
              window.location.href = '/index.php'
            } else {
              alert('很抱歉，新增题目失败，请稍后刷新重试。')
            }
          },
          error: function () {
            alert('很抱歉，出错了，请稍后刷新重试！')
          }
        })
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
            $('#type').val(r.result.type);
            $('#name').val(r.result.name);
            $('#isAnswer').val(r.result.isAnswer);
            $('#language').val(r.result.language);
            $('#difficulty').val(r.result.difficulty);
            $('#isCredit').val(r.result.isCredit);
            $('#passPercent').val(r.result.passPercent);
            $('#tag').val(r.result.tag);
            $('#credit').val(r.result.credit);
            $('#mark').val(r.result.mark);

            describe.value(r.result.describe);
            answer.value(r.result.answer);
            testCase.value(r.result.testCase);
            expectedResult.value(r.result.expectedResult);

            window.editor.setValue(r.result.phpCode);
            window.testEditor.setValue(r.result.phpUnitCode);
            window.jsEditor.setValue(r.result.javascriptCode);

            $('.tabs').tabslet({
              controls: {
                prev: '.prevTab',
                next: '.nextTab'
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
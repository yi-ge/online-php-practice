$(function () {

  var saveTestCode = function (code) {
    $.ajax({
      type: "POST",
      url: "/api.php?action=saveTestCode",
      dataType: "json",
      cache: !1,
      timeout: 6e4,
      data: {
        user_id: window.localStorage.id,
        code: code
      }
    })
  };

  require(["vs/editor/editor.main"], function () {
    $.ajax({
      type: "POST",
      url: "/api.php?action=getTestCode",
      dataType: "json",
      cache: !1,
      timeout: 6e4,
      data: {
        user_id: window.localStorage.id,
      },
      success: function (r) {
        if (r.status === 1) {
          window.editor = monaco.editor.create(document.getElementById('container'), {
            value: r.code,
            language: 'php'
          });

          function exec() {
            // var start = (new Date()).getTime()

            var javascriptCode = `'use strict';

var phpEngine = uniter.createEngine('PHP');

phpEngine.getStdout().on('data', function (data) {
    print(data);
});

phpEngine.getStderr().on('data', function (data) {
    print(data);
});

phpEngine.execute(phpCode).fail(function (error) {
    // print(error.toString());
});
    `,
              phpCode = window.editor.getValue(),
              resultIframe = document.getElementById('result'),
              resultDocument = resultIframe.contentWindow.document,
              resultBody;

            saveTestCode(phpCode);

            function clear() {
              resultBody.innerHTML = '';
            }

            function print(html) {
              // outExeTime();
              resultBody.insertAdjacentHTML('beforeEnd', html);
            }

            function printText(text) {
              resultBody.appendChild(document.createTextNode(text));
            }

            // function outExeTime () {
            //   // var execTimeString = getNowDate() + '执行结果(' + ((new Date()).getTime() - start) + 'ms)';
            //   // var headerHtml = '<span style="color: #888;font-weight: 300;font-size: 10px;">' + execTimeString + ':</span><br>'
            //   // $('#out').html(headerHtml)
            // }

            // Ensure the document has a body for IE9
            resultDocument.write('<body></body>');
            resultDocument.close();
            resultBody = resultDocument.body;

            clear();

            try {
              /*jshint evil: true */
              new Function('phpCode, print, resultBody', javascriptCode)(phpCode, print, resultBody);
            } catch (error) {
              printText('<JavaScript error> ' + error.toString());
            }
          }

          $('#run').click(function () {
            exec()
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
              exec()
            }
          });

          window.editor.layout();

          if (window.auto === '1') {
            $("#auto").prop("checked", 'true')
            exec()
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

          $('#loading').hide();
        } else {
          alert('很抱歉，获取已保存的代码失败，请稍后刷新重试。')
        }
      },
      error: function () {
        alert('很抱歉，出错了，请稍后刷新重试！')
      }
    });
  });
});
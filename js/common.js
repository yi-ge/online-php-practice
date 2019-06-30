require.config({paths: {'vs': 'https://lib.baomitu.com/monaco-editor/0.15.6/min/vs'}});

// Before loading vs/editor/editor.main, define a global MonacoEnvironment that overwrites
// the default worker url location (used when creating WebWorkers). The problem here is that
// HTML5 does not allow cross-domain web workers, so we need to proxy the instantiation of
// a web worker through a same-domain script
window.MonacoEnvironment = {
  getWorkerUrl: function (workerId, label) {
    return '/js/monaco-editor-worker-loader-proxy.js';
  }
};

window.isAdmin = false;

function getNowDate() {
  var date = new Date();
  var sign1 = "-";
  var sign2 = ":";
  var year = date.getFullYear() // 年
  var month = date.getMonth() + 1; // 月
  var day = date.getDate(); // 日
  var hour = date.getHours(); // 时
  var minutes = date.getMinutes(); // 分
  var seconds = date.getSeconds() //秒
  var weekArr = ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期天'];
  var week = weekArr[date.getDay()];
  // 给一位数数据前面加 “0”
  if (month >= 1 && month <= 9) {
    month = "0" + month;
  }
  if (day >= 0 && day <= 9) {
    day = "0" + day;
  }
  if (hour >= 0 && hour <= 9) {
    hour = "0" + hour;
  }
  if (minutes >= 0 && minutes <= 9) {
    minutes = "0" + minutes;
  }
  if (seconds >= 0 && seconds <= 9) {
    seconds = "0" + seconds;
  }
  return year + sign1 + month + sign1 + day + " " + hour + sign2 + minutes + sign2 + seconds + " " + week;
}

function GetQueryString(name) {
  var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
  var r = window.location.search.substr(1).match(reg);
  if (r!=null) return unescape(r[2]); return '';
}

$(function () {
  var id = window.localStorage.id;

  if (!id) {
    window.location.href = '/login.html'
  }

  var setRealName = function (id, realname) {
    $.ajax({
      type: "POST",
      url: "/api.php?action=setRealName",
      dataType: "json",
      cache: !1,
      timeout: 6e4,
      data: {
        id: id,
        realname: realname
      },
      success: function (r) {
        if (r.status === 1) {
          console.log('保存真实姓名成功！')
        } else {
          alert('很抱歉，保存真实姓名失败，请稍后刷新重试。')
        }
      },
      error: function () {
        alert('很抱歉，出错了，请稍后刷新重试！')
      }
    })
  };

  function loadImage(url, callback) {
    var img = new Image(); //创建一个Image对象，实现图片的预下载
    img.crossOrigin = "Anonymous";
    img.src = url;
    img.onload = function () { //图片下载完毕时异步调用callback函数
      callback.call(img);//将回调函数的this替换为Image对象
    };
  }

  var getUserInfo = function (id) {
    $.ajax({
      type: "POST",
      url: "/api.php?action=getUserInfo",
      dataType: "json",
      cache: !1,
      timeout: 6e4,
      data: {
        id: id
      },
      success: function (r) {
        if (r.status === 1) {
          if (!r.result.userinfo) {
            window.location.href = "login.html"
          }
          if (!r.result.userinfo.realname) {
            var name = prompt("请输入你的真实姓名，方便大家进行沟通", "");
            if (name != null && name !== "") {
              r.result.userinfo.realname = name;
              setRealName(id, name)
            }
          }

          window.isAdmin = r.result.userinfo.admin === '1';
          window.credit = r.result.userinfo.credit;

          if ($('.myCredit')) $('.myCredit').text('经验：' + window.credit);

          loadImage(r.result.userinfo.headimgurl.replace('http://', 'https://'), function () {
            $('#avator').append(this)
          });

          $('#realname').text(r.result.userinfo.realname);
          $('#nickname').text('(' + r.result.userinfo.nickname + ')');

          if (window.isAdmin) {
            $('.admin').css('display', 'block')
          }
        } else {
          alert('很抱歉，获取用户信息失败，请稍后刷新重试。')
        }
      },
      error: function () {
        alert('很抱歉，出错了，请稍后刷新重试！')
      }
    })
  };

  $('#logout').click(function () {
    window.localStorage.removeItem('id');
    window.location.href = "/login.html"
  });

  getUserInfo(id);

});
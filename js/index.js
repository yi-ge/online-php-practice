$(function () {
  var language = {
    "sProcessing": "处理中...",
    "sLengthMenu": "显示 _MENU_ 项结果",
    "sZeroRecords": "没有匹配结果",
    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
    "sInfoPostFix": "",
    "sSearch": "搜索:",
    "sUrl": "",
    "sEmptyTable": "表中数据为空",
    "sLoadingRecords": "载入中...",
    "sInfoThousands": ",",
    "oPaginate": {
      "sFirst": "首页",
      "sPrevious": "上页",
      "sNext": "下页",
      "sLast": "末页"
    },
    "oAria": {
      "sSortAscending": ": 以升序排列此列",
      "sSortDescending": ": 以降序排列此列"
    }
  };

  var tableInit = function (r) {
    $('#stage-table').DataTable({
      language: language,
      data: r.result.stage,
      columns: [
        {title: ""},
        {title: "ID"},
        {title: "No"},
        {title: "题名"},
        {title: "编程语言"},
        {title: "解答"},
        {title: "通过率"},
        {title: "难度"},
        {title: "管理"}
      ],
      "order": [[2, "asc"]],
      columnDefs: [
        {
          "targets": 0,
          "orderable": false,
          "className": "table-is-pass",
          "render": function (data, type, row) {
            if (data === '1') {
              return '<svg viewBox="0 0 1397 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27" height="20"><path d="M1396.363636 121.018182c0 0-223.418182 74.472727-484.072727 372.363636-242.036364 269.963636-297.890909 381.672727-390.981818 530.618182C512 1014.690909 372.363636 744.727273 0 549.236364l195.490909-186.181818c0 0 176.872727 121.018182 297.890909 344.436364 0 0 307.2-474.763636 902.981818-707.490909L1396.363636 121.018182 1396.363636 121.018182zM1396.363636 121.018182" fill="#1afa29"></path></svg>';
            }

            return '';
          }
        },
        {
          "targets": 1,
          "data": null,
          "visible": false,
          "orderable": false
        },
        {
          "targets": 3,
          "className": "table-pname",
          "render": function (data, type, row) {
            return '<a href="/page/run.php?id=' + row[1] + '" target="_blank">' + data + '</a>';
          }
        },
        {
          "targets": -1,
          "data": null,
          "visible": window.isAdmin,
          "render": function (data, type, row) {
            return '<a href="/page/edit.php?id=' + row[1] + '" target="_blank" class="pure-button" style="font-size: 70%;">编辑</a>';
          }
        }]
    });

    $('#challenge-table').DataTable({
      language: language,
      data: r.result.challenge,
      columns: [
        {title: ""},
        {title: "ID"},
        {title: "No"},
        {title: "题名"},
        {title: "编程语言"},
        {title: "解答"},
        {title: "通过率"},
        {title: "难度"},
        {title: "管理"}
      ],
      "order": [[2, "asc"]],
      columnDefs: [
        {
          "targets": 0,
          "orderable": false,
          "render": function (data, type, row) {
            return data;
          }
        },
        {
          "targets": 1,
          "data": null,
          "visible": false,
          "orderable": false
        },
        {
          "targets": 3,
          "className": "table-pname",
          "render": function (data, type, row) {
            return '<a href="/page/run.php?id=' + row[1] + '" target="_blank">' + data + '</a>';
          }
        },
        {
          "targets": -1,
          "data": null,
          "visible": window.isAdmin,
          "render": function (data, type, row) {
            return '<a href="/page/edit.php?id=' + row[1] + '" target="_blank" class="pure-button" style="font-size: 70%;">编辑</a>';
          }
        }]
    });

    $('.tabs').tabslet({
      controls: {
        prev: '.prevTab',
        next: '.nextTab'
      }
    });
    $('#loading').hide();
  };

  var checkAdmin = function (r) {
    if (window.isAdmin !== null) {
      tableInit(r);
    } else {
      setTimeout(function () {
        checkAdmin(r)
      }, 50);
    }
  };

  $.ajax({
    type: "GET",
    url: "/api.php?action=getProblemsetList&user_id=" + window.localStorage.id,
    dataType: "json",
    cache: !1,
    timeout: 6e4,
    success: function (r) {
      if (r.status === 1) {
        checkAdmin(r)
      } else {
        alert('很抱歉，获取数据失败，请稍后刷新重试。');
        $('#loading').hide();
      }
    },
    error: function () {
      alert('很抱歉，出错了，请稍后刷新重试！');
      $('#loading').hide();
    }
  });

  $('#myRecordButton').click(function() {
    $.ajax({
      type: "GET",
      url: "/api.php?action=getMyRecordCount&user_id=" + window.localStorage.id,
      dataType: "json",
      cache: !1,
      timeout: 6e4,
      success: function (r) {
        if (r.status === 1) {
          if (r.result.all === r.result.pass) {
            alert('恭喜您！已完成全部 ' + r.result.all + ' 道闯关题！');
          } else {
            alert('闯关题共有 ' + r.result.all + ' 道，您已经完成 ' + r.result.pass + '道。');
          }
        } else {
          alert('很抱歉，获取数据失败，请稍后刷新重试。');
        }
      },
      error: function () {
        alert('很抱歉，出错了，请稍后刷新重试！');
      }
    });
  });
});
<?php
error_reporting(0);
require_once 'config.php';
require_once 'medoo.php';

use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

$redis = new Redis();
$redis->connect($REDISCONFIG['host'], $REDISCONFIG['port'], $REDISCONFIG['database']);
$redis->auth($REDISCONFIG['password']);

header('Access-Control-Allow-Headers: Authorization, DNT, User-Agent, Keep-Alive, Origin, X-Requested-With, Content-Type, Accept, x-clientid');
header('Access-Control-Allow-Methods: PUT, POST, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

function action_login($database, $redis)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://login.yige.ink/info?token=' . $_ENV['API_TOKEN'] . '&code=' . $_GET['code']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    $output_array = json_decode($output, true);

    $output_array['privilege'] = json_encode($output_array['privilege']);

    $row = $database->select('userinfo', '*', [
        'openid' => $output_array['openid'],
    ]);

    if (sizeof($row) != 0) {
        $database->update('userinfo', $output_array, [
            'openid' => $output_array['openid'],
        ]);

        $output_array['openid'] = null;

        echo json_encode([
            'status' => 1,
            'result' => [
                'id' => $row[0]['id'],
                'userinfo' => $output_array
            ]
        ]);
    } else {
        $database->insert('userinfo', $output_array);

        $output_array['openid'] = null;

        echo json_encode([
            'status' => 1,
            'result' => [
                'id' => $database->id(),
                'userinfo' => $output_array
            ]
        ]);
    }
}

function action_listUser($database, $redis)
{
    $row = $database->select('userinfo', '*');

    echo json_encode([
        'status' => 1,
        'result' => [
            'list' => $row,
        ]
    ]);
}

function action_getUserInfo($database, $redis)
{
    $row = $database->select('userinfo', '*', [
        'id' => $_POST['id'],
    ]);

    echo json_encode([
        'status' => 1,
        'result' => [
            'userinfo' => $row[0]
        ]
    ]);
}

function action_setRealName($database, $redis)
{
    $database->update('userinfo', [
        'realname' => $_POST['realname']
    ], [
        'id' => $_POST['id'],
    ]);

    echo json_encode([
        'status' => 1
    ]);
}

function handleRow($row)
{
    foreach ($row as $ro => $val) {
        if (is_numeric($ro)) {
            unset($row[$ro]);
        }
    }
    if ($row['isAnswer'] == '1') {
        $row['isAnswer'] = '有';
    } else {
        $row['isAnswer'] = '无';
    }

    $row['passPercent'] = round((float)$row['passPercent'] * 100, 2) . '%';

    if ($row['difficulty'] == '1') {
        $row['difficulty'] = '简单';
    } else if ($row['difficulty'] == '2') {
        $row['difficulty'] = '中等';
    } else if ($row['difficulty'] == '3') {
        $row['difficulty'] = '困难';
    }

    unset($row['type']);

    return array_values($row);
}

function handleSortRows($rows)
{
    uksort($rows, function ($a, $b) {
        return (int)$a['no'] - (int)$b['no'];
    });
}

function action_getProblemsetList($database, $redis)
{
//    $rowAll = $database->debug()->select('problemset', [
//        "[>]record" => [
//            "id" => "problemset_id",
//            $_GET['user_id'] => "user_id"
//        ],
//    ], [
//        'record.is_pass',
//        'problemset.id',
//        'problemset.no',
//        'problemset.name',
//        'problemset.language',
//        'problemset.type',
//        'problemset.isAnswer',
//        'problemset.passPercent',
//        'problemset.difficulty',
//    ], [
//        "ORDER" => "no"
//    ]);

    $rowAll = $database->query("SELECT
    `record`.`is_pass`,
    `problemset`.`id`,
    `problemset`.`no`,
    `problemset`.`name`,
    `problemset`.`language`,
    `problemset`.`type`,
    `problemset`.`isAnswer`,
    `problemset`.`passPercent`,
    `problemset`.`difficulty`
FROM
    `problemset`
LEFT JOIN `record` ON `problemset`.`id` = `record`.`problemset_id` AND `record`.`user_id` = " . $_GET['user_id'] . " ORDER BY
    `no`")->fetchAll();

    $rowStage = [];
    $rowChallenge = [];

    foreach ($rowAll as $row)
        if ($row['type'] == '1')
            array_push($rowStage, handleRow($row));
        else
            array_push($rowChallenge, handleRow($row));


    handleSortRows($rowStage);

    handleSortRows($rowChallenge);

    echo json_encode([
        'status' => 1,
        'result' => [
            'stage' => $rowStage,
            'challenge' => $rowChallenge,
            'test' => $database->log()
        ]
    ]);
}

function action_editProblemset($database, $redis)
{
    $datas = $_POST;

    $database->update('problemset', $datas, [
        'id' => $_GET['id']
    ]);

    echo json_encode([
        'status' => 1
    ]);
}

function action_addProblemset($database, $redis)
{
    $datas = $_POST;

    $datas['no'] = $redis->incr("ids_" . $datas['type']);

    $database->insert('problemset', $datas);

    echo json_encode([
        'status' => 1,
        'result' => [
            'id' => $database->id(),
            'no' => $datas['no']
        ]
    ]);
}

function action_getProblemset($database, $redis)
{
    if (isset($_GET['id'])) {
        $row = $database->select('problemset', '*', [
            'id' => $_GET['id']
        ]);
        if (sizeof($row) > 0) {
            $row = $row[0];
            echo json_encode([
                'status' => 1,
                'result' => $row
            ]);
        } else {
            echo json_encode([
                'status' => 2,
                'msg' => '没有找到此题目信息'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 0,
            'msg' => '缺少必须参数'
        ]);
    }
}

function action_saveTestCode($database, $redis)
{
    $datas = $_POST;

    $redis->set('testCode_' . $datas['user_id'], $datas['code']);

//    if ($database->has("test_record", [
//        'user_id' => $datas['user_id']
//    ])) {
//        $database->update("test_record", [
//            'code' => $datas['code']
//        ], [
//            'user_id' => $datas['user_id']
//        ]);
//    } else {
//        $database->insert('test_record', $datas);
//    }

    echo json_encode([
        'status' => 1
    ]);
}

function action_getTestCode($database, $redis)
{
    $datas = $_POST;

//    $row = $database->select("test_record", ["code"], [
//        'user_id' => $datas['user_id']
//    ]);

    $res = $redis->get('testCode_' . $datas['user_id']);

    if ($res == null || $res == "") {
        echo json_encode([
            'status' => 1,
            'code' => '<?php
        echo "Hello World!";'
        ]);
    } else {
        echo json_encode([
            'status' => 1,
            'code' => $res
        ]);
    }
}

function addCredit($database, $problemsetId, $userId)
{
    $problemsetRow = $database->select("problemset", ['id', 'isCredit', 'credit', 'type', 'no'], [
        'id' => $problemsetId
    ]);

    if ($problemsetRow[0]['isCredit'] == '1') {
        $userinfoRow = $database->select("userinfo", ['id', "credit"], [
            'id' => $userId
        ]);

        $oldCredit = $userinfoRow[0]['credit'];

        $addCredit = $problemsetRow[0]['credit'];

        $newCredit = (int)$oldCredit + (int)$addCredit;

        if (!$database->has("credit_flow", [ // 限制一道题只能获得一次积分
            'problemset_id' => $problemsetId
        ])) {
            $theType = '闯关模式';
            if ($problemsetRow[0]['type'] == '2') {
                $theType = '挑战赛';
            }

            $log = $theType . '第' . $problemsetRow[0]['no'] . '题得分';

            $database->insert('credit_flow', [ // 写入积分变动日志
                'log' => $log,
                'user_id' => $userId,
                'problemset_id' => $problemsetId,
                'income' => $addCredit,
                'credit' => $newCredit
            ]);

            $database->update('userinfo', [
                'credit' => $newCredit
            ], [
                'id' => $userId
            ]);
        }
    }
}

function action_addRecord($database, $redis)
{
    $datas = $_POST;

    $row = $database->select("record", ['is_pass'], [
        'user_id' => $datas['user_id'],
        'problemset_id' => $datas['problemset_id']
    ]);

    if (sizeof($row) != 0) {
        if ($datas['is_pass'] == '1' || $row[0]['is_pass'] != '1') {
            $database->update('record', $datas, [
                'user_id' => $datas['user_id'],
                'problemset_id' => $datas['problemset_id']
            ]);
        }
    } else {
        $database->insert('record', $datas);
    }

    echo json_encode([
        'status' => 1
    ]);

    fastcgi_finish_request();

    // 异步

    if ($datas['is_pass'] == '1') { // 增加积分
        addCredit($database, $datas['problemset_id'], $datas['user_id']);
    }
}

function action_getRecord($database, $redis)
{
    $datas = $_POST;

    $row = $database->select("record", ['code'], [
        'user_id' => $datas['user_id'],
        'problemset_id' => $datas['problemset_id']
    ]);

    if (sizeof($row) != 0) {
        echo json_encode([
            'status' => 1,
            'code' => $row[0]['code']
        ]);
    } else {
        echo json_encode([
            'status' => 2
        ]);
    }
}

function action_getNextNo($database, $redis)
{
    $no = (int)$_GET['no'] + 1;

    $row = $database->select("problemset", ["id"], [
        'no' => $no,
        'type' => $_GET['type']
    ]);

    if (sizeof($row) != 0) {
        echo json_encode([
            'status' => 1,
            'id' => $row[0]['id']
        ]);
    } else {
        echo json_encode([
            'status' => 1,
            'id' => 0
        ]);
    }
}

function action_getMyRecordCount($database, $redis)
{
    $Allcount = $database->count("problemset", [
        "type" => "1"
    ]);

    $passCount = $database->count("record", [
        'user_id' => $_GET['user_id'],
        'is_pass' => '1'
    ]);

    echo json_encode([
        'status' => 1,
        'result' => [
            'all' => $Allcount,
            'pass' => $passCount
        ]
    ]);
}

if (isset($_GET['action'])) {
    $action = "action_" . $_GET['action'];
    $action($database, $redis);
} else {
    echo json_encode(['err' => '非法访问']);
}

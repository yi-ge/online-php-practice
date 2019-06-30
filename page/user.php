<?php include('../header.php'); ?>
<?php
//error_reporting(0);
require_once '../config.php';
require_once '../medoo.php';

use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

$Allcount = $database->count("problemset", [
    "type" => "1"
]);

$rows = $database->select('userinfo', '*', [
    "ORDER" => [
        "id" => "DESC"
    ]
]);
?>
    <div class="user" style="padding: 10px">
        <table class="pure-table" style="width: 100%;">
            <thead>
            <tr>
                <th>#</th>
                <th>头像</th>
                <th>用户昵称</th>
                <th>真实姓名</th>
                <th>用户性别</th>
                <th>进度</th>
                <th>管理</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($rows as $inx => $row) { ?>
                <tr class="<?php if ($inx % 2) echo 'pure-table-odd'; ?>">
                    <td><?php echo $row['id'] ?></td>
                    <td><img src="<?php echo $row['headimgurl']; ?>" width="50"/></td>
                    <td><?php echo $row['nickname']; ?></td>
                    <td><?php echo $row['realname']; ?></td>
                    <td><?php if ($row['sex'] == '1') {
                            echo '男';
                        } else {
                            echo '女';
                        } ?></td>
                    <td><?php
                        $passCount = $database->count("record", [
                            'user_id' => $row['id'],
                            'is_pass' => '1'
                        ]);

                        echo $passCount . '/' . $Allcount;
                        ?></td>
                    <td><a href="/page/userinfo.php?id=<?php echo $row['id']; ?>" target="_blank">查看详情</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php include('../footer.php'); ?>
<?php include('../header.php'); ?>
<?php
//error_reporting(0);
require_once '../config.php';
require_once '../medoo.php';

use Medoo\Medoo;

$database = new Medoo($DBCONFIG);

$rows = $database->query("SELECT
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
LEFT JOIN `record` ON `problemset`.`id` = `record`.`problemset_id` AND `record`.`user_id` = " . $_GET['id'] . " WHERE `problemset`.`type` = '1' ORDER BY
    `no`")->fetchAll();
?>
    <div class="user" style="padding: 10px">
        <table class="pure-table" style="width: 100%;">
            <thead>
            <tr>
                <th>No</th>
                <th>题目</th>
                <th>做题状态</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($rows as $inx => $row) { ?>
                <tr class="<?php if ($inx % 2) echo 'pure-table-odd'; ?>">
                    <td><?php echo $row['no'] ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php if ($row['is_pass'] == '1') {
                            echo '<svg viewBox="0 0 1397 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27" height="20"><path d="M1396.363636 121.018182c0 0-223.418182 74.472727-484.072727 372.363636-242.036364 269.963636-297.890909 381.672727-390.981818 530.618182C512 1014.690909 372.363636 744.727273 0 549.236364l195.490909-186.181818c0 0 176.872727 121.018182 297.890909 344.436364 0 0 307.2-474.763636 902.981818-707.490909L1396.363636 121.018182 1396.363636 121.018182zM1396.363636 121.018182" fill="#1afa29"></path></svg>';
                        } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php include('../footer.php'); ?>
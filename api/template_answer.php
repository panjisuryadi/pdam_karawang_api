<?php
require '../function.php';
require '../mlog.php';

$conn       = koneksi('eva_fitur');
$cmd        = dec('cmd');
$id         = dec('id');
$db         = dec('db');
$agent      = dec('agent');
$untuk      = dec('untuk');
$template   = dec('template');
$status     = dec('status');
$page       = dec('page', 1);
$limit      = dec('limit', 25);
$mulai      = ($page*$limit)-$limit;

if($cmd == 'list'){

}
elseif($cmd == 'insert'){
    try {
        $sql = "INSERT INTO eva_fitur.template_answer SET ta_db=:db, ta_agent=:agent, ta_untuk=:untuk, ta_template=:template";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':db' => $db,
            ':agent' => $agent,
            ':untuk' => $untuk,
            ':template' => $template,
        ]);
        if($stmt->rowCount() > 0){
            respon(200, 'inserted');
        }else{
            respon(500, 'fail to insert');
        }
    } catch (\Throwable $th) {
        errors($th);
        respon(500, 'error please try again later');
    }
}
elseif($cmd == 'update'){

}
elseif($cmd == 'delete'){

}
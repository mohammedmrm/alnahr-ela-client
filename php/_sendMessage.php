<?php
session_start();
//error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5]);
require_once("dbconnection.php");
require_once("_sendNoti.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$message  = $_REQUEST['message'];
$order_id = $_REQUEST['order_id'];


$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
]);

$v->validate([
    'message'    => [$message,    'required|min(1)|max(500)'],
    'order_id'   => [$order_id,    'required|int'],
]);

if($v->passes()) {
  $sql = 'insert into message (message,order_id,from_id,is_client) values
                             (?,?,?,?)';
  $result = setData($con,$sql,[$message,$order_id,$_SESSION['userid'],1]);
  if($result > 0){
    $sql = "select staff.token as s_token, clients.token as c_token from orders inner join staff
            on
            staff.id = orders.manager_id
            or
            staff.id = orders.driver_id
            inner join clients on clients.id = orders.client_id
            where orders.id = ?";
    $res =getData($con,$sql,[$order_id]);
    sendNotification([$res[0]['token'],[$order_id],$res[1]['token']],'رساله جديد - '.$order_id,$message,"../orderDetails.php?o=".$order_id);
    $success = 1;
  }
}else{
  $error = [
           'message'=> implode($v->errors()->get('message')),
           'order_id'=>implode($v->errors()->get('order_id')),
           ];
}
echo json_encode(['success'=>$success,'error'=>$error]);
?>
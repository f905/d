<?php
date_default_timezone_set('Asia/Baghdad');
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
$admin = '@GGGCG';
include 'madeline.php';
$settings['app_info']['api_id'] = 210897;
$settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff';
$MadelineProto = new \danog\MadelineProto\API('session.madeline', $settings);
$MadelineProto->start();
$user = readline('enter username : ');
$type = readline('Enter type (u/c/h/b): ');
if($type == 'h'){
  $ch = readline('Enter Channel : ');
} elseif($type == 'c'){
  $updates = $MadelineProto->channels->createChannel(['broadcast' => true, 'megagroup' => false, 'title' => "$admin", 'about'=>".", ]);
  $ch = $updates['updates'][1];
} elseif($type == 'b'){
  $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>'/newbot']);
  sleep(1);
  $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>$admin]);
}
$i = 1;

$MadelineProto->messages->sendMessage(['peer' => $admin, 'message' => "Start : @$user"]);
$t = $MadelineProto->get_info($user)['type'];

while(1){
  try { 
    if($t == 'channel'){
      $m = $MadelineProto->channels->getChannels(['id' => [$user], ])['chats'][0];
    } else {
      $m = $MadelineProto->users->getUsers(['id' => [$user]])[0];
    }
    if(strtolower($m['username']) != strtolower($user)){
      if($type == 'u'){
          $MadelineProto->account->updateUsername(['username' => $user]);
          $msg = 'in account';
        } elseif($type == 'c' or $type == 'h'){
          $MadelineProto->channels->updateUsername(['channel' =>$ch, 'username' => $user, ]);
          if(is_string($ch)){
            $msg = 'in channel '.$ch;
          } else {
            $msg = 'in channel';
          }
        } elseif($type == 'b'){
          $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>$user]);
          $msg = 'in bot';
        }
          $MadelineProto->messages->sendMessage(['peer' => $admin, 'message' => "Done @$user"]);
          exit();
    }
    echo $m['username'].' - '.$i.' = '.date('s')."\n——\n";
    $i++;
  } catch(Exception $e){
    if($e->getMessage() == 'Undefined index: username' or $e->getMessage() == 'You haven\'t joined this channel/supergroup' ){
      $Bool = $MadelineProto->account->checkUsername(['username' => $user, ]);
      if($Bool){
         if($type == 'u'){
          $MadelineProto->account->updateUsername(['username' => $user]);
          $msg = 'in account';
        } elseif($type == 'c' or $type == 'h'){
          $MadelineProto->channels->updateUsername(['channel' =>$ch, 'username' => $user, ]);
          if(is_string($ch)){
            $msg = 'in channel @'.$ch;
          } else {
            $msg = 'in channel';
          }
        } elseif($type == 'b'){
          $MadelineProto->messages->sendMessage(['peer' => '@botfather','message'=>$user]);
          $msg = 'in bot';
        }
          $MadelineProto->messages->sendMessage(['peer' => $admin, 'message' => "Done @$user"]);
          exit();
      }
    } else {
      $MadelineProto->messages->sendMessage(['peer' => $admin, 'message' => $e->getMessage()]);
      exit();
    }
    echo $e->getMessage();
    
  }
}

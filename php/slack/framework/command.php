<?php
  require_once (dirname(__FILE__).'/phpFastCache/phpFastCache.php');
  use phpFastCache\CacheManager;

  $config = array(
    "storage"   =>  "Files",
    "path" => sys_get_temp_dir()
  );
  CacheManager::setup($config);

  if ($_REQUEST["user_name"] == "slackbot") {
    exit;
  }

  $chat_id = $_REQUEST["token"] . "_" . $_REQUEST["user_name"];
  $chat_context = CacheManager::get($chat_id . "_context");
  if (is_null($chat_context)) {
    $chat_context = array ();
  }

  $human_says = $_REQUEST["text"];
  $bot_says = converse ($human_says, $chat_context);
  CacheManager::set($chat_id . "_context", $chat_context, 600); // cache for 600 seconds

  if (is_null($bot_says)) {
    $bot_says = "Sorry, I cannot understand you!";
  }

  if (is_array($bot_says)) {
    header('Content-type: application/json');
    echo json_encode($bot_says);
  } else {
    echo htmlspecialchars($bot_says);
  }


  // INPUT PARAMS
  //   $human is the human message to this bot
  //   $context is an array that contains any data related to this chat session. You can put any data here and access it later.

  // RETURNS the bot's next message to the human

  // function converse ($human, &$context) {
    // IMPLEMENT ME!
  // }
?>

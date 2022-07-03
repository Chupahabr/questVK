<?php
require_once "VkSync.php";
use Test\VkSync;

global $USER;

$res = new VkSync();

$token = $_SESSION["VK_TOKEN"]??"Тут был мой токен доступа, теперь его нет!";

switch ($_REQUEST["method"]) {
	case "auth":
		$auth = $res
			->auth($_REQUEST["NAME"], $_REQUEST["PASSWORD"]);

		$token = $res->getToken();

        if (!empty($token)) {
		    $_SESSION["VK_TOKEN"] = $token;
            echo json_encode($auth);
        } else {
			echo json_encode($auth);
        }

		break;
	case "groups":
		$groups = $res
			->setToken($token)
			->setUserId($_REQUEST["USER_ID"])
			->getGroupsUser();

		$groupsInfo = [];

		foreach($groups as $group){
			$groupsInfo["groups"][] = [
				"id" => $group["id"],
				"data-name" => $group["name"],
			];
		}

		echo json_encode($groupsInfo);

		break;
	case "audio":
		$audioPosts = $res
		    ->setToken($token)
		    ->getAudioInPost(100, $_REQUEST["idGroup"], $_REQUEST["next_from"]);

		$audioPostsInfo = [];
		$audioPostsInfo["next_from"] = $audioPosts["next_from"];
		$audioPostsInfo["idGroup"] = $_REQUEST["idGroup"];

		foreach($audioPosts["posts"] as $audioPost){
			$audioPostsInfo["posts"][] = [
				"id" => $audioPost["audio_playlist"]["id"],
				"owner_id" => $audioPost["audio_playlist"]["owner_id"],
				"access_key" => $audioPost["audio_playlist"]["access_key"],
			];
		}

		echo json_encode($audioPostsInfo);

		break;
}
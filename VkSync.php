<?php
namespace Test;

class VkSync
{
    private $url = "https://api.vk.com";
    private $method;
    private $token;
    private $user_id;
    private $type;
    // Базовое значение client_id и $client_secret из формы авторизации vk через мобильное приложение
    private $client_id = "2274003";
    private $client_secret = "hHbZxrka2uZ6jB1inYsH";

    public function __construct($token = "")
    {
        $this->token = $token;
    }

    public function auth($login, $password)
    {
        $this->type = "oauth";
        $this->method = "token";
        $res = $this->request([
            "grant_type" => "password",
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "username" => $login,
            "password" => $password,
        ]);
        $this->token = $res["access_token"];
        $this->user_id = $res["user_id"];

        return $res;
    }

    public function setUserId($userId = "")
    {
        $request = [
            "access_token" => $this->token,
        ];
        if (!empty($userId)) {
            $request["user_id"] = $userId;
        }
        $res = $this
            ->setType("method")
            ->setMethod("users.get")
            ->request($request);
        $this->user_id = $res["response"]["0"]["id"];

        return $this;
    }

    public function getUser()
    {
        $res = $this
            ->setType("method")
            ->setMethod("users.get")
            ->request([
                "user_ids" => $this->user_id,
                "access_token" => $this->token,
            ]);

        return $res["response"][0];
    }

    public function getGroupsUser()
    {
        $res = $this
            ->setType("method")
            ->setMethod("groups.get")
            ->request([
                "user_id" => $this->user_id,
                "access_token" => $this->token,
                "extended" => 1,
            ]);

        return $res["response"]["items"];
    }

    public function getPostsGroup($count, $idGroup, $offset)
    {
        $res = $this
            ->setType("method")
            ->setMethod("wall.get")
            ->request([
                "access_token" => $this->token,
                "owner_id" => "-" . $idGroup,
                "count" => $count,
                "offset" => $offset,
            ]);

        return $res;
    }

    public function getAudioInPost($count, $idGroup, $offset)
    {
        $posts = $this->getPostsGroup($count, $idGroup, $offset);
        $arrayAudioPost = [];
        foreach ($posts["response"]["items"] as $post) {
            if($post["attachments"]) {
                foreach ($post["attachments"] as $block) {
                    if ($block["type"] == "audio_playlist") {
                        $arrayAudioPost[] = $block;
                    }
                }
            }
        }

        return [
            "posts" => $arrayAudioPost,
            "next_from" => $posts["response"]["next_from"],
            "idGroup" => $idGroup,
        ];
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    private function request($request)
    {
        $request["v"] = "5.131";
        $url = $this->url . "/" . $this->type . "/" . $this->method;
        $curl = \curl_init($url);
        \curl_setopt($curl, CURLOPT_POST, true);
        \curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        \curl_setopt($curl, CURLOPT_HEADER, 'Content-type: application/json');
        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = \curl_exec($curl);

        return \json_decode($result, true);
    }
}

<?php

class CtfWeb
{
    public $aimWEB = "ctf.xpr0adic.top";
    public $robotCode = "198358517";
    public $groupCode = "891259761";
    public $API = "39.104.77.175:666";

    public function getChallenges()
    {
        $domain = $this->aimWEB;
        $cookie = file_get_contents("./co.okie");
        $allList = $this->use_curl("http://$domain/api/v1/challenges", $cookie, 0, 0);
        return $allList;
    }

    public function saveChallenges()
    {
        $challengesList = $this->getChallenges();
        $json = json_decode($challengesList, true);
        $num = 0;
        foreach ($json["data"] as $v) {
            if ($v["solves"] == 0) {
                $f = fopen("./save.Challenge", "w+");
                fwrite($f, $v["id"] . "*" . $v["name"] . "*" . $v["category"] . PHP_EOL);
                $num += 1;
            }
            if ($num == 0) {
                fopen("./save.Challenge", "w+");
            }
        }
        $date = date("Y-m-d H-i-s", time());
        echo $date, "保存成功" . PHP_EOL;
    }

    public function readChallenges()
    {
        $domain = $this->aimWEB;
        $cookie = file_get_contents("./co.okie");
        $aimTxt = @file_get_contents("./save.Challenge");
        if (strlen($aimTxt) != 0) {
            $aimList = explode(PHP_EOL, $aimTxt);
            array_pop($aimList);
            foreach ($aimList as $value) {
                $split = explode("*", $value);
                $getName = $this->use_curl("http://$domain/api/v1/challenges/$split[0]/solves", $cookie, 0, 0);
                $json = json_decode($getName, true);
                if (!empty($json["data"])) {
                    $name = $json["data"][0]["name"];
                    $date = $json["data"][0]["date"];
                    $news = "恭喜" . $name . "拿下《" . $split[1] . "》一血！";
                    $this->Robot(urlencode($news));
                    echo $news;
                }
            }
        }
    }

    public function loginWEB()
    {
        $user = "210246020@qq.com";
        $passwd = "yx123456yx";
        $domain = $this->aimWEB;
        $result_web = $this->use_curl("http://$domain/login?next=%2Fchallenges%3F", 0, 0, 1);
        $nonceGet = strstr($result_web, "csrfNonce");
        $nonceGet = strstr($nonceGet, ",", true);
        $split = explode('"', $nonceGet);
        $Nonce = $split[1];
        preg_match("/session=(.*?)(?=;)/", $result_web, $getCookie);
        $login_web = $this->use_curl("http://$domain/login", $getCookie[1], "name=$user&password=$passwd&_submit=Submit&nonce=$Nonce", 1);
        preg_match("/session=(.*?)(?=;)/", $login_web, $aimCookie);
        fwrite(fopen("co.okie", "w+"), $aimCookie[1]);
        echo "登录成功，cookie已存储" . PHP_EOL;
    }

    public function use_curl($url, $cookie, $post, $header)
    {
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL, $url);//访问的url
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HEADER, 1);
        }
        if (!empty($post)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        if (!empty($cookie)) {
            curl_setopt($curl, CURLOPT_COOKIE, "session=$cookie");
        }
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的方式
        $cz_json = curl_exec($curl);//执行curl;
        curl_close($curl); // 关闭CURL会话
        return $cz_json;
    }

    public function TopFiveDay()
    {
        $domain = $this->aimWEB;
        $cookie = file_get_contents("./co.okie");
        $allList = $this->use_curl("http://$domain/scoreboard", $cookie, 0, 0);
        $strip = str_replace("\n", "", $allList);
        $strip = str_replace("	", "", $strip);
        preg_match_all("/<td>(.*?)<\/td>/", $strip, $preg_str);
        print_r("今日TOP5" . PHP_EOL);
        for ($i = 0; $i < 9; $i += 2) {
            preg_match("/>(.*?)</", $preg_str[1][$i], $preg);
            print_r($preg[1] . " " . $preg_str[1][$i + 1] . PHP_EOL);
        }
    }

    public function Robot($news)
    {
        $robot = $this->robotCode;
        $group = $this->groupCode;
        $API = $this->API;
        $url = "http://$API/MyQQHTTPAPI?function=Api_SendMsg&token=&c1=$robot&c2=2&c3=$group&c4=&c5=$news";
        $this->use_curl($url, 0, 0, 0);
    }
}

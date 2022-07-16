# CTFDROBOT
Ctfd平台一血播报机器人

基于MYQQ框架https://www.myqqx.net/ 开发的一血播报机器人

作者：1us

使用教程：https://www.bilibili.com/video/bv1Sv4y1g7Tu

分数排行TOP5调用
$a = new CtfWeb();
$a->TopFiveDay();

该方法存在BUG，最新版本将直接修改Ctfd源码实现播报推送。
需要利用账户的Token。请去设置界面生成account token

将一下内容添加至 CTFd/CTFd/api/v1/challenges.py文件的第676行，注意缩进！！！！！
引用的包，添加至文件开头！！！


import requests
from urllib import parse

                robot = str(10001) #机器人qq
                group = str(11111) #推送群
                domain = "192.168.137.106:8000" #Ctfd平台域名
                api = "" #HTTPAPI
                token = "" #HTTPAPI Token
                aim = requests.get(url="http://{}/api/v1/challenges/".format(domain) + str(challenge_id) + "/solves",
                                   headers={
                                       "Authorization": "Token 此处填写account token",
                                       "Content-Type": "application/json"}).content.decode()
                json_aim = json.loads(aim)
                aim_name = requests.get(
                    url="http://{}".format(domain) + "/api/v1/challenges/" + str(challenge_id),
                    headers={
                        "Authorization": "Token 此处填写account token",
                        "Content-Type": "application/json"}).content.decode()
                json_name = json.loads(aim_name)["data"]["name"]
                if len(json_aim["data"]) < 3:
                    if len(json_aim["data"]) == 0:
                        name = parse.quote("恭喜" + user.name + "拿下《" + json_name + "》一血！！！")
                        requests.get(
                            url="http://{}/MyQQHTTPAPI?function=Api_SendMsg&token={}&c1={}&c2=2&c3={}&c4=&c5={}".format(
                                api, token,
                                robot, group, name))
                    if len(json_aim["data"]) == 1:
                        name = parse.quote("恭喜" + user + "拿下《" + json_name + "》二血！！")
                        requests.get(
                            url="http://{}/MyQQHTTPAPI?function=Api_SendMsg&token={}&c1={}&c2=2&c3={}&c4=&c5={}".format(
                                api, token,
                                robot, group, name))
                    if len(json_aim["data"]) == 2:
                        name = parse.quote("恭喜" + user.name + "拿下《" + json_name + "》三血！")
                        requests.get(
                            url="http://{}/MyQQHTTPAPI?function=Api_SendMsg&token={}&c1={}&c2=2&c3={}&c4=&c5={}".format(
                                api, token,
                                robot, group, name))

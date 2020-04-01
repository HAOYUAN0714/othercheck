# Secretary by go

### Compile

```sh
make build
```

compile in docker

```sh
make docker-img
GOROOT=$GOROOT PWD=$PWD make centos6
```

### Quick Start

```sh
# synopsis
# secretary -interval -schedule [FILE]
$ ./secretary -interval 1 -schedule ~/my.schedule.init
```

### 格式

#### 設定檔
```
# 秒 分 時 日 月 週|次數|命令

# 範例 1: 每秒執行, 執行中命令可重疊
* * * * * *|-1|echo hello world $(date) >> /tmp/schedule.test

# 範例 2: 在每分鐘指定秒數執行, 不限次數
5,10,20,40 * * * * *|0|echo xxx >> /dev/null

# 範例 3: 每天 12 點執行,共5次
* * 12 * * *|5|echo Good afternoon >> /dev/null
```

次數設定規則

* >= 1 限定次數
* == 0 不限次數
* == -1 不限次數,但可重疊執行

### web api

- `POST` api/register

    * repeat [int]
    * command [string 'url']
    * datetime [string '* * * * * *']

- `DELETE` api/revoke

    * id [string]

- `GET` api/dump

### 解析規則演算法取自 [robfig/cron]

[robfig/cron]:https://github.com/robfig/cron


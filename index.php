<?php
session_start();
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$client = ClientBuilder::create()->build();
$search=$_POST['search'];
$_SESSION['search']=$search;
if($search!=""){
    $json = '{
  "query": {
    "bool": {
      "should": [
        { "match_phrase": { "email":  "'.$search.' " }},
        { "match_phrase": { "username": "'.$search.'"   }}
      ]
    }
  }
}';
    $params = [
        'body' => $json
    ];
    $results = $client->search($params);
}else{
    $results="";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>淡定出品</title>
    <style>
        body {
            text-align: center;
        }
        #result{
            text-align: center;
        }
        #result h1 {
            color: blue;
        }
        th{
            color: rgba(255, 0, 0, 0.678);
        }
    </style>
</head>
<body>
<form action="" method="post">
    <h1>在线裤子查询</h1>
    <h3 style="color:rgba(247, 0, 255, 0.966);">站长(QQ223***1414)说明:本站搜索依赖于Elasticsearch，web客户端基于Elasticsearch-PHP,本网站仅用于丢失密码找回，请勿用于非法用途，所有数据来源于网络</h3>
    <input type="text" name="search" placeholder="请输入qq或邮箱或用户名" value="<?php echo $_SESSION['search'] ?>">
    <input type="submit" value="Search"  >
</form>
<hr>
<div id="result">
    <h1>查询结果<h3>(密码如果是加密的,请点<a href="http://www.cmd5.com/" target="_blank">这里</a>进行解密)</h3></h1>
    <table  cellspacing="0px" align="center"   width="80%" height="166" border="1" style="table-layout: fixed">
        <tr>
            <th>邮箱/QQ</th>
            <th >用户名</th>
            <th>密码</th>
            <th>ip</th>
            <th>来源</th>
        </tr>
        <?php
            if($results!=""){
//                print_r ("<pre>");
//                print_r($results);
                echo "当前本站一共有1484.8102万条数据，本次搜索花费了 ".$results["took"]/1000 ."秒";
                foreach ($results['hits']['hits'] as $res){        ?>
                    <tr>
                            <td width="50%" style="word-wrap: break-word;"><?php echo $res['_source']['email'] ?></td>
                            <td width="50%" style="word-wrap: break-word;"><?php echo $res['_source']['username'] ?></td>
                            <td width="50%" style="word-wrap: break-word;"><?php echo $res['_source']['password'] ?></td>
                            <td width="50%" style="word-wrap: break-word;"><?php echo $res['_source']['ip'] ?></td>
                            <td width="50%" style="word-wrap: break-word;"><?php echo $res['_source']['other'] ?></td>
                    </tr>
        <?php
                }
            }
        ?>
    </table>
</div>
</body>
</html>

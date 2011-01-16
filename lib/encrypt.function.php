<?php
/*
@名称:PHP字符串加密
@作者:风吟
@演示:无
@网站:http://demos.fengyin.name/
@博客:http://fengyin.name/
@更新:2009年9月22日 20:29:24
@版权:Copyright (c) 风吟版权所有，本程序为开源程序(开放源代码)。
只要你遵守 MIT licence 协议.您就可以自由地传播和修改源码以及创作衍生作品.
*/
/*
算法: http://bbs.phpchina.com/thread-37376-1-1.html
*/
function _encrypt($txt,$key){
   $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
   $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
   $nh1 = 20;//rand(0,64);
   $nh2 = 30;//rand(0,64);
   $nh3 = 40;//rand(0,64);
   $ch1 = $chars{$nh1};
   $ch2 = $chars{$nh2};
   $ch3 = $chars{$nh3};
   $nhnum = $nh1 + $nh2 + $nh3;
   $knum = 0;$i = 0;
   while(isset($key{$i})) $knum +=ord($key{$i++});
   $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
   $txt = base64_encode($txt);
   $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
   $tmp = '';
   $j=0;$k = 0;
   $tlen = strlen($txt);
   $klen = strlen($mdKey);
   for ($i=0; $i<$tlen; $i++) {
    $k = $k == $klen ? 0 : $k;
    $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
    $tmp .= $chars{$j};
   }

        $tmplen = strlen($tmp);
   $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
   $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
   $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
   return $tmp;

    }
function _decrypt($txt,$key)
   {
   $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
   $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
   $knum = 0;$i = 0;
   $tlen = strlen($txt);
   while(isset($key{$i})) $knum +=ord($key{$i++});
   $ch1 = $txt{$knum % $tlen};
   $nh1 = strpos($chars,$ch1);
   $txt = substr_replace($txt,'',$knum % $tlen--,1);
   $ch2 = $txt{$nh1 % $tlen};
   $nh2 = strpos($chars,$ch2);
   $txt = substr_replace($txt,'',$nh1 % $tlen--,1);
   $ch3 = $txt{$nh2 % $tlen};
   $nh3 = strpos($chars,$ch3);
   $txt = substr_replace($txt,'',$nh2 % $tlen--,1);
   $nhnum = $nh1 + $nh2 + $nh3;
   $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
   $tmp = '';
   $j=0; $k = 0;
   $tlen = strlen($txt);
   $klen = strlen($mdKey);
   for ($i=0; $i<$tlen; $i++) {
    $k = $k == $klen ? 0 : $k;
    $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
    while ($j<0) $j+=64;
    $tmp .= $chars{$j};
   }
   $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
   return trim(base64_decode($tmp));

    }
function encode_url($url){
	return USE_ENCRYPT?_encrypt($url,'j&j'):$url;
}
function decode_url($url){
	return USE_ENCRYPT?_decrypt($url,'j&j'):$url;
}

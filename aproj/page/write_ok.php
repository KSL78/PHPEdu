<?php

include $_SERVER['DOCUMENT_ROOT']."/aproj/db.php";
$date = date('Y-m-d');
$idx1 = $_GET['idx'];
$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
if(isset($_POST['lockpost'])){
	$lo_post = '1';
}else{
	$lo_post = '0';
}
$xname = $_POST['name'];

$path = "../upload/$xname";
$cntv = count($_FILES['upload_file']['name']);

$che = 0;
$out = 0;
$danamel = array();
for ( $i = 0; $i < $cntv; $i++) {
        $tmp_name = $_FILES['upload_file']['tmp_name'][$i];
        $ret =getimagesize($tmp_name, $extinfo);
        $ssi = json_encode($ret);
        if($ssi == 'false')
        {
            
            $out++;
        }
            

        else
        {
            $che++;
        }
            
        
        
}
if($che>0 && $out == 0) {
    if(!file_exists($path)){
        mkdir($path, 0777, true);
        chmod($path, 0777);
    }
    for ( $v = 0; $v < $cntv; $v++) {
        $ux_name = $_FILES['upload_file']['name'][$v];
        $tmp_name = $_FILES['upload_file']['tmp_name'][$v];
        $ret =getimagesize($tmp_name, $extinfo);
        $ssi = json_encode($ret[2]);
        if($ssi == 2)
        { 
        // 원하는 작업을 했다.
            $danamel[$v] = $ux_name;
            $up = move_uploaded_file($tmp_name, "$path/$ux_name");
            rename("$path/$ux_name","$path/$v.jpg");
            
        }
    }
}
//header("location:/aproj/page/write.php");
$danames = json_encode($danamel);
if($che>0 && $out == 0)
{
    $sql= mq("insert into board(name,pw,title,content,date,lock_post,file,pcnt) values('".$_POST['name']."','".$userpw."','".$_POST['title']."','".$_POST['content']."','".$date."','".$lo_post."','".$danames."','".$cntv."')");
    echo "<script>alert('jpg사진이 업로드 되었습니다.');</script>"; ?>
    <meta http-equiv="refresh" content="0 url=/aproj/index.php" />
    <?php
}
elseif ($out > 0)
{
    echo "<script>alert('사진이 아닙니다.');</script>";
    ?>
    <meta http-equiv="refresh" content="0 url=/aproj/page/write.php" />
    <?php

}
?>

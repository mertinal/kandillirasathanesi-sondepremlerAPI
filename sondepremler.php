<?php
/*
Bu dosya Mert İnal tarafından yazılmıştır...
*/
header('Content-Type: application/json');
include('simple_html_dom.php');
$newArray = array(
    "result" => array(
        array()
    ),
);
$html = file_get_contents('http://www.koeri.boun.edu.tr/scripts/lst4.asp');    
$html = str_get_html($html);
$data=$html->find('pre');
$parcali=explode("--------------                                  --------------  ",$data[0]);
$ilk=0;
function getArray($ilksatir){
    $format = "Y.m.d H:i:s";
    $tarih=explode("  ",$ilksatir);
    $dateobj = DateTime::createFromFormat($format, $tarih[0]);
    $iso_datetime = $dateobj->format(Datetime::ATOM);
    $time=strtotime($iso_datetime);
    $hambuyukluk=$hamderinlik=substr($ilksatir, 60,3);
    $islenmisbuyukluk=str_replace(' ', '', $hambuyukluk);
    $kordinatlar=substr($ilksatir,21,17);
    $kordinatlar=explode("   ",$kordinatlar);
    $hamderinlik=substr($ilksatir, 41,10);
    $islenmisderinlik = str_replace(' ', '', $hamderinlik);
    $hamadres=$hamderinlik=substr($ilksatir, 71,49);
    $hamadres = rtrim($hamadres, " ");
    $all=array(
        "magnitude"=>$islenmisbuyukluk,
        "latitude"=>$kordinatlar[0],
        "longitude"=>$kordinatlar[1],
        "location"=>$hamadres,
        "depth"=>$islenmisderinlik,
        "timestamp"=>$time,
        "time"=>$tarih[0],
    );
    return $all;
}

for($sayac=0;$sayac<500;$sayac++)
{
    $ilksatir=substr($parcali[1], $ilk,130);
    if (strpos($ilksatir, 'İlksel') !== false) {
        $newArray["result"][$sayac]=getArray($ilksatir);
        $ilk=$ilk+130;
    }
    else{
        $ilksatir=substr($parcali[1], $ilk,155);
        $newArray["result"][$sayac]=getArray($ilksatir);
        $ilk=$ilk+155;
    }
}

echo json_encode($newArray);

?>
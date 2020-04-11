<?php
  // JSON
  header('Content-Type: application/json');

  // mengijinkan semua host/domain/ip untuk menggunakan data JSON ini bila menggunakan AJAX
  // atau rubah tanda * menjadi domain yg di tentukan
  header('Access-Control-Allow-Origin: *');

  $url = 'https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/Statistik_Perkembangan_COVID19_Indonesia/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json';
  $content = file_get_contents($url);
  $array = json_decode($content, true);
  $array = $array['features'];

  foreach ( $array as $k=>$v ) {
    if (!$array[$k]['attributes']['Jumlah_Kasus_Kumulatif']) {
      unset($array[$k]);
    }
  }
  $array = array_shift(end($array));
  $array =  array_slice($array, 0, 2, true) +
          array("Negara" => "Indonesia") +
          array_slice($array, 2, count($array) - 1, true);
  $array['Tanggal']=date('j F Y',$array['Tanggal']/1000);

  foreach ( $array as $k=>$v ) {
    if (!in_array($k, array('Tanggal','Negara','Jumlah_Kasus_Baru_per_Hari','Jumlah_Kasus_Kumulatif','Jumlah_pasien_dalam_perawatan','Jumlah_Pasien_Sembuh','Jumlah_Pasien_Meninggal'))) {
      unset($array[$k]);
    }
  }
  /*
  unset($array['Hari_ke']);
  unset($array['Persentase_Pasien_dalam_Perawatan']);
  unset($array['Persentase_Pasien_Sembuh']);
  unset($array['Persentase_Pasien_Meninggal']);
  unset($array['Jumlah_Kasus_Sembuh_per_Hari']);
  unset($array['Jumlah_Kasus_Meninggal_per_Hari']);
  unset($array['Jumlah_Kasus_Dirawat_per_Hari']);
  unset($array['FID']);
  */

  if (!$array) {
    echo json_encode (array("404"=>"Data not found"), JSON_PRETTY_PRINT);
  }
  else {
    echo json_encode ($array, JSON_PRETTY_PRINT);
  }
  
  //print_r ($array);
?>

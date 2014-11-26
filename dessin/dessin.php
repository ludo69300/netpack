<?php
$cote=100;
$racie2sur2 = $cote * sqrt ( 2 )/2;;
$nbLong=4;
$nbLarg=3;
$nbHaut=2;
$largImage=20+$cote*$nbLarg+$racie2sur2*$nbLong;
$hautImage=20+$nbHaut*$cote+$racie2sur2*$nbLong;

header ("Content-type: image/png");
$image = imagecreate($largImage,$hautImage);
$blanc = imagecolorallocate($image, 255, 255, 255);
$orange = imagecolorallocate($image, 255, 128, 0);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$noir = imagecolorallocate($image, 0, 0, 0);

for ($i = 0; $i < $nbLarg; $i++) {
  for ($j = 0; $j < $nbHaut; $j++) {
    for ($k = 0; $k < $nbLong; $k++) {
      $x=10+$cote*$i+$racie2sur2*$k;
      $y=10+$cote*$j+$racie2sur2*$k;
      $x2=$x+$racie2sur2;
      $y2=$y+$racie2sur2;


//       imagedashedline($image, $x, $y, $x+$cote, $y, $noir);
//       imagedashedline($image, $x, $y, $x, $y+$cote, $noir);
//       imagedashedline($image, $x+$cote, $y, $x+$cote, $y+$cote, $noir);
//       imagedashedline($image, $x, $y+$cote, $x+$cote, $y+$cote, $noir);
      ImageRectangle ($image, $x, $y, $x+$cote, $y+$cote, $noir);
      ImageRectangle ($image,  $x2, $y2, $x2+$cote, $y2+$cote, $noir);
      ImageLine ($image, $x, $y, $x2, $y2, $noir);
      ImageLine ($image, $x+$cote, $y+$cote, $x2+$cote, $y2+$cote, $noir);
      ImageLine ($image, $x+$cote, $y, $x2+$cote, $y2, $noir);
      ImageLine ($image, $x, $y+$cote, $x2, $y2+$cote, $noir);
//       ImageLine ($image, $x+$racie2sur2*($k+1), $y+$racie2sur2*($k+1), $x+$racie2sur2*($k+1)+$cote, $y+$racie2sur2*($k+1), $noir);
    }
  }
}
imagepng($image);
?>

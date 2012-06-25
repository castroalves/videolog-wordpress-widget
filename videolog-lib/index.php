<!DOCTYPE html>
<html lang="en" xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <!-- /FACEBOOK META TAGS -->

    <meta charset="utf-8">
</head>
<body>
<?php

#Inclui todas as classes
require_once 'autoload.inc.php';

#Cria um novo objeto videolog passando o seu token
$videos = new vlog("c36acd2d-792c-4462-b984-782fe008c70b");

#Pega todos os resultados do canal "Jornalismo"
$resposta = $videos->getUserVideos(887357);
$video = $resposta->usuario->videos;
$totalVideos = count($video);

#Imprime o resultado na tela
//var_dump($resposta->usuario->videos);exit;

?>
<?php for($i = 0; $i < $totalVideos; $i++): ?>
<h4><?php echo $video[$i]->titulo; ?></h4>
<iframe width="264" height="172" src="http://embed.videolog.tv/v/index.php?id_video=<?php echo $video[$i]->id; ?>&amp;width=264&amp;height=172&amp;related=&amp;hd=&amp;color1=ffffff&amp;color2=ffffff&amp;color3=333333&amp;slideshow=true&amp;config_url=" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
<br>
<?php endfor; ?>
</body>
</html>
<?php 
require_once "Post.php" ;
require_once "Version.php" ;
require_once "migration/SchemaBdd.php";

$bdd = new SchemaBdd();

$bdd->migrate();
$post = new Post();

//$post->load(1);
//$post->setContent('yol');
//$post->save();
//$posts = Post::find("content like '%sha%'");
//var_dump($posts);


?>


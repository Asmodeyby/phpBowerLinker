# phpBowerLinker
Simple PHP class for generating css and js links from bower components. Just check simple demo from sample. Use any webserver and check /sample/index.php in your browser

#How to use class?
```PHP
$bowerClass = new Helper_Bower("bower.json", "bower_components/");
$bowerClass->getAssets();
$bowerClass->prepareAssets();
$assets = $bowerClass->processAssets();
$assets = join("\r", $assets);
```
That's all!

# PHP SDK FOR ALGORITHMIA API
This is a php sdk for the algorithmia.com API.
It only supports curl at this moment and give response back as an array.

## Setup:
Add a ```composer.json``` file to your project:
```
{
  "require": {
      "wimkumpen/algorithmia-php-sdk": "dev-master"
  }
}
```

Then provided you have [composer](http://getcomposer.org/) installed, you can run the following command:
```
$ composer.phar install
```

Or install by:
```
composer.phar require wimkumpen/algorithmia-php-sdk:dev-master
```

That will fetch the library and its dependencies inside your vendor folder. Then you can add the following to your .php files in order to use the library
```
require_once __DIR__.'/vendor/autoload.php';
```

Then you need to ```use``` the relevant classes, for example:
```
use Algorithmia\Algo;     // for calls to algorithmes
use Algorithmia\Connector // for the data connections
```

## Basic usage Algo:

http://docs.algorithmia.com/?shell#api-specification

```
$app = new \Algorithmia\Algo(array(
    'default_access_token' => 'YOUR_API_KEY',
    'version' => 'v1',
    false
));
```

Make your alogirthm calls, this will return Algorithmia\Http\DataResponse object

```
try {
    // basic algorithm 1
    $algorithmia = $app->algo("demo/Hello", ['yourname']);
    $response = $algorithmia->call();
 
    // basic algorithm 2
    $algorithmia = $app->algo("WebPredict/ListAnagrams/0.1", ["transformer", "terraforms", "retransform"]);
    $response = $algorithmia->call();
 
    // call algorithm with file
    $curlFile = new \CurlFile("/path/to/file/test.jpg");
    
    $algorithmia = $app->algo("opencv/SmartThumbnail/0.1", $curlFile, ["Content-Type" => "application/octet-stream"]);
    $response = $algorithmia->call();
    
} catch(\Algorithmia\Exceptions\ResponseException $e) {
    var_dump($e->getMessage());die;
} Catch(\Algorithmia\Exceptions\AuthorizationException $e) {
    var_dump($e->getMessage());die;
}
```

## Basic usage Connector:

http://docs.algorithmia.com/?shell#data-api-specification

```
$app = new \Algorithmia\Connector(array(
    'default_access_token' => 'YOUR_API_KEY',
    'version' => 'v1',
    false
));
```
Api folder request, returning a \Algorithmia\Http\DirectoryResponse instance
```
try {
    $response = $app->getDir("data" ,".my", "", false);
    $response = $app->createDir("data" ,".my", ['name' => 'elvis', 'acl' => ['read' => []]]);
    $response = $app->updateDir("data" ,".my/elvis", ['acl' => ['read' => []]]);
    $response = $app->deleteDir("data" ,".my/elvis", true);
} catch(\Algorithmia\Exceptions\ResponseException $e) {
    var_dump($e->getMessage());die;
} Catch(\Algorithmia\Exceptions\AuthorizationException $e) {
    var_dump($e->getMessage());die;
}
```
Api file request, returning a \Algorithmia\Http\FileResponse instance 
```
try {
    $response = $app->uploadFile("data" ,".my/elvis/testfile.txt", array("file content"));
    $response = $app->deleteFile("data" ,".my/elvis/testfile.txt");
    $response = $app->fileExist("data" ,".my/elvis/testfile.txt");
    $response = $app->getFile("data" ,".my/elvis/testfile.txt");
    
    $file = new CURLFile("/path/to/file/newfile.txt");
    $response = $app->uploadFile("data" ,".my/elvis/newfile.txt", [$file], ["Content-Type" => "application/x-www-form-urlencoded"]);
} catch(\Algorithmia\Exceptions\ResponseException $e) {
    var_dump($e->getMessage());die;
} Catch(\Algorithmia\Exceptions\AuthorizationException $e) {
    var_dump($e->getMessage());die;
}
```

## Todo:
- [ ] Clean some code out;
- [ ] Create examples
- [ ] Exceptions;
- [ ] Automated tested;
- [ ] Implement other Http Clients;
- [ ] Define requirements;
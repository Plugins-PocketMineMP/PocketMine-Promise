# PocketMine-Promise

A simple plugin for PocketMine-MP that implements Promise in PHP

## API Documentation

```php
class Main extends \pocketmine\plugin\PluginBase{
    public function onEnable() : void{
        $promise = new \alvin0319\Promise\Promise();
        $this->getServer()->getAsyncPool()->submitTask(new class($promise) extends \pocketmine\scheduler\AsyncTask{
            
            public function __construct(\alvin0319\Promise\Promise $promise){
                $this->storeLocal($promise);
            }

            public function onRun() : void{
                $res = ""; // do something
                $this->setResult($res);
            }

            public function onCompletion(\pocketmine\Server $server){
                /** @var \alvin0319\Promise\Promise $promise */
                $promise = $this->fetchLocal();
                if(is_string($this->getResult())){
                    $promise->resolve($this->getResult());                
                }else{
                    $promise->reject("Something did went wrong");
                }
            }
        });
        $promise->then(function($value){
            var_dump($value);        
        })->catch(function($err){
            var_dump("ERROR: $err");        
        });
    }
}
```
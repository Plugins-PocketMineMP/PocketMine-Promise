<?php
declare(strict_types=1);
namespace alvin0319\Promise;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class PromiseLoader extends PluginBase{

	public function onEnable() : void{
		if($this->getDescription()->getVersion() === "0.0.1"){

			$promise = new Promise();
			$this->getServer()->getAsyncPool()->submitTask(new class($promise) extends AsyncTask{

				public function __construct(Promise $promise){
					$this->storeLocal($promise);
				}

				public function onRun() : void{
					$res = Internet::getURL("http://pmmp.me/api/online-servers");
					$this->setResult($res);
				}

				public function onCompletion(Server $server) : void{
					/** @var Promise $promise */
					$promise = $this->fetchLocal();
					$res = $this->getResult();
					if(is_string($res)){
						$promise->resolve($res);
					}else{
						$promise->reject("ACCESS DENIED");
					}
				}
			});
			$promise->then(function(string $res){
				var_dump($res);
			})->catch(function($err){
				var_dump("ERROR: " . $err);
			});
		}
	}
}
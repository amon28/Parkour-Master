<?php

namespace Arena;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use Arena\Main;

class PkrTask extends Task {

    public $plugin;
    public $seconds = 60;
	public $minutes = 5;
	public $world;
	public $x=0;
	
	public function __construct(Main $plugin, string $world = "prk1",int $minutes){
	$this->plugin = $plugin;
	$this->world = $world;
	$this->minutes = $minutes;
	}
	
	public function getPlugin() {
          return $this->plugin;
      }

      public function onRun(int $tick) : void {
		foreach($this->getPlugin()->getServer()->getLevelByName($this->world)->getPlayers() as $pn){
		$pn->sendPopup($this->minutes.":".$this->seconds);
		
		if($this->x == 0){
		$this->getPlugin()->sendID2($this->getTaskId());
		$this->x++;	
		}
		
		if($this->minutes == 0 and $this->seconds == 0){
		$pn->sendMessage(C::YELLOW.C::UNDERLINE."Times over no one won the game...");
		$pn->teleport($this->getPlugin()->getServer()->getLevelByName("spawn")->getSpawnLocation());
		$this->getPlugin()->removeTask2($this->getTaskId());
		
		}
		if($this->seconds <= 0){
		$this->minutes--;
		$this->seconds = 60;
		}
		}		            
          // takes 1 to $this->seconds
          $this->seconds -= 1;
		  
      }
	  
	  public function stop(){
		$this->getPlugin()->removeTask2($this->getTaskId());
	  }
}
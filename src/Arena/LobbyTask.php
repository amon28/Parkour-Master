<?php

namespace Arena;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use pocketmine\block\Gold;
use pocketmine\event\PlayerMoveEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\block\Block;	
use Arena\Main;

class LobbyTask extends Task implements Listener {

    public $plugin;
    public $seconds=0;
	public $players = array();
	public $x=0;
	public $start = 0;	
	public $world;
	
	public function __construct(Main $plugin, string $world = "prk1"){
	$this->plugin = $plugin;
	$this->world = $world;
	}
	
	public function getPlugin() {
          return $this->plugin;
      }
	  	 
      public function onRun(int $tick) {
		$start = count($this->getPlugin()->getServer()->getLevelByName($this->world)->getPlayers());
		if($start == 0 and $this->x == 2 )
		{
		$this->getPlugin()->removeTask($this->getTaskId());	
		}
		  
        foreach($this->getPlugin()->getServer()->getLevelByName($this->world)->getPlayers() as $pn){
		if(!($pn->hasPermission("pkr.pass"))){
		if($this->x == 0)
		{
		$this->placebarrier();	
		}
		if($start <= 5)
		{		
		$pn->sendTip(C::GREEN.C::UNDERLINE.$start."/5");
		}
		 if($this->x == 0) {	 		 
          if($start >= 5) {	
			$this->x=1;
              $this->getPlugin()->getLogger()->info("Starting a parkour game!");         			  
			  //$this->getPlugin()->removeTask($this->getTaskId()); Stops the task
          }
		 }
		 
		if($this->x == 1)
		{
		if($this->seconds == 1){
		$pn->addTitle(C::RED.C::UNDERLINE."Game Start!");
		}
		if($this->seconds == 2){
		$pn->addTitle(C::RED.C::UNDERLINE."3");	
		}
		if($this->seconds == 3){
		$pn->addTitle(C::GOLD.C::UNDERLINE."2");	
		}
		if($this->seconds == 4){
		$pn->addTitle(C::GREEN.C::UNDERLINE."1");
		$this->removebarrier();
		$this->getPlugin()->sendID($this->getTaskId());
		$this->getPlugin()->pkrTask();
		$this->x = 2;		
		}
		$this->seconds += 1;
		}
		
		if($this->x == 2)
		{
	
    $block = $pn->getLevel()->getBlock($pn->subtract(0, 1, 0));
	if($block->getId() === Block::GRASS){
	$pn->teleport($this->getPlugin()->getServer()->getLevelByName($this->world)->getSpawnLocation());	
	}
    if($block->getId() === Block::GOLD_BLOCK) {
	array_push($this->players, $pn->getName());	
	$pn->sendMessage(C::YELLOW.C::UNDERLINE. implode($this->players) .C::RESET.C::UNDERLINE." Has Won the Parkour!");	
	$pn->teleport($this->getPlugin()->getServer()->getLevelByName("spawn")->getSpawnLocation());
	$this->getPlugin()->removeTask($this->getTaskId());//stops	
	}			
		}
		}
		}//foreach end			  
		 }	

		public function stop(){
		$this->getPlugin()->removeTask($this->getTaskId());
	  }


public function placebarrier(){
	$bl1 = Block::get(Block::GLASS);
	$place = $this->getPlugin()->getServer()->getLevelByName($this->world);
		
	$pos = new Vector3(289,28,290);
	$place->setBlock($pos, $bl1, false, false);
	$pos = new Vector3(289,28,289);
	$place->setBlock($pos, $bl1, false, false);
	$pos = new Vector3(289,28,288);
	$place->setBlock($pos, $bl1, false, false);
	$pos = new Vector3(289,28,287);
	$place->setBlock($pos, $bl1, false, false);
	$pos = new Vector3(289,28,286);
	$place->setBlock($pos, $bl1, false, false);	
}

public function removebarrier(){
	$bl2 = BLock::get(Block::AIR);
	$place = $this->getPlugin()->getServer()->getLevelByName($this->world);
	
	$pos = new Vector3(289,28,290);
	$place->setBlock($pos, $bl2, false, false);
	$pos = new Vector3(289,28,289);
	$place->setBlock($pos, $bl2, false, false);
	$pos = new Vector3(289,28,288);
	$place->setBlock($pos, $bl2, false, false);
	$pos = new Vector3(289,28,287);
	$place->setBlock($pos, $bl2, false, false);
	$pos = new Vector3(289,28,286);
	$place->setBlock($pos, $bl2, false, false);

}	
      }	  
<?php
namespace Arena;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\block\Gold;
use pocetmine\scheduler\TaskScheduler;
use pocketmine\scheduler\TaskHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\level\particle\FloatingTextParticle;

class Main extends PluginBase implements Listener {		

	public $tasks = [];
	public $tasks2 = [];
	public $id2;
	public $id1;
	public $world = "prk1";
	public $minutes = 5;
	
    public function onEnable(){
        $this->getLogger()->info("Online");
		
		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		if(!file_exists($this->getDataFolder() . "config.yml")){
			$this->saveDefaultConfig();
		}
		$this->world = $this->getConfig()->get("World", "prk1");
		$this->minutes = $this->getConfig()->get("Minutes", 5);
		$this->getLogger()->info("Parkour World: ".$this->world);
		$this->lobbytask();						
    }

		
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
	if(($command->getName()) == "pkr"){
		if(!isset($args[0])){
	$sender->sendMessage("/pkr [tp]");
	return true;
	}
		switch($args[0])
		{					
		//Final output
		case "tp":
		$players = array();
		
		foreach($this->getServer()->getLevelByName($this->world)->getPlayers() as $pl){
		array_push($players, $pl->getName()); 
		}
		if(count($players) >= 5)//checks if world is full
		{
		$sender->sendMessage(C::RED.C::UNDERLINE."The game is full!");
			return true;
		}
		$sender->teleport($this->getServer()->getLevelByName($this->world)->getSpawnLocation());				
		break;				
	}	
	}
	return true;
	}
	
	public function onPlace(BlockPlaceEvent $ev){
	$block = $this->getServer()->getLevelByName($this->world)->$ev->getBlock();	
	if($block->getId() === Block::GOLD_BLOCK){
	$pos = $block->getLevel();	
	$ftext = new FloatingTextParticle(new Vector3($pos->add(0,1,0)),"END");
	$ev->getPlayer()->sendMessage("Succesfully place an end block");
	$this->getServer()->getLevelByName($this->world)->addParticle($ftext);
	}
	}
	
	public function onQuit(PlayerQuitEvent $ev){
	$gp = $ev->getPlayer();
	if(($gp->getLevelByName()) == $this->world){
	$gp->teleport($this->getServer()->getLevelByName("spawn")->getSpawnLocation());
	}
	}
	
	public function sendID2($id){
	$this->id2 = $id;										
	}
	
	public function sendID($id){
	$this->id1 = $id;	
	}
	
	public function pkrTask(){
	$task2 = new PkrTask($this,$this->world,$this->minutes);	
    $h2 = $this->getScheduler()->scheduleRepeatingTask($task2,20);
	$task2->setHandler($h2);
    $this->tasks2[$task2->getTaskId()] = $task2->getTaskId();	
	}
	
	//start main task
	public function lobbytask(){
	$task = new LobbyTask($this,$this->world);	
    $h = $this->getScheduler()->scheduleRepeatingTask($task,20);
	$task->setHandler($h);
    $this->tasks[$task->getTaskId()] = $task->getTaskId();				
	}
	
	public function removeTask($id) {

    // Removes the task from your array of tasks
    unset($this->tasks[$id]);
	unset($this->tasks2[$this->id2]);
    // Cancels the task and stops it from running
    $this->getScheduler()->cancelTask($id);
	$this->getScheduler()->cancelTask($this->id2);
	$this->lobbytask();
}	

	public function removeTask2($id) {
	unset($this->tasks2[$id]);
	unset($this->tasks[$this->id1]);
    // Cancels the task and stops it from running
    $this->getScheduler()->cancelTask($id);
	$this->getScheduler()->cancelTask($this->id1);
	}
	 	           
  
    public function onDisable(){
     $this->getLogger()->info("§cOffline");
    }
}

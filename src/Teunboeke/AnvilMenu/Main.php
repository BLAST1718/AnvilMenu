
<?php

namespace Teunboeke\AnvilMenu;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\Armor;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as T;

use jojoe77777\FormAPI\{SimpleForm, CustomForm};
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener {
   
    public function onEnable(){
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);
  }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
    	if($sender instanceof Player){
        switch($command->getName()){
            case "anvil":
                $this->rruiform($sender);
        }
        return true;
    }
    return false;
 }
public function rruiform(Player $sender){
    $form = new SimpleForm(function(Player $sender, ?int $data){
             if(!isset($data)) return;
			switch($data){
		
                        case 0:
                            $this->menu($sender);
                            break;
                        case 1:
                            $this->renamemenu($sender);
                            break;
                        case 2:
                            $this->setLore($sender);
                            break;
                        case 3:
                            break;
      }
    });
    $form->setTitle(T::BOLD . T::GREEN . "AnvilMenu");
    $form->addButton(T::YELLOW . "•REPAIR•");
    $form->addButton(T::AQUA . "•RENAME•");
    $form->addButton(T::GOLD . "•Custom Lore•");
    $form->addButton(T::RED . "•EXIT•");
    $form->sendToPlayer($sender);
 }
public function menu(Player $sender){
    $form = new SimpleForm(function(Player $sender, ?int $data){
             if(!isset($data)) return;
			switch($data){
		
                        case 0:
                            $this->xp($sender);
                            break;
                        case 1:
                            $this->money($sender);
                            break;
                        case 2:
                            break;
      }
    });
    $form->setTitle(T::BOLD . T::GREEN . "•RRUI•");
    $form->addButton(T::YELLOW . "•USE EXP•");
    $form->addButton(T::AQUA . "•USE MONEY•");
    $form->addButton(T::RED . "•EXIT•");
    $form->sendToPlayer($sender);
 }
public function xp(Player $sender){
		  $f = new CustomForm(function(Player $sender, ?array $data){
		      if(!isset($data)) return;
          $xp = $this->getConfig()->get("xp-repair");
          $pxp = $sender->getXpLevel();
          $dg = $sender->getInventory()->getItemInHand()->getDamage();
          if($pxp >= $xp * $dg){
	      $sender->subtractXpLevels($xp * $dg);
	      $index = $sender->getPlayer()->getInventory()->getHeldItemIndex();
          $item = $sender->getInventory()->getItem($index);
	      $id = $item->getId();
				if($item instanceof Armor or $item instanceof Tool){
				        if($item->getDamage() > 0){
								$sender->getInventory()->setItem($index, $item->setDamage(0));
					        $sender->sendMessage("§aYour item have been repaired");
					return true;
							}else{
								$sender->sendMessage("§cItem doesn't have any damage.");
								return false;
							}
							return true;
							}else{
								$sender->sendMessage("§cThis item can't repaired");
								return false;
						}
						return true;
						}else{
									$sender->sendMessage("§cYou don't have enough xp!");
									return true;
					}
					});
	  $xp = $this->getConfig()->get("xp-repair");
          $dg = $sender->getInventory()->getItemInHand()->getDamage();
          $pc = $xp * $dg;
          $xps = $sender->getXpLevel();
		  $f->setTitle("Repair your item using xp");
		  $f->addLabel("§eYour XP: $xps \n§aXP perDamage: $xp\n§aItem damage: $dg \n§dTotal XP needed : $pc");
		  $f->sendToPlayer($sender);
		   }
public function money(Player $sender){
		  $f = new CustomForm(function(Player $sender, ?array $data){
		   if(!isset($data)) return;
		  $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $cash = $this->getConfig()->get("price-repair");
          $dg = $sender->getInventory()->getItemInHand()->getDamage();
          if($mymoney >= $cash * $dg){
	      $economy->reduceMoney($sender, $cash * $dg);
          $index = $sender->getPlayer()->getInventory()->getHeldItemIndex();
	  $item = $sender->getInventory()->getItem($index);
	  $id = $item->getId();
	   if($item instanceof Armor or $item instanceof Tool){
	     if($item->getDamage() > 0){
		 $sender->getInventory()->setItem($index, $item->setDamage(0));
                 $sender->sendMessage(T::GREEN . "Your item have been repaired");
		  return true;
		    }else{
		 $sender->sendMessage(T::RED . "Item doesn't have any damage.");
	       	return false;			
     }
		return true;
	           }else{
         	$sender->sendMessage(T::RED . "This item can't repaired");
		return false;
		}
		  return true;
			}else{
		$sender->sendMessage(T::RED . "You don't have enough money!");
		return true;
	 }
	   });
	  $mny = $this->getConfig()->get("price-repair");
          $dg = $sender->getInventory()->getItemInHand()->getDamage();
          $pc = $mny * $dg;
          $economy = EconomyAPI::getInstance();
          $mne = $economy->myMoney($sender);
          $f->setTitle(T::BOLD . T::GOLD . "RepairUI");
	  $f->addLabel("§eYour money: $mne \n§aPrice perDamage: $mny\n§aItem damage: $dg \n§dTotal money needed : $pc");
          $f->sendToPlayer($sender);
   }

public function renamemenu(Player $sender){
    $form = new SimpleForm(function(Player $sender, ?int $data){
             if(!isset($data)) return;
			switch($data){
		
                        case 0:
                            $this->renamexp($sender);
                            break;
                        case 1:
                            $this->renamemoney($sender);
                            break;
                        case 2:
                            break;
      }
    });
    $form->setTitle(T::BOLD . T::GREEN . "•RRUI•");
    $form->addButton(T::YELLOW . "•USE EXP•");
    $form->addButton(T::AQUA . "•USE MONEY•");
    $form->addButton(T::RED . "•EXIT•");
    $form->sendToPlayer($sender);
 }

public function renamemoney(Player $sender){
	    $f = new CustomForm(function(Player $sender, ?array $data){
             if(!isset($data)) return;
		 $item = $sender->getInventory()->getItemInHand();
		  if($item->getId() == 0) {
                    $sender->sendMessage(T::RED . "Hold item in hand!");
                    return;
                }
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $rename = $this->getConfig()->get("price-rename");
          if($mymoney >= $rename){
	      $economy->reduceMoney($sender, $rename);
                $item->setCustomName(T::colorize($data[1]));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(T::GREEN . "successfully changed item name to §r$data[1]");
                }else{
             $sender->sendMessage(T::RED . "You don't have enough money!");
             }
	    });
	   
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $rename = $this->getConfig()->get("price-rename");
	  $f->setTitle(T::BOLD . T::YELLOW . "•RenameUI•");
	  $f->addLabel("§aRename cost: §e$rename\n§bYour money: $mymoney");
          $f->addInput(T::RED . "Rename Item:", "HardCore");
	  $f->sendToPlayer($sender);
   }

public function renamexp(Player $sender){
	    $f = new CustomForm(function(Player $sender, ?array $data){
             if(!isset($data)) return;
                 $item = $sender->getInventory()->getItemInHand();
		  if($item->getId() == 0) {
                    $sender->sendMessage(T::RED . "Hold item in hand!");
                    return;
                }
          $xp = $this->getConfig()->get("xp-rename");
          $pxp = $sender->getXpLevel();
          if($pxp >= $xp){
	      $sender->subtractXpLevels($xp);
                $item->setCustomName(T::colorize($data[1]));
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(T::GREEN . "successfully changed item name to §r$data[1]");
                }else{
             $sender->sendMessage(T::RED . "You don't have enough EXP!");
             }
	    });
	   
          $xp = $this->getConfig()->get("xp-rename");
          $pxp = $sender->getXpLevel();
	  $f->setTitle(T::BOLD . T::YELLOW . "•RenameUI•");
	  $f->addLabel("§aRename cost: §e$xp\n§bYour EXP: $pxp");
          $f->addInput(T::RED . "Rename Item:", "HardCore");
	  $f->sendToPlayer($sender);
   }


public function setLore(Player $sender){
	    $f = new CustomForm(function(Player $sender, ?array $data){
             if(!isset($data)) return;
		 $item = $sender->getInventory()->getItemInHand();
		  if($item->getId() == 0) {
                    $sender->sendMessage(T::RED . "Hold item in hand!");
                    return;
                }
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $lore = $this->getConfig()->get("price-lore");
          if($mymoney >= $lore){
	      $economy->reduceMoney($sender, $lore);
                $item->setLore([$data[1]]);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(T::GREEN . "successfully changed item lore to §r$data[1]");
                }else{
             $sender->sendMessage(T::RED . "You don't have enough money!");
             }
	    });
	   
          $economy = EconomyAPI::getInstance();
          $mymoney = $economy->myMoney($sender);
          $lore = $this->getConfig()->get("price-lore");
	  $f->setTitle(T::BOLD . T::YELLOW . "•Custom Lore•");
	  $f->addLabel("§aSet lore cost: §e$lore\n§bYour money: $mymoney");
          $f->addInput(T::RED . "SetLore:", "HardCore");
	  $f->sendToPlayer($sender);
   }
}
<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

class CustomForm extends Form {

    private $labelMap = [];

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "custom_form";
        $this->data["title"] = "";
        $this->data["content"] = [];
    }

    public function processData(&$data) : void {
        if(is_array($data)) {
            $new = [];
            foreach ($data as $i => $v) {
                $new[$this->labelMap[$i]] = $v;
            }
            $data = $new;
        }
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void {
        $this->data["title"] = $title;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @param string $text
     * @param string|null $label
     */
    public function addLabel(string $text, ?string $label = null) : void {
        $this->addContent(["type" => "label", "text" => $text]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param string $text
     * @param bool|null $default
     * @param string|null $label
     */
    public function addToggle(string $text, bool $default = null, ?string $label = null) : void {
        $content = ["type" => "toggle", "text" => $text];
        if($default !== null) {
            $content["default"] = $default;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param string $text
     * @param int $min
     * @param int $max
     * @param int $step
     * @param int $default
     * @param string|null $label
     */
    public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1, ?string $label = null) : void {
        $content = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max];
        if($step !== -1) {
            $content["step"] = $step;
        }
        if($default !== -1) {
            $content["default"] = $default;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param string $text
     * @param array $steps
     * @param int $defaultIndex
     * @param string|null $label
     */
    public function addStepSlider(string $text, array $steps, int $defaultIndex = -1, ?string $label = null) : void {
        $content = ["type" => "step_slider", "text" => $text, "steps" => $steps];
        if($defaultIndex !== -1) {
            $content["default"] = $defaultIndex;
        }
        $this->addContent($content);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param string $text
     * @param array $options
     * @param int $default
     * @param string|null $label
     */
    public function addDropdown(string $text, array $options, int $default = null, ?string $label = null) : void {
        $this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param string $text
     * @param string $placeholder
     * @param string $default
     * @param string|null $label
     */
    public function addInput(string $text, string $placeholder = "", string $default = null, ?string $label = null) : void {
        $this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    /**
     * @param array $content
     */
    private function addContent(array $content) : void {
        $this->data["content"][] = $content;
    }

}
<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

abstract class Form implements IForm{

    /** @var array */
    protected $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @deprecated
     * @see Player::sendForm()
     *
     * @param Player $player
     */
    public function sendToPlayer(Player $player) : void {
        $player->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, $data) : void {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data) : void {
    }

    public function jsonSerialize(){
        return $this->data;
    }
}
<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use pocketmine\plugin\PluginBase;

class FormAPI extends PluginBase{

    /**
     * @deprecated
     *
     * @param callable|null $function
     * @return CustomForm
     */
    public function createCustomForm(?callable $function = null) : CustomForm {
        return new CustomForm($function);
    }

    /**
     * @deprecated
     *
     * @param callable|null $function
     * @return SimpleForm
     */
    public function createSimpleForm(?callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

    /**
     * @deprecated
     *
     * @param callable|null $function
     * @return ModalForm
     */
    public function createModalForm(?callable $function = null) : ModalForm {
        return new ModalForm($function);
    }
}
<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

class ModalForm extends Form {

    /** @var string */
    private $content = "";

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["button1"] = "";
        $this->data["button2"] = "";
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void {
        $this->data["title"] = $title;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return $this->data["content"];
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) : void {
        $this->data["content"] = $content;
    }

    /**
     * @param string $text
     */
    public function setButton1(string $text) : void {
        $this->data["button1"] = $text;
    }

    /**
     * @return string
     */
    public function getButton1() : string {
        return $this->data["button1"];
    }

    /**
     * @param string $text
     */
    public function setButton2(string $text) : void {
        $this->data["button2"] = $text;
    }

    /**
     * @return string
     */
    public function getButton2() : string {
        return $this->data["button2"];
    }
}

     
	     

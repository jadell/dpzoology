<?php
//////////////////////////////////////////////////////////////////////
// ZOO COMMAND CENTER ///////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

abstract class CommandHandler
{
	protected $nextHandler = null;
	public function setNext(CommandHandler $next)
	{
		$this->nextHandler = $next;
		return $this->nextHandler;
	}

	public function handle($command)
	{
		if ($this->handled($command)) {
			return true;
		} else if ($this->nextHandler) {
			return $this->nextHandler->handle($command);
		} else {
			return false;
		}
	}

	abstract public function handled($command);
}

class CommandDispatch
{
	protected $handler;
	public function setHandler(CommandHandler $handler)
	{
		$this->handler = $handler;
		return $this->handler;
	}

	public function execute($readCommand)
	{
		$readCommand = strtolower($readCommand);
		$command = explode(' ', $readCommand);
		array_unshift($command, $readCommand);

		if (!$this->handler->handle($command)) {
			echo "Invalid command\n";
		}
		echo "\n";
	}
}

class HelpHandler extends CommandHandler
{
	public function handled($command)
	{
		if ($command[0] != 'help') {
			return false;
		}

		echo <<<HELP
Available commands:
	list animals
	list diets
	list exercise
	list stock
	stock (plant|meat)
	feed <animalName> (plant|meat)
	exercise <animalName>
	talk <animalName>
	help
	exit

HELP;

		return true;
	}
}

class ExitHandler extends CommandHandler
{
	public function handled($command)
	{
		if ($command[0] == 'exit') {
			exit(0);
		}
	}
}

class ListAnimalsHandler extends CommandHandler
{
	protected $zoo;
	public function __construct(Zoo $zoo)
	{
		$this->zoo = $zoo;
	}

	public function handled($command)
	{
		if ($command[0] == 'list animals') {
			$this->zoo->listAnimals();
			return true;
		}
		return false;
	}
}

class FeedAnimalHandler extends CommandHandler
{
	protected $zoo;
	protected $stock;
	protected $events;
	public function __construct(Zoo $zoo, FoodStock $stock, EventHandler $events)
	{
		$this->zoo = $zoo;
		$this->stock = $stock;
		$this->events = $events;
	}

	public function handled($command)
	{
		if ($command[1] != 'feed') {
			return false;
		}

		$animal = $this->zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
			return true;
		}

		if ($command[3] == 'plant') {
			$food = $this->stock->getPlant();
		} else if ($command[3] == 'meat') {
			$food = $this->stock->getMeat();
		} else {
			echo "Unknown food: {$command[3]}\n";
			return true;
		}

		if (!$food) {
			echo "Food not available: {$command[3]}\n";
			return true;
		}

		if ($animal->eat($food)) {
			$this->events->dispatch('gaveFood', $animal, $food);
		}
		return true;
	}
}

class ExerciseAnimalHandler extends CommandHandler
{
	protected $zoo;
	protected $events;
	public function __construct(Zoo $zoo, EventHandler $events)
	{
		$this->zoo = $zoo;
		$this->events = $events;
	}

	public function handled($command)
	{
		if ($command[1] != 'exercise') {
			return false;
		}

		$animal = $this->zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
			return true;
		}

		$animal->move();
		$this->events->dispatch('gaveExercise', $animal);
		return true;
	}
}

class TalkAnimalHandler extends CommandHandler
{
	protected $zoo;
	public function __construct(Zoo $zoo)
	{
		$this->zoo = $zoo;
	}

	public function handled($command)
	{
		if ($command[1] != 'talk') {
			return false;
		}

		$animal = $this->zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
			return true;
		}

		$animal->speak();
		return true;
	}
}

class ListDietHandler extends CommandHandler
{
	protected $monitor;
	public function __construct(DietMonitor $monitor)
	{
		$this->monitor = $monitor;
	}

	public function handled($command)
	{
		if ($command[0] == 'list diets') {
			$this->monitor->listDiets();
			return true;
		}
		return false;
	}
}

class ListStockHandler extends CommandHandler
{
	protected $stock;
	public function __construct(FoodStock $stock)
	{
		$this->stock = $stock;
	}

	public function handled($command)
	{
		if ($command[0] == 'list stock') {
			$this->stock->listStock();
			return true;
		}
		return false;
	}
}

class ListExerciseHandler extends CommandHandler
{
	protected $monitor;
	public function __construct(ExerciseMonitor $monitor)
	{
		$this->monitor = $monitor;
	}

	public function handled($command)
	{
		if ($command[0] == 'list exercise') {
			$this->monitor->listExercise();
			return true;
		}
		return false;
	}
}

class StockFoodHandler extends CommandHandler
{
	protected $stock;
	public function __construct(FoodStock $stock)
	{
		$this->stock = $stock;
	}

	public function handled($command)
	{
		if ($command[1] != 'stock') {
			return false;
		} else if ($command[2] == 'plant') {
			$this->stock->stockPlant(new Plant());
		} else if ($command[2] == 'meat') {
			$this->stock->stockMeat(new Meat());
		}
		$this->stock->listStock();
		return true;
	}
}

//////////////////////////////////////////////////////////////////////
// INPUT ////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

class KeyboardInput
{
	protected $dispatch;
	public function __construct(CommandDispatch $dispatch)
	{
		$this->dispatch = $dispatch;
	}

	public function listen()
	{
		while (true) {
			$command = trim(readline('> '));
			if ($command) {
				$this->dispatch->execute($command);
			}
		}
	}
}

//////////////////////////////////////////////////////////////////////
// EVENTS ///////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

class EventHandler
{
	protected $listeners = array();

	public function listen($event, $listener)
	{
		if (!isset($this->listeners[$event])) {
			$this->listeners[$event] = array();
		}
		$this->listeners[$event][] = $listener;
	}

	public function dispatch($event)
	{
		if (!isset($this->listeners[$event])) {
			return;
		}

		$args = func_get_args();
		array_shift($args);
		foreach ($this->listeners[$event] as $listener) {
			call_user_func_array(array($listener, $event), $args);
		}
	}
}


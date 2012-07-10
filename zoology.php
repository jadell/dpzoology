<?php
require_once 'animals.php';
require_once 'facilities.php';

//////////////////////////////////////////////////////////////////////
// MAIN /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

$zoo = new Zoo();
$zoo->addAnimal(new Lion('Rex'));
$zoo->addAnimal(new Owl('Hooter'));
$zoo->addAnimal(new Gazelle('Bounder'));
$zoo->addAnimal(new Chimp('Bobo'));
$zoo->addAnimal(new Hipster('Jean'));

$foodStock = new FoodStock();

while (true) {
	$readCommand = strtolower(trim(readline('> ')));
	if (!$readCommand) {
		continue;
	}
	$command = explode(' ', $readCommand);
	array_unshift($command, $readCommand);
	$handled = false;

	// HELP COMMAND
	if ($command[1] == 'help') {
		$handled = true;
		echo <<<HELP
Available commands:
	list animals
	list stock
	stock (plant|meat)
	feed <animalName> (plant|meat)
	exercise <animalName>
	talk <animalName>
	help
	exit

HELP;

	// EXIT COMMAND
	} else if ($command[0] == 'exit') {
		exit(0);

	// list animals
	} else if ($command[0] == 'list animals') {
		$handled = true;
		$zoo->listAnimals();

	// list stock
	} else if ($command[0] == 'list stock') {
		$handled = true;
		$foodStock->listStock();

	// stock (plant|meat)
	} else if ($command[1] == 'stock' && $command[2] == 'plant') {
		$handled = true;
		$foodStock->stockPlant(new Plant());
		$foodStock->listStock();
	} else if ($command[1] == 'stock' && $command[2] == 'meat') {
		$handled = true;
		$foodStock->stockMeat(new Meat());
		$foodStock->listStock();

	// feed <animalName> (plant|meat)
	} else if ($command[1] == 'feed') {
		$handled = true;
		$animal = $zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
		} else if ($command[3] == 'plant') {
			$food = $foodStock->getPlant();
		} else if ($command[3] == 'meat') {
			$food = $foodStock->getMeat();
		} else {
			echo "Unknown food: {$command[3]}\n";
			$food = false;
		}

		if ($food === null) {
			echo "Food not available: {$command[3]}\n";
		}

		if ($food) {
			$animal->eat($food);
		}

	// exercise <animalName>
	} else if ($command[1] == 'exercise') {
		$handled = true;
		$animal = $zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
		} else {
			$animal->move();
		}

	// talk <animalName>
	} else if ($command[1] == 'talk') {
		$handled = true;
		$animal = $zoo->getAnimal($command[2]);
		if (!$animal) {
			echo "No such animal: {$command[2]}\n";
		} else {
			$animal->speak();
		}
	}

	// INVALID
	if (!$handled) {
		echo "Invalid command\n";
	}
	echo "\n";
}

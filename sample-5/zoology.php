<?php
require_once 'animals.php';
require_once 'facilities.php';
require_once 'commands.php';

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
$dietMonitor = new DietMonitor();
$exerciseMonitor = new ExerciseMonitor();

$commandDispatch = new CommandDispatch();
$commandDispatch->setHandler(new ExitHandler())
	->setNext(new HelpHandler())
	->setNext(new ListAnimalsHandler($zoo))
	->setNext(new StockFoodHandler($foodStock))
	->setNext(new FeedAnimalHandler($zoo, $foodStock))
	->setNext(new ExerciseAnimalHandler($zoo))
	->setNext(new TalkAnimalHandler($zoo))
	->setNext(new ListStockHandler($foodStock))
	->setNext(new ListExerciseHandler($exerciseMonitor))
	->setNext(new ListDietHandler($dietMonitor));
$input = new KeyboardInput($commandDispatch);
$input->listen();

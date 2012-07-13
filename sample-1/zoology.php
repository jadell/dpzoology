<?php
//////////////////////////////////////////////////////////////////////
// ANIMALS //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

abstract class Animal
{
	// Redefine these in child classes!
	protected $movement = 'UNDEFINED';
	protected $voice = 'UNDEFINED';
	protected $descriptor = 'UNDEFINED';
	protected $species = 'UNDEFINED';
	protected $diet = 'UNDEFINED';

	protected $name;

	public function __construct($name)
	{
		$this->name = (string)$name;
	}

	public function move()
	{
		echo $this->getName() . ' ' . $this->movement . "!\n";
	}

	public function speak()
	{
		echo $this->getName() . ' ' . $this->voice . "\n";
	}

	public function eat($food)
	{
		if ($this->diet == 'carnivore') {
			if ($food == 'meat') {
				echo $this->getName() . " tears hungrily into the meat.\n";
				return true;
			} else {
				echo $this->getName() . " doesn't eat that.\n";
				return false;
			}
		} else if ($this->diet == 'herbivore') {
			if ($food == 'plant') {
				echo $this->getName() . " munches the tasty leaves.\n";
				return true;
			} else {
				echo $this->getName() . " doesn't eat that.\n";
				return false;
			}
		}
	}

	public function describe()
	{
		$description = $this->getName() . ' is ';
		$species = $this->species;
		$diet = $this->diet;
		if (in_array(strtolower($species[0]), array('a','e','i','o','u'))) {
			$description .= 'an ';
		} else {
			$description .= 'a ';
		}
		$description .= $species;
		$description .= ' [' . $diet . ']';
		$description .= $this->descriptor;
		return $description;
	}

	public function getName()
	{
		return $this->name;
	}
}

class Lion extends Animal
{
	protected $movement = 'runs!';
	protected $voice = 'roars!';
	protected $descriptor = ', 4 legs, a shaggy mane, sharp teeth, a tail';
	protected $species = 'lion';
	protected $diet = 'carnivore';
}

class Gazelle extends Animal
{
	protected $movement = 'runs!';
	protected $voice = 'bleats!';
	protected $descriptor = ', 4 legs, horns, flat teeth, a tail';
	protected $species = 'gazelle';
	protected $diet = 'herbivore';
}

class Owl extends Animal
{
	protected $movement = 'flies!';
	protected $voice = 'hooooots!';
	protected $descriptor = ', 2 legs, 2 wings, a beak';
	protected $species = 'owl';
	protected $diet = 'carnivore';
}

//////////////////////////////////////////////////////////////////////
// ZOO FACILITIES ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

class Zoo
{
	protected $animals = array();

	public function addAnimal(Animal $animal)
	{
		$this->animals[strtolower($animal->getName())] = $animal;
	}

	public function listAnimals()
	{
		foreach ($this->animals as $animal) {
			echo $animal->describe(). "\n";
		}
	}

	public function getAnimal($name)
	{
		$name = strtolower($name);
		if (!isset($this->animals[$name])) {
			return null;
		}
		return $this->animals[$name];
	}
}

//////////////////////////////////////////////////////////////////////
// MAIN /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

$zoo = new Zoo();
$zoo->addAnimal(new Lion('Rex'));
$zoo->addAnimal(new Owl('Hooter'));
$zoo->addAnimal(new Gazelle('Bounder'));

$zoo->listAnimals();
echo "\n";

$zoo->getAnimal('Hooter')->move();
echo "\n";

$zoo->getAnimal('Rex')->eat('meat');
echo "\n";

$zoo->getAnimal('Rex')->eat('plant');
echo "\n";

$zoo->getAnimal('Bounder')->eat('plant');
echo "\n";

$zoo->getAnimal('Rex')->speak();
echo "\n";

<?php

//////////////////////////////////////////////////////////////////////
// ANIMALS //////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

class Animal
{
	protected $movement;
	protected $voice;
	protected $diet;
	protected $descriptor;
	protected $name;
	protected $species;

	public function __construct(
		$name,
		$species,
		Movement $move,
		Voice $voice,
		Diet $diet,
		Descriptor $descriptor)
	{
		$this->name = (string)$name;
		$this->species = (string)$species;
		$this->movement = $move;
		$this->voice = $voice;
		$this->diet = $diet;
		$this->descriptor = $descriptor;
	}

	public function move()
	{
		$this->movement->move($this);
	}

	public function speak()
	{
		$this->voice->speak($this);
	}

	public function eat(Food $food)
	{
		return $this->diet->eat($food);
	}

	public function describe()
	{
		return $this->descriptor->describe();
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSpecies()
	{
		return $this->species;
	}

	public function getDiet()
	{
		return $this->diet;
	}
}

class Lion extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'lion', new Run(), new Roar(), new Carnivore($this), new LionDescriptor($this));
	}
}

class Chimp extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'chimp', new Climb(), new Ook(), new Omnivore($this), new ChimpDescriptor($this));
	}
}

class Gazelle extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'gazelle', new Run(), new Bleat(), new Herbivore($this), new GazelleDescriptor($this));
	}
}

class Owl extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'owl', new Fly(), new Hoot(), new Carnivore($this), new OwlDescriptor($this));
	}
}

//////////////////////////////////////////////////////////////////////
// MOVEMENT /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

interface Movement
{
	public function move(Animal $animal);
}

class Run implements Movement
{
	public function move(Animal $animal)
	{
		echo $animal->getName() . " runs!\n";
	}
}

class Climb implements Movement
{
	public function move(Animal $animal)
	{
		echo $animal->getName() . " climbs!\n";
	}
}

class Fly implements Movement
{
	public function move(Animal $animal)
	{
		echo $animal->getName() . " flies!\n";
	}
}

//////////////////////////////////////////////////////////////////////
// SPEAK ////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

interface Voice
{
	public function speak(Animal $animal);
}

class Roar implements Voice
{
	public function speak(Animal $animal)
	{
		echo $animal->getName() . " roars!\n";
	}
}

class Bleat implements Voice
{
	public function speak(Animal $animal)
	{
		echo $animal->getName() . " bleats!\n";
	}
}

class Hoot implements Voice
{
	public function speak(Animal $animal)
	{
		echo $animal->getName() . " hooooots!\n";
	}
}

class Ook implements Voice
{
	public function speak(Animal $animal)
	{
		echo $animal->getName() . " ook-ooks!\n";
	}
}

//////////////////////////////////////////////////////////////////////
// DIET /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

interface Food {}
class Plant implements Food {}
class Meat implements Food {}

interface Diet
{
	public function eat(Food $food);
}

class Carnivore implements Diet
{
	protected $animal;
	public function __construct(Animal $animal)
	{
		$this->animal = $animal;
	}

	public function eat(Food $food)
	{
		if ($food instanceof Meat) {
			echo $this->animal->getName() . " tears hungrily into the meat.\n";
			return true;
		} else {
			echo $this->animal->getName() . " doesn't eat that.\n";
			return false;
		}
	}
}

class Herbivore implements Diet
{
	protected $animal;
	public function __construct(Animal $animal)
	{
		$this->animal = $animal;
	}

	public function eat(Food $food)
	{
		if ($food instanceof Plant) {
			echo $this->animal->getName() . " munches the tasty leaves.\n";
			return true;
		} else {
			echo $this->animal->getName() . " doesn't eat that.\n";
			return false;
		}
	}
}

class Omnivore implements Diet
{
	protected $plantDiet;
	protected $meatDiet;
	public function __construct(Animal $animal)
	{
		$this->plantDiet = new Herbivore($animal);
		$this->meatDiet = new Carnivore($animal);
	}

	public function eat(Food $food)
	{
		if ($food instanceof Plant) {
			return $this->plantDiet->eat($food);
		} else if ($food instanceof Meat) {
			return $this->meatDiet->eat($food);
		} else {
			echo $animal->getName() . " doesn't eat that.\n";
			return false;
		}
	}
}

//////////////////////////////////////////////////////////////////////
// DESCRIBE /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

abstract class Descriptor
{
	protected $description = 'UNDEFINED';

	protected $animal;
	public function __construct(Animal $animal)
	{
		$this->animal = $animal;
	}

	public function describe()
	{
		$description = $this->animal->getName() . ' is ';
		$species = $this->animal->getSpecies();
		$diet = $this->animal->getDiet();
		if (in_array(strtolower($species[0]), array('a','e','i','o','u'))) {
			$description .= 'an ';
		} else {
			$description .= 'a ';
		}
		$description .= $species;
		$description .= ' [' . strtolower(get_class($diet)) . ']';
		$description .= ' ' . $this->description;
		return $description;
	}
}

class LionDescriptor extends Descriptor
{
	protected $description = ', 4 legs, a shaggy mane, sharp teeth, a tail';
}

class OwlDescriptor extends Descriptor
{
	protected $description = ', 2 legs, 2 wings, a beak';
}

class GazelleDescriptor extends Descriptor
{
	protected $description = ', 4 legs, horns, flat teeth, a tail';
}

class ChimpDescriptor extends Descriptor
{
	protected $description = ', 2 legs, 2 arms, sharp teeth, flat teeth';
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
$zoo->addAnimal(new Chimp('Bobo'));

$zoo->listAnimals();
echo "\n";

$zoo->getAnimal('Hooter')->move();
echo "\n";

$zoo->getAnimal('Rex')->eat(new Meat());
echo "\n";

$zoo->getAnimal('Rex')->eat(new Plant());
echo "\n";

$zoo->getAnimal('Bounder')->eat(new Plant());
echo "\n";

$zoo->getAnimal('Rex')->speak();
echo "\n";

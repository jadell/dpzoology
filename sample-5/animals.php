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
		parent::__construct($name, 'lion', new Run(), new Roar(), new Carnivore($this),
			new TailDescriptor(
			new TeethDescriptor(TeethDescriptor::SHARP,
			new ManeDescriptor(
			new LimbsDescriptor(4, LimbsDescriptor::LEGS,
			new BaseDescriptor($this)
			)))));
	}
}


class Chimp extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'chimp', new Climb(), new Ook(), new Omnivore($this),
			new TeethDescriptor(TeethDescriptor::FLAT,
			new TeethDescriptor(TeethDescriptor::SHARP,
			new LimbsDescriptor(2, LimbsDescriptor::ARMS,
			new LimbsDescriptor(2, LimbsDescriptor::LEGS,
			new BaseDescriptor($this)
			)))));
	}
}

class Gazelle extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'gazelle', new Run(), new Bleat(), new Herbivore($this),
			new TailDescriptor(
			new TeethDescriptor(TeethDescriptor::FLAT,
			new HornsDescriptor(
			new LimbsDescriptor(4, LimbsDescriptor::LEGS,
			new BaseDescriptor($this)
			)))));
	}
}

class Owl extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'owl', new Fly(), new Hoot(), new Carnivore($this),
			new TeethDescriptor(TeethDescriptor::BEAK,
			new LimbsDescriptor(2, LimbsDescriptor::WINGS,
			new LimbsDescriptor(2, LimbsDescriptor::LEGS,
			new BaseDescriptor($this)
			))));
	}
}

class Hipster extends Animal
{
	public function __construct($name)
	{
		parent::__construct($name, 'hipster', new Fixie(), new SelfRighteous(), new Locavore($this),
			new VintageDescriptor(
			new LimbsDescriptor(2, LimbsDescriptor::ARMS,
			new LimbsDescriptor(2, LimbsDescriptor::LEGS,
			new BaseDescriptor($this)
			))));
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

class Fixie implements Movement
{
	public function move(Animal $animal)
	{
		echo $animal->getName() . " rides his fixie!\n";
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

class SelfRighteous implements Voice
{
	public function speak(Animal $animal)
	{
		echo $animal->getName() . " says \"You've probably never heard of them.\"\n";
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

class Locavore implements Diet
{
	protected $animal;
	public function __construct(Animal $animal)
	{
		$this->animal = $animal;
	}

	public function eat(Food $food)
	{
		echo $this->animal->getName() . " only eats locally-sourced organic free-range cruelty-free paleo tofu and PBR.\n";
		return false;
	}
}

//////////////////////////////////////////////////////////////////////
// DESCRIBE /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

interface Descriptor
{
	public function describe();
}

class BaseDescriptor implements Descriptor
{
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
		return $description;
	}
}

abstract class ExtendedDescriptor implements Descriptor
{
	protected $descriptor;
	public function __construct(Descriptor $descriptor)
	{
		$this->descriptor = $descriptor;
	}
}

class LimbsDescriptor extends ExtendedDescriptor
{
	const ARMS = 'arms';
	const LEGS = 'legs';
	const WINGS = 'wings';

	protected $legs;
	protected $type;
	public function __construct($legs, $type, Descriptor $descriptor)
	{
		$this->legs = (int)$legs;
		$this->type = (string)$type;
		parent::__construct($descriptor);
	}

	public function describe()
	{
		return $this->descriptor->describe() . ", {$this->legs} {$this->type}";
	}
}

class TeethDescriptor extends ExtendedDescriptor
{
	const SHARP = 'sharp teeth';
	const FLAT = 'flat teeth';
	const BEAK = 'a beak';

	protected $type;
	public function __construct($type, Descriptor $descriptor)
	{
		$this->type = (string)$type;
		parent::__construct($descriptor);
	}

	public function describe()
	{
		return $this->descriptor->describe() . ", {$this->type}";
	}
}

class ManeDescriptor extends ExtendedDescriptor
{
	public function describe()
	{
		return $this->descriptor->describe() . ', a shaggy mane';
	}
}

class TailDescriptor extends ExtendedDescriptor
{
	public function describe()
	{
		return $this->descriptor->describe() . ', a tail';
	}
}

class HornsDescriptor extends ExtendedDescriptor
{
	public function describe()
	{
		return $this->descriptor->describe() . ', horns';
	}
}

class VintageDescriptor extends ExtendedDescriptor
{
	public function describe()
	{
		return $this->descriptor->describe() . ', vintage t-shirt, $400 pre-ripped jeans, feelings of self-righteous entitlement';
	}
}

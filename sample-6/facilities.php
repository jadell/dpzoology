<?php
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

class DietMonitor
{
	protected $diets = array();

	public function gaveFood(Animal $animal, Food $food)
	{
		$animalOid = spl_object_hash($animal);
		if (!isset($this->diets[$animalOid])) {
			$this->diets[$animalOid] = array('animal'=>$animal, 'food'=>array());
		}
		$this->diets[$animalOid]['food'][] = $food;
	}

	public function listDiets()
	{
		if (!$this->diets) {
			echo "No animals have eaten yet\n";
		}
		foreach ($this->diets as $oid => $diet) {
			echo $diet['animal']->getName() . " has eaten " . count($diet['food']) . " times\n";
		}
	}
}

class ExerciseMonitor
{
	protected $exercise = array();

	public function gaveExercise(Animal $animal)
	{
		$animalOid = spl_object_hash($animal);
		if (!isset($this->exercise[$animalOid])) {
			$this->exercise[$animalOid] = array('animal'=>$animal, 'count'=>0);
		}
		$this->exercise[$animalOid]['count']++;
	}

	public function listExercise()
	{
		if (!$this->exercise) {
			echo "No animals have exercised yet\n";
		}
		foreach ($this->exercise as $oid => $exercise) {
			echo $exercise['animal']->getName() . " exercised " . $exercise['count'] . " times\n";
		}
	}
}

class KeeperNotification
{
	public function gaveFood(Animal $animal, Food $food)
	{
		echo "Keeper Notified: " . $animal->getName() . " just ate a " . get_class($food) . "\n";
	}

	public function gaveExercise(Animal $animal)
	{
		echo "Keeper Notified: " . $animal->getName() . " just exercised\n";
	}
}

class FoodStock
{
	protected $plants = array();
	protected $meats = array();

	public function listStock()
	{
		echo "Food Stock:\n";
		echo "\t" . count($this->plants) . " plants\n";
		echo "\t" . count($this->meats) . " meats\n";
	}

	public function stockPlant(Plant $plant)
	{
		$this->plants[] = $plant;
	}

	public function stockMeat(Meat $meat)
	{
		$this->meats[] = $meat;
	}

	public function getPlant()
	{
		return array_shift($this->plants);
	}

	public function getMeat()
	{
		return array_shift($this->meats);
	}
}


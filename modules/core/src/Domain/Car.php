<?php
namespace Modules\Shared\Domain;

use Modules\Shared\Domain\Entity;

class Car extends Entity{
    // Class variables (properties)
    public $brand;
    public $model;
    public $color;
    public $year;
    private $mileage;
    protected $fuelType;
    
    // Constructor method (called when object is created)
    public function __construct($brand = "", $model = "", $color = "", $year = 2020) {
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->year = $year;
        $this->mileage = 0;
        $this->fuelType = "Petrol";
    }
    
    // Normal methods
    public function startEngine() {
        return "The {$this->brand} {$this->model}'s engine is now running.";
    }
    
    public function stopEngine() {
        return "The {$this->brand} {$this->model}'s engine has stopped.";
    }
    
    public function drive($distance) {
        $this->mileage += $distance;
        return "Drove {$distance} km. Total mileage: {$this->mileage} km";
    }
    
    public function getCarInfo() {
        return "{$this->year} {$this->brand} {$this->model} - Color: {$this->color}";
    }
    
    public function setColor($newColor) {
        $this->color = $newColor;
        return "Car color changed to {$newColor}";
    }
    
    // Getter for private property
    public function getMileage() {
        return $this->mileage;
    }
    
    // Setter with validation
    public function setFuelType($type) {
        $validFuels = ["Petrol", "Diesel", "Electric", "Hybrid"];
        if (in_array($type, $validFuels)) {
            $this->fuelType = $type;
            return true;
        }
        return false;
    }
    
    // Getter for protected property
    public function getFuelType() {
        return $this->fuelType;
    }

    public function toArray(): array {
        return [
            'brand' => $this->brand,
            'model' => $this->model,
            'color' => $this->color,
            'year' => $this->year,
            'mileage' => $this->mileage,
            'fuelType' => $this->fuelType
        ];
    }
}

// Usage example
// $myCar = new Car("Toyota", "Camry", "Red", 2022);
// echo $myCar->getCarInfo() . "<br>";
// echo $myCar->startEngine() . "<br>";
// echo $myCar->drive(50) . "<br>";
// echo $myCar->setColor("Blue") . "<br>";
// echo $myCar->getCarInfo() . "<br>";
// echo "Mileage: " . $myCar->getMileage() . " km<br>";

?>
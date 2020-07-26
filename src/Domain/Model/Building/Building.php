<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Aggregate Root Entity for a Building with Elevators
 * It tricks its internal Elevators Aggregate using an
 */
final class Building
{
    /**
     * Elevators of the building
     *
     * @var ElevatorsCollection
     */
    protected $elevators;

    /**
     * Flats where elevators can stop
     *
     * @var array
     */
    protected $flats = [];


    /**
     * Building constructor
     *
     * Initializes a building with its elevators
     *
     * @param string[] $flats an ascending ordered array with names of building's flats
     * @param integer $elevator_count
     */
    public function __construct(array $flats)
    {
        foreach ($flats as $flat_name) {
            $this->createFlat($flat_name);
        }
        $this->elevators = new ElevatorsCollection;
    }


    /**
     * Creates a new Flat in the Building
     *
     * @param string $name
     * @param integer $position
     * @return Flat
     */
    public function createFlat(string $name, int $position = null) : Flat
    {
        if (is_null($position)) {
            $position = count($this->flats);
        }
        $this->flats[$position] = new Flat($name, $position, $this);

        return $this->flats[$position];
    }

    /**
     * Building $flats getter
     *
     * @return array The flats of building
     */
    public function getFlats(): array
    {
        return $this->flats;
    }

    /**
     * Building $elevators getter
     *
     * @return ElevatorsCollection.php The elevators of the building with their state
     */
    public function getElevators(): ElevatorsCollection
    {
        return $this->elevators;
    }


    /**
     * Adds a new Elevator to Building
     *
     * @param Flat $flat
     * @return Elevator The elevator created
     */
    public function createElevator(Flat $flat = null)
    {
        $flat = is_null($flat) ? reset($this->flats) : $flat;
        $this->validateFlat($flat);

        $elevator = new Elevator($flat);
        $this->elevators->add($elevator);

        return $elevator;
    }

    /**
     * Moves an Elevator in the Building
     *
     * @param Elevator $elevator
     * @param Flat $to_flat
     * @return Elevator The $elevator moved
     *
     * @throws \AssertionError If elevator or flat do not exist in the building
     *
     */
    public function moveElevator(Elevator $elevator, Flat $to_flat)
    {
        $this->validateFlat($to_flat);
        $this->validateElevator($elevator);

        $elevator->setFlat($to_flat);
            
        return $elevator;
    }


    /**
     * Checks if a given flat exists in the building
     *
     * @param Flat $flat
     * @return true if flat belongs to Building
     */
    protected function validateFlat(Flat $flat)
    {
        if ((!in_array($flat, $this->flats)) || ($flat->building !== $this)) {
            throw new \AssertionError("Unexisting flat in this building");
        }
        return true;
    }

    /**
     * Checks if a given elevator exists in the building
     *
     * @param Elevator $elevator
     * @return true if elevator exists in the Building
     */
    protected function validateElevator(Elevator $elevator) : bool
    {
        $found = $this->elevators->findOne($elevator->id);
        if (false === $found) {
            throw new \AssertionError("Unexisting elevator in this building");
        }
        return true;
    }
}

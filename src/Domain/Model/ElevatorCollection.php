<?php namespace Proweb21\Elevator\Model;

/**
 * Collection of Elevators 
 */
final class ElevatorCollection
    
{
    /**
     * The list of elevators compounding the collection
     *
     * @var array
     */
    protected $elevators = [];

    /**
     * Stores an elevator to the collection if not present
     * 
     * @param Elevator $elevator
     * @return Elevator The elevator added
     */
    public function add(Elevator $elevator): Elevator
    {
        $this->elevators[$elevator->id] = $elevator;
        
        return $elevator;
    }

    /**
     * Removes an elevator from the collection
     *
     * @param Elevator $elevator The elevator to remove from the collection
     * @return Elevator The given elevator once removed (in case it was present)
     */
    public function remove(Elevator $elevator) : Elevator
    {
        if (array_key_exists($elevator->id,$this->elevators))
            unset($this->elevators[$elevator->id]);

        return $elevator;
    }

    /**
     * Finds an elevator in the collection given its id
     *
     * @param string $id
     * @return Elevator|False The elevator with the given id or null if no elevator was found
     */
    public function findOne(string $id)
    {        
        $result = false;

        if (array_key_exists($id,$this->elevators))
            $result = $this->elevators[$id];

        return $result;
    }

}
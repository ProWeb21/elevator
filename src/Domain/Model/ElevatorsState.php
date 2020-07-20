<?php namespace Proweb21\Elevator\Model;

use Proweb21\Elevator\Domain\ObservableTrait;
use Proweb21\Elevator\Events\Observable;

/**
 * Elevators' state in a building
 * 
 * An state is the location of the Elevators in the Building flats
 * 
 */
final class ElevatorsState
    implements Observable
{

    use ObservableTrait;

    /**
     * Internal state of the Elevators in a Building
     *
     * @var array
     */
    protected $state = [];

    /**
     * Sets the state for an elevator in a Building
     *
     * @param string $elevator id The elevator to state
     * @return void
     */
    public function setState(string $elevator, int $flat): string
    {
        $previous_state = $this->removeState($elevator);

        if (!array_key_exists($flat, $this->state))        
            $this->state[$flat] = [$elevator];
        else
            $this->state[$flat][]=$elevator;

        $this->publishStateChanged($elevator, $flat, $previous_state);

        return $elevator;
    }

    
    /**
     * Removes the state of an elevator in a Building
     *
     * @param string $elevator The elevator which state has to be removed
     * @return array|FALSE The former elevator state before being removed or FALSE if had no state
     */
    public function removeState(string $elevator)
    {
        $state = $this->getState($elevator);
        
        if ($state) {
            array_splice($this->state[$state["flat"]], $state["order"], 1);
            // Flat State is reindexed
            $this->state[$state["flat"]] = array_values($this->state[$state["flat"]]);
        }

        return $state;
    }

    /**
     * Gets the state of an Elevator in a Building 
     *
     * @param string $elevator
     * @return array|false the Elevator state or false if not stated in the building
     */
    public function getState(string $elevator)
    {
        
        $result = false;
        $key = false;
        $flat = 0;

        while ($flat<count($this->state) && !$result){
            if (is_array($this->state[$flat]))
                $key = array_search($elevator,$this->state[$flat]);
            if ($key !== false)
                $result = ["flat"=>$flat,"order"=>$key,"elevator"=>$elevator];
            $flat++;
        }

        return $result;
    }

    /**
     * Gets the state of Elevators located in a building flat
     *
     * @param integer $flat
     * @return array list of elevators in the given flat
     */
    public function getFlatState(int $flat)
    {
        $result = [];

        if (array_key_exists($flat,$this->state))
            $result = $this->state[$flat];               

        return $result;
    }

 
    /**
     * Notifies observers a ElevatorsStateChanged domain event
     *
     * @param string $elevator The Elevator what caused the state change
     * @param int $flat The new flat (position) where the elevator is located
     * @param array|FALSE $previous_state The previous state for the Elevator
     * @return void
     */
    protected function publishStateChanged(string $elevator, int $flat, $previous_state)
    {        
        $flats_moved = 0;

        if ($previous_state)
            $flats_moved = intval(abs($previous_state['flat']-$flat));

        $this->publish(new ElevatorsStateChanged($flats_moved, $elevator, $flat, $this->state));
    }
    

}
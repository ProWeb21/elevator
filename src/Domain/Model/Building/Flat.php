<?php namespace Proweb21\Elevator\Model\Building;

/**
 * Flat in a Building
 *
 * @property-read string $name
 * @property-read int $position
 * @property-read Building $building
 */
final class Flat
{
    /**
     * Flat identifier
     *
     * @var string
     */
    protected $name;

    /**
     * Flat position in a building
     *
     * @var int
     */
    protected $position;

    /**
     * Building where the flat was built
     *
     * @var Building
     */
    protected $buiding;


    /**
     * Constructor
     *
     * Creates a flat with a given name and position in the given building
     *
     * @param string $name
     * @param integer $position
     * @param Building $building
     */
    public function __construct(string $name, int $position, Building $building)
    {
        $this->name = $name;
        $this->position = $position;
        $this->building = $building;
    }

    /**
     * Read-only properties accessor
     *
     * @param string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        switch ($property) {
            case "name":
                return $this->getName();
            break;
            case "position":
                return $this->getPosition();
            break;
            case "building":
                return $this->getBuilding();
            break;
        }
    }

    /**
     * Getter for $name read-only property
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Getter for $position read-only property
     *
     * @return int
     */
    public function getPosition() : int
    {
        return $this->position;
    }

    /**
     * Getter for $building read-only property
     *
     * @return Building
     */
    public function getBuilding() : Building
    {
        return $this->building;
    }
}
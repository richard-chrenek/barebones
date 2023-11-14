<?php

namespace Barebones\Model;

use Exception;
use Serializable;

/**
 * Example class used for demonstration of working with serialization, API responses and database
 */
class Person implements Serializable, \JsonSerializable
{
    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $surname;

    /** @var int|null */
    protected $age;

    /** @var string[] */
    protected $hobbies = [];

    /** @var string|null */
    private $privateData;

    /**
     * Person constructor
     * @param string|null $name
     * @param string|null $surname
     * @param int|null $age
     * @param string[] $hobbies
     * @param string $privateData
     */
    public function __construct(
        $name = null,
        $surname = null,
        $age = null,
        $hobbies = [],
        $privateData = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->age = $age;
        $this->hobbies = $hobbies;
        $this->privateData = $privateData;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Person
     */
    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return Person
     */
    public function setSurname($surname)
    {
        if (is_string($surname)) {
            $this->surname = $surname;
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return Person
     */
    public function setAge($age)
    {
        if (is_int($age) && $age > 0) {
            $this->age = $age;
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @param mixed $hobbies
     * @return Person
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;
        return $this;
    }

    /**
     * @param string $hobby
     * @return Person
     */
    public function addHobby($hobby)
    {
        if (is_string($hobby)) {
            $this->hobbies[] = $hobby;
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrivateData()
    {
        return $this->privateData;
    }

    /**
     * @param string $privateData
     * @return Person
     */
    public function setPrivateData($privateData)
    {
        if (is_string($privateData)) {
            $this->privateData = $privateData;
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Person
     */
    public function setId($id)
    {
        if (is_int($id)) {
            $this->id = $id;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->surname,
            $this->age,
            $this->hobbies,
            $this->privateData,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($data)
    {
        list(
            $this->id,
            $this->name,
            $this->surname,
            $this->age,
            $this->hobbies,
            $this->privateData,
            ) = unserialize($data);
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function __serialize()
    {
        return $this->serialize();
    }

    /**
     * @param $data
     * @return void
     */
    public function __unserialize($data)
    {
        $this->unserialize($data);
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'age' => $this->age,
            'hobbies' => $this->hobbies,
        ];
    }
}

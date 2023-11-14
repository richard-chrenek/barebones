<?php

namespace Barebones\Service;

use Barebones\App\Database;
use Barebones\Classes\Singleton;
use Barebones\Model\Person;
use PDO;
use PDOException;

/**
 * Example of service, handling model class instance operations with the database.
 * Provides basic CRUD methods, in addition with custom methods, which may come in handy when
 * manipulating the data.
 */
class PersonService extends Singleton
{
    /** @var PDO */
    protected $db;
    const TABLE_NAME = 'Person';

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * @param string $name
     * @param string $surname
     * @param int $age
     * @param string[] $hobbies
     * @param string $privateData
     * @return Person
     */
    public function create($name, $surname, $age, $hobbies, $privateData)
    {
        $query = $this->db->prepare(
            'INSERT INTO ' . self::TABLE_NAME . ' (name, surname, age, hobbies, privateData)
             VALUES (:name, :surname, :age, :hobbies, :privateData)'
        );

        $query->execute(
            [
                'name' => $name,
                'surname' => $surname,
                'age' => $age,
                'hobbies' => json_encode($hobbies),
                'privateData' => $privateData,
            ]
        );

        $person = new Person($name, $surname, $age, $hobbies, $privateData);
        $person->setId(intval($this->db->lastInsertId()));

        return $person;
    }

    /**
     * @param int $id
     * @return Person|null
     */
    public function read($id)
    {
        $query = $this->db->prepare('SELECT * FROM ' . self::TABLE_NAME . ' WHERE id=:id');

        try {
            $query->execute(
                [
                    'id' => $id,
                ]
            );
            $result = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Unable to execute read statement - " . $e->getTraceAsString());
            return null;
        }

        if (empty($result)) {
            return null;
        }

        $user = new Person();
        $user->unserialize($result);

        return $user;
    }

    /**
     * @param string $name
     * @param string $surname
     * @param int $age
     * @param string[] $hobbies
     * @param string $privateData
     * @return bool
     */
    public function update($name, $surname, $age, $hobbies, $privateData)
    {
        $query = $this->db->prepare('UPDATE ' . self::TABLE_NAME .
            ' SET name=:name, surname=:surname, age=:age, hobbies=:hobbies, privateData=:privateData WHERE id=:id');
        $result = false;

        try {
            $result = $query->execute(
                [
                    'name' => $name,
                    'surname' => $surname,
                    'age' => $age,
                    'hobbies' => json_encode($hobbies),
                    'privateData' => $privateData,
                ]
            );
        } catch (PDOException $e) {
            trigger_error("Unable to execute update statement - " . $e->getTraceAsString());
            return false;
        }

        return $result;
    }

    /**
     * @param int $id
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function updateAttribute($id, $attribute, $value)
    {
        switch ($attribute) {
            case 'name':
            case 'surname':
            case 'privateData':
                if (!is_string($value)) {
                    return false;
                }
                break;
            case 'age':
                if (!is_int($value)) {
                    return false;
                }
                break;
            case 'hobbies':
                if (!is_array($value)) {
                    return false;
                }
                $value = json_encode($value);
                break;
            case 'id':
            default:
                return false;
        }

        $query = $this->db->prepare('UPDATE ' . self::TABLE_NAME . ' SET ' . $attribute . '=:value WHERE id=:id');
        try {
            $result = $query->execute(
                [
                    'id' => $id,
                    'value' => $value
                ]
            );
        } catch (PDOException $e) {
            trigger_error("Unable to execute updateAttribute statement - " . $e->getMessage());
            return false;
        }
        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $query = $this->db->prepare('DELETE FROM ' . self::TABLE_NAME . ' WHERE id=:id');
        $result = false;

        try {
            $result = $query->execute(
                [
                    'id' => $id,
                ]
            );
        } catch (PDOException $e) {
            trigger_error("Unable to execute delete statement - " . $e->getTraceAsString());
            return false;
        }

        return $result;
    }
}

<?php

// src/AppBundle/Entity/ToDoItem.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table (name="todolist")
*/

class ToDoItem{
    
    /**
    * @ORM\Column (type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue (strategy="AUTO")
    */
    private $id;
    
    /**
    * @ORM\Column (type="text")
    */
    private $description;
        
    /**
    * @ORM\Column (type="date")
    */
    private $date;    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ToDoItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ToDoItem
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

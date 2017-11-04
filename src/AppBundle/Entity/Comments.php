<?php

// src/AppBundle/Entity/Comments.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table (name="comments")
*/

class Comments{
    
    /**
    * @ORM\Column (type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue (strategy="AUTO")
    */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="ToDoItem")
    * @ORM\JoinColumn(name="todo_id", referencedColumnName="id")
    */
    private $todo_id;
    
    /**
    * @ORM\Column (type="text")
    */
    private $comment;


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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set todoId
     *
     * @param \AppBundle\Entity\ToDoItem $todoId
     *
     * @return Comments
     */
    public function setTodoId(\AppBundle\Entity\ToDoItem $todoId = null)
    {
        $this->todo_id = $todoId;

        return $this;
    }

    /**
     * Get todoId
     *
     * @return \AppBundle\Entity\ToDoItem
     */
    public function getTodoId()
    {
        return $this->todo_id;
    }
}

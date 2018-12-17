<?php
namespace Bpai\Models;
/**
 * 用户角色Models
 * @package Bpai\Models 
 * @version 1.0
 */
class Roles extends \Catchtech\Extensions\Model\BaseModel
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $code;

    /**
     *
     * @var string
     */
    protected $key;


    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $pid;

    /**
     *
     * @var integer
     */
    protected $created;

    /**
     *
     * @var string
     */
    protected $createdby;

    /**
     *
     * @var integer
     */
    protected $updated;

    /**
     *
     * @var string
     */
    protected $updatedby;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }
    public function getPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Method to set the value of field code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Method to set the value of field key
     *
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }


    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Method to set the value of field created
     *
     * @param integer $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Method to set the value of field createdby
     *
     * @param string $createdby
     * @return $this
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Method to set the value of field updated
     *
     * @param integer $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Method to set the value of field updatedby
     *
     * @param string $updatedby
     * @return $this
     */
    public function setUpdatedby($updatedby)
    {
        $this->updatedby = $updatedby;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Returns the value of field created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Returns the value of field createdby
     *
     * @return string
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Returns the value of field updated
     *
     * @return integer
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Returns the value of field updatedby
     *
     * @return string
     */
    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'code' => 'code', 
            'key' => 'key', 
            'module' => 'module', 
            'name' => 'name', 
            'pid' => 'pid',
            'created' => 'created', 
            'createdby' => 'createdby', 
            'updated' => 'updated', 
            'updatedby' => 'updatedby'
        );
    }

}

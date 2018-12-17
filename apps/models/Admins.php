<?php
namespace Bpai\Models;

/**
 * 后台用户Models
 * @package Bpai\Models 
 * @version 1.0
 */
class Admins extends \Catchtech\Extensions\Model\BaseModel
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
    protected $username;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $role_code;

    /**
     *
     * @var string
     */
    protected $seller_code;

    /**
     *
     * @var integer
     */
    protected $last_ip;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $qq;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var integer
     */
    protected $last_time;

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
     * @return string
     */
    public function getSellerCode()
    {
        return $this->seller_code;
    }

    /**
     * @param string $seller_code
     */
    public function setSellerCode($seller_code)
    {
        $this->seller_code = $seller_code;
    }


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
     * Method to set the value of field login
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Method to set the value of field username
     *
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

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
     * Method to set the value of field role_code
     *
     * @param string $role_code
     * @return $this
     */
    public function setRoleCode($role_code)
    {
        $this->role_code = $role_code;

        return $this;
    }

    /**
     * Method to set the value of field last_ip
     *
     * @param integer $last_ip
     * @return $this
     */
    public function setLastIp($last_ip)
    {
        $this->last_ip = $last_ip;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field qq
     *
     * @param string $qq
     * @return $this
     */
    public function setQq($qq)
    {
        $this->qq = $qq;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return  $this;
    }

    /**
     * Method to set the value of field last_time
     *
     * @param integer $last_time
     * @return $this
     */
    public function setLastTime($last_time)
    {
        $this->last_time = $last_time;

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
     * Returns the value of field login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the value of field username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Returns the value of field role_code
     *
     * @return string
     */
    public function getRoleCode()
    {
        return $this->role_code;
    }

    /**
     * Returns the value of field last_ip
     *
     * @return integer
     */
    public function getLastIp()
    {
        return $this->last_ip;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field qq
     *
     * @return string
     */
    public function getQq()
    {
        return $this->qq;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field last_time
     *
     * @return integer
     */
    public function getLastTime()
    {
        return $this->last_time;
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
}

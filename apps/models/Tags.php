<?php

namespace Bpai\Models;

class Tags extends \Catchtech\Extensions\Model\BaseModel
{

    /**
     *
     * @var integer
     */
    protected $tagid;

    /**
     *
     * @var string
     */
    protected $tagname;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     * Method to set the value of field tagid
     *
     * @param integer $tagid
     * @return $this
     */
    public function setTagid($tagid)
    {
        $this->tagid = $tagid;

        return $this;
    }

    /**
     * Method to set the value of field tagname
     *
     * @param string $tagname
     * @return $this
     */
    public function setTagname($tagname)
    {
        $this->tagname = $tagname;

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
     * Returns the value of field tagid
     *
     * @return integer
     */
    public function getTagid()
    {
        return $this->tagid;
    }

    /**
     * Returns the value of field tagname
     *
     * @return string
     */
    public function getTagname()
    {
        return $this->tagname;
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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tags';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tags[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tags
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

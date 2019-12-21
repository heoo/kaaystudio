<?php

namespace Bpai\Models;

class Category extends \Catchtech\Extensions\Model\BaseModel
{
    protected $id;
    protected $code;
    protected $key;
    protected $name;
    protected $en_name;
    protected $language;
    protected $type;
    protected $logo;
    protected $listorder;
    protected $text;
    protected $en_text;
    protected $val;
    protected $more;
    protected $en_more;
    protected $isnav;
    protected $sort;
    protected $status;
    protected $created;
    protected $createdby;
    protected $updated;
    protected $updatedby;

    /**
     * @return mixed
     */
    public function getEnName()
    {
        return $this->en_name;
    }

    /**
     * @param mixed $en_name
     */
    public function setEnName($en_name)
    {
        $this->en_name = $en_name;
    }

    /**
     * @return mixed
     */
    public function getEnText()
    {
        return $this->en_text;
    }

    /**
     * @param mixed $en_text
     */
    public function setEnText($en_text)
    {
        $this->en_text = $en_text;
    }


    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getMore()
    {
        return $this->more;
    }

    /**
     * @param mixed $more
     */
    public function setMore($more)
    {
        $this->more = $more;
    }

    /**
     * @return mixed
     */
    public function getEnMore()
    {
        return $this->en_more;
    }

    /**
     * @param mixed $en_more
     */
    public function setEnMore($en_more)
    {
        $this->en_more = $en_more;
    }

    /**
     * @return mixed
     */
    public function getListorder()
    {
        return $this->listorder;
    }

    /**
     * @param mixed $listorder
     */
    public function setListorder($listorder)
    {
        $this->listorder = $listorder;
    }



    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $val
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->_condition;
    }

    /**
     * @param mixed $condition
     */
    public function setCondition($condition)
    {
        $this->_condition = $condition;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * @param mixed $createdby
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIsnav()
    {
        return $this->isnav;
    }

    /**
     * @param mixed $isnav
     */
    public function setIsnav($isnav)
    {
        $this->isnav = $isnav;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getUpdatedby()
    {
        return $this->updatedby;
    }

    /**
     * @param mixed $updatedby
     */
    public function setUpdatedby($updatedby)
    {
        $this->updatedby = $updatedby;
    }

    /**
     * @return mixed
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @param mixed $val
     */
    public function setVal($val)
    {
        $this->val = $val;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

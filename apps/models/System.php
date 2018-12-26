<?php
namespace Bpai\Models;

class System extends  \Catchtech\Extensions\Model\BaseModel
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="code", type="string", length=30, nullable=false)
     */
    public $code;

    /**
     *
     * @var string
     * @Column(column="key", type="string", length=20, nullable=false)
     */
    public $key;

    /**
     *
     * @var string
     * @Column(column="web_name", type="string", length=50, nullable=false)
     */
    public $web_name;

    /**
     *
     * @var string
     * @Column(column="web_url", type="string", length=50, nullable=false)
     */
    public $web_url;

    /**
     *
     * @var string
     * @Column(column="logo", type="string", length=50, nullable=false)
     */
    public $logo;

    /**
     *
     * @var string
     * @Column(column="seo_keywords", type="string", length=255, nullable=false)
     */
    public $seo_keywords;

    /**
     *
     * @var string
     * @Column(column="description", type="string", length=255, nullable=false)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(column="copyright", type="string", length=100, nullable=false)
     */
    public $copyright;

    /**
     *
     * @var string
     * @Column(column="phone", type="string", length=50, nullable=false)
     */
    public $phone;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=50, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(column="icp", type="string", length=50, nullable=false)
     */
    public $icp;

    /**
     *
     * @var string
     * @Column(column="analytics", type="string", length=255, nullable=false)
     */
    public $analytics;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("system");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'system';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return System[]|System|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return System|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

<?php

namespace PrgmrBill\RateMyCatBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cats' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.PrgmrBill.RateMyCatBundle.Model.map
 */
class CatsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PrgmrBill.RateMyCatBundle.Model.map.CatsTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('cats');
        $this->setPhpName('Cats');
        $this->setClassname('PrgmrBill\\RateMyCatBundle\\Model\\Cats');
        $this->setPackage('src.PrgmrBill.RateMyCatBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'CatID', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CatPictures', 'PrgmrBill\\RateMyCatBundle\\Model\\CatPictures', RelationMap::ONE_TO_MANY, array('id' => 'cat_id', ), 'CASCADE', null, 'CatPicturess');
        $this->addRelation('CatRatings', 'PrgmrBill\\RateMyCatBundle\\Model\\CatRatings', RelationMap::ONE_TO_MANY, array('id' => 'cat_id', ), 'CASCADE', null, 'CatRatingss');
    } // buildRelations()

} // CatsTableMap

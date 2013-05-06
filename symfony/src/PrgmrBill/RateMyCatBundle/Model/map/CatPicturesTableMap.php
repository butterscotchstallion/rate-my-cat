<?php

namespace PrgmrBill\RateMyCatBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cat_pictures' table.
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
class CatPicturesTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.PrgmrBill.RateMyCatBundle.Model.map.CatPicturesTableMap';

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
        $this->setName('cat_pictures');
        $this->setPhpName('CatPictures');
        $this->setClassname('PrgmrBill\\RateMyCatBundle\\Model\\CatPictures');
        $this->setPackage('src.PrgmrBill.RateMyCatBundle.Model');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('cat_id', 'CatId', 'INTEGER', 'cats', 'id', true, null, null);
        $this->addColumn('filename', 'filename', 'VARCHAR', false, 255, null);
        $this->getColumn('filename', false)->setPrimaryString(true);
        $this->addColumn('width', 'Width', 'INTEGER', false, null, null);
        $this->addColumn('height', 'Height', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CatPictures', 'PrgmrBill\\RateMyCatBundle\\Model\\Cats', RelationMap::MANY_TO_ONE, array('cat_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // CatPicturesTableMap

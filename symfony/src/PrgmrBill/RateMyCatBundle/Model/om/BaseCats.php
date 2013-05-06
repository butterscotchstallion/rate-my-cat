<?php

namespace PrgmrBill\RateMyCatBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use PrgmrBill\RateMyCatBundle\Model\CatPictures;
use PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery;
use PrgmrBill\RateMyCatBundle\Model\CatRatings;
use PrgmrBill\RateMyCatBundle\Model\CatRatingsQuery;
use PrgmrBill\RateMyCatBundle\Model\Cats;
use PrgmrBill\RateMyCatBundle\Model\CatsPeer;
use PrgmrBill\RateMyCatBundle\Model\CatsQuery;

abstract class BaseCats extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PrgmrBill\\RateMyCatBundle\\Model\\CatsPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CatsPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * @var        PropelObjectCollection|CatPictures[] Collection to store aggregation of CatPictures objects.
     */
    protected $collCatPicturess;
    protected $collCatPicturessPartial;

    /**
     * @var        PropelObjectCollection|CatRatings[] Collection to store aggregation of CatRatings objects.
     */
    protected $collCatRatingss;
    protected $collCatRatingssPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $catPicturessScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $catRatingssScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getCatID()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Cats The current object (for fluent API support)
     */
    public function setCatID($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CatsPeer::ID;
        }


        return $this;
    } // setCatID()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Cats The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CatsPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Cats The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = CatsPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->created_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 3; // 3 = CatsPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Cats object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CatsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collCatPicturess = null;

            $this->collCatRatingss = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CatsQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CatsPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->catPicturessScheduledForDeletion !== null) {
                if (!$this->catPicturessScheduledForDeletion->isEmpty()) {
                    CatPicturesQuery::create()
                        ->filterByPrimaryKeys($this->catPicturessScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->catPicturessScheduledForDeletion = null;
                }
            }

            if ($this->collCatPicturess !== null) {
                foreach ($this->collCatPicturess as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->catRatingssScheduledForDeletion !== null) {
                if (!$this->catRatingssScheduledForDeletion->isEmpty()) {
                    CatRatingsQuery::create()
                        ->filterByPrimaryKeys($this->catRatingssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->catRatingssScheduledForDeletion = null;
                }
            }

            if ($this->collCatRatingss !== null) {
                foreach ($this->collCatRatingss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CatsPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CatsPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CatsPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CatsPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CatsPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }

        $sql = sprintf(
            'INSERT INTO `cats` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setCatID($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = CatsPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCatPicturess !== null) {
                    foreach ($this->collCatPicturess as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCatRatingss !== null) {
                    foreach ($this->collCatRatingss as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CatsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getCatID();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Cats'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Cats'][$this->getPrimaryKey()] = true;
        $keys = CatsPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getCatID(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCreatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collCatPicturess) {
                $result['CatPicturess'] = $this->collCatPicturess->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCatRatingss) {
                $result['CatRatingss'] = $this->collCatRatingss->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CatsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setCatID($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CatsPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setCatID($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCreatedAt($arr[$keys[2]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CatsPeer::DATABASE_NAME);

        if ($this->isColumnModified(CatsPeer::ID)) $criteria->add(CatsPeer::ID, $this->id);
        if ($this->isColumnModified(CatsPeer::NAME)) $criteria->add(CatsPeer::NAME, $this->name);
        if ($this->isColumnModified(CatsPeer::CREATED_AT)) $criteria->add(CatsPeer::CREATED_AT, $this->created_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CatsPeer::DATABASE_NAME);
        $criteria->add(CatsPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getCatID();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setCatID($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getCatID();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Cats (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setCreatedAt($this->getCreatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCatPicturess() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCatPictures($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCatRatingss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCatRatings($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setCatID(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Cats Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CatsPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CatsPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CatPictures' == $relationName) {
            $this->initCatPicturess();
        }
        if ('CatRatings' == $relationName) {
            $this->initCatRatingss();
        }
    }

    /**
     * Clears out the collCatPicturess collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Cats The current object (for fluent API support)
     * @see        addCatPicturess()
     */
    public function clearCatPicturess()
    {
        $this->collCatPicturess = null; // important to set this to null since that means it is uninitialized
        $this->collCatPicturessPartial = null;

        return $this;
    }

    /**
     * reset is the collCatPicturess collection loaded partially
     *
     * @return void
     */
    public function resetPartialCatPicturess($v = true)
    {
        $this->collCatPicturessPartial = $v;
    }

    /**
     * Initializes the collCatPicturess collection.
     *
     * By default this just sets the collCatPicturess collection to an empty array (like clearcollCatPicturess());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCatPicturess($overrideExisting = true)
    {
        if (null !== $this->collCatPicturess && !$overrideExisting) {
            return;
        }
        $this->collCatPicturess = new PropelObjectCollection();
        $this->collCatPicturess->setModel('CatPictures');
    }

    /**
     * Gets an array of CatPictures objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Cats is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CatPictures[] List of CatPictures objects
     * @throws PropelException
     */
    public function getCatPicturess($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCatPicturessPartial && !$this->isNew();
        if (null === $this->collCatPicturess || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCatPicturess) {
                // return empty collection
                $this->initCatPicturess();
            } else {
                $collCatPicturess = CatPicturesQuery::create(null, $criteria)
                    ->filterByCatPictures($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCatPicturessPartial && count($collCatPicturess)) {
                      $this->initCatPicturess(false);

                      foreach($collCatPicturess as $obj) {
                        if (false == $this->collCatPicturess->contains($obj)) {
                          $this->collCatPicturess->append($obj);
                        }
                      }

                      $this->collCatPicturessPartial = true;
                    }

                    $collCatPicturess->getInternalIterator()->rewind();
                    return $collCatPicturess;
                }

                if($partial && $this->collCatPicturess) {
                    foreach($this->collCatPicturess as $obj) {
                        if($obj->isNew()) {
                            $collCatPicturess[] = $obj;
                        }
                    }
                }

                $this->collCatPicturess = $collCatPicturess;
                $this->collCatPicturessPartial = false;
            }
        }

        return $this->collCatPicturess;
    }

    /**
     * Sets a collection of CatPictures objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $catPicturess A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Cats The current object (for fluent API support)
     */
    public function setCatPicturess(PropelCollection $catPicturess, PropelPDO $con = null)
    {
        $catPicturessToDelete = $this->getCatPicturess(new Criteria(), $con)->diff($catPicturess);

        $this->catPicturessScheduledForDeletion = unserialize(serialize($catPicturessToDelete));

        foreach ($catPicturessToDelete as $catPicturesRemoved) {
            $catPicturesRemoved->setCatPictures(null);
        }

        $this->collCatPicturess = null;
        foreach ($catPicturess as $catPictures) {
            $this->addCatPictures($catPictures);
        }

        $this->collCatPicturess = $catPicturess;
        $this->collCatPicturessPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CatPictures objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CatPictures objects.
     * @throws PropelException
     */
    public function countCatPicturess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCatPicturessPartial && !$this->isNew();
        if (null === $this->collCatPicturess || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCatPicturess) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getCatPicturess());
            }
            $query = CatPicturesQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCatPictures($this)
                ->count($con);
        }

        return count($this->collCatPicturess);
    }

    /**
     * Method called to associate a CatPictures object to this object
     * through the CatPictures foreign key attribute.
     *
     * @param    CatPictures $l CatPictures
     * @return Cats The current object (for fluent API support)
     */
    public function addCatPictures(CatPictures $l)
    {
        if ($this->collCatPicturess === null) {
            $this->initCatPicturess();
            $this->collCatPicturessPartial = true;
        }
        if (!in_array($l, $this->collCatPicturess->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCatPictures($l);
        }

        return $this;
    }

    /**
     * @param	CatPictures $catPictures The catPictures object to add.
     */
    protected function doAddCatPictures($catPictures)
    {
        $this->collCatPicturess[]= $catPictures;
        $catPictures->setCatPictures($this);
    }

    /**
     * @param	CatPictures $catPictures The catPictures object to remove.
     * @return Cats The current object (for fluent API support)
     */
    public function removeCatPictures($catPictures)
    {
        if ($this->getCatPicturess()->contains($catPictures)) {
            $this->collCatPicturess->remove($this->collCatPicturess->search($catPictures));
            if (null === $this->catPicturessScheduledForDeletion) {
                $this->catPicturessScheduledForDeletion = clone $this->collCatPicturess;
                $this->catPicturessScheduledForDeletion->clear();
            }
            $this->catPicturessScheduledForDeletion[]= clone $catPictures;
            $catPictures->setCatPictures(null);
        }

        return $this;
    }

    /**
     * Clears out the collCatRatingss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Cats The current object (for fluent API support)
     * @see        addCatRatingss()
     */
    public function clearCatRatingss()
    {
        $this->collCatRatingss = null; // important to set this to null since that means it is uninitialized
        $this->collCatRatingssPartial = null;

        return $this;
    }

    /**
     * reset is the collCatRatingss collection loaded partially
     *
     * @return void
     */
    public function resetPartialCatRatingss($v = true)
    {
        $this->collCatRatingssPartial = $v;
    }

    /**
     * Initializes the collCatRatingss collection.
     *
     * By default this just sets the collCatRatingss collection to an empty array (like clearcollCatRatingss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCatRatingss($overrideExisting = true)
    {
        if (null !== $this->collCatRatingss && !$overrideExisting) {
            return;
        }
        $this->collCatRatingss = new PropelObjectCollection();
        $this->collCatRatingss->setModel('CatRatings');
    }

    /**
     * Gets an array of CatRatings objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Cats is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CatRatings[] List of CatRatings objects
     * @throws PropelException
     */
    public function getCatRatingss($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCatRatingssPartial && !$this->isNew();
        if (null === $this->collCatRatingss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCatRatingss) {
                // return empty collection
                $this->initCatRatingss();
            } else {
                $collCatRatingss = CatRatingsQuery::create(null, $criteria)
                    ->filterByCats($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCatRatingssPartial && count($collCatRatingss)) {
                      $this->initCatRatingss(false);

                      foreach($collCatRatingss as $obj) {
                        if (false == $this->collCatRatingss->contains($obj)) {
                          $this->collCatRatingss->append($obj);
                        }
                      }

                      $this->collCatRatingssPartial = true;
                    }

                    $collCatRatingss->getInternalIterator()->rewind();
                    return $collCatRatingss;
                }

                if($partial && $this->collCatRatingss) {
                    foreach($this->collCatRatingss as $obj) {
                        if($obj->isNew()) {
                            $collCatRatingss[] = $obj;
                        }
                    }
                }

                $this->collCatRatingss = $collCatRatingss;
                $this->collCatRatingssPartial = false;
            }
        }

        return $this->collCatRatingss;
    }

    /**
     * Sets a collection of CatRatings objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $catRatingss A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Cats The current object (for fluent API support)
     */
    public function setCatRatingss(PropelCollection $catRatingss, PropelPDO $con = null)
    {
        $catRatingssToDelete = $this->getCatRatingss(new Criteria(), $con)->diff($catRatingss);

        $this->catRatingssScheduledForDeletion = unserialize(serialize($catRatingssToDelete));

        foreach ($catRatingssToDelete as $catRatingsRemoved) {
            $catRatingsRemoved->setCats(null);
        }

        $this->collCatRatingss = null;
        foreach ($catRatingss as $catRatings) {
            $this->addCatRatings($catRatings);
        }

        $this->collCatRatingss = $catRatingss;
        $this->collCatRatingssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CatRatings objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CatRatings objects.
     * @throws PropelException
     */
    public function countCatRatingss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCatRatingssPartial && !$this->isNew();
        if (null === $this->collCatRatingss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCatRatingss) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getCatRatingss());
            }
            $query = CatRatingsQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCats($this)
                ->count($con);
        }

        return count($this->collCatRatingss);
    }

    /**
     * Method called to associate a CatRatings object to this object
     * through the CatRatings foreign key attribute.
     *
     * @param    CatRatings $l CatRatings
     * @return Cats The current object (for fluent API support)
     */
    public function addCatRatings(CatRatings $l)
    {
        if ($this->collCatRatingss === null) {
            $this->initCatRatingss();
            $this->collCatRatingssPartial = true;
        }
        if (!in_array($l, $this->collCatRatingss->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCatRatings($l);
        }

        return $this;
    }

    /**
     * @param	CatRatings $catRatings The catRatings object to add.
     */
    protected function doAddCatRatings($catRatings)
    {
        $this->collCatRatingss[]= $catRatings;
        $catRatings->setCats($this);
    }

    /**
     * @param	CatRatings $catRatings The catRatings object to remove.
     * @return Cats The current object (for fluent API support)
     */
    public function removeCatRatings($catRatings)
    {
        if ($this->getCatRatingss()->contains($catRatings)) {
            $this->collCatRatingss->remove($this->collCatRatingss->search($catRatings));
            if (null === $this->catRatingssScheduledForDeletion) {
                $this->catRatingssScheduledForDeletion = clone $this->collCatRatingss;
                $this->catRatingssScheduledForDeletion->clear();
            }
            $this->catRatingssScheduledForDeletion[]= clone $catRatings;
            $catRatings->setCats(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->created_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collCatPicturess) {
                foreach ($this->collCatPicturess as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCatRatingss) {
                foreach ($this->collCatRatingss as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCatPicturess instanceof PropelCollection) {
            $this->collCatPicturess->clearIterator();
        }
        $this->collCatPicturess = null;
        if ($this->collCatRatingss instanceof PropelCollection) {
            $this->collCatRatingss->clearIterator();
        }
        $this->collCatRatingss = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}

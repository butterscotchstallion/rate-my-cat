<?php

namespace PrgmrBill\RateMyCatBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use PrgmrBill\RateMyCatBundle\Model\CatPictures;
use PrgmrBill\RateMyCatBundle\Model\CatPicturesPeer;
use PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery;
use PrgmrBill\RateMyCatBundle\Model\Cats;

/**
 * @method CatPicturesQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CatPicturesQuery orderByCatId($order = Criteria::ASC) Order by the cat_id column
 * @method CatPicturesQuery orderByfilename($order = Criteria::ASC) Order by the filename column
 * @method CatPicturesQuery orderByWidth($order = Criteria::ASC) Order by the width column
 * @method CatPicturesQuery orderByHeight($order = Criteria::ASC) Order by the height column
 *
 * @method CatPicturesQuery groupById() Group by the id column
 * @method CatPicturesQuery groupByCatId() Group by the cat_id column
 * @method CatPicturesQuery groupByfilename() Group by the filename column
 * @method CatPicturesQuery groupByWidth() Group by the width column
 * @method CatPicturesQuery groupByHeight() Group by the height column
 *
 * @method CatPicturesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CatPicturesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CatPicturesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CatPicturesQuery leftJoinCatPictures($relationAlias = null) Adds a LEFT JOIN clause to the query using the CatPictures relation
 * @method CatPicturesQuery rightJoinCatPictures($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CatPictures relation
 * @method CatPicturesQuery innerJoinCatPictures($relationAlias = null) Adds a INNER JOIN clause to the query using the CatPictures relation
 *
 * @method CatPictures findOne(PropelPDO $con = null) Return the first CatPictures matching the query
 * @method CatPictures findOneOrCreate(PropelPDO $con = null) Return the first CatPictures matching the query, or a new CatPictures object populated from the query conditions when no match is found
 *
 * @method CatPictures findOneByCatId(int $cat_id) Return the first CatPictures filtered by the cat_id column
 * @method CatPictures findOneByfilename(string $filename) Return the first CatPictures filtered by the filename column
 * @method CatPictures findOneByWidth(int $width) Return the first CatPictures filtered by the width column
 * @method CatPictures findOneByHeight(int $height) Return the first CatPictures filtered by the height column
 *
 * @method array findById(int $id) Return CatPictures objects filtered by the id column
 * @method array findByCatId(int $cat_id) Return CatPictures objects filtered by the cat_id column
 * @method array findByfilename(string $filename) Return CatPictures objects filtered by the filename column
 * @method array findByWidth(int $width) Return CatPictures objects filtered by the width column
 * @method array findByHeight(int $height) Return CatPictures objects filtered by the height column
 */
abstract class BaseCatPicturesQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCatPicturesQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'PrgmrBill\\RateMyCatBundle\\Model\\CatPictures', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CatPicturesQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CatPicturesQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CatPicturesQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CatPicturesQuery) {
            return $criteria;
        }
        $query = new CatPicturesQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CatPictures|CatPictures[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CatPicturesPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CatPicturesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CatPictures A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CatPictures A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `cat_id`, `filename`, `width`, `height` FROM `cat_pictures` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new CatPictures();
            $obj->hydrate($row);
            CatPicturesPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return CatPictures|CatPictures[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|CatPictures[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CatPicturesPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CatPicturesPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CatPicturesPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CatPicturesPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatPicturesPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the cat_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCatId(1234); // WHERE cat_id = 1234
     * $query->filterByCatId(array(12, 34)); // WHERE cat_id IN (12, 34)
     * $query->filterByCatId(array('min' => 12)); // WHERE cat_id >= 12
     * $query->filterByCatId(array('max' => 12)); // WHERE cat_id <= 12
     * </code>
     *
     * @see       filterByCatPictures()
     *
     * @param     mixed $catId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByCatId($catId = null, $comparison = null)
    {
        if (is_array($catId)) {
            $useMinMax = false;
            if (isset($catId['min'])) {
                $this->addUsingAlias(CatPicturesPeer::CAT_ID, $catId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($catId['max'])) {
                $this->addUsingAlias(CatPicturesPeer::CAT_ID, $catId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatPicturesPeer::CAT_ID, $catId, $comparison);
    }

    /**
     * Filter the query on the filename column
     *
     * Example usage:
     * <code>
     * $query->filterByfilename('fooValue');   // WHERE filename = 'fooValue'
     * $query->filterByfilename('%fooValue%'); // WHERE filename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filename The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByfilename($filename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $filename)) {
                $filename = str_replace('*', '%', $filename);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CatPicturesPeer::FILENAME, $filename, $comparison);
    }

    /**
     * Filter the query on the width column
     *
     * Example usage:
     * <code>
     * $query->filterByWidth(1234); // WHERE width = 1234
     * $query->filterByWidth(array(12, 34)); // WHERE width IN (12, 34)
     * $query->filterByWidth(array('min' => 12)); // WHERE width >= 12
     * $query->filterByWidth(array('max' => 12)); // WHERE width <= 12
     * </code>
     *
     * @param     mixed $width The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByWidth($width = null, $comparison = null)
    {
        if (is_array($width)) {
            $useMinMax = false;
            if (isset($width['min'])) {
                $this->addUsingAlias(CatPicturesPeer::WIDTH, $width['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($width['max'])) {
                $this->addUsingAlias(CatPicturesPeer::WIDTH, $width['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatPicturesPeer::WIDTH, $width, $comparison);
    }

    /**
     * Filter the query on the height column
     *
     * Example usage:
     * <code>
     * $query->filterByHeight(1234); // WHERE height = 1234
     * $query->filterByHeight(array(12, 34)); // WHERE height IN (12, 34)
     * $query->filterByHeight(array('min' => 12)); // WHERE height >= 12
     * $query->filterByHeight(array('max' => 12)); // WHERE height <= 12
     * </code>
     *
     * @param     mixed $height The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function filterByHeight($height = null, $comparison = null)
    {
        if (is_array($height)) {
            $useMinMax = false;
            if (isset($height['min'])) {
                $this->addUsingAlias(CatPicturesPeer::HEIGHT, $height['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($height['max'])) {
                $this->addUsingAlias(CatPicturesPeer::HEIGHT, $height['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatPicturesPeer::HEIGHT, $height, $comparison);
    }

    /**
     * Filter the query by a related Cats object
     *
     * @param   Cats|PropelObjectCollection $cats The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CatPicturesQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCatPictures($cats, $comparison = null)
    {
        if ($cats instanceof Cats) {
            return $this
                ->addUsingAlias(CatPicturesPeer::CAT_ID, $cats->getId(), $comparison);
        } elseif ($cats instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CatPicturesPeer::CAT_ID, $cats->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCatPictures() only accepts arguments of type Cats or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CatPictures relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function joinCatPictures($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CatPictures');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CatPictures');
        }

        return $this;
    }

    /**
     * Use the CatPictures relation Cats object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PrgmrBill\RateMyCatBundle\Model\CatsQuery A secondary query class using the current class as primary query
     */
    public function useCatPicturesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCatPictures($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CatPictures', '\PrgmrBill\RateMyCatBundle\Model\CatsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CatPictures $catPictures Object to remove from the list of results
     *
     * @return CatPicturesQuery The current query, for fluid interface
     */
    public function prune($catPictures = null)
    {
        if ($catPictures) {
            $this->addUsingAlias(CatPicturesPeer::ID, $catPictures->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}

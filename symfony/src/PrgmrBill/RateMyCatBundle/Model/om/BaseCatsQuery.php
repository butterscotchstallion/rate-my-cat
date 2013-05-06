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
use PrgmrBill\RateMyCatBundle\Model\CatRatings;
use PrgmrBill\RateMyCatBundle\Model\Cats;
use PrgmrBill\RateMyCatBundle\Model\CatsPeer;
use PrgmrBill\RateMyCatBundle\Model\CatsQuery;

/**
 * @method CatsQuery orderByCatID($order = Criteria::ASC) Order by the id column
 * @method CatsQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method CatsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 *
 * @method CatsQuery groupByCatID() Group by the id column
 * @method CatsQuery groupByName() Group by the name column
 * @method CatsQuery groupByCreatedAt() Group by the created_at column
 *
 * @method CatsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CatsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CatsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CatsQuery leftJoinCatPictures($relationAlias = null) Adds a LEFT JOIN clause to the query using the CatPictures relation
 * @method CatsQuery rightJoinCatPictures($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CatPictures relation
 * @method CatsQuery innerJoinCatPictures($relationAlias = null) Adds a INNER JOIN clause to the query using the CatPictures relation
 *
 * @method CatsQuery leftJoinCatRatings($relationAlias = null) Adds a LEFT JOIN clause to the query using the CatRatings relation
 * @method CatsQuery rightJoinCatRatings($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CatRatings relation
 * @method CatsQuery innerJoinCatRatings($relationAlias = null) Adds a INNER JOIN clause to the query using the CatRatings relation
 *
 * @method Cats findOne(PropelPDO $con = null) Return the first Cats matching the query
 * @method Cats findOneOrCreate(PropelPDO $con = null) Return the first Cats matching the query, or a new Cats object populated from the query conditions when no match is found
 *
 * @method Cats findOneByName(string $name) Return the first Cats filtered by the name column
 * @method Cats findOneByCreatedAt(string $created_at) Return the first Cats filtered by the created_at column
 *
 * @method array findByCatID(int $id) Return Cats objects filtered by the id column
 * @method array findByName(string $name) Return Cats objects filtered by the name column
 * @method array findByCreatedAt(string $created_at) Return Cats objects filtered by the created_at column
 */
abstract class BaseCatsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCatsQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'PrgmrBill\\RateMyCatBundle\\Model\\Cats', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CatsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CatsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CatsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CatsQuery) {
            return $criteria;
        }
        $query = new CatsQuery();
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
     * @return   Cats|Cats[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CatsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Cats A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneByCatID($key, $con = null)
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
     * @return                 Cats A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `created_at` FROM `cats` WHERE `id` = :p0';
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
            $obj = new Cats();
            $obj->hydrate($row);
            CatsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Cats|Cats[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Cats[]|mixed the list of results, formatted by the current formatter
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
     * @return CatsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CatsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CatsPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterByCatID(1234); // WHERE id = 1234
     * $query->filterByCatID(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterByCatID(array('min' => 12)); // WHERE id >= 12
     * $query->filterByCatID(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $catID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function filterByCatID($catID = null, $comparison = null)
    {
        if (is_array($catID)) {
            $useMinMax = false;
            if (isset($catID['min'])) {
                $this->addUsingAlias(CatsPeer::ID, $catID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($catID['max'])) {
                $this->addUsingAlias(CatsPeer::ID, $catID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatsPeer::ID, $catID, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CatsPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CatsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CatsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CatsPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query by a related CatPictures object
     *
     * @param   CatPictures|PropelObjectCollection $catPictures  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CatsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCatPictures($catPictures, $comparison = null)
    {
        if ($catPictures instanceof CatPictures) {
            return $this
                ->addUsingAlias(CatsPeer::ID, $catPictures->getCatId(), $comparison);
        } elseif ($catPictures instanceof PropelObjectCollection) {
            return $this
                ->useCatPicturesQuery()
                ->filterByPrimaryKeys($catPictures->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCatPictures() only accepts arguments of type CatPictures or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CatPictures relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CatsQuery The current query, for fluid interface
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
     * Use the CatPictures relation CatPictures object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery A secondary query class using the current class as primary query
     */
    public function useCatPicturesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCatPictures($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CatPictures', '\PrgmrBill\RateMyCatBundle\Model\CatPicturesQuery');
    }

    /**
     * Filter the query by a related CatRatings object
     *
     * @param   CatRatings|PropelObjectCollection $catRatings  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CatsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCatRatings($catRatings, $comparison = null)
    {
        if ($catRatings instanceof CatRatings) {
            return $this
                ->addUsingAlias(CatsPeer::ID, $catRatings->getCatId(), $comparison);
        } elseif ($catRatings instanceof PropelObjectCollection) {
            return $this
                ->useCatRatingsQuery()
                ->filterByPrimaryKeys($catRatings->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCatRatings() only accepts arguments of type CatRatings or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CatRatings relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function joinCatRatings($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CatRatings');

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
            $this->addJoinObject($join, 'CatRatings');
        }

        return $this;
    }

    /**
     * Use the CatRatings relation CatRatings object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PrgmrBill\RateMyCatBundle\Model\CatRatingsQuery A secondary query class using the current class as primary query
     */
    public function useCatRatingsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCatRatings($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CatRatings', '\PrgmrBill\RateMyCatBundle\Model\CatRatingsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Cats $cats Object to remove from the list of results
     *
     * @return CatsQuery The current query, for fluid interface
     */
    public function prune($cats = null)
    {
        if ($cats) {
            $this->addUsingAlias(CatsPeer::ID, $cats->getCatID(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}

<?php

namespace FOQ\AlbumBundle\Model;
use Doctrine\Common\Collections\Collection;

abstract class CollectionWrapper implements Collection
{
    protected $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     * @return boolean Always TRUE.
     */
    public function add($element)
    {
        return $this->collection->add($element);
    }

    /**
     * Clears the collection, removing all elements.
     */
    public function clear()
    {
        return $this->collection->clear();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param string|integer $key The kex/index of the element to remove.
     * @return mixed The removed element or NULL, if the collection did not contain the element.
     */
    public function remove($key)
    {
        return $this->collection->remove($key);
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeElement($element)
    {
        return $this->collection->removeElement($element);
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $key The key/index to check for.
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *          FALSE otherwise.
     */
    public function containsKey($key)
    {
        return $this->collection->containsKey($key);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     * @return mixed
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *          elements in the collection.
     */
    public function getKeys()
    {
        return $this->collection->getKeys();
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *          appear in the collection.
     */
    public function getValues()
    {
        return $this->collection->getValues();
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to set.
     * @param mixed $value The element to set.
     */
    public function set($key, $value)
    {
        return $this->collection->set($key, $value);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * Sets the internal iterator to the first element in the collection and
     * returns this element.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * Sets the internal iterator to the last element in the collection and
     * returns this element.
     *
     * @return mixed
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * Moves the internal iterator position to the next element.
     *
     */
    public function next()
    {
        return $this->collection->next();
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param Closure $p The predicate.
     * @return boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(Closure $p)
    {
        return $this->collection->exists($p);
    }

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     * @return Collection A collection with the results of the filter operation.
     */
    public function filter(Closure $p)
    {
        return $this->collection->filter($p);
    }

    /**
     * Applies the given predicate p to all elements of this collection,
     * returning true, if the predicate yields true for all elements.
     *
     * @param Closure $p The predicate.
     * @return boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    public function forAll(Closure $p)
    {
        return $this->collection->forAll($p);
    }

    /**
     * Applies the given public function to each element in the collection and returns
     * a new collection with the elements returned by the public function.
     *
     * @param Closure $func
     * @return Collection
     */
    public function map(Closure $func)
    {
        return $this->collection->map($func);
    }

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param Closure $p The predicate on which to partition.
     * @return array An array with two elements. The first element contains the collection
     *               of elements where the predicate returned TRUE, the second element
     *               contains the collection of elements where the predicate returned FALSE.
     */
    public function partition(Closure $p)
    {
        return $this->collection->partition($p);
    }

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     * @return mixed The key/index of the element or FALSE if the element was not found.
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * Extract a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    public function count()
    {
        return $this->collection->count();
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->collection->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->collection->offsetUnset($offset);
    }
}

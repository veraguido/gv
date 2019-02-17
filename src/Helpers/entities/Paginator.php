<?php

namespace Gvera\Helpers\entities;

use Gvera\Exceptions\InvalidConstructorParameterException;

class Paginator
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 20;
    private $paginableObjects;
    private $page;
    private $size;
    private $totalPages;
    private $totalItems;

    /**
     * Paginator constructor.
     * @param array $paginableObjects
     * @param int $page
     * @param int $size
     * @throws InvalidConstructorParameterException
     */
    public function __construct(array $paginableObjects, $page = self::DEFAULT_PAGE, $size = self::DEFAULT_PAGE_SIZE)
    {
        $this->paginableObjects = $paginableObjects;
        $this->page = $page;
        $this->size = $size;
        $this->totalPages = floor(count($paginableObjects) / $this->size) + 1;
        $this->totalItems = count($paginableObjects);
        if (!is_iterable($this->paginableObjects)) {
            throw new InvalidConstructorParameterException('paginableObjects must be an iterable');
        }
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * @param int|void $totalItems
     */
    public function setTotalItems($totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    /**
     * @return float
     */
    public function getTotalPages(): float
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function paginate():array
    {
        $offset = ($this->getPage() - 1) * $this->getSize();
        $result = array_slice($this->paginableObjects, $offset, $this->getSize());
        return $result;
    }
}

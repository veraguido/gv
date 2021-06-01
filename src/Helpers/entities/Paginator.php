<?php

namespace Gvera\Helpers\entities;

use Gvera\Exceptions\InvalidConstructorParameterException;

class Paginator
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 20;
    private array $pageableObjects;
    private int $page;
    private int $size;
    private int $totalPages;
    private int $totalItems;

    /**
     * Paginator constructor.
     * @param array $pageableObjects
     * @param int $page
     * @param int $size
     * @throws InvalidConstructorParameterException
     */
    public function __construct(
        array $pageableObjects,
        int $page = self::DEFAULT_PAGE,
        int $size = self::DEFAULT_PAGE_SIZE
    ) {
        $this->pageableObjects = $pageableObjects;
        $this->page = $page;
        $this->size = $size;
        $this->totalPages = floor(count($pageableObjects) / $this->size) + 1;
        $this->totalItems = count($pageableObjects);
        if (!is_iterable($this->pageableObjects)) {
            throw new InvalidConstructorParameterException('paginableObjects must be an iterable');
        }
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function getTotalPages(): int
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

    public function paginate(): array
    {
        $offset = ($this->getPage() - 1) * $this->getSize();
        return array_slice($this->pageableObjects, $offset, $this->getSize());
    }
}

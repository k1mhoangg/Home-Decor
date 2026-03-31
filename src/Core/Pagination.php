<?php
namespace Core;

class Pagination
{
    private $total;
    private $perPage;
    private $currentPage;
    private $totalPages;

    public function __construct(int $total, int $perPage = 10, int $currentPage = 1)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = (int) ceil($total / $perPage);
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function hasPages(): bool
    {
        return $this->totalPages > 1;
    }
}

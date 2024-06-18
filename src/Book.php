<?php

namespace Yaser\Libmanager;

class Book
{
    private $title;
    private $author;
    private $year;
    private $availableCopies;

    public function __construct(string $title, string $author, int $year, int $availableCopies)
    {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->availableCopies = $availableCopies;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getAvailableCopies(): int
    {
        return $this->availableCopies;
    }

    public function isAvailable(): bool
    {
        return $this->availableCopies > 0;
    }

    public function borrowCopy(): bool
    {
        if ($this->isAvailable()) {
            $this->availableCopies--;
            return true;
        }
        return false;
    }

    public function returnCopy(): void
    {
        $this->availableCopies++;
    }
}
<?php

namespace Yaser\Libmanager;

class BorrowRequest
{
    private $studentUsername;
    private $bookTitle;
    private $approved = false;

    public function __construct(string $studentUsername, string $bookTitle)
    {
        $this->studentUsername = $studentUsername;
        $this->bookTitle = $bookTitle;
    }

    public function getStudentUsername(): string
    {
        return $this->studentUsername;
    }

    public function getBookTitle(): string
    {
        return $this->bookTitle;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function approve(): void
    {
        $this->approved = true;
        $student = Library::findStudentByUsername($this->studentUsername);
        $book = Library::findBook($this->bookTitle);
        if ($student && $book) {
            $student->borrowBook($book);
        }
    }
}
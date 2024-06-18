<?php

namespace Yaser\Libmanager;

class Student extends User
{
    private $borrowedBooks = [];

    public function borrowBook(Book $book)
    {
        if (count($this->borrowedBooks) < 2) {
            $this->borrowedBooks[] = $book;
            $book->borrowCopy();
            return true;
        }
        return false;
    }

    public function returnBook(Book $book)
    {
        $key = array_search($book, $this->borrowedBooks);
        if ($key !== false) {
            unset($this->borrowedBooks[$key]);
            $book->returnCopy();
            return true;
        }
        return false;
    }

    public function getBorrowedBooks()
    {
        return $this->borrowedBooks;
    }

    public function requestBook(string $bookTitle):bool
    {
        $book = Library::findBook($bookTitle);
        if ($book && $book->isAvailable() && count($this->borrowedBooks) < 2) {
            $request = new BorrowRequest($this->getUsername(), $bookTitle);
            Library::$borrowRequests[] = $request;
            return true;
        }
        return false;
    }
}
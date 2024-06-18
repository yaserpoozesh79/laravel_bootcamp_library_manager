<?php

namespace Yaser\Libmanager;

class Library
{
    public static $students = [];
    public static $books = [];
    public static $borrowRequests = [];

    public static function loginStudent(string $username, string $password):Student|null
    {
        foreach (self::$students as $student) {
            if ($student->getUsername() === $username && $student->checkPassword($password)) {
                return $student;
            }
        }
        return null;
    }

    public static function findBook(string $title)
    {
        foreach (self::$books as $book) {
            if ($book->getTitle() === $title) {
                return $book;
            }
        }
        return null;
    }

    public static function findStudentByUsername(string $username)
    {
        foreach (self::$students as $student) {
            if ($student->getUsername() === $username) {
                return $student;
            }
        }
        return null;
    }
}
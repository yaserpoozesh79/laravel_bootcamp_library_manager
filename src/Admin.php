<?php

namespace Yaser\Libmanager;

class Admin extends User
{
    public function addStudent(string $username, string $password, string $firstName, string $lastName, string $nationalCode)
    {
        $student = new Student($username, $password, $firstName, $lastName, $nationalCode);
        Library::$students[] = $student;
    }

    public function addBook(string $title, string $author, int $year, int $availableCopies)
    {
        $book = new Book($title, $author, $year, $availableCopies);
        Library::$books[] = $book;
    }

    public function saveData()
    {
        $data = [
            'admins' => array_map(function ($admin) {
                return [
                    'username' => $admin->getUsername(),
                    'firstName' => $admin->getFirstName(),
                    'lastName' => $admin->getLastName(),
                    'nationalCode' => $admin->getNationalCode(),
                ];
            }, array_filter(Library::$students, function ($user) {
                return $user instanceof Admin;
            })),
            'students' => array_map(function ($student) {
                return [
                    'username' => $student->getUsername(),
                    'firstName' => $student->getFirstName(),
                    'lastName' => $student->getLastName(),
                    'nationalCode' => $student->getNationalCode(),
                    'borrowedBooks' => array_map(function ($book) {
                        return $book->getTitle();
                    }, $student->getBorrowedBooks()),
                ];
            }, array_filter(Library::$students, function ($user) {
                return $user instanceof Student;
            })),
            'books' => array_map(function ($book) {
                return [
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthor(),
                    'year' => $book->getYear(),
                    'availableCopies' => $book->getAvailableCopies(),
                ];
            }, Library::$books),
            'borrowRequests' => array_map(function ($request) {
                return [
                    'studentUsername' => $request->getStudentUsername(),
                    'bookTitle' => $request->getBookTitle(),
                    'approved' => $request->isApproved(),
                ];
            }, Library::$borrowRequests),
        ];

        file_put_contents('library.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public function updateData()
    {
        if (file_exists('library.json')) {
            $data = json_decode(file_get_contents('library.json'), true);

            Library::$students = array_merge(
                array_map(function ($admin) {
                    return new Admin(
                        $admin['username'],
                        'password',
                        $admin['firstName'],
                        $admin['lastName'],
                        $admin['nationalCode']
                    );
                }, $data['admins'] ?? []),
                array_map(function ($student) {
                    $borrowedBooks = array_map(function ($bookTitle) {
                        return Library::findBook($bookTitle);
                    }, $student['borrowedBooks'] ?? []);

                    $studentObj = new Student(
                        $student['username'],
                        'password',
                        $student['firstName'],
                        $student['lastName'],
                        $student['nationalCode']
                    );

                    foreach ($borrowedBooks as $book) {
                        $studentObj->borrowBook($book);
                    }

                    return $studentObj;
                }, $data['students'] ?? [])
            );

            Library::$books = array_map(function ($book) {
                return new Book(
                    $book['title'],
                    $book['author'],
                    $book['year'],
                    $book['availableCopies']
                );
            }, $data['books'] ?? []);

            Library::$borrowRequests = array_map(function ($request) {
                $borrowRequest = new BorrowRequest(
                    $request['studentUsername'],
                    $request['bookTitle']
                );

                if ($request['approved']) {
                    $borrowRequest->approve();
                }

                return $borrowRequest;
            }, $data['borrowRequests'] ?? []);
        }
    }
}
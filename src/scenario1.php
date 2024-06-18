<?php

require_once __DIR__."/../vendor/autoload.php";

use Yaser\Libmanager\Admin;
use Yaser\Libmanager\Library;
use Yaser\Libmanager\Student;


/**
 * @var Admin $admin
 */
$admin = new Admin('admin', 'password', 'John', 'Doe', '1234567890');

$admin->addBook('The Great Gatsby', 'F. Scott Fitzgerald', 1925, 3);
$admin->addBook('To Kill a Mockingbird', 'Harper Lee', 1960, 2);
$admin->addBook('Pride and Prejudice', 'Jane Austen', 1813, 1);
$admin->addBook('1984', 'George Orwell', 1949, 2);

// Create a student
$admin->addStudent('jane', 'password', 'Jane', 'Smith', '0987654321');
/**
 * @var Student $student
 */
$student = Library::loginStudent('jane', 'password');

// Student requests a book
$student->requestBook('The Great Gatsby');

// Admin approves the request
$request = Library::$borrowRequests[0];
$request->approve();

// Student requests another book
$student->requestBook('To Kill a Mockingbird');

// Admin approves the request
$request = Library::$borrowRequests[1];
$request->approve();

// Student requests another book (should fail as they already have 2 borrowed books)
$success = $student->requestBook('1984');
if (!$success) {
    echo "Request failed: Student already has 2 borrowed books.\n";
}

// Save data to JSON file
$admin->saveData();

echo "Data saved to library.json\n";
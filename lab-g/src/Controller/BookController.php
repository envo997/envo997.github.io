<?php

namespace App\Controller;

use App\Model\Book;
use App\Service\Router;
use App\Service\Templating;

class BookController
{
    public function indexAction(Router $router, Templating $templating): string
    {
        $books = Book::findAll();

        return $templating->render('book/index.html.php', [
            'books' => $books
        ]);
    }

    public function showAction(Router $router, Templating $templating, int $id): string
    {
        $book = Book::find($id);

        return $templating->render('book/show.html.php', [
            'book' => $book
        ]);
    }

    public function createAction(Router $router, Templating $templating): string
    {
        $book = new Book();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $book->setTitle($_POST['title']);
            $book->setAuthor($_POST['author']);
            $book->setDescription($_POST['description']);

            $book->save();

            header('Location: /index.php?action=book-index');

            exit;
        }

        return $templating->render('book/create.html.php', [
            'book' => $book
        ]);
    }

    public function editAction(Router $router, Templating $templating, int $id): string
    {
        $book = Book::find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $book->setTitle($_POST['title']);
            $book->setAuthor($_POST['author']);
            $book->setDescription($_POST['description']);

            $book->save();

            header('Location: /index.php?action=book-index');

            exit;
        }

        return $templating->render('book/edit.html.php', [
            'book' => $book
        ]);
    }

    public function deleteAction(Router $router, int $id): void
    {
        $book = Book::find($id);

        $book->delete();

        header('Location: /index.php?action=book-index');

        exit;
    }
}
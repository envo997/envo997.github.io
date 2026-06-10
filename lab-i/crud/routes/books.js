var express = require('express');
var router = express.Router();
var Database = require('better-sqlite3');
var path = require('path');

var db = new Database(path.join(__dirname, '..', 'data.db'));

db.exec(`
  CREATE TABLE IF NOT EXISTS book (
                                    id INTEGER NOT NULL CONSTRAINT book_pk PRIMARY KEY AUTOINCREMENT,
                                    title TEXT NOT NULL,
                                    author TEXT NOT NULL,
                                    description TEXT NOT NULL
  )
`);

router.get('/', function(req, res) {
  var books = db.prepare('SELECT * FROM book').all();
  res.render('books/index', {
    title: 'Książki',
    bodyClass: 'index',
    books: books
  });
});

router.get('/new', function(req, res) {
  res.render('books/create', {
    title: 'Dodaj książkę',
    bodyClass: 'edit',
    book: {}
  });
});

router.post('/', function(req, res) {
  var title = req.body.title;
  var author = req.body.author;
  var description = req.body.description;
  db.prepare('INSERT INTO book (title, author, description) VALUES (?, ?, ?)').run(title, author, description);
  res.redirect('/books');
});

router.get('/:id', function(req, res) {
  var book = db.prepare('SELECT * FROM book WHERE id = ?').get(req.params.id);
  res.render('books/show', {
    title: book.title,
    bodyClass: 'show',
    book: book
  });
});

router.get('/:id/edit', function(req, res) {
  var book = db.prepare('SELECT * FROM book WHERE id = ?').get(req.params.id);
  res.render('books/edit', {
    title: 'Edytuj książkę',
    bodyClass: 'edit',
    book: book
  });
});

router.post('/:id/edit', function(req, res) {
  var title = req.body.title;
  var author = req.body.author;
  var description = req.body.description;
  db.prepare('UPDATE book SET title = ?, author = ?, description = ? WHERE id = ?').run(title, author, description, req.params.id);
  res.redirect('/books');
});

router.post('/:id/delete', function(req, res) {
  db.prepare('DELETE FROM book WHERE id = ?').run(req.params.id);
  res.redirect('/books');
});

module.exports = router;
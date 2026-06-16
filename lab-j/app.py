from flask import Flask, render_template, request, redirect, url_for
import sqlite3
import os

app = Flask(__name__)

DB_PATH = os.path.join(os.path.dirname(__file__), 'data.db')


def get_db():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn


def init_db():
    conn = get_db()
    conn.execute('''
        CREATE TABLE IF NOT EXISTS book (
            id INTEGER NOT NULL CONSTRAINT book_pk PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            author TEXT NOT NULL,
            description TEXT NOT NULL
        )
    ''')
    conn.commit()
    conn.close()


@app.route('/books')
def books_index():
    conn = get_db()
    books = conn.execute('SELECT * FROM book').fetchall()
    conn.close()
    return render_template('books/index.html', title='Książki', books=books)


@app.route('/books/new')
def books_new():
    return render_template('books/create.html', title='Dodaj książkę', book={})


@app.route('/books', methods=['POST'])
def books_create():
    title = request.form['title']
    author = request.form['author']
    description = request.form['description']
    conn = get_db()
    conn.execute('INSERT INTO book (title, author, description) VALUES (?, ?, ?)',
                 (title, author, description))
    conn.commit()
    conn.close()
    return redirect(url_for('books_index'))


@app.route('/books/<int:id>')
def books_show(id):
    conn = get_db()
    book = conn.execute('SELECT * FROM book WHERE id = ?', (id,)).fetchone()
    conn.close()
    return render_template('books/show.html', title=book['title'], book=book)


@app.route('/books/<int:id>/edit')
def books_edit(id):
    conn = get_db()
    book = conn.execute('SELECT * FROM book WHERE id = ?', (id,)).fetchone()
    conn.close()
    return render_template('books/edit.html', title='Edytuj książkę', book=book)


@app.route('/books/<int:id>/edit', methods=['POST'])
def books_update(id):
    title = request.form['title']
    author = request.form['author']
    description = request.form['description']
    conn = get_db()
    conn.execute('UPDATE book SET title = ?, author = ?, description = ? WHERE id = ?',
                 (title, author, description, id))
    conn.commit()
    conn.close()
    return redirect(url_for('books_index'))


@app.route('/books/<int:id>/delete', methods=['POST'])
def books_delete(id):
    conn = get_db()
    conn.execute('DELETE FROM book WHERE id = ?', (id,))
    conn.commit()
    conn.close()
    return redirect(url_for('books_index'))


@app.route('/')
def index():
    return redirect(url_for('books_index'))


if __name__ == '__main__':
    init_db()
    app.run(port=57938, debug=True)

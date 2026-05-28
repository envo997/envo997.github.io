<h1>Books</h1>

<a href="/index.php?action=book-create">Create new book</a>

<ul>
    <?php foreach ($books as $book): ?>
        <li>

            <?= htmlspecialchars($book->getTitle()); ?>
            by <?= htmlspecialchars($book->getAuthor()); ?>

            <a href="/index.php?action=book-show&id=<?= $book->getId(); ?>">
                Show
            </a>

            <a href="/index.php?action=book-edit&id=<?= $book->getId(); ?>">
                Edit
            </a>

            <a href="/index.php?action=book-delete&id=<?= $book->getId(); ?>">
                Delete
            </a>

        </li>
    <?php endforeach; ?>
</ul>
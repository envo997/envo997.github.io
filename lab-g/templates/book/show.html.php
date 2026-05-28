<h1><?= htmlspecialchars($book->getTitle()); ?></h1>

<h3>
    Author:
    <?= htmlspecialchars($book->getAuthor()); ?>
</h3>

<p>
    <?= nl2br(htmlspecialchars($book->getDescription())); ?>
</p>

<a href="/index.php?action=book-index">
    Back
</a>
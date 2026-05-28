<h1>Edit book</h1>

<form method="post">

    <div>
        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($book->getTitle()); ?>"
        >
    </div>

    <div>
        <label>Author</label>

        <input
            type="text"
            name="author"
            value="<?= htmlspecialchars($book->getAuthor()); ?>"
        >
    </div>

    <div>
        <label>Description</label>

        <textarea name="description"><?= htmlspecialchars($book->getDescription()); ?></textarea>
    </div>

    <button type="submit">
        Save
    </button>

</form>
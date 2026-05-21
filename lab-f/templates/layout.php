<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Konwerter formatow damian</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #eee; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border: 1px solid #999; }
        textarea { width: 100%; height: 120px; }
        select, button { width: 100%; padding: 8px; margin: 5px 0 15px 0; }
        pre { background: #222; color: #fff; padding: 10px; overflow-x: auto; min-height: 40px; }
    </style>
</head>
<body>

<div class="box">
    <h3>Konwerter Formatów</h3>

    <form method="POST">
        <label>Dane wejściowe (textarea):</label>
        <textarea name="input_data"><?php echo htmlspecialchars($input_data); ?></textarea>

        <label>Format wejściowy:</label>
        <select name="format_input" id="format_input">
            <option value="csv" <?php echo ($format_input === 'csv') ? 'selected' : ''; ?>>CSV (,)</option>
            <option value="ssv" <?php echo ($format_input === 'ssv') ? 'selected' : ''; ?>>SSV (;)</option>
            <option value="tsv" <?php echo ($format_input === 'tsv') ? 'selected' : ''; ?>>TSV (\t)</option>
            <option value="json" <?php echo ($format_input === 'json') ? 'selected' : ''; ?>>JSON</option>
            <option value="yaml" <?php echo ($format_input === 'yaml') ? 'selected' : ''; ?>>YAML</option>
        </select>

        <label>Format wyjściowy:</label>
        <select name="format_output" id="format_output">
            <option value="csv" <?php echo ($format_output === 'csv') ? 'selected' : ''; ?>>CSV (,)</option>
            <option value="ssv" <?php echo ($format_output === 'ssv') ? 'selected' : ''; ?>>SSV (;)</option>
            <option value="tsv" <?php echo ($format_output === 'tsv') ? 'selected' : ''; ?>>TSV (\t)</option>
            <option value="json" <?php echo ($format_output === 'json') ? 'selected' : ''; ?>>JSON</option>
            <option value="yaml" <?php echo ($format_output === 'yaml') ? 'selected' : ''; ?>>YAML</option>
        </select>

        <button type="submit">Konwertuj i zapisz stan</button>
    </form>

    <label>Dane wyjściowe (element pre):</label>
    <pre><?php echo htmlspecialchars($result); ?></pre>
</div>

</body>
</html>
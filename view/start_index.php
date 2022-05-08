<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<form method="post" action="<?php echo __SITE_URL . '/wordle.php?rt=game' ?>">
    <label for="name">Ime:</label>
    <input type="text" name="name" id="name"></input>

    <br />
    <br />

    <label for="length">Tezina:</label>
    <select name="length" id="length">
        <option value="5" selected>5 slova</option>
        <option value="6">6 slova</option>
        <option value="7">7 slova</option>
    </select>
    <button type="submit">Zapocni</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>

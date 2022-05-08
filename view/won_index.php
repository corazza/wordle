<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<h2>Cestitke <?php echo $name ?>! </h2>

<?php print_table($length, $attempts, $green_color, $brown_color) ?>

<br />

<form method="post" action="<?php echo __SITE_URL; ?>/wordle.php?rt=game/newgame">
    <button type="submit">Nova igra</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>

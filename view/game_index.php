<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<h2> Bok <?php echo $name ?> </h2>

Duljina: <?php echo $length ?>
<br/>
Broj pokusaja: <?php echo count($attempts) + $sup_attempts ?>
<br/>
<br/>

<?php print_big_hints($length, $big_hints) ?>

<?php print_hints($length, $hints) ?>

<?php print_table($length, $attempts, $green_color, $brown_color) ?>

<form method="post" action="<?php echo __SITE_URL; ?>/wordle.php?rt=game/action">
    <br/>
    <input type="text" name="attempt" />
    <br/>
    <br/>
    <input type="radio" name="akcija" value="pogodi" checked>Probaj pogoditi rijec</input>
    <br/>
    <input type="radio" name="akcija" value="hint">Hint</input>
    <br/>
    <input type="radio" name="akcija" value="veliki_hint">Veliki hint</input>
    <br/>
    <br/>
    <button type="submit">Izvrsi akciju</button>
</form>

<br />

<?php 
if ($length_error) {
    echo '<div class="error">Kriva duljina!</div>';
}
?>

<br />

<form method="post" action="<?php echo __SITE_URL; ?>/wordle.php?rt=game/newgame">
    <button type="submit">Nova igra</button>
</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>

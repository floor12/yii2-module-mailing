<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26.11.2016
 * Time: 18:11
 */

?>

<li<?php if (isset($item['active']) && $item['active']) echo " class='active'"; ?>>
    <a href="<?= $item['href'] ?><?= $linkPostfix ? "/" . $linkPostfix : NULL ?>">
        <?= $item['name'] ?>
    </a>
</li>

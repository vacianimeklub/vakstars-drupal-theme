<?php
if ($classes) {
  $classes = ' class="'. $classes . ' media box"';
}
?>
<?php if( theme_get_setting('mothership_poorthemers_helper') ){ ?>
<!-- comment.tpl.php -->
<?php } ?>
<article<?php print $classes; ?><?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <h3<?php print $title_attributes; ?>>
      <?php print $title; ?>
    </h3>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <div class="media-left">
    <div class="author-info">
      <?php print $picture; ?>
      <figcaption><?php print $author; ?></figcaption>
    </div>
  </div>

  <div class="media-content content"<?php print $content_attributes; ?>>
    <small>
        <span class="date"><time><?php print $created; ?></time></span>
        <?php if ($created != $changed): ?>
        | <span class="changed"><?php print t('Last modified'); ?> <time><?php print $changed; ?></time></span>
        <? endif ?>
        | <span><?php print $permalink; ?></span>
    </small>

    <div class="content-body">
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['links']);
      print render($content);
    ?>
    </div>

    <?php if ($signature): ?>
      <aside class="user-signature">
        <?php print $signature; ?>
      </aside>
    <?php endif; ?>
  </div>

  <div class="media-right">
      <?php if ($new): ?>
        <mark class="tag is-warning"><?php print $new; ?></mark>
      <?php endif; ?>
      <div class="comment-links">
      <?php print render($content['links']) ?>
      </div>
  </div>
</article>
<?php if( theme_get_setting('mothership_poorthemers_helper') ){ ?>
<!-- comment.tpl.php -->
<?php } ?>


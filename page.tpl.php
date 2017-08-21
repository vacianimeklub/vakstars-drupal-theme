<?php
//kpr(get_defined_vars());
//kpr($theme_hook_suggestions);
//template naming
//page--[CONTENT TYPE].tpl.php
?>
<?php if( theme_get_setting('mothership_poorthemers_helper') ){ ?>
<!-- page.tpl.php-->
<?php } ?>

<?php print $mothership_poorthemers_helper; ?>

<div class="container">
  <header role="banner">
    <?php if($page['header']): ?>
      <div class="header-region">
        <?php print render($page['header']); ?>
      </div>
    <?php endif; ?>

  </header>

  <div class="page columns">

    <div role="main" id="main-content" class="is-three-quarters column">
      <div class="content-title content">
        <?php print render($title_prefix); ?>
        <?php if ($title): ?>
          <h1><?php print $title; ?></h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
      </div>

      <?php print $breadcrumb; ?>

      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>

      <?php if (isset($tabs['#primary'][0]) || isset($tabs['#secondary'][0])): ?>
        <nav class="tabs"><?php print render($tabs); ?></nav>
      <?php endif; ?>

      <?php if($page['highlighted'] OR $messages){ ?>
        <div class="drupal-messages">
        <?php print render($page['highlighted']); ?>
        <?php print $messages; ?>
        </div>
      <?php } ?>

      <?php print render($page['content_pre']); ?>

      <?php print render($page['content']); ?>

      <?php print render($page['content_post']); ?>

    </div><!-- /main-->

    <?php if ($page['sidebar_first']): ?>
      <div id="sidebar-first" class="sidebar-first">
      <?php print render($page['sidebar_first']); ?>
      </div>
    <?php endif; ?>

    <?php if ($page['sidebar_second']): ?>
      <div id="sidebar-second" class="sidebar-second">
        <?php print render($page['sidebar_second']); ?>
      </div>
    <?php endif; ?>
  </div><!-- /page-->

  <footer role="contentinfo">
    <?php print render($page['footer']); ?>
  </footer>
</div>

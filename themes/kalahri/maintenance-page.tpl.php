<?php
	$vars = get_defined_vars();
	$view = get_artx_drupal_view();
	$view->print_head($vars);

	if (isset($page))
		foreach (array_keys($page) as $name)
				$$name = & $page[$name];
	
	$art_sidebar_left = isset($sidebar_left) && !empty($sidebar_left) ? $sidebar_left : NULL;
	$art_sidebar_right = isset($sidebar_right) && !empty($sidebar_right) ? $sidebar_right : NULL;
	if (!isset($vnavigation_left)) $vnavigation_left = NULL;
	if (!isset($vnavigation_right)) $vnavigation_right = NULL;
	$tabs = (isset($tabs) && !(empty($tabs))) ? '<ul class="arttabs_primary">'.render($tabs).'</ul>' : NULL;
	$tabs2 = (isset($tabs2) && !(empty($tabs2))) ?'<ul class="arttabs_secondary">'.render($tabs2).'</ul>' : NULL;
?>

<div id="art-main">
    <div class="cleared reset-box"></div>
<div class="art-header">
<div class="art-header-position">
    <div class="art-header-wrapper">
        <div class="cleared reset-box"></div>
        <div class="art-header-inner">
<div class="art-logo">
     <?php   if (!empty($site_name)) { echo '<h1 class="art-logo-name"><a href="'.check_url($front_page).'" title = "'.$site_name.'">'.$site_name.'</a></h1>'; } ?>
</div>

        </div>
    </div>
</div>


</div>
<div class="cleared reset-box"></div>
<div class="art-box art-sheet">
    <div class="art-box-body art-sheet-body">
<?php if (!empty($banner1)) { echo '<div id="banner1">'.render($banner1).'</div>'; } ?>
<?php echo art_placeholders_output(render($top1), render($top2), render($top3)); ?>
<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
<?php if (!empty($art_sidebar_left) || !empty($vnavigation_left))
echo art_get_sidebar($art_sidebar_left, $vnavigation_left, 'art-sidebar1'); ?>
<?php echo '<div class="art-layout-cell art-content">'; ?>
<?php if (!empty($banner2)) { echo '<div id="banner2">'.render($banner2).'</div>'; } ?>
<?php if ((!empty($user1)) && (!empty($user2))) : ?>
<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo render($user1); ?></td>
<td><?php echo render($user2); ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user1)) { echo '<div id="user1">'.render($user1).'</div>'; }?>
<?php if (!empty($user2)) { echo '<div id="user2">'.render($user2).'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner3)) { echo '<div id="banner3">'.render($banner3).'</div>'; } ?>
<?php if (!empty($breadcrumb)): ?>
<div class="art-box art-post">
    <div class="art-box-body art-post-body">
<div class="art-post-inner art-article">
<div class="art-postcontent">
<?php { echo $breadcrumb; } ?>

</div>
<div class="cleared"></div>

</div>

		<div class="cleared"></div>
    </div>
</div>
<?php endif; ?>
<div class="art-box art-post">
    <div class="art-box-body art-post-body">
<div class="art-post-inner art-article">
<div class="art-postcontent">
<?php print render($title_prefix); ?>
<?php if ($title): ?>
  <h1 class="title" id="page-title">
    <?php print $title; ?>
  </h1>
<?php endif; ?>
<?php print render($title_suffix); ?><?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>
<?php if (isset($mission) && !empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo render($help); } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php echo art_content_replace(render($content)); ?>

</div>
<div class="cleared"></div>

</div>

		<div class="cleared"></div>
    </div>
</div>
<?php if (!empty($banner4)) { echo '<div id="banner4">'.render($banner4).'</div>'; } ?>
<?php if (!empty($user3) && !empty($user4)) : ?>
<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo render($user3); ?></td>
<td><?php echo render($user4); ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user3)) { echo '<div id="user1">'.render($user3).'</div>'; }?>
<?php if (!empty($user4)) { echo '<div id="user2">'.render($user4).'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner5)) { echo '<div id="banner5">'.render($banner5).'</div>'; } ?>
</div>

        </div>
    </div>
</div>
<div class="cleared"></div>

<?php echo art_placeholders_output(render($bottom1), render($bottom2), render($bottom3)); ?>
<?php if (!empty($banner6)) { echo '<div id="banner6">'.render($banner6).'</div>'; } ?>
<div class="art-footer">
    <div class="art-footer-body">
        <?php 
            if (!empty($feed_icons)) {
                echo $feed_icons;
            }
            else {
                echo '<a href="'.url("rss.xml").'" class="art-rss-tag-icon"></a>';
            }
        ?>
                <div class="art-footer-text">
                        <?php
                    $footer = render($footer_message);
                    if (!empty($footer) && (trim($footer) != '')) {
                        echo $footer;
                    }
                    else {
                        ob_start(); ?>
<p><a href="#">Link1</a> | <a href="#">Link2</a> | <a href="#">Link3</a></p><p>Copyright © 2012. All Rights Reserved.</p>

                        <?php echo str_replace('%YEAR%', date('Y'), ob_get_clean());
                    }
                ?>
                <?php if (!empty($copyright)) { echo '<div id="copyright">'.render($copyright).'</div>'; } ?>
                </div>
		<div class="cleared"></div>
    </div>
</div>
		<div class="cleared"></div>
    </div>
</div>
<div class="cleared"></div>
<p class="art-page-footer"><?php echo t('Powered by').' <a href="http://drupal.org/">'.t('Drupal').'</a> '.t('and').' <a href="http://www.artisteer.com/?p=drupal_themes">Drupal Theme</a> '.t('created with').' Artisteer'; ?>.</p>

    <div class="cleared"></div>
</div>


<?php $view->print_closure($vars); ?>
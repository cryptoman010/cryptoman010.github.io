<?php
/**
 * This file is part of the TinyMCE Advanced WordPress plugin and is released under the same license.
 * For more information please see tinymce-advanced.php.
 *
 * Copyright (c) 2007-2016 Andrew Ozz. All rights reserved.
 */

if ( ! defined( 'TADV_ADMIN_PAGE' ) ) {
	exit;
}

// TODO
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Invalid request' );
}

$message = '';

$this->set_paths();
$imgpath = TADV_URL . 'images/';
$tadv_options_updated = false;
$settings = $admin_settings = array();

if ( isset( $_POST['tadv-save'] ) ) {
	check_admin_referer( 'tadv-save-buttons-order' );
	$this->save_settings();
} elseif ( isset( $_POST['tadv-restore-defaults'] ) ) {
	check_admin_referer( 'tadv-save-buttons-order' );

	// TODO: only for admin || SA
	$this->admin_settings = $this->get_default_admin_settings();
	update_option( 'tadv_admin_settings', $this->get_default_admin_settings() );

	// TODO: all users that can have settings
	$this->user_settings = $this->get_default_user_settings();
	update_option( 'tadv_settings', $this->get_default_user_settings() );

	$message = '<div class="updated notice notice-success is-dismissible"><p>' .  __('Default settings restored.', 'tinymce-advanced') . '</p></div>';
} elseif ( isset( $_POST['tadv-export-settings'] ) ) {
	check_admin_referer( 'tadv-save-buttons-order' );

	$this->load_settings();
	$output = array( 'settings' => $this->user_settings );

	// TODO: only admin || SA
	$output['admin_settings'] = $this->admin_settings;

	?>
	<div class="wrap tinymce-advanced">
	<h2><?php _e( 'TinyMCE Advanced Settings Export', 'tinymce-advanced' ); ?></h2>

	<div class="tadv-import-export">
	<p>
	<?php _e( 'The settings are exported as a JSON encoded string.', 'tinymce-advanced' ); ?>
	<?php _e( 'Please copy the content and save it in a <b>text</b> (.txt) file, using a plain text editor like Notepad.', 'tinymce-advanced' ); ?>
	<?php _e( 'It is important that the export is not changed in any way, no spaces, line breaks, etc.', 'tinymce-advanced' ); ?>
	</p>

	<form action="">
		<p><textarea readonly="readonly" id="tadv-export"><?php echo json_encode( $output ); ?></textarea></p>
		<p><button type="button" class="button" id="tadv-export-select"><?php _e( 'Select All', 'tinymce-advanced' ); ?></button></p>
	</form>
	<p><a href=""><?php _e( 'Back to Editor Settings', 'tinymce-advanced' ); ?></a></p>
	</div>
	</div>
	<?php

	return;
} elseif ( isset( $_POST['tadv-import-settings'] ) ) {
	check_admin_referer( 'tadv-save-buttons-order' );

	// TODO: all users
	?>
	<div class="wrap tinymce-advanced">
	<h2><?php _e( 'TinyMCE Advanced Settings Import', 'tinymce-advanced' ); ?></h2>

	<div class="tadv-import-export">
	<p><?php _e( 'The settings are imported from a JSON encoded string. Please paste the exported string in the text area below.', 'tinymce-advanced' );	?></p>

	<form action="" method="post">
		<p><textarea id="tadv-import" name="tadv-import"></textarea></p>
		<p>
			<button type="button" class="button" id="tadv-import-verify"><?php _e( 'Verify', 'tinymce-advanced' ); ?></button>
			<input type="submit" class="button button-primary alignright" name="tadv-import-submit" value="<?php _e( 'Import', 'tinymce-advanced' ); ?>" />
		</p>
		<?php wp_nonce_field('tadv-import'); ?>
		<p id="tadv-import-error"></p>
	</form>
	<p><a href=""><?php _e( 'Back to Editor Settings', 'tinymce-advanced' ); ?></a></p>
	</div>
	</div>
	<?php

	return;
} elseif ( isset( $_POST['tadv-import-submit'] ) && ! empty( $_POST['tadv-import'] ) && is_string( $_POST['tadv-import'] ) ) {
	check_admin_referer( 'tadv-import' );

	// TODO: all users
	$import = json_decode( trim( wp_unslash( $_POST['tadv-import'] ) ), true );

	if ( ! is_array( $import ) ) {
		$message = '<div class="error"><p>' .  __( 'Importing of settings failed.', 'tinymce-advanced' ) . '</p></div>';
	} else {
		$this->save_settings( $import );
	}
}

if ( empty( $_POST ) ) {
	$this->check_plugin_version();
}

$this->load_settings();

if ( empty( $this->toolbar_1 ) && empty( $this->toolbar_2 ) && empty( $this->toolbar_3 ) && empty( $this->toolbar_4 ) ) {
	$message = '<div class="error"><p>' . __( 'ERROR: All toolbars are empty. Default settings loaded.', 'tinymce-advanced' ) . '</p></div>';

	$this->admin_settings = $this->get_default_admin_settings();
	$this->user_settings = $this->get_default_user_settings();
	$this->load_settings();
}

$all_buttons = $this->get_all_buttons();

?>
<div class="wrap tinymce-advanced" id="contain">
<h2><?php _e( 'Editor Settings', 'tinymce-advanced' ); ?></h2>
<?php

// TODO admin || SA
$this->warn_if_unsupported();

if ( isset( $_POST['tadv-save'] ) && empty( $message ) ) {
	?><div class="updated notice notice-success is-dismissible"><p><?php _e( 'Settings saved.', 'tinymce-advanced' ); ?></p></div><?php
} else {
	echo $message;
}

?>
<form id="tadvadmin" method="post" action="">

<p class="tadv-submit tadv-submit-top">
	<input class="button-primary button-large top-button" type="submit" name="tadv-save" value="<?php _e( 'Save Changes', 'tinymce-advanced' ); ?>" />
</p>

<div id="classic-editor">
<h3><?php _e( 'Toolbars for the Classic Editor (TinyMCE)', 'tinymce-advanced' ); ?></h3>

<div id="tadvzones">
<p>
	<input type="checkbox" name="options[]" id="menubar" value="menubar" <?php if ( $this->check_user_setting( 'menubar' ) ) { echo ' checked="checked"'; } ?>>
	<label for="menubar"><?php _e( 'Enable the editor menu.', 'tinymce-advanced' ); ?></label>
</p>

<div class="tadv-mce-menu tadv-classic-editor mce-container mce-menubar mce-toolbar mce-first mce-stack-layout-item
	<?php if ( $this->check_user_setting( 'menubar' ) ) { echo ' enabled'; } ?>">
	<div class="mce-container-body mce-flow-layout">
		<div class="mce-widget mce-btn mce-menubtn mce-first mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">File</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Edit</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Insert</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item mce-toolbar-item">
			<button type="button">
				<span class="tadv-translate">View</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Format</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Table</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-last mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Tools</span>
				<i class="mce-caret"></i>
			</button>
		</div>
	</div>
</div>

<?php

$mce_text_buttons = array( 'styleselect', 'formatselect', 'fontselect', 'fontsizeselect' );
$all_buttons_classic = $all_buttons;

for ( $i = 1; $i < 5; $i++ ) {
	$toolbar = "toolbar_$i";

	?>
	<div class="tadvdropzone mce-toolbar">
	<ul id="toolbar_<?php echo $i; ?>" class="container">
	<?php

	foreach( $this->$toolbar as $button ) {
		if ( strpos( $button, 'separator' ) !== false || in_array( $button, array( 'moveforward', 'movebackward', 'absolute' ) ) ) {
			continue;
		}

		if ( isset( $all_buttons_classic[ $button ] ) ) {
			$name = $all_buttons_classic[ $button ];
			unset( $all_buttons_classic[ $button ] );
		} else {
			continue;
		}

		?>
		<li class="tadvmodule" id="<?php echo $button; ?>">
			<?php

			if ( in_array( $button, $mce_text_buttons, true ) ) {
				?>
				<div class="tadvitem mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox">
					<div class="the-button">
						<span class="descr"><?php echo $name; ?></span>
						<i class="mce-caret"></i>
						<input type="hidden" class="tadv-button" name="toolbar_<?php echo $i; ?>[]" value="<?php echo $button; ?>" />
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="tadvitem">
					<i class="mce-ico mce-i-<?php echo $button; ?>" title="<?php echo $name; ?>"></i>
					<span class="descr"><?php echo $name; ?></span>
					<input type="hidden" class="tadv-button" name="toolbar_<?php echo $i; ?>[]" value="<?php echo $button; ?>" />
				</div>
				<?php
			}

			?>
		</li>
		<?php

	}

	?>
	</ul></div>
	<?php
}

?>
</div>

<p><?php _e( 'Drop buttons in the toolbars, or drag the buttons to rearrange them.', 'tinymce-advanced' ); ?></p>

<div class="unuseddiv">
	<h4><?php _e( 'Unused Buttons', 'tinymce-advanced' ); ?></h4>
	<div>
		<ul id="unused" class="unused container">
		<?php

		foreach( $all_buttons_classic as $button => $name ) {
			if ( strpos( $button, 'separator' ) !== false ) {
				continue;
			}

			?>
			<li class="tadvmodule" id="<?php echo $button; ?>">
				<?php

				if ( in_array( $button, $mce_text_buttons, true ) ) {
					?>
					<div class="tadvitem mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox">
						<div class="the-button">
							<span class="descr"><?php echo $name; ?></span>
							<i class="mce-caret"></i>
							<input type="hidden" class="tadv-button" name="unused[]" value="<?php echo $button; ?>" />
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="tadvitem">
						<i class="mce-ico mce-i-<?php echo $button; ?>" title="<?php echo $name; ?>"></i>
						<span class="descr"><?php echo $name; ?></span>
						<input type="hidden" class="tadv-button" name="unused[]" value="<?php echo $button; ?>" />
					</div>
					<?php
				}

				?>
			</li>
			<?php
		}

		?>
		</ul>
	</div><!-- /highlighted -->
</div>
</div><!-- /classic-editor -->

<div id="block-editor">
<h3><?php _e( 'Toolbars for the Classic Blocks in the Block Editor (Gutenberg)', 'tinymce-advanced' ); ?></h3>

<p>
	<?php _e( 'The toolbars in the Classic Block are narrower and show on focus.', 'tinymce-advanced' ); ?>
	<?php _e( 'For best results enable the menu and add only essential buttons.', 'tinymce-advanced' ); ?>
	<?php _e( 'The buttons will wrap around depending on the width of the toolbar.', 'tinymce-advanced' ); ?>
</p>

<p>
	<input type="checkbox" name="options[]" id="menubar_block" value="menubar_block" <?php if ( $this->check_user_setting( 'menubar_block' ) ) { echo ' checked'; } ?>>
	<label for="menubar_block"><?php _e( 'Enable the editor menu (recommended).', 'tinymce-advanced' ); ?></label>
</p>

<div class="tadv-mce-menu tadv-block-editor mce-container mce-menubar mce-toolbar mce-first mce-stack-layout-item
	<?php if ( $this->check_user_setting( 'menubar' ) ) { echo ' enabled'; } ?>">
	<div class="mce-container-body mce-flow-layout">
		<div class="mce-widget mce-btn mce-menubtn mce-first mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">File</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Edit</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Insert</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item mce-toolbar-item">
			<button type="button">
				<span class="tadv-translate">View</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Format</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Table</span>
				<i class="mce-caret"></i>
			</button>
		</div>
		<div class="mce-widget mce-btn mce-menubtn mce-last mce-flow-layout-item">
			<button type="button">
				<span class="tadv-translate">Tools</span>
				<i class="mce-caret"></i>
			</button>
		</div>
	</div>
</div>

<div class="tadvdropzone tadv-block-editor mce-toolbar">
	<ul id="toolbar_classic_block" class="container-classic-block">
	<?php

	$all_buttons_block = $all_buttons;

	// Remove the toolbar-toggle
	unset( $all_buttons_block['wp_adv'] );

	foreach( $this->toolbar_classic_block as $button_id ) {
		$name = '';

		if ( strpos( $button, 'separator' ) !== false || in_array( $button, array( 'moveforward', 'movebackward', 'absolute' ) ) ) {
			continue;
		}

		if ( isset( $all_buttons_block[ $button_id ] ) ) {
			$name = $all_buttons_block[ $button_id ];
			unset( $all_buttons_block[ $button_id ] );
		} else {
			continue;
		}

		?>
		<li class="tadvmodule" id="<?php echo $button_id; ?>">
			<?php

			if ( in_array( $button_id, $mce_text_buttons, true ) ) {
				?>
				<div class="tadvitem mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox">
					<div class="the-button">
						<span class="descr"><?php echo $name; ?></span>
						<i class="mce-caret"></i>
						<input type="hidden" class="tadv-button" name="toolbar_classic_block[]" value="<?php echo $button_id; ?>" />
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="tadvitem">
					<i class="mce-ico mce-i-<?php echo $button_id; ?>" title="<?php echo $name; ?>"></i>
					<span class="descr"><?php echo $name; ?></span>
					<input type="hidden" class="tadv-button" name="toolbar_classic_block[]" value="<?php echo $button_id; ?>" />
				</div>
				<?php
			}

			?>
		</li>
		<?php
	}

	?>
	</ul>
</div>

<p><?php _e( 'Drop buttons in the toolbars, or drag the buttons to rearrange them.', 'tinymce-advanced' ); ?></p>

<div class="unuseddiv">
	<h4><?php _e( 'Unused Buttons for the Classic Block', 'tinymce-advanced' ); ?></h4>
	<div>
		<ul id="unused-classic-block" class="unused container-classic-block">
		<?php

		foreach( $all_buttons_block as $button_id => $name ) {
			if ( strpos( $button, 'separator' ) !== false ) {
				continue;
			}

			?>
			<li class="tadvmodule" id="<?php echo $button_id; ?>">
				<?php

				if ( in_array( $button_id, $mce_text_buttons, true ) ) {
					?>
					<div class="tadvitem mce-widget mce-btn mce-menubtn mce-fixed-width mce-listbox">
						<div class="the-button">
							<span class="descr"><?php echo $name; ?></span>
							<i class="mce-caret"></i>
							<input type="hidden" class="tadv-button" name="unused-classic-block[]" value="<?php echo $button_id; ?>" />
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="tadvitem">
						<i class="mce-ico mce-i-<?php echo $button_id; ?>" title="<?php echo $name; ?>"></i>
						<span class="descr"><?php echo $name; ?></span>
						<input type="hidden" class="tadv-button" name="unused-classic-block[]" value="<?php echo $button_id; ?>" />
					</div>
					<?php
				}

				?>
			</li>
			<?php
		}

		?>
		</ul>
	</div><!-- /highlight -->
</div><!-- /unuseddiv -->
</div><!-- /block-editor -->

<div class="advanced-options">
	<h3><?php _e( 'Options', 'tinymce-advanced' ); ?></h3>

	<div>
		<input type="checkbox" name="options[]" value="no_merge_toolbars" id="merge_toolbars" <?php if ( ! $this->check_user_setting('no_merge_toolbars') ) echo ' checked'; ?> />
		<label for="merge_toolbars"><?php _e( 'Append all buttons to the top toolbar in the Classic and Classic Paragraph blocks.', 'tinymce-advanced' ); ?></label>
		<p><?php _e( 'This affects buttons that are added by other plugins. These buttons will be appended to the top toolbar row instead of forming second, third, and forth rows.', 'tinymce-advanced' ); ?></p>
	</div>

	<div>
		<input type="checkbox" name="options[]" value="advlist" id="advlist" <?php if ( $this->check_user_setting('advlist') ) echo ' checked'; ?> />
		<label for="advlist"><?php _e( 'List Style Options', 'tinymce-advanced' ); ?></label>
		<p>
			<?php _e( 'Enable more list options: upper or lower case letters for ordered lists, disk or square for unordered lists, etc.', 'tinymce-advanced' ); ?>
		</p>
	</div>

	<div>
		<input type="checkbox" name="options[]" value="contextmenu" id="contextmenu" <?php if ( $this->check_user_setting('contextmenu') ) echo ' checked'; ?> />
		<label for="contextmenu"><?php _e( 'Context Menu', 'tinymce-advanced' ); ?></label>
		<p><?php _e( 'Replace the browser context (right-click) menu.', 'tinymce-advanced' ); ?></p>
	</div>

	<div>
		<input type="checkbox" name="options[]" value="advlink" id="advlink" <?php if ( $this->check_user_setting('advlink') ) echo ' checked'; ?> />
		<label for="advlink"><?php _e( 'Alternative link dialog', 'tinymce-advanced' ); ?></label>
		<p><?php _e( 'Open the TinyMCE link dialog when using the link button on the toolbar or the link menu item.', 'tinymce-advanced' ); ?></p>
	</div>

	<div>
		<input type="checkbox" name="options[]" value="fontsize_formats" id="fontsize_formats" <?php if ( $this->check_user_setting( 'fontsize_formats' ) ) echo ' checked="checked"'; ?> />
		<label for="fontsize_formats"><?php _e( 'Font sizes', 'tinymce-advanced' ); ?></label>
		<p><?php printf( __( 'Replace the size setting available for fonts with: %s.', 'tinymce-advanced' ), $this->fontsize_formats ); ?></p>
	</div>
</div>
<?php

if ( ! is_multisite() || current_user_can( 'manage_sites' ) ) {
	$preselect = function_exists( 'use_block_editor_for_post_type' ) ? '' : '<p>' . __( 'You can pre-select this option. It will be enabled as soon as you upgrade to WordPress 5.0 or later.', 'tinymce-advanced' ) . '</p>';

	?>
	<div class="advanced-options">
	<h3><?php _e( 'Advanced Options', 'tinymce-advanced' ); ?></h3>
	<div>
		<input type="checkbox" name="admin_options[]" value="no_hybrid_mode" id="no_hybrid_mode" <?php if ( ! $this->check_admin_setting( 'no_hybrid_mode' ) ) echo ' checked'; ?> />
		<label for="no_hybrid_mode"><?php _e( 'Hybrid Block Editor Mode', 'tinymce-advanced' ); ?></label>
		<p><strong><?php _e( 'Brings the best of both editors together!', 'tinymce-advanced' ); ?></strong></p>
		<p>
			<?php _e( 'You can continue to use the familiar TinyMCE Editor in the (new and improved) &#8220;Classic&#8221; Paragraph Block, and at the same time have access to all blocks and new goodies of the Block Editor.', 'tinymce-advanced' ); ?>
		</p>
		<p>
			<?php _e( 'Selecting this option makes the Classic Block in the Block Editor more prominent and adds a new &#8220;Classic Paragraph Block&#8221; that includes the TinyMCE Editor and replaces the Paragraph Block.', 'tinymce-advanced' ); ?>
			<?php _e( 'Most existing TinyMCE plugins and add-ons will continue to work in the Classic Paragraph Block as well as in the Classic Block.', 'tinymce-advanced' ); ?>
			<?php _e( 'This makes the Block Editor more familiar, easier to use, and much more compatible with your existing workflow.', 'tinymce-advanced' ); ?>
		</p>
		<p>
			<?php _e( 'Note: The Classic Paragraph Block and the Classic Block are interchangeable. When saving a post that contains several successive Classic Paragraph blocks, they will be merged into one Classic Block.', 'tinymce-advanced' ); ?>
			<?php _e( 'This will keep the blocks compatible should you decide to uninstall this plugin or turn the above option off.', 'tinymce-advanced' ); ?>
		</p>
		<?php echo $preselect ?>
	</div>
	<div>
		<?php

		if ( function_exists( 'is_plugin_active' ) && ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {

			?>
			<input type="checkbox" name="admin_options[]" value="replace_block_editor" id="replace_block_editor" <?php if ( $this->check_admin_setting( 'replace_block_editor' ) ) echo ' checked'; ?> />
			<label for="replace_block_editor"><?php _e( 'Replace the Block Editor with the Classic Editor', 'tinymce-advanced' ); ?></label>
			<p>
				<?php _e( 'Selecting this option will restore the previous (&#8220;classic&#8221;) editor and the previous Edit Post screen.', 'tinymce-advanced' ); ?>
				<?php _e( 'It will allow you to use other plugins that enhance that editor, add old-style Meta Boxes, or in some way depend on the previous Edit Post screen.', 'tinymce-advanced' ); ?>
			</p>
			<?php echo $preselect ?>
			<p>
				<?php

				$text = __( 'If you prefer to use both editors side by side, do not enable this option. It is better to install the %1$sClassic Editor plugin%2$s.', 'tinymce-advanced' );
				$url = 'https://wordpress.org/plugins/classic-editor/';
				printf( $text, '<a href="' . esc_url( $url ) . '">', '</a>' );

				?>
			</p>
			<?php
		}

		?>
	</div>
	<div>
		<input type="checkbox" name="admin_options[]" value="no_autop" id="no_autop" <?php if ( $this->check_admin_setting( 'no_autop' ) ) echo ' checked'; ?> />
		<label for="no_autop"><?php _e( 'Keep paragraph tags', 'tinymce-advanced' ); ?></label>
		<p><strong><?php _e( 'Recommended for better compatibility with the Block Editor (Gutenberg).', 'tinymce-advanced' ); ?></strong></p>
		<p>
			<?php _e( 'Stop removing the &lt;p&gt; and &lt;br&gt; tags when saving and show them in the Text editor.', 'tinymce-advanced' ); ?>
			<?php _e( 'This will make it possible to use more advanced coding in the Text editor without the back-end filtering affecting it much.', 'tinymce-advanced' ); ?>
			<?php _e( 'However it may behave unexpectedly in rare cases, so test it thoroughly before enabling it permanently.', 'tinymce-advanced' ); ?>
			<?php _e( 'Line breaks in the Text editor would still affect the output, in particular do not use empty lines, line breaks inside HTML tags or multiple &lt;br&gt; tags.', 'tinymce-advanced' ); ?>
		</p>
	</div>
	<?php

	$has_editor_style = $this->has_editor_style();
	$disabled = ' disabled';

	if ( $has_editor_style === false ) {
		add_editor_style();
		$has_editor_style = $this->has_editor_style();
	}

	if ( $has_editor_style ) {
		$disabled = '';
	}

	?>
	<div>
		<input type="checkbox" name="admin_options[]" value="importcss" id="importcss" <?php if ( ! $disabled && $this->check_admin_setting( 'importcss' ) ) echo ' checked'; echo $disabled; ?> />
		<label for="importcss"><?php _e( 'Create CSS classes menu', 'tinymce-advanced' ); ?></label>
		<p>
			<?php _e( 'Load the CSS classes used in editor-style.css and replace the Formats menu.', 'tinymce-advanced' ); ?>
		</p>
		<?php

		if ( $disabled ) {
			?>
			<p>
			<span class="tadv-error"><?php _e( 'Disabled:', 'tinymce-advanced' ); ?></span>
			<?php _e( 'A stylesheet file named editor-style.css was not added by your theme.', 'tinymce-advanced' ); ?>
			<br>
			<?php
		}

		?>
		</p>
	</div>
	<div>
		<input type="checkbox" name="admin_options[]" value="paste_images" id="paste_images" <?php if ( $this->check_admin_setting( 'paste_images' ) ) echo ' checked'; ?> />
		<label for="paste_images"><?php _e( 'Enable pasting of image source', 'tinymce-advanced' ); ?></label>
		<p>
			<?php _e( 'Works only in Firefox and Safari. These browsers support pasting of images directly in the editor and convert them to base64 encoded text.', 'tinymce-advanced' ); ?>
			<?php _e( 'This is not acceptable for larger images like photos or graphics, but may be useful in some cases for very small images like icons, not larger than 2-3KB.', 'tinymce-advanced' ); ?>
			<?php _e( 'These images will not be available in the Media Library.', 'tinymce-advanced' ); ?>
		</p>
	</div>
	<div>
		<p class="tadv-help">
		<?php

		$text = __( 'For other advanced TinyMCE settings, including settings for the Classic Block in the Block Editor, you can use the %1$sAdvanced TinyMCE Configuration plugin%2$s.', 'tinymce-advanced' );
		$url = 'https://wordpress.org/plugins/advanced-tinymce-configuration/';
		printf( $text, '<a href="' . esc_url( $url ) . '">', '</a>' );

		?>
		</p>
	</div>
	</div>

	<div class="advanced-options">
	<h3><?php _e( 'Administration', 'tinymce-advanced' ); ?></h3>
	<div>
		<h4><?php _e( 'Settings import and export', 'tinymce-advanced' ); ?></h4>
		<p>
			<input type="submit" class="button" name="tadv-export-settings" value="<?php _e( 'Export Settings', 'tinymce-advanced' ); ?>" /> &nbsp;
			<input type="submit" class="button" name="tadv-import-settings" value="<?php _e( 'Import Settings', 'tinymce-advanced' ); ?>" />
		</p>
	</div>
	<div>
		<h4><?php _e( 'Enable the editor enhancements for:', 'tinymce-advanced' ); ?></h4>
		<p>
			<input type="checkbox" id="tadv_enable_1" name="tadv_enable_at[]" value="edit_post_screen" <?php if ( $this->check_admin_setting( 'enable_edit_post_screen' ) ) echo ' checked'; ?> />
			<label for="tadv_enable_1"><?php _e( 'The main editor (Add New and Edit posts and pages)', 'tinymce-advanced' ); ?></label>
		</p>
		<p>
			<input type="checkbox" id="tadv_enable_2" name="tadv_enable_at[]" value="rest_of_wpadmin" <?php if ( $this->check_admin_setting( 'enable_rest_of_wpadmin' ) ) echo ' checked'; ?> />
			<label for="tadv_enable_2"><?php _e( 'Other editors in wp-admin', 'tinymce-advanced' ); ?></label>
		</p>
		<p>
			<input type="checkbox" id="tadv_enable_3" name="tadv_enable_at[]" value="on_front_end" <?php if ( $this->check_admin_setting( 'enable_on_front_end' ) ) echo ' checked'; ?> />
			<label for="tadv_enable_3"><?php _e( 'Editors on the front end of the site', 'tinymce-advanced' ); ?></label>
		</p>
	</div>
	</div>
	<?php

}
?>

<hr>

<p class="tadv-submit">
	<?php wp_nonce_field( 'tadv-save-buttons-order' ); ?>
	<input class="button" type="submit" name="tadv-restore-defaults" value="<?php _e( 'Restore Default Settings', 'tinymce-advanced' ); ?>" />
	<input class="button-primary button-large" type="submit" name="tadv-save" value="<?php _e( 'Save Changes', 'tinymce-advanced' ); ?>" />
</p>
</form>

<div id="wp-adv-error-message" class="tadv-error">
<?php _e( 'The [Toolbar toggle] button shows or hides the second, third, and forth button rows. It will only work when it is in the first row and there are buttons in the second row.', 'tinymce-advanced' ); ?>
</div>
</div><!-- /wrap -->

<div class="wrap">
	<p>
		<label>Artist:</label>
		<select name="mw_artist_id" id="mw_artist_id">
			<option value=""></option>
			<?php foreach ($artists as $artist): ?>
			<option value="<?php echo $artist->artist_id ?>"<?php if ($artist->artist_id == $mw_artist_id): ?> selected="selected"<?php endif; ?>><?php echo (!empty($artist->artist_asian_name_utf8)) ? $artist->artist_asian_name_utf8 : $artist->artist_display_name; ?></option>
			<?php endforeach; ?>
		</select>
	</p>

	<?php if (!empty($mw_artist_id)): ?>
	<p>
		<label>Album:</label>
		<select name="mw_album_id" id="mw_album_id">
			<option value=""></option>
			<?php foreach ($albums as $album): ?>
			<option value="<?php echo $album->album_id ?>"<?php if ($album->album_id == $mw_album_id): ?> selected="selected"<?php endif; ?>><?php echo $album->album_title; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php endif; ?>
	
	<?php if (!empty($mw_album_id)): ?>
	<p>
		<label>Release:</label>
		<select name="mw_release_id" id="mw_release_id">
			<option value=""></option>
			<?php foreach ($releases as $release): ?>
			<option value="<?php echo $release->release_id ?>"<?php if ($release->release_id == $mw_release_id): ?> selected="selected"<?php endif; ?>><?php echo $release->release_catalog_num; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php endif; ?>
</div>

<script type="text/javascript">
(function ($) {
	$('#mw_artist_id').chosen({
		'allow_single_deselect': true
	});
	$('#mw_album_id').chosen();
	$('#mw_release_id').chosen();
})(jQuery);
</script>
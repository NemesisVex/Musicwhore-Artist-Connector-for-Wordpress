<div class="wrap mw-meta">
	<?php if ( $artist_model->get_driver_status() === false ): ?>
	<p>
		The database driver may not be configured correctly. Check the <a href="<?php echo admin_url( 'options-general.php?page=musicwhore_artist_connector'); ?>">Musicwhore Artist Connector settings</a>.
	</p>
	<?php endif; ?>

	<?php if (!empty( $artists )): ?>
	<p>
		<label for="mw_artist_id">Artist:</label>
		<select name="mw_artist_id" id="mw_artist_id">
			<option value=""></option>
			<?php foreach ($artists as $artist): ?>
			<option value="<?php echo $artist->artist_id ?>"<?php if ($artist->artist_id == $mw_artist_id): ?> selected="selected"<?php endif; ?>><?php echo $artist->artist_name; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php endif; ?>

	<?php if (!empty($mw_artist_id)): ?>
	<p>
		<label for="mw_album_id">Album:</label>
		<select name="mw_album_id" id="mw_album_id">
			<option value=""></option>
			<?php foreach ($albums as $album): ?>
			<option value="<?php echo $album->album_id ?>"<?php if ($album->album_id == $mw_album_id): ?> selected="selected"<?php endif; ?>><?php echo $album->album_title; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php else: ?>
	<p>
		<label for="mw_album_id">Album:</label>
		<select name="mw_album_id" id="mw_album_id">
			<option value="">Select an artist to choose an album</option>
		</select>
	</p>
	<?php endif; ?>
	
	<?php if (!empty($mw_album_id)): ?>
	<p>
		<label for="mw_release_id">Release:</label>
		<select name="mw_release_id" id="mw_release_id">
			<option value=""></option>
			<?php foreach ($releases as $release): ?>
			<option value="<?php echo $release->release_id ?>"<?php if ($release->release_id == $mw_release_id): ?> selected="selected"<?php endif; ?>><?php echo $release->release_catalog_num; ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php else: ?>
	<p>
		<label for="mw_release_id">Release:</label>
		<select name="mw_release_id" id="mw_release_id">
			<option value="">Select an album to choose a release</option>
		</select>
	</p>
	<?php endif; ?>
</div>

<script type="text/javascript">
(function ($) {
	$('body').ajaxStart(function() {
		$(this).css({'cursor':'wait'})
	}).ajaxStop(function() {
		$(this).css({'cursor':'default'})
	});

	var MetaBoxHandler = {
		empty_albums: function () {
			$('#mw_album_id').empty();
			$('#mw_album_id').append( $('<option>') );
			$('#mw_album_id').trigger('chosen:updated');
		},
		empty_releases: function () {
			$('#mw_release_id').empty();
			$('#mw_release_id').append( $('<option>') );
			$('#mw_release_id').trigger('chosen:updated');
		}
	};

	$('#mw_artist_id').chosen({
		'allow_single_deselect': true
	});
	$('#mw_artist_id').change( function (event, params) {

		MetaBoxHandler.empty_albums();
		MetaBoxHandler.empty_releases();

		var data = {
			action: 'get_artist_albums',
			mw_artist_id: this.value
		}

		$.post(ajaxurl, data, function ( response ) {
			var albums = $.parseJSON( response );

			if ( albums.length > 0 ) {
				for (var a in albums) {
					var album = $('<option>').val( albums[a].album_id ).html( albums[a].album_title );
					$('#mw_album_id').append( album );
				}
				$('#mw_album_id').trigger('chosen:updated');

			}
		});
	});

	$('#mw_album_id').chosen();
	$('#mw_album_id').change( function () {

		MetaBoxHandler.empty_releases();

		var data = {
			action: 'get_album_releases',
			mw_album_id: this.value
		}

		$.post(ajaxurl, data, function ( response ) {
			var releases = $.parseJSON( response );

			if ( releases.length > 0 ) {

				for (var r in releases) {
					var release = $('<option>').val( releases[r].release_id ).html( releases[r].release_catalog_num );
					$('#mw_release_id').append( release );
				}
				$('#mw_release_id').trigger('chosen:updated');

			}
		});
	});

	$('#mw_release_id').chosen();
})(jQuery);
</script>
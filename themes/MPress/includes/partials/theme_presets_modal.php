<div class="mpress-modal">
	<div class="modal-panel">
		<header class="modal-header">

		</header>
		<div class="modal-body">
			<div class="input-wrapper">
				<label for="preset">Select Preset</label>
				<select name="preset" id="preset" class="widefat">
					<?php foreach( $presets as $name => $preset ) : ?>
						<option value="<?php echo $name ?>"><?php echo $name ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="input-wrapper">
				<label for="output">Output</label>
				<textarea name="output" id="output" class="widefat" cols="30" rows="10"><?php echo $first['content']; ?></textarea>
			</div>
		</div>
		<footer class="modal-footer">
			<button id="insert" class="button button-primary pull-right">Insert</button>
			<button id="cancel" class="button button-default pull-right cancel">Cancel</button>
		</footer>
	</div>
</div>
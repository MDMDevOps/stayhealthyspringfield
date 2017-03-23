<div class="wrap <?php echo parent::$plugin_name; ?>">
	<div id="terminus" class="mdmpanel">
		<h2>GIT Terminus</h2>
		<hr>
		<style scoped>
			#terminus .terminus {
				background: #282A36;
				color: #32A3C5;
				padding: 1.618rem;
				line-height: 1.618;
				font-size: 1rem;
				font-family: monospace;
				margin-bottom: 1.618rem;
				max-height: 400px;
				overflow: auto;
				position: relative;
				bottom: 0;
			}
		</style>
		<div class="terminus terminal">
			Ready...<span class="blinking-cursor">|</span>
			<?php
				//chdir( ABSPATH . 'wp-content/' );
				// echo shell_exec( 'git status' );
				//parent::expose( system( 'git status' ) );
			?>
		</div>
		<div id="gitcommands">
			<form action="<?php printf( '?page=%s&tab=git', parent::$plugin_name ); ?>">
				<p>
					<label for="command">Command</label>
					<select name="command" id="command" class="widefat">
						<option value="status">git status</option>
						<option value="commit">commit all</option>
						<option value="push">push to master</option>
						<option value="pull">pull from master</option>
						<option value="reset">reset hard</option>
					</select>
				</p>
				<p>
					<button type="submit" class="button button-primary pull-left">Run Command</button>
				</p>
			</form>
		</div>
	</div>
	<div id="githistory" class="mdmpanel">
		<style scoped>
			#githistory {
				padding: 0;
			}
			#githistory .panel-header {
				padding: 1rem;
			}
			#githistory table {
				border: none;
			}
			#githistory td {
				border-bottom: 1px solid #dddddd;
				white-space: nowrap;
				vertical-align: top;
			}
			#githistory td:first-child {
				white-space: initial;
			}
			#githistory tr:last-child td {
				border-bottom: none;
			}
			#githistory tr:nth-of-type(even) {
				background-color: #f9f9f9;
			}
		</style>
		<header class="panel-header">
			<h1><span class="fa fa-github" aria-hidden="true"></span>&nbsp;History</h1>
			<hr>
		</header>
		<?php $commits = apply_filters( 'repository_data', 'commits', array() ); ?>
		<?php if( $commits === false ) : ?>
			<p>Repository not set up</p>
		<?php elseif( isset( $commits['message'] ) ) : ?>
			<p><?php echo esc_attr( $commits['message'] ); ?></p>
		<?php else : ?>
			<table class="table widefat">
			    <thead>
			        <tr>
			            <th>Message</th>
			            <th>Date</th>
			            <th>Author</th>
			        </tr>
			    </thead>
			    <tbody>
			<?php foreach( $commits as $index => $commit ) : ?>
			    <tr>
			        <td class="message"><a href="<?php echo esc_url_raw( $commit['html_url'] ); ?>"><?php echo $commit['commit']['message']; ?></a></td>
			        <td class="date"><?php echo date( 'F j, Y, g:i a', strtotime( $commit['commit']['committer']['date'] ) ) ?></td>
			        <td class="person"><a href="<?php echo esc_url_raw( $commit['author']['html_url'] ); ?>" target="_blank"><img src="<?php echo esc_url_raw( $commit['author']['avatar_url'] ); ?>" height="20px" width="20px" style="vertical-align: top;" >&nbsp;<?php echo $commit['author']['login']; ?></a></td>
			    </tr>
			<?php endforeach; ?>
			    </tbody>
			</table>
		<?php endif; ?>
	</div>
</div>

<div class="wrap <?php echo parent::$plugin_name; ?>">
   	<h2>PHP Info</h2>
    <hr>
    <div id="gitstatus" class="mdmpanel">
    	<style scoped>
    		#systemreport-phpinfo table {
				border: 1px solid rgba(158, 156, 156, 0.22);
				border-collapse: collapse;
				min-width: 100%;
				margin-bottom: 1em;
    		}
    		#systemreport-phpinfo td, th {
    			border: 1px solid rgba(193, 191, 191, 0.19);
    			padding: .4em 1em;
    			text-align: left;
    		}
    		#systemreport-phpinfo h2 {
    			font-size: 2rem;
    			border-bottom: 1px solid rgba(128, 128, 128, 0.18);
    			margin-bottom: .4rem;
    		}
    		#systemreport-phpinfo td.e {
    			font-weight: 600;
    			background: rgb(241, 241, 241);
    		}
    		#systemreport-phpinfo th {
    			font-size: 1.1em;
    		}
    	</style>
		<?php $this->output_php_info(); ?>
    </div>
</div>
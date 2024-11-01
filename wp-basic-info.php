<?php
if ( ! function_exists( 'plugins_api' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
}
global $wpdb, $post;
$posts = wp_count_posts( 'post' );
$pages = wp_count_posts( 'page' );
$all_plugins = get_plugins(); $i =1;  
$active_plugins = get_option('active_plugins'); 
?>
<style type="text/css">
table.mainTbl{}
table.mainTbl > thead{background:#030325; color:#fff; font-size:24px; }
table.mainTbl > thead th{padding:30px;}
table.mainTbl{background:#fff; width:100%; max-width:1000px; clear:both;}
table.mainTbl > tbody > tr > td{border-top:1px solid #030325;}
table.mainTbl > tbody > tr > td:first-child{border-top:1px solid #fff;}
table.mainTbl > tbody > tr > td:first-child{background:#030325; color:#fff; font-weight:600;}
table.mainTbl > tbody > tr > td td{background:#03032538; padding:10px; text-align:center;}
table.mainTbl > tbody > tr > td table{width:100%;}
</style>
<table cellpadding="10" cellspacing="0" class="mainTbl">
	<thead>
		<th colspan="3">WP Info</th>
	</thead>
	<tbody>
		<tr>
			<td>Wordpress Version</td>
			<td colspan="2">
			<?php echo $current_version = get_bloginfo( 'version' ); ?> 
			<?php
				$url = 'https://api.wordpress.org/core/version-check/1.7/';
				$response = wp_remote_get($url);
				
				$json = $response['body'];
				$obj = json_decode($json);
				$upgrade = $obj->offers[0];
				
				if($current_version < $upgrade->version ){echo "Your Wordpress is <b style='color:red;'>Outdated</b>. Please update to latest version<b style='color:red;'> ".$upgrade->version."</b>";}else{echo "Up-to-date";}
			?>
			</td>
		</tr>
		<tr>
			<td>Posts & Pages</td>
			<td>
				<table cellspacing="" cellpadding="">
					<thead>
						<th colspan="2">
							Posts
						</th>
					</thead>
					<tbody>
						<tr>
							<td>Published</td>
							<td><b><?php echo $posts->publish ;?></b></td>
						</tr>
						<tr>
							<td>Pending</td>
							<td><b><?php echo $posts->pending ;?></b></td>
						</tr>
						<tr>
							<td>Draft</td>
							<td><b><?php echo $posts->draft ;?></b></td>
						</tr>
						<tr>
							<td>Trashed</td>
							<td><b><?php echo $posts->trash ;?></b></td>
						</tr>
						<tr>
							<td>Scheduled</td>
							<td><b><?php echo $posts->future ;?></b></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td>
				<table cellspacing="" cellpadding="">
					<thead>
						<th colspan="2">
							Pages
						</th>
					</thead>
					<tbody>
						<tr>
							<td>Published</td>
							<td><b><?php echo $pages->publish ;?></b></td>
						</tr>
						<tr>
							<td>Pending</td>
							<td><b><?php echo $pages->pending ;?></b></td>
						</tr>
						<tr>
							<td>Draft</td>
							<td><b><?php echo $pages->draft ;?></b></td>
						</tr>
						<tr>
							<td>Trashed</td>
							<td><b><?php echo $pages->trash ;?></b></td>
						</tr>
						<tr>
							<td>Scheduled</td>
							<td><b><?php echo $pages->future ;?></b></td>
						</tr>
					</tbody>
				</table>	
			</td>
		</tr>
		<tr>
			<td>Plugins</td>
			<td colspan="2">
				<table cellspacing="" cellpadding="">
					<thead>
						<th colspan="2">Active plugins</th>
					</thead>
					<tbody>
					<tr>
						<td>#</td>
						<td>Plugin Name</td>
						<td>Version</td>
						<td>Status</td>
					</tr>
					<?php 
					foreach($all_plugins as $key=>$plug){  //echo "<pre>"; print_r($plug);echo "</pre>"; 
						if(in_array($key,$active_plugins)){?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $plug['Name']; ?> </td>
							<td><?php echo $plug['Version']; ?></td>
							<td>
								<?php
									$call_api = plugins_api( 'plugin_information', array("slug"=>$plug['TextDomain']));
										if ( is_wp_error( $call_api ) ) {
											echo  '<b style="color:green">Custom/PRO plugin. No version detail available.</b>';
										} else {
										if ( ! empty( $call_api->version ) ) {
											$latestV = $call_api->version;
											if($latestV >  $plug['Version']){
												echo '<b style="color:red;">Outdated plugin. </b>Latest version is <b style="color:red;">'.$latestV.'</b>';
											} else{
												echo  '<b style="color:green">Up to date</b>';
											}
										}
									}
								?>
							</td>
						</tr>
						<?php $i++; }else{
						$inactive_plugins[$key] = $plug;
						}
					}  ?>
					</tbody>
				</table>
				<table cellspacing="" cellpadding="">
					<thead>
						<th colspan="2"><br />Inactive plugins</th>
					</thead>
					<tbody>
						<tr>
							<td>#</td>
							<td>Plugin Name</td>
							<td>Version</td>
							<td>Status</td>
						</tr>
						<?php 
						$j = 1;
						foreach($inactive_plugins as $key=>$plug){ ?>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $plug['Name']; ?> </td>
								<td><?php echo $plug['Version']; ?></td>
								<td>
								<?php
									$call_api = plugins_api( 'plugin_information', array("slug"=>$plug['TextDomain']));
										if ( is_wp_error( $call_api ) ) {
											echo  '<b style="color:green">Custom/PRO plugin. No version detail available.</b>';
										} else {
										if ( ! empty( $call_api->version ) ) {
											$latestV = $call_api->version;
											if($latestV >  $plug['Version']){
												echo '<b style="color:red;">Outdated plugin. </b>Latest version is <b style="color:red;">'.$latestV.'</b>';
											} else{
												echo  '<b style="color:green">Up to date</b>';
											}
										}
									}
								?>
							</td>
							</tr>
							<?php $j++;
						}  ?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>Active Theme</td>
			<td colspan="2">
			<?php $current_theme = wp_get_theme(); ?>
			<table cellspacing="" cellpadding="">
				<tr>
					<td><b>Theme Name</b></td>
					<td><?php echo esc_html( $current_theme->get( 'Name' ) ); ?></td>
				</tr>
				<tr>
					<td><b>Version</b></td>
					<td><?php echo esc_html( $current_theme->get( 'Version' ) ); ?></td>
				</tr>
				<tr>
					<td><b>Author</b></td>
					<td><?php echo esc_html( $current_theme->get( 'Author' ) ); ?></td>
				</tr>
			</table>
			</td>
		</tr>
	</tbody>
</table>
<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Users_List
 * @subpackage Users_List/public/partials
 */

	get_header();

	do_action( 'users_list_before_container' );

	$response = Users_List_Public::load_users_list();
	
?>

	<div class="container-fluid" id="users_list_container">

		<?php do_action( 'users_list_after_container' ); ?>

	    <div class="jumbotron">

	    	<div class="row">
	    		<div class="col-md-12">
	    			<div class="page-heading m-b-10">
						<?php do_action( 'users_list_before_plugin_title' ); ?>    				
	            		<h4><?php echo apply_filters('users_list_plugin_title', 'Users List'); ?></h4>

	            		<?php do_action( 'users_list_after_plugin_title' ); ?>
	            	</div>
	    		</div>
	    	</div>

	        <div class="row">
	        	<div class="col-md-7">
	        		<?php
	        		if($response['success'] == false)
	        		{
	        		?>
	        			<div class="alert alert-danger fade show" role="alert">
							<strong>Error!</strong> <?php echo !empty($response['message']) ? $response['message'] : __( 'Something went wrong!', 'users-list' ); ?>
						</div>
	        		<?php	
	        		}
					?>
	        		<section id="users_list_section">
	        			<div class="card user-card">
			                <div class="card-block table-responsive ul-table">
			                	
			                	<?php do_action( 'users_list_before_table' ); ?>

			                	<table class="table table-hover">
			                		<caption>List of users</caption>
									<thead>
										<tr>
											<th>Name</th>
											<th>Username</th>
											<th>Email</th>
											<th>Phone</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if( $response['success'] == true && !empty($response['data']) ) 
										{
											$users_list =  $response['data'];
											foreach($users_list as $user):
												if(is_array($user))
													$user = (object)$user;

												echo '<tr data-id="'.$user->id.'">';
													echo '<td><a href="javascript:void(0);" class="load-info">'.$user->name.'</a></td>';
													echo '<td><a href="javascript:void(0);" class="load-info">'.$user->username.'</a></td>';
													echo '<td><a href="javascript:void(0);" class="load-info">'.$user->email.'</a></td>';
													echo '<td><a href="javascript:void(0);" class="load-info">'.$user->phone.'</a></td>';
												echo '<tr>';
											endforeach;
										}
										else
										{
										?>
											<tr>
												<td colspan="4"><?php echo apply_filters('users_list_no_users', __( 'No users found!', 'users-list' )); ?></td>
											</tr>
										<?php
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th>Name</th>
											<th>Username</th>
											<th>Email</th>
											<th>Phone</th>
										</tr>
									</tfoot>
								</table>

								<?php do_action( 'users_list_after_table' ); ?>

			                </div>
			            </div>
					</section>
	        	</div>
	        	<div class="col-md-5">
	        		<section id="user_info_section">

	        			<?php do_action( 'users_list_before_user_info' ); ?>

						<div id="user_info_div">
							<span class="f-w-600 m-t-25 m-b-10 text-center">
								<?php echo apply_filters('users_list_guide_msg', __( '<h3>Hey,</h3>
								<h4>Click on User To see user information</h4>', 'users-list' )); ?>
			            	</span>
						</div>
						<div id="loader" style="display: none;"></div>

						<?php do_action( 'users_list_after_user_info' ); ?>

					</section>
	        	</div>
	        </div>

	    </div>
	</div>

<?php

	get_footer();

?>
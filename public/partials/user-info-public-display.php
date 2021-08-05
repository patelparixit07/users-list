<?php

if( get_query_var( 'ul_user_info', false) !== false ) 
{
	$user_info =  get_query_var( 'ul_user_info');

?>

<div class="card user-card">
    <div class="card-block">
        <div class="user-image">
            <img src="<?php echo plugin_dir_url( __DIR__ ) . '/images/user_avtar.png'; ?>" class="img-radius" alt="User-Profile-Image">
        </div>
        <h6 class="f-w-600 m-t-0 m-b-10"><?php echo $user_info->name; ?></h6>
        <p class="text-muted"><?php echo '('.$user_info->username.')'; ?></p>
        <p class="text-muted"><?php echo $user_info->email.' | '.$user_info->phone; ?></p>
        <p class="text-center">
        	<svg class="svg-icon" viewBox="0 0 20 20">
				<path d="M10,1.375c-3.17,0-5.75,2.548-5.75,5.682c0,6.685,5.259,11.276,5.483,11.469c0.152,0.132,0.382,0.132,0.534,0c0.224-0.193,5.481-4.784,5.483-11.469C15.75,3.923,13.171,1.375,10,1.375 M10,17.653c-1.064-1.024-4.929-5.127-4.929-10.596c0-2.68,2.212-4.861,4.929-4.861s4.929,2.181,4.929,4.861C14.927,12.518,11.063,16.627,10,17.653 M10,3.839c-1.815,0-3.286,1.47-3.286,3.286s1.47,3.286,3.286,3.286s3.286-1.47,3.286-3.286S11.815,3.839,10,3.839 M10,9.589c-1.359,0-2.464-1.105-2.464-2.464S8.641,4.661,10,4.661s2.464,1.105,2.464,2.464S11.359,9.589,10,9.589"></path>
			</svg>
		</p>
		<p class="m-t-15 text-muted"><?php echo $user_info->address->street.', '.$user_info->address->suite.', '.$user_info->address->city.'-'.$user_info->address->zipcode; ?></p>
        <hr>
        <h6 class="f-w-600 m-t-25 m-b-10"><i>Company</i></h6>
        <p class="m-t-15 text-muted"><?php echo $user_info->company->name.'<br>'.$user_info->company->catchPhrase.'<br>'.$user_info->company->bs; ?></p>
        <hr>
        <div class="row justify-content-center user-social-link">
            <span>
            	<svg class="svg-icon" viewBox="0 0 20 20">
				<path fill="none" d="M10,2.531c-4.125,0-7.469,3.344-7.469,7.469c0,4.125,3.344,7.469,7.469,7.469c4.125,0,7.469-3.344,7.469-7.469C17.469,5.875,14.125,2.531,10,2.531 M10,3.776c1.48,0,2.84,0.519,3.908,1.384c-1.009,0.811-2.111,1.512-3.298,2.066C9.914,6.072,9.077,5.017,8.14,4.059C8.728,3.876,9.352,3.776,10,3.776 M6.903,4.606c0.962,0.93,1.82,1.969,2.53,3.112C7.707,8.364,5.849,8.734,3.902,8.75C4.264,6.976,5.382,5.481,6.903,4.606 M3.776,10c2.219,0,4.338-0.418,6.29-1.175c0.209,0.404,0.405,0.813,0.579,1.236c-2.147,0.805-3.953,2.294-5.177,4.195C4.421,13.143,3.776,11.648,3.776,10 M10,16.224c-1.337,0-2.572-0.426-3.586-1.143c1.079-1.748,2.709-3.119,4.659-3.853c0.483,1.488,0.755,3.071,0.784,4.714C11.271,16.125,10.646,16.224,10,16.224 M13.075,15.407c-0.072-1.577-0.342-3.103-0.806-4.542c0.673-0.154,1.369-0.243,2.087-0.243c0.621,0,1.22,0.085,1.807,0.203C15.902,12.791,14.728,14.465,13.075,15.407 M14.356,9.378c-0.868,0-1.708,0.116-2.515,0.313c-0.188-0.464-0.396-0.917-0.621-1.359c1.294-0.612,2.492-1.387,3.587-2.284c0.798,0.97,1.302,2.187,1.395,3.517C15.602,9.455,14.99,9.378,14.356,9.378"></path>
				</svg>
			</span>
			<span>
				<a href="<?php echo esc_url_raw($user_info->website); ?>" target="_blank"><i class="text-website"><?php echo $user_info->website; ?></i></a>
			</span>
        </div>
    </div>
</div>
<?php

}

?>
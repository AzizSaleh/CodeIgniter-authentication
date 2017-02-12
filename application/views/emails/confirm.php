<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<p>Hi <?=$username;?>,

<p>You have signed up for an account. However before using the account you need to confirm it. Please visit:</p>

<p><a href="<?=$url;?>"><?=$url;?></a></p>

<p>And use the confirmation code: <?=$confirm_code;?></p>
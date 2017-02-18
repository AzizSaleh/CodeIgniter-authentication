<?php
/**
 * Custom form validation
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'third_party' . 
	DIRECTORY_SEPARATOR . 'recaptcha' . DIRECTORY_SEPARATOR . 'autoload.php';

/**
 * MY_Form_validation
 * 
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @extends   CI_Form_validation
 */
class MY_Form_validation extends CI_Form_validation
{
	/**
	 * Captcha options
	 *
	 * @param array
	 */
	public $captcha_options;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->CI->load->config('captcha', true);
        $this->captcha_options = $this->CI->config->config['captcha'];
	}

	/**
	 * Validate captcha
	 *
	 * @param string $code
	 *
	 * @return boolean
	 */
	public function valid_captcha()
	{
		if (!$this->captcha_options['enable_recaptcha']) {
			$this->set_message('valid_captcha', 'Captcha functionality disabled');
    		return false;
		}

		$recaptcha = new \ReCaptcha\ReCaptcha($this->captcha_options['secret_key']);
		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    	if (!$resp->isSuccess()) {
    		$error = '';
            foreach ($resp->getErrorCodes() as $code) {
                $error .= ',' . $code;
            }

    		$this->set_message('valid_captcha', 'Invalid Captcha. Error: ' . $error);
    		return false;
    	}

    	return true;
	}

	/**
	 * Set captcha code
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	public function set_captcha($before = '', $after = '')
	{
		if (!$this->captcha_options['enable_recaptcha']) {
			return '';
		}

		return $before . '<script src="//www.google.com/recaptcha/api.js" async defer></script>
		<div class="g-recaptcha" data-sitekey="' . $this->captcha_options['site_key'] . '"></div>
		<noscript>
		  <div>
		    <div style="width: 302px; height: 422px; position: relative;">
		      <div style="width: 302px; height: 422px; position: absolute;">
		        <iframe src="//www.google.com/recaptcha/api/fallback?k=' . $this->captcha_options['site_key'] . '"
		                frameborder="0" scrolling="no"
		                style="width: 302px; height:422px; border-style: none;">
		        </iframe>
		      </div>
		    </div>
		    <div style="width: 300px; height: 60px; border-style: none;
		                   bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
		                   background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
		      <textarea id="g-recaptcha-response" name="g-recaptcha-response"
		                   class="g-recaptcha-response"
		                   style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
		                          margin: 10px 25px; padding: 0px; resize: none;" >
		      </textarea>
		    </div>
		  </div>
		</noscript>' . $after;
	}
}

if (! function_exists('set_captcha')) {
	function set_captcha($before = '', $after = '') {
		$CI =& get_instance();
		return $CI->form_validation->set_captcha($before, $after);
	}
}
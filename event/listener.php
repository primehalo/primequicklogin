<?php
/**
*
* Prime User Topics extension for the phpBB Forum Software package.
*
* @copyright (c) 2018 Ken F. Innes IV <https://www.absoluteanime.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace primehalo\primequicklogin\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/**
	* Service Containers
	*/
	protected $template;
	protected $user;
	protected $config;
	protected $root_path;
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config		$config		Config object
	* @param \phpbb\template\template 	$template	Template object
	* @param \phpbb\user				$user		User object
	* @param $root_path					$root_path	phpBB root path
	* @param $phpExt					$phpExt		php file extension
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$root_path,
		$phpExt)
	{
		$this->template		= $template;
		$this->user			= $user;
		$this->config		= $config;
		$this->root_path	= $root_path;
		$this->php_ext		= $phpExt;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header_after'				=> 'page_header_after',
		);
	}

	/**
	* Create the needed template variables for the quick login form
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_header_after($event)
	{
		$user_id = $this->user->data['user_id'];
		if ($user_id == ANONYMOUS)
		{
			$this->user->add_lang_ext('primehalo/primequicklogin', 'primequicklogin');

			$redirect = '&amp;redirect=' . urlencode($this->user->page['page_dir'] ? $this->user->page['page'] : str_replace('&amp;', '&', build_url(array())));
			$this->template->assign_var('S_PRIME_QUICK_LOGIN', append_sid("{$this->root_path}ucp.{$this->php_ext}", 'mode=login' .  $redirect));
			if ($this->config['allow_autologin'])
			{
				$this->template->assign_var('S_PRIME_QUICK_LOGIN_AUTO', true);
			}
		}
	}
}

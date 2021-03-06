<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class ContactFormBlockController extends BlockController {
/*    ^^^^^^^^^^^^^^^^^
      Change this portion
      of the class name to
      correspond with the
      block's directory name
*/

	protected $btDescription = "Happy Medium Custom Contact Form";
	protected $btName = "Happy Medium Contact Form";
	protected $btTable = 'btContactForm'; //Change db.xml table name to match this
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "450";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	protected $btCacheBlockOutputLifetime = 300;
	
	public function view() {
		$this->set('showThanks', $this->get('thanks'));
	}
	
	public function action_submit_form() {
		$error = $this->validate_form($this->post());
		
		if ($error->has()) {
			//Fail -- re-display the form (C5 form helpers will repopulate user's entered data for us)
			$this->set('errors', $error->getList());
		} else {
			//Success -- send notification email and reload/redirect page to avoid browser warnings about re-posting content if user reloads page 
			$this->send_notification_email($this->post());
			$this->send_thanks_email($this->post());
			$redirect_to_path = Page::getCurrentPage()->getCollectionPath() . '?thanks=1';
			$this->redirect($redirect_to_path);
		}
	}
	
	public function validate_form($post) { //Note: this function can't just be called "validate" because then C5 automatically calls it to validate the add/edit dialog!
		$val = Loader::helper('validation/form');
		$val->setData($post);
		$val->addRequired('name', 'You must enter your name.');
		$val->addRequiredEmail('email', 'You must provide a valid email address');
		$val->test();
		$error = $val->getError();
		
		//Perform manual checks (anything that the validation helper doesn't have rules for)
		$iph = Loader::helper('validation/ip');
		if (!$iph->check()) {
			$error->add($iph->getErrorMessage());
		}
		
		//Note that we don't have to validate CSRF tokens ourselves
		// because C5 handles it for us via the $this->action() function.
		
		return $error;
	}
	
	private function send_notification_email($data) {
		$subject = '['.SITE.'] New Contact Form Submission';
		$body = <<<EOB
A new submission has been made to the Happy Medium contact form:

Name: {$data['name']}
Email: {$data['email']}
Phone: {$data['phone']}
Subject: {$data['request']}

Message:
{$data['message']}

EOB;
//Dev Note: The "EOB;" above must be at the far-left of the page (no whitespace before it),
//          and cannot have anything after it (not even comments).
//			See http://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
		
		//Send email
		$mh = Loader::helper('mail');
		//$mh->from(UserInfo::getByID(USER_SUPER_ID)->getUserEmail());
		$mh->from('info@itsahappymedium.com');
		$mh->to($this->notifyEmail);
		$mh->setSubject($subject);
		$mh->setBody($body); //Use $mh->setBodyHTML() if you want an HTML email instead of (or in addition to) plain-text
		$mh->sendMail(); 
	}

	private function send_thanks_email($data) {
		$subject = '['.SITE.'] Your Contact Form Submission';
		$body = <<<EOB
Thanks for submitting the following to the Happy Medium contact form:

Name: {$data['name']}
Email: {$data['email']}
Phone: {$data['phone']}
Subject: {$data['request']}

Message:
{$data['message']}

EOB;
//Dev Note: The "EOB;" above must be at the far-left of the page (no whitespace before it),
//          and cannot have anything after it (not even comments).
//			See http://www.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
		
		//Send email
		$mh = Loader::helper('mail');
		$mh->from('info@itsahappymedium.com');
		$mh->to($data['email']);
		$mh->setSubject($subject);
		$mh->setBody($body); //Use $mh->setBodyHTML() if you want an HTML email instead of (or in addition to) plain-text
		$mh->sendMail(); 
	}
}

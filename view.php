<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$fh = Loader::helper('form');
?>

<div class="custom_contact_form">
	
	<?php if ($_GET['thanks'] == '1'): ?>
	<div class="success">
		<p><strong><?php echo nl2br($thanksMsg); ?></strong></p>
	</div>
	<?php endif; ?>
	
	<?php if (!empty($errors)): ?>
	<div class="errors">
		Please fix the following errors:
		<ul>
			<?php foreach ($errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<form class="two-columns form" method="post" action="<?php echo $this->action('submit_form'); ?>">

	<?php /* NOTE: If you add a file upload field to your form, you need to change the above <form> tag to:
	<form method="post" action="<?php echo $this->action('submit_form'); ?>" enctype="multipart/form-data">
	(notice that it adds the entype="multipart/form-data" attribute -- form uploads don't work without this) */ ?>

		<div class="col">
            <p>
				<?php $options = array(
					"I need a website or a redesign" => 'I need a website or redesign',
                    "I need SEO help" => 'I need SEO help',
                    "I need social media marketing help" => 'I need social media marketing help',
                    "I need graphic design help" => 'I need graphic design help',
                    "I need email marketing help" => 'I need email marketing help',
                    "I need help with media placement" => 'I need help with media placement',
                    "I'd like to talk about something else" => 'I\'d like to talk about something else'
					);
				?>
				<?php echo $fh->label('request', 'Need Help?*'); ?>
				<?php echo $fh->select('request', $options, $request, array('class'=>'pseudoSelect')); ?>
            </p>

            <p>
                <?php echo $fh->label('name', 'Name*'); ?>
				<?php echo $fh->text('name'); ?>
            </p>

            <p>
                <?php echo $fh->label('email', 'Email*:'); ?>
				<?php echo $fh->email('email'); ?>
            </p>

            <p>
                <?php echo $fh->label('phone', 'Phone:'); ?>
				<?php echo $fh->telephone('phone'); ?>
            </p>
        </div>

        <div class="col">
			<?php echo $fh->textarea('message', $message, array('cols' => "30", 'rows' => "13",  'placeholder' => "Tell us a little about how we can help you.")); ?>

            <input type="submit" class="btn pink" value="Send it our way" onclick="_gaq.push(['_trackEvent', 'Lead', 'Submitted']);" />
        </div>

	</form>

</div>

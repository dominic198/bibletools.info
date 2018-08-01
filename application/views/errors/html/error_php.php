<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$to      = 'adam@bibletools.info';
$subject = $message;
$headers = 'From: adam@bibletools.info' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$headers  .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = <<<EOT
<p>Severity: $severity
<p>Message:  $message
<p>Filename: $filepath
<p>Line Number: $line
EOT;

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE):

	$body .= "<p>Backtrace:</p>";
	foreach (debug_backtrace() as $error):

		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0):

			$body .= <<<EOT
<p style='margin-left:10px'>
File: {$error['file']}<br />
Line: {$error['line']}<br />
Function: {$error['function']}
</p>
EOT;

		endif;

	endforeach;

endif;

mail($to, $subject, $message, $headers);
?>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: <?php echo $severity; ?></p>
<p>Message:  <?php echo $message; ?></p>
<p>Filename: <?php echo $filepath; ?></p>
<p>Line Number: <?php echo $line; ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach (debug_backtrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo $error['file'] ?><br />
			Line: <?php echo $error['line'] ?><br />
			Function: <?php echo $error['function'] ?>
			</p>

		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>
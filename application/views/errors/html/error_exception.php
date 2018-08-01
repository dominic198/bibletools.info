<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$to      = 'adam@bibletools.info';
$subject = $message;
$type = get_class($exception);
$filename = $exception->getFile();
$line = $exception->getLine();
$headers = 'From: adam@bibletools.info' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$headers  .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = <<<EOT
<p>Type: $type
<p>Message:  $message
<p>Filename: $filename
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

<h4>An uncaught Exception was encountered</h4>

<p>Type: <?php echo get_class($exception); ?></p>
<p>Message: <?php echo $message; ?></p>
<p>Filename: <?php echo $exception->getFile(); ?></p>
<p>Line Number: <?php echo $exception->getLine(); ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach ($exception->getTrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo $error['file']; ?><br />
			Line: <?php echo $error['line']; ?><br />
			Function: <?php echo $error['function']; ?>
			</p>
		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>
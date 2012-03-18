<?php
require_once('includes/header.php');
require_once('includes/command_writer.php');
?>

<h3>Dependencies</h3>

<ul>
  <li>Python 2.6 w/ argparse <strong>or</strong>
      <a href="http://python.org/download/">Python 2.7</a>.</li>
  <li>An Internet connection to perform GO enrichment.</li>
</ul>

<p>We do not currently support versions older than Python 2.6 at this time.</p>
<p>If you're running Python 2.6, 'argparse' can be installed from PyPI:</p>
<p><code>easy_install-2.6 argparse</code></p>

<h3>Installation</h3>

<ul>
  <li><a href="#linux">For Linux</a></li>
  <li><a href="#mac">For Mac</a></li>
  <li><a href="#windows">For Windows</a></li>
  <li><a href="#noinstall">No installation</a></li>
</ul>

<h4 id="linux">For Linux</h4>

<p><a href="download_linux.php">Download</a> Genecentric for Linux.</p>
<p>Extract the tar archive and `cd` into the new directory:</p>
<p><code>
  tar zxf <?php echo $currentFile ?>.tar.gz<br>
  cd <?php echo $currentFile ?>
</code></p>

<p>You can now install it directly:</p>
<p>(<strong>NOTE:</strong> Depending upon how Python is set up on your system,
    you may need to change <?=code('python2')?> below to
    <?=code('python')?> or <?=code('python2.7')?>.)</p>
<p><code>python2 setup.py install</code></p>

<p>Or, if you have <?=code('easy_install')?> installed:</p>
<p><code>easy_install ./</code></p>
<p>The <?=code('easy_install')?> approach works well if you're 
   installing Genecentric in an environment where you don't have root 
   access.</p>

<p>You should now be able to run <?=code('genecentric-bpms --help')?> and see
   the command line documentation.</p>
  
<h4 id="mac">For Mac</h4>
<p>See the <a href="#linux">installation instructions for Linux</a>.</p>

<h4 id="windows">For Windows</h4>
<p>Nothing here yet.</p>

<h4 id="noinstall">No Installation</h4>
<p>It is also possible to use Genecentric without installing it to your system.
   This may be desirable when you don't have root access to your system, or
   if you don't want to set up a virtual environment.</p>
<p>First, like the other steps, <a href="download.php">download Genecentric</a>
   for your operating system and extract it to some directory. (On Linux/Mac,
   use <?=code('tar zxf ' . $currentFile . '.tar.gz')?>, and on Windows,
   use your preferred program to unzip files like WinRAR or Winzip.)</p>
<p>Secondly, change into the directroy that you unzipped Genecentric to (On
   Linux/Mac use a terminal, and on Windows use <?=code('cmd')?>.) You should
   now be able to run any of the Genecentric executables in the directory like
   so:</p>
<?=cmd('python2 genecentric-bpms --help')?>
<p>Or if that doesn't work, try:</p>
<?=cmd('python genecentric-bpms --help')?>
<p>Or:</p>
<?=cmd('python2.7 genecentric-bpms --help')?>
<p>The name of Python on your system will vary depending upon how it is set up.
   For Windows users, you will have to make sure that the path to the Python
   executable is in your PATH environment variable, or else you'll need to
   write out the full path. Which might look something like
   <?=code('C:/Python27/python.exe')?>.</p>

<?php require_once('includes/footer.php'); ?>


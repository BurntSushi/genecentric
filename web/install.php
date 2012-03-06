<?php require_once('includes/header.php'); ?>

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
</ul>

<h4 id="linux">For Linux</h4>

<p><a href="download_linux.php">Download</a> Genecentric for Linux.</p>
<p>Extract the tar archive and `cd` into the new directory:</p>
<p><code>
  tar zxf <?php echo $currentFile ?>.tar.gz<br>
  cd <?php echo $currentFile ?>
</code></p>

<p>You can now install it directly:</p>
<p><code>python2 setup.py install</code></p>

<p>Or, if you have <span class="code">easy_install</span> installed:</p>
<p><code>easy_install ./</code></p>
<p>The <span class="code">easy_install</span> approach works well if you're 
   installing Genecentric in an environment where you don't have root 
   access.</p>
  
<h4 id="mac">For Mac</h4>
<p>See the <a href="#linux">installation instructions for Linux</a>.</p>

<h4 id="windows">For Windows</h4>
<p>Nothing here yet.</p>

<?php require_once('includes/footer.php'); ?>


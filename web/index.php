<?php require_once('includes/header.php'); ?>

<h3>What is Genecentric?</h3>
<p>Genecentric is a package that performs two main tasks:</p>
<ol>
  <li>Implement the between-pathway module (BPM) generation algorithm with 
      pruning as described in Leiserson et al, 2010. In particular, the 
      implementation is automatically parallelized when more than one CPU is 
      present.</li>
  <li>Perform GO enrichment on generated BPMs using the JSON-RPC API of
      FuncAssociate 2.0.</li>
</ol>
<p>Genecentric is <a href="doc.php">completely configurable</a> via the command 
   line.</p>

<h3>How do I use it?</h3>
<p>Here's a quick example of generating BPM's using the Collins et al dataset
   from the <a href="http://interactome-cmp.ucsf.edu/">Krogan Lab
   Interactome Database</a>.</p>
<p><code>genecentric-bpms yeast_emap.gi output.bpm</code></p>
<p>And to perform GO enrichment on 'output.bpm', simply use:</p>
<p><code>genecentric-go yeast_emap.gi output.bpm enrichment.gobpm</code></p>
<p>Windows users will need to prefix each of the above commands with
   their python2 interpreter. Like this:</p>
<p><code>C:\Python2.7\python.exe genecentric-bpms ...</code></p>

<h3>Dependencies?</h3>
<p>If you have Python 2.7, that should be enough to get Genecentric up and
   and running. No other dependencies are required.</p>
<p>If you're running Python 2.6, you'll need to install the 'argparse' module
   from PyPI. If you have 'easy_install' installed, it should be as simple
   as:</p>
<p><code>easy_install-2.6 argparse</code></p>
<p>And Genecentric should work after that.</p>
<p>We don't currently support Python versions older than 2.6.</p>

<?php require_once('includes/footer.php'); ?>
